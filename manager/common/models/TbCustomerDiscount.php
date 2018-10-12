<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_customer_discount".
 *
 * @property integer $id
 * @property string $title
 * @property double $discount
 */
class TbCustomerDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_customer_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount'], 'number'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '客户类型名称',
            'discount' => '折扣',
        ];
    }
}
