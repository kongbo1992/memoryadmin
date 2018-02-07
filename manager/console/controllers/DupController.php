<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace console\controllers;

use common\tools\DistStorage;
use Yii;
use backend\modules\question\models\TbQuPaperQuestionTmp;
use backend\modules\question\models\TbQuPaperTmp;
use yii\console\Controller;

/**
 * DefaultController implements the CRUD actions for TbQuPaperTmp model.
 */
class DupController extends Controller{
    private function _replaceHtmlAndJs($document){
	$document = trim($document);
	if (strlen($document) <= 0){
		return $document;
	}
	$search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
	                  "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
	                  "'([\r\n])[\s]+'",                // 去掉空白字符
	                  "'&(quot|#34);'i",                // 替换 HTML 实体
	                  "'&(amp|#38);'i",
	                  "'&(lt|#60);'i",
	                  "'&(gt|#62);'i",
	                  "'&(nbsp|#160);'i"
	                  );                    // 作为 PHP 代码运行
	$replace = array ("",
	                   "",
	                   "\\1",
	                   "\"",
	                   "&",
	                   "<",
	                   ">",
	                   " "
	                   );
	return @preg_replace($search, $replace, $document);
    }
    
    private function getEduQuestionIds($edu_id,$exam_level){
        $result = array();
        $redis = DistStorage::getMainRedisConn();
        $children = $redis->smembers("EDU_CHILDREN:".$edu_id);
        if ($children){
            foreach($children as $cld){
                $result = array_merge($result,$redis->smembers("EDU_QUESTION_MAP:$cld:$exam_level"));
            }
        }
        return $result;
    }
    
    public function actionCheck(){
        $redis = DistStorage::getMainRedisConn();
        //最大id
        $qmaxid = (new \yii\db\Query())->select("max(id) maxid")->from("tb_qu_question")->one();
        $maxid = $qmaxid["maxid"];

        //搜索所有未提交卷子的题列表
        $questions = (new \yii\db\Query())
            ->select(['tb_qu_paper_tmp.exam_level','tb_qu_paper_question_tmp.id', 'tb_qu_paper_question_tmp.edu_id','tb_qu_paper_question_tmp.title','tb_qu_paper_question_tmp.item_a','tb_qu_paper_question_tmp.item_b','tb_qu_paper_question_tmp.item_c','tb_qu_paper_question_tmp.item_d','tb_qu_paper_question_tmp.item_e','tb_qu_paper_question_tmp.item_f','tb_qu_paper_question_tmp.paperid'])
            ->from('tb_qu_paper_question_tmp')
            ->join('INNER JOIN','tb_qu_paper_tmp','tb_qu_paper_tmp.id=tb_qu_paper_question_tmp.paperid')
            ->where('tb_qu_paper_tmp.state = 4 or tb_qu_paper_tmp.state = 8 or tb_qu_paper_tmp.state = 9 or tb_qu_paper_tmp.state = 6')
            ->all();
        $llen = count($questions);
        echo "begin check dup,question count = [$llen] <br/> \n";
        Yii::$app->db->createCommand()->insert('tb_qu_dup_check', [
            'addtime' => date("Y-m-d H:i:s"),
            'maxquestionid' => $maxid,
            'fromtmpquestionid' => $questions[0]["id"],
            'maxtmpquestionid' => $questions[$llen - 1]["id"]
        ])->execute();
        $checkid = Yii::$app->db->getLastInsertID();

        for($l=0;$l<=$llen-1;$l++){
            $arr = $questions[$l];
            $questions[$l]["plantext"] = $this->_replaceHtmlAndJs($arr["title"].$arr["item_a"].$arr["item_b"].$arr["item_c"].$arr["item_d"].$arr["item_e"].$arr["item_f"]);
        }
        $dupflag = 0;
        $paperid = array();
        $ls_id = array();
        $zs_id = array();
        $all_paperid = array();
        $all_id[] = $questions[$llen-1]['id'];
        for($i = 0;$i < $llen - 1;$i ++){
            $all_id[] = $questions[$i]['id'];
            $all_paperid[] = $questions[$i]['paperid'];
            //本身库内去重
            for($j=$i+1;$j<$llen ;$j++){
                similar_text($questions[$i]["plantext"],$questions[$j]["plantext"],$per);
                if ($per >= 95){
                    Yii::$app->db->createCommand()->insert('tb_qu_dup_check_result', [
                        'checkid' => $checkid,
                        'tmpquestionid' => $questions[$i]["id"],
                        'dup_tmpquestionid' => $questions[$j]["id"],
                        'dup_questionid' => 0
                    ])->execute();
                    $dupflag ++;
                    $ls_id[] = $questions[$i]['id'];
                    $paperid[] = $questions[$j]["paperid"];
                    $paperid[] = $questions[$i]["paperid"];
                    break;
                }
            }
        }
        $all_id = implode(",",array_unique($all_id));
        //            将所有替换未不是重题
        $sql = "UPDATE tb_qu_paper_question_tmp SET dupflag=3 WHERE id in ($all_id) ";
        Yii::$app->db->createCommand($sql)->execute();
//        正式库去重
        if($dupflag==0){
            for($i = 0;$i < $llen - 1;$i ++){
                var_dump($i);
                //题库去重
                $eduids = empty($questions[$i]["edu_id"]) ? false : explode(",", $questions[$i]["edu_id"]);
                if (empty($questions[$i]["exam_level"])){
                    $examlevels = array(1,2,3,4);
                }else{
                    $examlevels = explode(",", $questions[$i]["exam_level"]);
                }
                if ($eduids){
                    $result = array();

                    $peduids = array();
                    foreach($eduids as $eduid){
                        $peduids[] = $redis->hget("EDU_INFO:".$eduid,"pid");
                    }
                    $peduids = array_unique($peduids);

                    foreach($peduids as $peduid){
                        foreach($examlevels as $el){
                            $result = array_merge($result,$this->getEduQuestionIds($peduid,$el));
                        }
                    }
                    $quids = array_unique($result);
                    foreach($quids as $qid){
                        if ($info2 = $redis->hgetall("QUESTION_INFO:".$qid)){
                            similar_text($this->_replaceHtmlAndJs($info2["title"].$info2["item_a"].$info2["item_b"].$info2["item_c"].$arr["item_d"].$arr["item_e"].$info2["item_f"]),$questions[$i]["plantext"],$per);
                            if ($per >= 95){
                                Yii::$app->db->createCommand()->insert('tb_qu_dup_check_result', [
                                    'checkid' => $checkid,
                                    'tmpquestionid' => $questions[$i]["id"],
                                    'dup_tmpquestionid' => 0,
                                    'dup_questionid' => $qid
                                ])->execute();
                                $dupflag ++;
                                $paperid[] = $questions[$i]["paperid"];
                                TbQuPaperQuestionTmp::updateAll(['dupquestionid' => $qid,'dupflag'=> 1],[ 'id' => $questions[$i]["id"]]);
                            }
                        }
                    }
                }
            }
            $all_paperid = array_unique($all_paperid);
            $all_paperid = implode(",",$all_paperid);
            $sql = "UPDATE tb_qu_paper_tmp SET state=6 WHERE id in ($all_paperid) ";
            Yii::$app->db->createCommand($sql)->execute();
            if($dupflag){
//                将试卷装换为题库有重题
                $paperid = implode(",",array_unique($paperid));
                $sql = "UPDATE tb_qu_paper_tmp SET state=9 WHERE id in ($paperid) ";
                Yii::$app->db->createCommand($sql)->execute();
            }
        }else{
//            转变试卷状态
            $paperid = implode(",",array_unique($paperid));
            $ls_id = implode(",",array_unique($ls_id));
            $sql = "UPDATE tb_qu_paper_tmp SET state=8 WHERE id in ($paperid) ";
            Yii::$app->db->createCommand($sql)->execute();
//            将是重题的题目改变状态
            $sql = "UPDATE tb_qu_paper_question_tmp SET dupflag=2 WHERE id in ($ls_id) ";
            Yii::$app->db->createCommand($sql)->execute();
        }
    }
}