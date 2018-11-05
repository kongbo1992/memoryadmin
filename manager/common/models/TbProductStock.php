<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_product_stock".
 *
 * @property integer $product_id
 * @property integer $total
 * @property integer $stock
 * @property integer $sales
 */
class TbProductStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_product_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'total', 'stock', 'sales'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'total' => '产品总量',
            'stock' => '库存',
            'sales' => '销量(包含所有减去库存的操作)',
        ];
    }
}
