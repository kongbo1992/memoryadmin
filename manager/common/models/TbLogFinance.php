<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_log_finance".
 *
 * @property integer $id
 * @property string $oper_time
 * @property string $oper_code
 * @property string $finance_code
 * @property string $add
 * @property string $reduce
 * @property string $balance
 * @property string $oper_event
 * @property string $oper_serialno
 * @property integer $oper_custid
 */
class TbLogFinance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_log_finance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oper_time'], 'safe'],
            [['add', 'reduce', 'balance'], 'number'],
            [['oper_custid'], 'integer'],
            [['oper_code', 'finance_code', 'oper_event', 'oper_serialno'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'oper_time' => 'Oper Time',
            'oper_code' => 'Oper Code',
            'finance_code' => 'Finance Code',
            'add' => 'Add',
            'reduce' => 'Reduce',
            'balance' => 'Balance',
            'oper_event' => 'Oper Event',
            'oper_serialno' => 'Oper Serialno',
            'oper_custid' => 'Oper Custid',
        ];
    }
}
