<?php
namespace common\helpers;

use com_eeo_api\EeoApi;
use common\models\ClassChapter;
use common\models\ClassList;
use common\models\ClassTemp;
use Yii;
use yii\db\Exception;
use common\models\StatClassDetail;
use common\tools\DistStorage;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/29
 * Time: 17:23
 */
class ClassRedis {
    /*
      * 添加课程后调用
      * 入参：课程id  课程信息
      * 出参：
      */

    public static function afterAdd($class_id, $class) {
        $redis = DistStorage::getMainRedisConn();
        $class = array_change_key_case($class, CASE_LOWER);
        $class["frozen"] = 0;
//        if (!empty($data["parentid"])){
//            //面试课子课程,在EEO创建下课程
//            $eeoapi = new EeoApi();
//            $eeo_courseid = $eeoapi->createClass($data["classname"]);
//            if ($eeo_courseid){
//                $data["eeo_courseid"] = $eeo_courseid;
//                //这里还可以更新下数据库
//            }
//        }
        $redis->hMset('CLASS_' . $class_id, $class);
        $redis->sAdd("APPOINT_CLASS_" . $class_id, 0);
        if(isset($class['parentid']) && $class['parentid'] > 2){
            for($i=0;$i<$class['stock'];$i++){
                $redis -> lPush("CLASS_FROZEN_LIST_".$class_id,1);
            }
            if(!empty($class['interviewid'])){
                $redis -> zAdd("INTERVIEW_CLASSLIST_UP_".$class['interviewid'],(int)0,$class['classid']);
            }

        }
    }
    /**
     * @param $class_id
     * @param
     * @throws
     */
    public static function afterDelete($class_id,$class){
        $class = array_change_key_case($class, CASE_LOWER);
        $redis = DistStorage::getMainRedisConn();
        $redis -> del('CLASS_' . $class_id);
        if($class['interviewid']){
            $redis -> zRem("INTERVIEW_CLASSLIST_UP_".$class['interviewid'],$class_id);
            Yii::$app->db->createCommand("UPDATE class_temp SET roomcount = roomcount - 1 WHERE ClassID = ".$class['interviewid'])->execute();
        }
        //维护包对应关系
        if(!empty($class['parentid'])){
            $redis -> zRem("CHILD_CLASSLIST_".$class['parentid'],$class_id);
        }
    }
    /*
     * 修改课程后调用
     * 入参：课程id  课程信息
     * 出参：
     */

    public static function afterUpd($class_id, $class ,$old_attrs=null) {
        $redis = DistStorage::getMainRedisConn();
        $class = array_change_key_case($class, CASE_LOWER);
        $SalesCount = $redis->hGet('CLASS_' . $class_id, 'salescount');
        $class['total'] = $class['stock'] - $SalesCount;
        unset($class['salescount']);
        $redis->hMset('CLASS_' . $class_id, $class);
        if(isset($class['parentid']) && $class['parentid'] > 2 && !empty($old_attrs)){ //包课程增加库存逻辑
            $old_attrs = array_change_key_case($old_attrs, CASE_LOWER);
            if(isset($old_attrs['stock']) && $class['stock'] > $old_attrs['stock']){
                $minus = $class['stock'] - $old_attrs['stock'];
                for($i=0;$i<$minus;$i++){
                    $redis -> lPush("CLASS_FROZEN_LIST_".$class_id,1);
                }
            }
        }
    }
    /**课程 上架 下架
     * @param $class_id
     * @param $class
     * @param bool $multi 如果为 true 则不修改自动上下架逻辑
     * @throws Exception
     */
    public static function afterStatusChange($class_id,$class,$multi = false) {
        $redis = DistStorage::getMainRedisConn();
        $class = array_change_key_case($class, CASE_LOWER);
        if (!$redis->exists("CLASS_" . $class_id)) {
            $redis->hmSet('CLASS_' . $class_id, $class);
        } else {
            $redis->hSet('CLASS_' . $class_id, 'classstatus', $class['classstatus']);
        }
        //面试课处理逻辑
        if(!empty($class['interviewid'])){
            if( $class['classstatus'] == 1){
                $redis -> zAdd("INTERVIEW_CLASSLIST_UP_".$class['interviewid'],$class_id,$class_id);
                $redis -> sRem("INTERVIEW_CLASSLIST_DOWN_".$class["interviewid"],$class_id);
            }else if( $class['classstatus'] == 2){
                $redis -> zRem("INTERVIEW_CLASSLIST_UP_".$class['interviewid'],$class_id);
                $redis -> zRem("CHILD_CLASSLIST_".$class['parentid'],$class_id);
                if(!$multi){
                    $redis -> sadd("INTERVIEW_CLASSLIST_DOWN_".$class["interviewid"],$class_id);
                }
            }else{ //暂无逻辑

            }
        }
    }
    /***
     * 用户有没有购买过这个课
     */
    public static function isPay($classid,$userid){
        $redisMain = DistStorage::getRedisConn(1);
        $class = $redisMain->hGetAll('CLASS_' . $classid);
        $redis = DistStorage::getRedisConn(3,$userid);
        if ($redis->sismember('USER_CLASS_' . $userid,$classid)){
            return true;
        }

        if (!empty($class["parentid"])){
            if ($redis->sismember('USER_CLASS_' . $userid,$class["parentid"])){
                return true;
            }
        }
        return false;
    }
    /*
    * 支付成功调用
    * 入参：课程id
    * 出参：
    */
    public static function afterPay($class_id, $user_id, $mobile = null) {
        if (!empty($class_id) && !empty($user_id)) {
            $redis = DistStorage::getMainRedisConn();

            $redis->hIncrBy('CLASS_' . $class_id, 'salescount', 1); //售出加一
            $redis->hIncrBy('CLASS_' . $class_id, 'total', -1);     //库存减一

            $userredis = DistStorage::getRedisConn(3,$user_id);
            $userredis->sAdd('USER_CLASS_' . $user_id, $class_id);  //用户购课记录

            $queueredis = DistStorage::getQueueRedisConn();
            $queueredis->sAdd('SYNC_CLASS_LIST', $class_id);    //每日数据同步用
            //发送提醒
            //TODO
            $queueredis->lpush('USER_PAY_CLASSLIST_' . $class_id, $user_id);

            $class_info = $redis->hMGet('CLASS_' . $class_id, ['eeo_courseid','total','interviewid','frozen','parentid']);
            if (!empty($class_info['eeo_courseid'])){
                $redis -> rpop("CLASS_FROZEN_LIST_".$class_id);
                $queueredis->lpush("EEO_SYNC_USER_LIST",$class_info['eeo_courseid'].":".$user_id);
                $redis->lPop('CLASS_FROZEN_LIST_' . $class_id);
                if ($class_info["total"] <= $class_info["frozen"]  && !empty($class_info['interviewid'])){
                    $redis->zrem("INTERVIEW_CLASSLIST_UP_".$class_info["interviewid"],$class_id);
                    $redis->sadd("INTERVIEW_CLASSLIST_DOWN_".$class_info["interviewid"],$class_id);
                }
            }
            if (!empty($class_info['parentid'])){
                $redis->hIncrBy('CLASS_' . $class_info["parentid"], 'salescount', 1); //售出加一
                $redis->hIncrBy('CLASS_' . $class_info["parentid"], 'total', -1);     //库存减一
            }
        }
    }
    /*
     * 用户退费调用
     * 入参：课程id
     * 出参：
     */
    public static function afterTuifei($class_id, $user_id, $order_info = null) {
        if (!empty($class_id) && !empty($user_id)) {
            $userredis = DistStorage::getRedisConn(3,$user_id);
            $mredis = DistStorage::getMainRedisConn();
            if ($userredis->sRem('USER_CLASS_' . $user_id, $class_id)){ //用户购课记录
                $mredis->hIncrBy('CLASS_' . $class_id, 'salescount', -1); //售出加一
                $mredis->hIncrBy('CLASS_' . $class_id, 'total', 1);     //库存减一
            }
            
            $class_info = $mredis->hmget('CLASS_' . $class_id, ['eeo_courseid','interviewid','frozen','total','parentid']);
            if (!empty($class_info) && !empty($class_info['eeo_courseid'])){
                $mobile = $mredis->hget("USER_INFO:".$user_id,"mobile");
                if($class_info['frozen'] < $class_info['total']){
                    $mredis -> lPush("CLASS_FROZEN_LIST_".$class_id,1);
                    $mredis -> sRem('INTERVIEW_CLASSLIST_DOWN_'.$class_info['interviewid'],$class_id);
                    $mredis -> zAdd("INTERVIEW_CLASSLIST_UP_".$class_info['interviewid'],$class_id,$class_id);
                }
                $eeo = new EeoApi();
                $eeo ->deleteUserFromCourse($class_info['eeo_courseid'], 1, $mobile);
            }
            if (!empty($class_info["parentid"])){
                $mredis->hIncrBy('CLASS_' . $class_info["parentid"], 'salescount', -1); //售出减一
                $mredis->hIncrBy('CLASS_' . $class_info["parentid"], 'total', 1);     //库存加一
            }
            //退费后加入统计表
            if (!empty($order_info)) {
                $tuifei_date = date("Y-m-d H", time());
                $stat = StatClassDetail::find()->where(['class_id'=>$class_id,'create_date'=>$tuifei_date])->one();
                if ($stat) {
                    $stat -> refund_users = $stat -> refund_users + 1 ;
                    $stat -> refund = $stat -> refund + $order_info['Money'] ;
                    $stat -> save(false);
                } else {
                    $stat = new StatClassDetail();
                    $stat -> refund_users = 1 ;
                    $stat -> refund = $order_info['Money'] ;
                    $stat -> class_id = $class_id ;
                    $stat -> create_date = $tuifei_date ;
                    $stat -> save(false);
                }
            }
        }
    }

    /** 章节课程添加
     * @param $chapter
     * @throws Exception
     */
    public static function afterChapterAdd($chapter){
        $chapter = array_change_key_case($chapter, CASE_LOWER);
        $redis = DistStorage::getMainRedisConn();
        $redis -> hmset('CLASS_CHAPTER_'.$chapter['id'],$chapter);
        $score = 0;
        if(isset($chapter['iftrial']) && $chapter['iftrial']==1){
            $score = 1;
        }
        $redis -> zadd('CLASS_CHAPTER_LIST_'.$chapter['classid'],$score,$chapter['id']);
        if(!empty($chapter['zhibourl'])){
            $redis->sAdd("RM_".$chapter['zhibourl'],$chapter['id']);
        }
    }

    /** 章节课程修改
     * @param $chapter
     * @throws Exception
     */
    public static function afterChapterUpd($chapter){
        $chapter = array_change_key_case($chapter, CASE_LOWER);
        $redis = DistStorage::getMainRedisConn();
        $redis -> hmset('CLASS_CHAPTER_'.$chapter['id'],$chapter);
        $score = 0;
        if(isset($chapter['iftrial']) && $chapter['iftrial']==1){
            $score = 1;
        }
        $redis -> zadd('CLASS_CHAPTER_LIST_'.$chapter['classid'],$score,$chapter['id']);
        if(!empty($chapter['zhibourl'])){
            $redis->sAdd("RM_".$chapter['zhibourl'],$chapter['id']);
        }
    }

    /** 删除章节课程
     * @param $chapter
     * @throws Exception
     */
    public static function afterChapterDel($chapter){
        $redis = DistStorage::getMainRedisConn();
        $redis -> del('CLASS_CHAPTER_'.$chapter->id);
        $redis -> zrem('CLASS_CHAPTER_LIST_'.$chapter->classid,$chapter->id);
        if(!empty($chapter->zhibourl)){
            $redis->srem("RM_".$chapter->zhibourl,$chapter->id);
        }
        if(!empty($chapter->eeo_classid)){
            $chapter_else = ClassChapter::find()->where(['eeo_classid'=>$chapter->eeo_classid])->one();
            if(empty($chapter_else)){
                    $eeo = new EeoApi();
                    $eeo -> deleteChapter($chapter->eeo_courseid,$chapter->eeo_classid);
            }else{
                $parent = ClassList::findOne($chapter->classid);
                if($parent->eeo_courseid != $chapter->eeo_courseid){
                    $eeo = new EeoApi();
                    $students = Yii::$app->db->createCommand("SELECT u.mobile FROM z_order AS o INNER JOIN tb_u_user AS u ON o.userid = u.id WHERE o.ClassID = ".$chapter->classid." AND o.`Status` = 2")->queryAll();
                    if(!empty($students)){
                        $eeo -> deleteUserFromClassMulti($chapter->eeo_courseid,$chapter->eeo_classid,array_column($students,'mobile'));
                    }
                }
            }
        }
    }
    /** 批量上架面试课程
     * @param $interviewid
     * @param int $sale_count
     * @throws Exception
     */
    public static function afterBatchUp($interviewid,$sale_count = 3){
        $interviewid = (int)$interviewid;
        $sale_count = (int)$sale_count;
        $redis = DistStorage::getMainRedisConn();
        $down_class = $redis->sMembers("INTERVIEW_CLASSLIST_DOWN_".$interviewid);
        $all_classes = ClassList::find()->select('ClassID')->where(['interviewid' => $interviewid])->asArray()->indexBy('ClassID')->all();
        if($all_classes){
            $all_ids = array_keys($all_classes);
            $c_ids = array_diff($all_ids,$down_class);
            if(empty($c_ids)){
                $c_ids = [$all_ids[count($all_ids)-1]];
            }
            $c_ids = implode(',',$c_ids);
            Yii::$app->db->createCommand("UPDATE class SET ClassStatus = 1 WHERE interviewid = $interviewid AND ClassID in ($c_ids)")->execute();
            $up_classes = ClassList::find()->where(['interviewid'=>$interviewid,'ClassStatus'=>1])->orderBy(['ClassID'=>SORT_ASC])->all();
            if($up_classes){
                $num = 0;
                foreach($up_classes as $up){
                    self::afterStatusChange($up->ClassID,$up->attributes,true);
                    $num ++ ;
                    if($num <= $sale_count){
                        $redis -> zAdd("CHILD_CLASSLIST_".$up->ParentID,$interviewid,$up->ClassID);
                    }else{
                        $redis -> zRem("CHILD_CLASSLIST_".$up->ParentID,$up->ClassID);
                    }
                }
            }
        }

    }

    /**面试课批量下架
     * @param $intervieweid
     */
    public static function afterBatchDown($intervieweid){
        if(ClassList::updateAll(['ClassStatus'=>2],['interviewid'=>$intervieweid])){
            $classes = ClassList::find()->where(['interviewid'=>$intervieweid])->all();
            if($classes){
                $redis = DistStorage::getMainRedisConn();
                foreach($classes as $val){
                    ClassRedis::afterStatusChange($val->ClassID,$val->attributes,true);
                    $redis -> zRem("CHILD_CLASSLIST_".$val->ParentID,$val->ClassID);
                }
            }

        }
    }
}