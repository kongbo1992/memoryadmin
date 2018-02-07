<?php
namespace common\tools;
use Yii;
use yii\db\Exception;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/29
 * Time: 17:23
 */
class DistStorage {
    /*
     * 获取redis实例
     * 入参：用户id
     * 出参：redis实例
     */
    public static function getMainRedisConn(){
        static $main_redis_con = false;
        $options = Yii::$app->params["main_redis_conf"];
        if(!$options){
            throw new Exception("Redis error: main_redis_conf is not exists!");
        }
        if(!$main_redis_con){
            $main_redis_con = new \Redis();
            $main_redis_con -> connect($options['host'],$options['port']);
            $main_redis_con -> auth($options['auth']);
        }
        return $main_redis_con;
    }

    /*
     * 获取redis实例
     * 入参：用户id
     * 出参：redis实例
     */
    public static function getQueueRedisConn(){
        static $queue_redis_con = false;
        $options = Yii::$app->params["queue_redis_conf"];
        if(!$options){
            throw new Exception("Redis error: queue_redis_conf is not exists!");
        }
        if(!$queue_redis_con){
            $queue_redis_con = new \Redis();
            $queue_redis_con -> connect($options['host'],$options['port']);
            $queue_redis_con -> auth($options['auth']);
        }
        return $queue_redis_con;
    }
    /*
        * 获取redis实例
        * 入参：用户id
        * 出参：redis实例
        */
    public static function getRedisConn($type=1,$userid=null){
        //1,主数据库 用户数据、大纲、题、课、模考信息
        //2,模考数据
        //3,课程数据
        //4,练习数据
        //REDIS_HOST_COUNT_$TYPE 当前在用的服务器数量
        //REDIS_HOST_COUNT_OLD_$TYPE 老的服务器数量,初始化之后用来把老数据删除
        //REDIS_HOST_COUNT_NEW_$TYPE 新的服务器数量,用来新数据的初始化
        $num = Yii::$app->params['REDIS_HOST_COUNT_'.$type];
        $rh = new RedisHash($num);
        if ($type == 1 || empty($userid)){
            return $rh->getRedis($type,session_id());
        }else{
            return $rh->getRedis($type,$userid);
        }
    }
}