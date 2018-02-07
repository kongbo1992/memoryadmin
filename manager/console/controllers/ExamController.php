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
use common\models\TbQuPaper;

use common\models\TbZyStudentJobs;
use common\tools\DistStorage;
use Yii;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ExamController extends Controller
{
    public function actionAllUpdate(){
        $this->actionExamlist1();
        $this->actionExamlist2();
        $this->actionPapers();
        $this->actionProvinces();
    }


    public function actionExamlist2()
    {
        $redis = DistStorage::getMainRedisConn();
        $provinces = array_keys(Yii::$app->params['exam_province']);
        foreach ($provinces as $k => $v) {
            $paperlist = array();
            $str = "APP_EXAMLIST_ZP_" .$k;
            $paperlist = TbQuPaper::find()
                ->where(['provinceid' => $k])
                ->andWhere('type=2 and exam_type=2 and validstate=1')
                ->orderBy('starttime desc')
                ->asArray()
                ->all();
            $redis->hset($str, 0, json_encode($paperlist));
        }
    }
//    教师资格证模考展示
    public function actionExamlist1()
    {
        $redis = DistStorage::getMainRedisConn();
        $exam_level = array_keys(Yii::$app->params['exam_level']);
        foreach ($exam_level as $k => $v) {
            $paperlist = array();
            $str = "APP_EXAMLIST_ZGZ_" .$k;
            if ($k == 4) $k = 3;
            $paperlist = TbQuPaper::find()
                ->where(['exam_level' => $k])
                ->andWhere('type=2 and exam_type=1 and validstate=1')
                ->orderBy('starttime desc')
                ->asArray()
                ->all();
            $redis->hset($str, 0, json_encode($paperlist));
        }
    }

    public function actionProvinces()
    {
//        $exam_province = array_keys(Yii::$app->params['exam_province']);
        $redis = DistStorage::getMainRedisConn();
        $exam_level = array_keys(Yii::$app->params['exam_level']);
        $exam_type = array_keys(Yii::$app->params['exam_type']);
        foreach ($exam_type as $k => $v) {
            foreach ($exam_level as $kk => $vv) {
                $provinces = array();
                $str = "APP_PROVINCES_" . $k . "_" . $kk;
                $sql = "select distinct provinceid  from tb_qu_paper where type = 1 and exam_type = $k and find_in_set($kk,exam_level)  and provinceid > 0 and validstate = 1";
                $pvlist = Yii::$app->db->createCommand($sql)->queryAll();
                if ($pvlist) {
                    foreach ($pvlist as $pl) {
                        $provinces[] = $pl["provinceid"];
                    }
                }
                $redis->hset($str, 0, json_encode($provinces));
            }
        }
    }

    public function actionPapers()
    {
        $redis = DistStorage::getMainRedisConn();
        $provinces = Yii::$app->params['exam_province'];
        $provinces[0] = "全部";
        $exam_type = Yii::$app->params['exam_type'];
        $exam_level = Yii::$app->params['exam_level'];
        $years = array();
        foreach ($exam_type as $k0 => $v0) {
            foreach ($exam_level as $k1 => $v1) {
                foreach ($provinces as $k2 => $v2) {
                    $years = array();
                    $str = "APP_YEAR_" . $k0 . "_" . $k1 . "_" . $k2;
                    $sql = "select distinct year  from tb_qu_paper where type = 1 and exam_type = $k0 and find_in_set($k1,exam_level)  and (provinceid = 0 or provinceid = $k2 OR $k2=0) and validstate = 1 order by year desc";
                    $ylist = Yii::$app->db->createCommand($sql)->queryAll();
                    if ($ylist) {
                        foreach ($ylist as $yl) {
                            $years[] = $yl["year"];
                        }
                    }
                    $redis->hset($str, 0, json_encode($years));

//                    点击进入按年份展示的所以模考试卷
                    foreach ($years as $value) {
                        $str1 = "APP_PPLIST_" . $k0 . "_" . $k1 . "_" . $k2 . "_" . $value;
                        $sql = "select id,title,subjectid from tb_qu_paper where type = 1 and exam_type = $k0 and find_in_set($k1,exam_level)  and (provinceid = 0 or provinceid = $k2 OR $k2=0) and year = '$value' and validstate = 1 order by title";
                        $pplist = Yii::$app->db->createCommand($sql)->queryAll();
                        $redis->hset($str1, 0, json_encode($pplist));
                    }
                }
            }
        }

    }

//    更新分省大纲缓存
    public function actionGenProvinceEduRedis($id){
        $redis = DistStorage::getMainRedisConn();
        $list = (new \yii\db\Query())
            ->from('tb_dc_education_practice')
            ->where(['pid'=>$id])
            ->all();
        if ($list){
            foreach($list as $one){
                $edu_id = $one["edu_id"];
                if (!empty($edu_id)){
                    $eduids = explode(",", $edu_id);
                    foreach($eduids as $eid){
                        $redis->sadd("EDU_PRACTICE_MAP:".$one["id"],$eid);
                    }
                }
                $this->actionGenProvinceEduRedis($one["id"]);
            }
        }
    }
//    更新标准大纲缓存
    public function actionGenPracticeEduRedis($id){
        $redis = DistStorage::getMainRedisConn();
        $one['id']=88888;
        $redis->hMset("EDU_INFO:".$one["id"],$one);
        $list = (new \yii\db\Query())
            ->from('tb_dc_education')
            ->where(['pid'=>$id])
            ->all();
        if ($list){
            foreach($list as $one){
                $redis->hMset("EDU_INFO:".$one["id"],$one);
                $redis->del("EDU_QUESTION_MAP:".$one["id"].":1");
                $redis->del("EDU_QUESTION_MAP:".$one["id"].":2");
                $redis->del("EDU_QUESTION_MAP:".$one["id"].":3");
                $redis->del("EDU_QUESTION_MAP:".$one["id"].":4");

                $redis->sadd("EDU_CHILDREN:".$one["id"],$one["id"]);
                if ($id > 0){
                    $this->actionAddPracticeEdu($one["id"],$id);
                }
                $this->actionGenPracticeEduRedis($one["id"]);
            }
        }
    }

    //把id添加到夫id，以及父id上面所有的父id
    public function actionAddPracticeEdu($id,$pid){
        $redis = DistStorage::getMainRedisConn();
        $theid = $pid;
        $info = (new \yii\db\Query())
            ->from('tb_dc_education')
            ->where(['id'=>$theid])
            ->one();
        while ($info){
            //处理当前节点
            $redis->sadd("EDU_CHILDREN:".$theid,$id);

            //父亲节点的值
            if ($info["pid"] == 0 || $info["pid"] == 1) break;

            $theid = $info["pid"];
        }
    }

}
