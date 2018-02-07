<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tp_op_sale".
 *
 * @property integer $id
 * @property integer $bookid
 * @property string $oper_event
 * @property integer $custid
 * @property string $custname
 * @property string $price
 * @property integer $num
 * @property string $fee
 * @property string $express_fee
 * @property string $oper_time
 * @property string $oper_code
 * @property string $remark
 * @property string $oper_other
 * @property string $pay_time
 */
class TpOpSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tp_op_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookid', 'custid', 'num'], 'integer'],
            [['price', 'fee', 'express_fee'], 'number'],
            [['oper_time', 'pay_time'], 'safe'],
            [['oper_other'], 'string'],
            [['oper_event', 'oper_code'], 'string', 'max' => 32],
            [['custname', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bookid' => 'Bookid',
            'oper_event' => 'Oper Event',
            'custid' => 'Custid',
            'custname' => 'Custname',
            'price' => 'Price',
            'num' => 'Num',
            'fee' => 'Fee',
            'express_fee' => 'Express Fee',
            'oper_time' => 'Oper Time',
            'oper_code' => 'Oper Code',
            'remark' => 'Remark',
            'oper_other' => 'Oper Other',
            'pay_time' => 'Pay Time',
        ];
    }
}
