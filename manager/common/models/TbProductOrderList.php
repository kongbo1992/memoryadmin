<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_product_order_list".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $num
 * @property string $price
 * @property string $product_name
 */
class TbProductOrderList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_product_order_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'num'], 'integer'],
            [['price'], 'number'],
            [['product_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '对应订单id',
            'product_id' => 'Product ID',
            'num' => '产品数量',
            'price' => '单价',
            'product_name' => '产品名称',
        ];
    }
}
