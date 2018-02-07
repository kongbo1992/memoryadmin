<?php
namespace common\tools;
use Yii;
use yii\db\Exception;


class RedisHash {
    private $_servers = array();    //虚拟节点

    private $_serverKeys = array();

    private $_badServers = array(); // 故障服务器列表

    private $_count = 0;        //服务器最大索引

    const SERVER_REPLICAS = 32; //服务器副本数量，提高一致性哈希算法的数据分布均匀程度

    public function __construct( $num ){
        $this->_count = $num;

        //Redis虚拟节点哈希表
        for ($i=1;$i<=$this->_count;$i++) {
            for ($j = 0; $j < self::SERVER_REPLICAS; $j++) {
                $hash = sprintf("%u",crc32('REDISHASH#'. $i."_".$j));
                $this->_servers [$hash] = $i;
            }
        }
        ksort( $this->_servers );
        $this->_serverKeys = array_keys($this-> _servers);
    }

    /**
     * 使用一致性哈希计算服务器索引
     */
    public function getRedisIndex($key){
        $hash = sprintf("%u", crc32('redishost#'.$key));
        $slen = $this->_count * self:: SERVER_REPLICAS;

        // 快速定位虚拟节点
        $sid = $hash > $this->_serverKeys [$slen-1] ? 0 : $this->quickSearch($this->_serverKeys, $hash, 0, $slen);

        return $this->_servers [$this->_serverKeys[$sid]];
    }

    /**
     * 使用一致性哈希分派服务器，附加故障检测及转移功能
     */
    public function getRedis($type,$key){
        $hash = sprintf("%u", crc32($key));
        $slen = $this->_count * self:: SERVER_REPLICAS;

        // 快速定位虚拟节点
        $sid = $hash > $this->_serverKeys [$slen-1] ? 0 : $this->quickSearch($this->_serverKeys, $hash, 0, $slen);

        $conn = false;
        $i = 0;
        do {
            $n = $this->_servers [$this->_serverKeys[$sid]];

            !in_array($type."_".$n, $this->_badServers ) && $conn = $this->getRedisConnect($type,$n);
            $sid = ($sid + 1) % $slen;
        } while (!$conn && $i++ < $slen);

        return $conn ? $conn : false;
    }

    /**
     * 二分法快速查找
     */
    private function quickSearch($stack, $find, $start, $length) {
        if ($length == 1) {
            return $start;
        }
        else if ($length == 2) {
            return $find <= $stack[$start] ? $start : ($start +1);
        }

        $mid = intval($length / 2);
        if ($find <= $stack[$start + $mid - 1]) {
            return $this->quickSearch($stack, $find, $start, $mid);
        }
        else {
            return $this->quickSearch($stack, $find, $start+$mid, $length-$mid);
        }
    }

    private function getRedisConnect($type,$n=1){
        static $REDIS = array();
        if (empty($REDIS[$type."_".$n])){
            $options["host"] = Yii::$app->params['REDIS_HOST'.$type.":".$n] ? : Yii::$app->params['REDIS_HOST'.$type];
            $options["port"] = Yii::$app->params['REDIS_HOST_PORT'.$type.":".$n] ? : 6379;
            $options['auth'] = 'Born';
            try{
                $REDIS[$type."_".$n] = new \Redis();
                $REDIS[$type."_".$n] -> connect($options['host'],$options['port']);
                $REDIS[$type."_".$n] -> auth($options['auth']);
            } catch(Exception $e){
                unset($REDIS[$type."_".$n]);
                $this->_badServers [] = $type."_".$n;
                return false;
            }
        }
        return $REDIS[$type."_".$n];
    }
}