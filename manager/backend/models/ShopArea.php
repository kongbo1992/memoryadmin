<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

class ShopArea extends \common\models\ShopArea
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }


    public static function get_province(){
        static  $citys = [];
        if(empty($citys)){
            $citys = ArrayHelper::map(Yii::$app->db->createCommand("SELECT areaname FROM shop_area WHERE parentid = 0 ")->queryAll(),'areaname','areaname');
        }
        return $citys;
    }

    public static function get_city_by_name($province){
        static  $citys = [];
        if(empty($citys)){
            $citys = ArrayHelper::map(Yii::$app->db->createCommand("SELECT areaname FROM shop_area WHERE parentid = ( SELECT id FROM shop_area WHERE areaname = '$province' AND `level` = 1 )")->queryAll(),'areaname','areaname');
        }
        return $citys;
    }

    public static function get_area_by_name($city){
        static  $citys = [];
        if(empty($citys)){
            $citys = ArrayHelper::map(Yii::$app->db->createCommand("SELECT areaname FROM shop_area WHERE parentid = ( SELECT id FROM shop_area WHERE areaname = '$city' AND `level` = 2 )")->queryAll(),'areaname','areaname');
        }
        return $citys;
    }
}
