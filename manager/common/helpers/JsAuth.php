<?php
namespace common\helpers;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * CREATE TABLE `tb_u_user_token` (
  `userid` int(11) NOT NULL,
  `pctoken` varchar(255) DEFAULT NULL,
  `apptoken` varchar(255) DEFAULT NULL,
  `wxtoken` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */


use common\tools\DistStorage;
use common\helpers\Message;
class JsAuth{
    const LOGIN_TYPE_PC = 1;
    const LOGIN_TYPE_APP = 2;
    const LOGIN_TYPE_WX = 3;
    
    /*
     * 生成用户token，在用户注册时使用
     * 入参：电话号码、登录类型
     * 出参：新的认证key
     */
    public function genAuthKey($userid,$type = self::LOGIN_TYPE_PC){
        $redis = DistStorage::getRedisConn(3,$userid);
        //1，生成一个随机token
        $class = new Message();
        $userToken = $class->random(8);
        //2，保存token到数据库
        $data["userid"] = $userid;
        $data["pctoken"] = "";
        $data["apptoken"] = "";
        $data["wxtoken"] = "";
        switch ($type){
            case self::LOGIN_TYPE_PC:
                $data["pctoken"] = $userToken;
                break;
            case self::LOGIN_TYPE_APP:
                $data["apptoken"] = $userToken;
                break;
            case self::LOGIN_TYPE_WX:
                $data["wxtoken"] = $userToken;
                break;
            default :break;
        }
        $info = array(
            'exam_type' => 1,
            'userid' => $userid,
            'remind' => 0,
            'amount' => 30,
            'isdeleteerrorquestion' => 1,
            'isopenclass' => 1,
            'showfavoriteclass' => 0,
        );
        $conn =\Yii::$app->db;
        $conn -> createCommand()->insert('gongji.tb_u_user_token', $data)->execute();
        $conn -> createCommand()->insert('tb_u_user_token', $data)->execute();
        $conn -> createCommand()->insert('tb_u_user_setting', $info)->execute();
        $conn -> createCommand()->insert('gongji.tb_u_user_setting', $info)->execute();

        $redis->hMset('USER_TOKEN:'.$userid,$data );
        $redis->hMset('USER_SETTING:'.$userid,$info );
        //3，生成key
        return $this->encryptUserToken($userid, $type, $userToken);
    }
    

    /*
     * 生成认证key
     * 入参：电话号码、登录类型、token
     * 出掺：认证key
     */
    private function encryptUserToken($userid,$type,$token){
        return base64_encode($userid." ".$type." ".$token);
    }
    
    /*
     * 解密认证key
     * 入参：认证key
     * 出掺：数组（userid=用户id、type=登录类型、token）
     */
    private function decryptAuthKey($key){
        $keybase = base64_decode($key);
        $userInfo = explode(" ",$keybase);
        if (count($userInfo) != 3){
            return false;
        }
        return array(
            'userid' => $userInfo[0],
            'type' => $userInfo[1],
            'token' => $userInfo[2]
        );
    }

}

