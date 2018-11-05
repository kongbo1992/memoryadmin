<?php

namespace backend\modules\product\models;

use Yii;

/**
 * This is the model class for table "tb_product_stock".
 *
 * @property integer $product_id
 * @property integer $total
 * @property integer $stock
 * @property integer $sales
 */
class TbProductStock extends \common\models\TbProductStock
{
    public $name;
    public $unit;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),
            [
                [['product_id','stock'],'required'],
            ]
        );
    }

    public function getTbProduct()
    {
        return $this->hasOne(TbProduct::className(), ['id' => 'product_id','type' => 2]);
    }

//    获取产品数据
    public static function get_product()
    {
        $product = Yii::$app->db->createCommand("select name,id from tb_product WHERE type = 2")->queryAll();
        $result = [];
        foreach( $product as $key => $val ) {
            $result[$val['id']] = $val['name'];
        }
        return $result;
    }

}
