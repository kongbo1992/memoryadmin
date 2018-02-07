<?php
namespace common\helpers;

use backend\modules\stat\models\StatsDownload;
use common\tools\DistStorage;
use Yii;
use yii\db\Exception;
use backend\modules\zhaopin\models\JobsJob;
class Stats
{
//    数据分析模块的获取
    public static function Module($code=null){
        static $module =[];
        if(empty($module)){
            $module = (new \yii\db\Query())
                ->select("id,name")
                ->from("stats_module")
                ->andWhere(['status' => [0,1]])
                ->all();
        }
        $result = [];
        foreach($module as $key => $val){
            $result[$val['id']] = $val['name'];
        }
        if(!empty($code)){
            return !empty($result[$code])?$result[$code]:"错误";
        }else{
            return $result;
        }
    }

//    启用的模块
    public static function ModuleUse(){
        $role = Yii::$app->user->identity->role;
        $module = (new \yii\db\Query())
            ->select("id,name")
            ->from("stats_module")
            ->where("FIND_IN_SET($role,power) and status = 1")
            ->all();
        $result = [];
        foreach($module as $key => $val){
                $result[$val['id']] = $val['name'];
        }
        return $result;
    }

//    查看用户分布省份情况；入参 users
    public static function ProvinceDistribution($users){
        $sql = "SELECT province,userid from tb_u_user_setting WHERE userid in (".implode(',',$users['total']).")";
        $user = Yii::$app->db->createCommand($sql)->queryAll();
        $user_pid = [];
        foreach($user as $key => $val ){
            $user_pid[$val['userid']] = $val['province'];
        }
        $province = [];
        foreach($user as $key => $val){
            if($val['province'] < 1 || $val['province'] > 31){
                $province['total'][199][] = $val['userid'];
            }else{
                $province['total'][$val['province']][] = $val['userid'];
            }
        }
        if(!empty($users['day'])){
            foreach($users['day'] as $key => $val){
                if(!empty($user_pid[$val])){
                    if($user_pid[$val] <1 || $user_pid[$val] >31){
                        $province['day'][199][] = $val;
                    }else{
                        $province['day'][$user_pid[$val]][] = $val;
                    }
                }else{
                    $province['day'][199][] = $val;
                }
            }
        }
        $num = [];
        foreach($province as $key => $val){
            foreach($val as $ko => $vo){
                $num[$key][$ko] = count($vo);
            }
        }
        $result = [];
        if(!empty($num['total'][199])){
            $num['total'][199] = $num['total'][199] + (count($users['total']) - count($user));
        }
        foreach($num['total'] as $key => $val){
            $key = StatsDownload::province_view($key);
            $result[$key] = array(
                'total' => $val,
                'day' => !empty($num['day'][$key])?$num['day'][$key]:0
            );
        }
        return $result;
    }


    //    查看用户分布城市情况；入参 users
    public static function CityDistribution($users,$id){
        $sql = "SELECT a.postcity,o.userid from z_order as o INNER JOIN tb_u_user_address as a ON a.id = o.addressid WHERE o.userid in (".implode(',',$users['total']).") and o.`Status` = 2 and  ClassID = $id";
        $adress = Yii::$app->db->createCommand($sql)->queryAll();
        $user = [];
        $city = [];
        foreach($adress as $key => $val ){
            $user[$val['userid']] = $val['postcity'];
            $city['total'][$val['postcity']][] = $val['userid'];
        }
        $city['total']['其他'] = count($users['total']) - count($adress);

        if(!empty($users['day'])){
            foreach($users['day'] as $key => $val){
                if(!empty($user[$val])){
                    $city['day'][$user[$val]][] = $val;
                }else{
                    $city['day']['其他'][] = $val;
                }
            }
        }
        $num = [];
        foreach($city as $key => $val){
            foreach($val as $ko => $vo){
                if($key == "total" && $ko == "其他"){
                    continue;
                }
                $num[$key][$ko] = count($vo);
            }
        }
        $result = [];
        $a = 0;
        foreach($num['total'] as $key => $val){
//            $a = $a + $val;
            $result[$key] = array(
                'total' => $val,
                'day' => !empty($num['day'][$key])?$num['day'][$key]:0
            );
        }
//        var_dump($a);
        return $result;
    }

}
  
      