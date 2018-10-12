<?php

namespace backend\modules\customer\models;

use Yii;

/**
 * This is the model class for table "tb_customer_discount".
 *
 * @property integer $id
 * @property string $title
 * @property double $discount
 */
class TbCustomerDiscount extends \common\models\TbCustomerDiscount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules());
    }

    /**
     * @inheritdoc
     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'ID',
//            'title' => '客户类型名称',
//            'discount' => '折扣',
//        ];
//    }
}
