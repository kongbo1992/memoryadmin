<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/21
 * Time: 14:20
 */

namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\helpers\SendRdyxMsg;
use common\tools\Elasticsearch;
use common\tools\DistStorage;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StatsController extends Controller
{

//    定时任务（购课数）
    public function actionTasksClassNum(){
        ini_set('max_execution_time', 0);
        $time = date("Y-m-d",strtotime("-1 day"));
        $sql = "SELECT count(*) as num ,DATE_FORMAT(PayTime,'%H') as h_time,DATE_FORMAT(PayTime,'%y-%m-%d') as d_time,ClassID from z_order WHERE PayTime >='".$time." 00:00:00' and  PayTime <='".$time." 23:59:59' AND `Status` = 2 GROUP BY DATE_FORMAT(PayTime,'%y-%m-%d %H'),ClassID";
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $list = [];
        foreach($data as $key => $val){
            $list[$val['ClassID']][$val['d_time']][] = $val;
        }
        $sql = "SELECT count(*) as num ,ClassID from z_order_tuifei WHERE tuifei_time >='".$time." 00:00:00' and tuifei_time <='".$time." 23:59:59' GROUP BY DATE_FORMAT(tuifei_time,'%y-%m-%d'),ClassID";
        $tuifei = Yii::$app->db->createCommand($sql)->queryAll();
        $tuifei_fz = [];
        foreach($tuifei as $key => $value){
            $tuifei_fz[$value['ClassID']] = $value['num'];
        }
        $result = [];
        foreach($list as $key=>$val){
            foreach($val as $ko => $vo){
                $a = [];
                $a['classid'] = $key;
                $a['statday'] = $ko;
                $a['total'] = 0;
                if(!empty($tuifei_fz[$key])){
                    $a['backnum'] = $tuifei_fz[$key];
                    unset($tuifei_fz[$key]);
                }else{
                    $a['backnum'] = 0;
                }
                for($i = 1;$i <= 24;$i++){
                    $name = "num".$i;
                    $a[$name] = 0;
                }
                foreach($vo as $k => $v){
                    $v['h_time'] = $v['h_time']+1;
                    $name = "num".$v['h_time'];
                    $a[$name] = $v['num'];
                    $a['total'] = $a['total'] + $v['num'];
                }
                for($i = 1;$i <= 24;$i++){
                    $name = "num".$i;
                    if(empty($a[$name])){
                        $a[$name] = 0;
                    }else{
                    }
                }
                $result[] = $a;
            }
        }
        if(!empty($tuifei_fz)){
            foreach($tuifei_fz as $key => $val){
                $a = [];
                $a['classid'] = $key;
                $a['statday'] = $time;
                $a['total'] = 0;
                $a['backnum'] = $val;
                for($i = 1;$i <= 24;$i++){
                    $name = "num".$i;
                    $a[$name] = 0;
                }
                $result[] = $a;
            }
        }
        Yii::$app->db->createCommand()->batchInsert('stats_class_num', ['classid','statday','total','backnum','num1','num2','num3','num4','num5','num6','num7','num8','num9','num10','num11','num12','num13','num14','num15','num16','num17','num18','num19','num20','num21','num22','num23','num24'], $result)->execute();
        return true;
    }
//    看课数据生成（总体）
//    public function actionClassPlayDisposable(){
//        ini_set('max_execution_time', 0);
//        $sql = "SELECT begintime,endtime,classname,id ,classid  from class_chapter  WHERE begintime >= '2016-06-01'";
//        $chapter = Yii::$app->db->createCommand($sql)->queryAll();
//        $class_id = [];
//        $class = [];
//        foreach($chapter as $key => $val){
//            $class_id[] = $val['id'];
//            $class[$val['id']] = $val;
//        }
//        $class_id = array_unique($class_id);
//        $time = strtotime(date("Y-m-d",strtotime("-1 day")));
//        $time = 1494172800;
//        foreach($class_id as $key => $val){
//            $result = [];
//            $sql = "select inter_time,leave_time,classid,userid from tb_u_play_record_20170508_copy WHERE inter_time<$time AND classid = ".$val;
//            $data = Yii::$app->db->createCommand($sql)->queryAll();
//            $details = $class[$val];
//            $mredis = DistStorage::getMainRedisConn();
//            $salescount =  $mredis->hget('CLASS_' . $details['classid'], 'salescount');
//            if(empty($salescount)){
//                $salescount = (new \yii\db\Query())
//                    ->select('SalesCount')
//                    ->from('class')
//                    ->where("ClassID = ". $details['classid'])
//                    ->one();
//                $salescount = $salescount['SalesCount'];
//            }
//            $details['endtime'] = strtotime($details['endtime']);
//            $details['begintime'] = strtotime($details['begintime']);
////            看直播课次数
//            $to_num = [];
////            看课总次数
//            $all_num = [];
////            录播次数
//            $rec_num = [];
//            foreach($data as $k => $v){
//                if($v['inter_time']>=$details['begintime'] && $v['inter_time']<=$details['endtime']){
//                    $to_num[] = $v['userid'];
//                }else{
//                    $rec_num[] = $v['userid'];
//                }
//                $all_num[] = $v['userid'];
//            }
//            $to_num_z = array_unique($to_num);
//            $all_num_z = array_unique($all_num);
//            $rec_num_z = array_unique($rec_num);
//            $result[] = array(
//                'classchapterid' => $details['id'],
//                'classid' =>$details['classid'],
//                'class_chapter_name' => $details['classname'],
//                'class_salescount' => $salescount,
//                'live_times' => count($to_num),
//                'live_num' => count($to_num_z),
//                'total_num' => count($all_num_z),
//                'rec_num' => count($rec_num_z),
//                'rec_times' => count($rec_num),
//            );
//            Yii::$app->db->createCommand()->batchInsert('stats_play_rate', ['classchapterid','classid','class_chapter_name','class_salescount','live_times','live_num','total_num','rec_num','rec_times'], $result)->execute();
//        }
//        return true;
//    }

    //    课程脚本
    public function actionClassNumDisposable(){
        ini_set('max_execution_time', 0);
        $sql = "SELECT ClassID,ClassName from class WHERE OnSaleTime >= '2016-01-01'";
        $class = Yii::$app->db->createCommand($sql)->queryAll();
        $class_name = [];
        foreach($class as $key => $val){
            $class_name[$val['ClassID']] = $val['ClassName'];
        }
        $time = date("Y-m-d",strtotime("-1 day"));
        foreach($class_name as  $keys => $vals){
            $sql = 'SELECT count(*) as num ,DATE_FORMAT(PayTime,"%H") as h_time,DATE_FORMAT(PayTime,"%y-%m-%d")as d_time,ClassID from z_order WHERE ClassID = '.$keys.' AND `Status` = 2 and PayTime<"'.$time.'" GROUP BY DATE_FORMAT(PayTime,"%y-%m-%d %H")';
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            $list = [];
            foreach($data as $key => $val){
                $list[$val['d_time']][] = $val;
            }
//            var_dump($list);die;

            $result = [];
            foreach($list as $key=>$val){
                $a = [];
                $a['classid'] = $val[0]['ClassID'];
                $a['statday'] = $val[0]['d_time'];
                $a['total'] = 0;
                for($i = 1;$i <= 24;$i++){
                    $name = "num".$i;
                    $a[$name] = 0;
                }
                foreach($val as $k => $v){
                    $v['h_time'] = $v['h_time']+1;
                    $name = "num".$v['h_time'];
                    $a[$name] = $v['num'];
                    $a['total'] = $a['total'] + $v['num'];
                }
                for($i = 1;$i <= 24;$i++){
                    $name = "num".$i;
                    if(empty($a[$name])){
                        $a[$name] = 0;
                    }else{
                    }
                }
                $result[] = $a;
            }
            Yii::$app->db->createCommand()->batchInsert('stats_class_num', ['classid','statday','total','num1','num2','num3','num4','num5','num6','num7','num8','num9','num10','num11','num12','num13','num14','num15','num16','num17','num18','num19','num20','num21','num22','num23','num24'], $result)->execute();
        }
        return true;
    }

//    定时任务（更新看课记录）
    public function actionTasksStatPlay(){
        ini_set('max_execution_time', 0);
        $limit = 1000;
        $sql = "SELECT MAX(stats_play_id) as maxid FROM stats_paly_log";
        $start_num = Yii::$app->db->createCommand($sql)->queryAll();
        $start =  !empty($start_num[0]['maxid'])?$start_num[0]['maxid']:50000;
        $end = $start + $limit;
        $sql = "SELECT MAX(ID) as maxid FROM tb_u_play_record WHERE inter_time<".strtotime(date("Y-m-d"));
//        $sql = "SELECT MAX(ID) as maxid FROM tb_u_play_record_20170508_copy WHERE inter_time< 1494172800";
        $max_play = Yii::$app->db->createCommand($sql)->queryAll();
        $max_play = $max_play[0]['maxid'];
//        var_dump($start);var_dump($max_play);die;
        $class = [];
        $class_id = [];
        while($start < $max_play){
            if($end > $max_play){
                $end = $max_play;
            }
            $sql = "select inter_time,leave_time,classid,userid from tb_u_play_record WHERE id > $start and id <= $end";
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            if(empty($data)){
                $start+=$limit;
                $end = $start + $limit;
                continue;
            }
            foreach($data as $key => $val){
                $class_id[] = $val['classid'];
                $class[$val['classid']][] = $val;
            }
            $start+=$limit;
            $end = $start + $limit;
            if($start > $max_play){
                Yii::$app->db->createCommand()->insert('stats_paly_log', [
                    'create_time' => date("Y-m-d H:i:s"),
                    'stats_play_id' => $max_play
                ])->execute();
                break;
            }
        }
        $class_chapter_id = [];
        foreach($class as $key => $val){
            $sql = "select * from stats_play_rate WHERE classchapterid = $key";
            $class_play = Yii::$app->db->createCommand($sql)->queryAll();
//            判断是否之前有看课记录
            if(!empty($class_play)){
//                读取redis获取购课数
                $mredis = DistStorage::getMainRedisConn();
                $salescount =  $mredis->hget('CLASS_' . $class_play[0]['classid'], 'salescount');
                if(empty($salescount)){
                    $salescount = (new \yii\db\Query())
                        ->select('SalesCount')
                        ->from('class')
                        ->where("ClassID = ". $class_play[0]['classid'])
                        ->one();
                    $salescount = $salescount['SalesCount'];
                }
//                获取今天新增的看课用户（之前没有看课）
                $sql = "SELECT DISTINCT userid FROM	tb_u_play_record WHERE	classid = $key AND userid NOT IN ( SELECT DISTINCT userid	FROM tb_u_play_record	WHERE	classid = $key AND inter_time < ".strtotime(date("Y-m-d",strtotime("-1 day"))).") AND inter_time >= ".strtotime(date("Y-m-d",strtotime("-1 day")))." AND inter_time <= ".strtotime(date("Y-m-d"));
                $user_new = Yii::$app->db->createCommand($sql)->queryAll();
                $total_num = $class_play[0]['total_num'] + count($user_new);
                $rec_num = $class_play[0]['rec_num'] + count($user_new);
                $rec_times = $class_play[0]['rec_times'] + count($val);
                $sql = "update stats_play_rate set class_salescount = $salescount ,total_num = $total_num , rec_num = $rec_num , rec_times = $rec_times WHERE classchapterid = $key";
                Yii::$app->db->createCommand($sql)->execute();die;
            }else{
                $class_chapter_id[] = $key;
            }
        }
//        之前没有记录课程
        if(!empty($class_chapter_id)){
            $class_chapter_class = [];
            $sql = "SELECT id,classid,begintime,endtime,classname from class_chapter WHERE id in (".implode(',',$class_chapter_id).")";
            $class_chapter = Yii::$app->db->createCommand($sql)->queryAll();
//            获取主课的classid
            foreach($class_chapter as $key => $val){
                $class_chapter_class[$val['id']] = $val;
            }
            foreach($class_chapter_id as $key => $val){
                $result = [];
                $mredis = DistStorage::getMainRedisConn();
                $salescount =  $mredis->hget('CLASS_' . $class_chapter_class[$val]['classid'], 'salescount');
                if(empty($salescount)){
                    $salescount = (new \yii\db\Query())
                        ->select('SalesCount')
                        ->from('class')
                        ->where("ClassID = ". $class_chapter_class[$val]['classid'])
                        ->one();
                    $salescount = $salescount['SalesCount'];
                }
//            看直播课次数
                $to_num = [];
//            看课总次数
                $all_num = [];
//            录播次数
                $rec_num = [];
                foreach($class[$val] as $k => $v){
                    if($v['inter_time']>=strtotime($class_chapter_class[$val]['begintime']) && $v['inter_time']<=strtotime($class_chapter_class[$val]['endtime'])){
                        $to_num[] = $v['userid'];
                    }else{
                        $rec_num[] = $v['userid'];
                    }
                    $all_num[] = $v['userid'];
                }
                $to_num_z = array_unique($to_num);
                $all_num_z = array_unique($all_num);
                $rec_num_z = array_unique($rec_num);
                $result[] = array(
                    'classchapterid' => $val,
                    'classid' => $class_chapter_class[$val]['classid'],
                    'class_chapter_name' => $class_chapter_class[$val]['classname'],
                    'class_salescount' => $salescount,
                    'live_times' => count($to_num),
                    'live_num' => count($to_num_z),
                    'total_num' => count($all_num_z),
                    'rec_num' => count($rec_num_z),
                    'rec_times' => count($rec_num),
                );
                Yii::$app->db->createCommand()->batchInsert('stats_play_rate', ['classchapterid','classid','class_chapter_name','class_salescount','live_times','live_num','total_num','rec_num','rec_times'], $result)->execute();
            }
        }
        return true;
    }

    public function actionClassPlayDisposable(){
        ini_set('max_execution_time', 0);
        $sql = "SELECT begintime,endtime,classname,id ,classid  from class_chapter  WHERE begintime >= '2016-06-01'";
        $chapter = Yii::$app->db->createCommand($sql)->queryAll();
        $class_id = [];
        $class = [];
        foreach($chapter as $key => $val){
            $class_id[] = $val['id'];
            $class[$val['id']] = $val;
        }
        $class_id = array_unique($class_id);
        $time = strtotime(date("Y-m-d",strtotime("-1 day")));

//        分段获取看课记录
        $limit = 5000;
        $sql = "SELECT MAX(ID) as maxid,MIN(ID) as minid FROM tb_u_play_record WHERE inter_time< $time ";
        $max_play = Yii::$app->db->createCommand($sql)->queryAll();
        $maxid = $max_play[0]['maxid'];
        $start =  !empty($max_play[0]['minid'])?$max_play[0]['minid']:1;
        $end = $start + $limit;
        $record_id = [];
        $play_record = [];
        while($start < $maxid){
            if($end > $maxid){
                $end = $maxid;
            }
            $sql = "select inter_time,leave_time,classid,userid,id from tb_u_play_record WHERE id > $start and id <= $end";
            $data = Yii::$app->db->createCommand($sql)->queryAll();
            if(empty($data)){
                $start+=$limit;
                $end = $start + $limit;
                continue;
            }
            foreach($data as $key => $val){
                $record_id[] = $val['classid'];
                $play_record[$val['classid']][] = $val;
            }
            $start+=$limit;
            $end = $start + $limit;
            if($start > $maxid){
                Yii::$app->db->createCommand()->insert('stats_paly_log', [
                    'create_time' => date("Y-m-d H:i:s"),
                    'stats_play_id' => $maxid
                ])->execute();
                break;
            }
        }

//        生成对应课程的看课记录
        foreach($play_record as $key => $val){
            $result = [];
//            看直播课次数
            $to_num = [];
//            看课总次数
            $all_num = [];
//            录播次数
            $rec_num = [];
            $begin_time = strtotime($class[$key]['begintime']);
            $end_time = strtotime($class[$key]['endtime']);
            foreach($val as $ko => $vo){
                if($vo['inter_time']>=$begin_time && $vo['inter_time']<=$end_time){
                    $to_num[] = $vo['userid'];
//                    $to_num['details'][] = $vo;
                }else{
                    $rec_num[] = $vo['userid'];
                }
                $all_num[] = $vo['userid'];
            }
//            生成直播记录
//            $this->LiveTelecast($to_num['details'],$begin_time,$end_time,$key);
            $to_num_z = array_unique($to_num);
            $all_num_z = array_unique($all_num);
            $rec_num_z = array_unique($rec_num);
//            获取课程卖出人数
            $mredis = DistStorage::getMainRedisConn();
            $salescount =  $mredis->hget('CLASS_' . $class[$key]['classid'], 'salescount');
            if(empty($salescount)){
                $salescount = (new \yii\db\Query())
                    ->select('SalesCount')
                    ->from('class')
                    ->where("ClassID = ". $class[$key]['classid'])
                    ->one();
                $salescount = $salescount['SalesCount'];
            }
            $result[] = array(
                'classchapterid' => $key,
                'classid' =>$class[$key]['classid'],
                'class_chapter_name' => $class[$key]['classname'],
                'class_salescount' => $salescount,
                'live_times' => count($to_num),
                'live_num' => count($to_num_z),
                'total_num' => count($all_num_z),
                'rec_num' => count($rec_num_z),
                'rec_times' => count($rec_num),
            );
            Yii::$app->db->createCommand()->batchInsert('stats_play_rate', ['classchapterid','classid','class_chapter_name','class_salescount','live_times','live_num','total_num','rec_num','rec_times'], $result)->execute();
        }

        return true;
    }



}
