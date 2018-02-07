<?php
namespace common\helpers;

use common\tools\DistStorage;
use Yii;
use yii\db\Exception;
class ExamRedis
{
//    教师招聘模考展示
    public static function Examlist2()
    {
        $redis = DistStorage::getMainRedisConn();
        $provinces = Yii::$app->params['exam_province'];
        foreach ($provinces as $k => $v) {
            $paperlist = array();
            $str = "APP_EXAMLIST_ZP_" .$k;
            $paperlist = (new \yii\db\Query())
                ->from('tb_qu_paper')
                ->where('type=2 and exam_type=2')
                ->andWhere(['provinceid' => $k])
                ->orderBy('starttime desc')
                ->all();
            var_dump($paperlist);
//            $paperlist = M("tb_qu_paper")->where("type=2 and exam_type=2 and provinceid=" . $k)->order("starttime desc")->select();
            $redis->hset($str, 0, json_encode($paperlist));
        }die;
    }
//    教师资格证模考展示
    public static function Examlist1()
    {
        $redis = DistStorage::getMainRedisConn();
        $exam_level = Yii::$app->params['exam_level'];
        foreach ($exam_level as $k => $v) {
            $paperlist = array();
            $str = "APP_EXAMLIST_ZGZ_" .$k;
            if ($k == 4) $k = 3;
            $paperlist = (new \yii\db\Query())
                ->from('tb_qu_paper')
                ->where('type=2 and exam_type=1')
                ->andWhere(['exam_level' => $k])
                ->orderBy('starttime')
                ->limit(2)
                ->all();
            $redis->hset($str, 0, json_encode($paperlist));
        }
    }

    public static function provinces()
    {
        $redis = DistStorage::getMainRedisConn();
        $exam_type = Yii::$app->params['exam_type'];
        $exam_level = Yii::$app->params['exam_level'];
        foreach ($exam_type as $k => $v) {
            foreach ($exam_level as $kk => $vv) {
                $provinces = array();
                $str = "APP_PROVINCES_" . $k . "_" . $kk;
                $sql = "select distinct provinceid  from tb_qu_paper where type = 1 and exam_type = $k and find_in_set($kk,exam_level)  and provinceid > 0 and validstate = 1";
                $pvlist = Yii::$app->db->createCommand($sql)->queryAll();
//                $pvlist = M()->query("select distinct provinceid  from tb_qu_paper where type = 1 and exam_type = $k and find_in_set($kk,exam_level)  and provinceid > 0 and validstate = 1");
                if ($pvlist) {
                    foreach ($pvlist as $pl) {
                        $provinces[] = $pl["provinceid"];
                    }
                }
                $redis->hset($str, 0, json_encode($provinces));
            }
        }
    }

    public static function papers()
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
//                    $ylist = M()->query("select distinct year  from tb_qu_paper where type = 1 and exam_type = $k0 and find_in_set($k1,exam_level)  and (provinceid = 0 or provinceid = $k2 OR $k2=0) and validstate = 1 order by year desc");
                    if ($ylist) {
                        foreach ($ylist as $yl) {
                            $years[] = $yl["year"];
                        }
                    }
                    $redis->hset($str, 0, json_encode($years));
                    var_dump($years);
//                    点击进入按年份展示的所以模考试卷
                    foreach ($years as $value) {
                        $str1 = "APP_PPLIST_" . $k0 . "_" . $k1 . "_" . $k2 . "_" . $value;
                        $sql = "select id,title,subjectid from tb_qu_paper where type = 1 and exam_type = $k0 and find_in_set($k1,exam_level)  and (provinceid = 0 or provinceid = $k2 OR $k2=0) and year = '$value' and validstate = 1 order by title";
                        $pplist = Yii::$app->db->createCommand($sql)->queryAll();
//                        $pplist = M()->query("select id,title,subjectid from tb_qu_paper where type = 1 and exam_type = $k0 and find_in_set($k1,exam_level)  and (provinceid = 0 or provinceid = $k2 OR $k2=0) and year = '$value' and validstate = 1 order by title ");
                        $redis->hset($str1, 0, json_encode($pplist));
                    }
                    var_dump($pplist);
                }
            }
        }die;

    }

//    做题记录初始化
    public function setting()
    {
        for ($i = 0; $i < 100; $i++) {
            $table = "tb_u_user_practice_record_" . $i;
            $data = M($table)->where(" paperid > 0 ")->field('userid,paperid')->select();
            foreach ($data as $k => $v) {
                $redis = DistStorage::getRedisConn(4,$v['userid']);
                $redis->sadd("USER_EXAM_HISTROY_".$v['userid'],$v['paperid']);
            }
        }
    }
  } 
  
      