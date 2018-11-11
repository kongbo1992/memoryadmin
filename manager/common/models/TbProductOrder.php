<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_product_order".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $num
 * @property string $price
 * @property string $money
 * @property string $linkname
 * @property string $linkphone
 * @property string $address
 * @property double $discount
 * @property string $createtime
 * @property string $sales_time
 * @property integer $customer_id
 * @property integer $auser_id
 * @property integer $duser_id
 * @property integer $state
 * @property string $remarks
 */
class TbProductOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_product_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'num', 'customer_id', 'auser_id', 'duser_id', 'state'], 'integer'],
            [['price', 'money', 'discount'], 'number'],
            [['createtime', 'sales_time'], 'safe'],
            [['remarks'], 'string'],
            [['linkname'], 'string', 'max' => 128],
            [['linkphone'], 'string', 'max' => 11],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => '产品id',
            'num' => '销售量',
            'price' => '产品单价',
            'money' => '实际收款金额',
            'linkname' => '收货人姓名',
            'linkphone' => '收货人手机号',
            'address' => '收货地址',
            'discount' => '特殊折扣',
            'createtime' => '创建时间',
            'sales_time' => '销售时间',
            'customer_id' => '购买用户id',
            'auser_id' => '添加管理员id',
            'duser_id' => '删除管理员id',
            'state' => '0.正常;2.删除',
            'remarks' => '备注',
        ];
    }
}
