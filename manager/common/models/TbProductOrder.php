<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_product_order".
 *
 * @property integer $id
 * @property string $money
 * @property string $linkname
 * @property string $linkphone
 * @property string $address
 * @property integer $dct_type
 * @property string $discount
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
            [['money'], 'number'],
            [['dct_type', 'customer_id', 'auser_id', 'duser_id', 'state'], 'integer'],
            [['createtime', 'sales_time'], 'safe'],
            [['remarks'], 'string'],
            [['linkname', 'discount'], 'string', 'max' => 128],
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
            'money' => '实际收款金额',
            'linkname' => '收货人姓名',
            'linkphone' => '收货人手机号',
            'address' => '收货地址',
            'dct_type' => '折扣类型：0（无特殊折扣）1（折上折）2（再次优惠金额）3（新折扣）',
            'discount' => '特殊折扣(金额或者折扣数)',
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
