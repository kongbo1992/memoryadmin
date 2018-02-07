<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;
use backend\modules\news\models\TbANews;
use common\models\TbQuQuestion;
use common\models\TbUUser;
use common\models\TbZyJobs;
use common\models\TbQuQuestionItems;

use common\models\TbZyStudentJobs;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class InitController extends Controller
{
    /**
     *  作业初始化 步骤
     * 1. work-init
     * 2. record-init
     * 3. del-error
     */
    /**
     * 初始化 作业
     */
    public function actionWorkInit(){
        ini_set('max_execution_time', '0');
        $model = TbZyJobs::find()->all();
        foreach($model as $job){
            if(!empty($job->paperid)){
                $sql = "select * from tb_qu_paper_question WHERE paperid = ".$job->paperid." order by orders,id";
                $paper =Yii::$app->db->createCommand($sql)->queryAll();
                if(!empty($paper)){
                    $questions_ids = array_column($paper,'statquestionid');
                    $questions = TbQuQuestion::find()->where(['id'=>$questions_ids])->asArray()->all();
                    if(empty($questions)){
                        continue;
                    }
                    // 初始化提数据
                    foreach($questions as $p_q){
                        $p_q['questionid'] = $p_q['id'];
                        $p_q['id'] = $job->classid*1000000+$p_q['id'];
                        $p_q['job_id'] = $job->id;
                        unset($p_q['practiceflag']);
                        unset($p_q['first_paper_id']);
                        unset($p_q['year']);
                        unset($p_q['province']);
                        $res = Yii::$app->db->createCommand()->insert('tb_zy_job_questions', $p_q)->execute();
                        if($p_q['type'] == 8){
                            $items = TbQuQuestionItems::find()->where(['questionid'=>$p_q['id']])->asArray()->all();
                            if(!empty($items)){
                                $item_data = [];
                                foreach($items as $item){
                                    $item['id'] = $job->classid*1000000+$item['id'];
                                    $item['job_questionid'] = $p_q['id'];
                                    unset($item['questionid']);
                                    $item_data[] = $item;
                                }
                                Yii::$app->db->createCommand()->batchInsert('tb_zy_job_question_items', array_keys($item_data[0]), $item_data)->execute();
                            }
                        }
                    }

                }

            }
        }
        echo 'ok';
    }
    /**
     * 初始化做作业记录
     */
    public function actionRecordInit(){
        ini_set('max_execution_time', '0');
        $jobs = TbZyJobs::find()->where('paperid > 0 ')->asArray()->all();
        $paper_ids = array_column($jobs,'paperid');
        $p_j = [];
        foreach($jobs as $val){
            $p_j[$val['paperid']][] = $val['id'];
        }
        for($i=0;$i<100;$i++){
            $sql = "select * from tb_u_user_practice_record_$i WHERE paperid  in (".implode(',',$paper_ids).")";
            $practice = Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($practice)){
                foreach($practice as $pra){
                    $stu_job = TbZyStudentJobs::find()->where(['userid'=>$pra['userid'],'jobid'=>$p_j[$pra['paperid']]])->one();
                    if($stu_job){
                        $stu_job -> starttime = $pra['starttime'];
                        $stu_job -> duration = $pra['duration'];
                        $stu_job -> save(false);
                        $sql = "select * from tb_u_user_question_record_$i WHERE practiceid = ".$pra['id'];
                        $question_record  = Yii::$app->db->createCommand($sql)->queryAll();
                        if($question_record){
                            foreach($question_record as $key => &$q_r){
                                unset($q_r['id']);
                                unset($q_r['practiceid']);
                                unset($q_r['exam_level']);
                                unset($q_r['exam_type']);
                                $q_r['student_job_id'] = $stu_job->id;
                                $q_r['questionid'] = $stu_job->classid*1000000+$q_r['questionid'];
                                if(!empty($q_r['questionitemid'])){
                                    $q_r['questionitemid'] =  $stu_job->classid*1000000+$q_r['questionitemid'];

                                }
                            }
                            Yii::$app->db->createCommand()->batchInsert('tb_zy_question_record', array_keys($question_record[0]), $question_record)->execute();
                            $sql2 = "select * from tb_u_user_question_items_record_$i WHERE practiceid = ".$pra['id'];
                            $question_item_record  = Yii::$app->db->createCommand($sql2)->queryAll();
                            if(!empty($question_item_record)){
                                foreach($question_item_record as $kk => &$q_i_r){
                                    unset($q_i_r['id']);
                                    unset($q_i_r['practiceid']);
                                    $q_i_r['student_job_id'] = $stu_job->id;
                                    $q_i_r['questionid'] = $stu_job->classid*1000000+$q_i_r['questionid'];
                                    if(!empty($q_i_r['questionitemid'])){
                                        $q_i_r['questionitemid'] = $stu_job->classid*1000000+$q_i_r['questionitemid'];
                                    }
                                }
                                Yii::$app->db->createCommand()->batchInsert('tb_zy_question_items_record', array_keys($question_item_record[0]), $question_item_record)->execute();
                            }
                        }
                    }
                }
            }
            echo "complate $i \r\n";

        }
        echo "ok \r\n";

    }
    /**
     * 清除无用数据
     */
    public function actionDelError(){
        $sql = "DELETE FROM tb_zy_student_jobs WHERE jobstate = 0";
        $question_record  = Yii::$app->db->createCommand($sql)->execute();
        $sql = "UPDATE tb_zy_jobs SET state = 1 ";
        $question_record  = Yii::$app->db->createCommand($sql)->execute();
        echo "ok \r\n";
    }
    /**
     * app banner 弹框 h5活动 初始化
     * 定时任务
     * 修改后调用
     */
    public function actionAppBannerInit(){
        $exam_province = array_keys(Yii::$app->params['exam_province']);
        $exam_level = array_keys(Yii::$app->params['exam_level']);
        $exam_type = array_keys(Yii::$app->params['exam_type']);
        $user_group = ["TRY","NORMAL"];
        $date = date("Y-m-d H:i:s");
        $redis = DistStorage::getMainRedisConn();
        foreach($user_group as $group){
            foreach($exam_province as $province){
                foreach($exam_type as $type){
                    foreach($exam_level as $level){
                        $app_banner = [];
                        $open_banner = []; //旧版本
                        $open_banner_3 = []; //新版本
                        $app_activity = [];
                        $app_activity_types = [];
                        if($group == 'TRY'){
                            $sql = "SELECT * FROM tb_a_app_banner WHERE price < 0.01 and lapsedtime >='$date' and inuretime <= '$date' and ( user_group = 2 or ( user_group = 0 and FIND_IN_SET('$province',province) and FIND_IN_SET('$level',examlevel)  and examtype = $type ) ) ORDER BY advflag desc,orders desc,id desc ";
                        }else{
                            $sql = "SELECT * FROM tb_a_app_banner WHERE user_group in (0,1) AND lapsedtime >='$date' and inuretime <= '$date' and FIND_IN_SET('$province',province) and FIND_IN_SET('$level',examlevel)  and examtype = $type  ORDER BY advflag desc,orders desc,id desc ";
                        }
                        $banners = Yii::$app->db->createCommand($sql)->queryAll();
                        if($banners){
                            foreach($banners as $ban){
                                if($ban['advflag'] == 3 || $ban['advflag'] == 2){ //h5活动 弹框
                                    if(!in_array($ban['advflag'],$app_activity_types)){
                                        $app_activity[] = $ban;
                                        $app_activity_types[] = $ban['advflag'];
                                    }
                                }elseif($ban['advflag'] == 1){ //启动页面
                                    if(count($open_banner) < 1 && $ban['showtype'] = 1 ){
                                        if($group == 'TRY'){
                                            if($ban['type'] != 2){
                                                $open_banner[] = $ban;
                                            }
                                        }else{
                                            $open_banner[] = $ban;
                                        }
                                    }
                                    if(count($open_banner_3) < 1){
                                        if($group == 'TRY'){
                                            if($ban['type'] != 2){
                                                $open_banner_3[] = $ban;
                                            }
                                        }else{
                                            $open_banner_3[] = $ban;
                                        }
                                    }
                                }elseif($ban['advflag'] == 0){ //banner
                                    if(count($app_banner) < 3){
                                        $app_banner[] = $ban;
                                    }
                                }
                            }
                        }
                        $redis -> hmset("APP_ADS_".$group."_".$province."_".$type."_".$level,[
                            'banner' => json_encode($app_banner),
                            'activity' => json_encode($app_activity),
                            'open_banner' => json_encode($open_banner),
                            'open_banner_3' => json_encode($open_banner_3),
                        ]);
                    }
                }
            }
        }
    }

}
