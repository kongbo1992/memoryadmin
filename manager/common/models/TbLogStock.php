<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_log_stock".
 *
 * @property integer $id
 * @property integer $bookid
 * @property integer $add
 * @property integer $reduce
 * @property integer $stock
 * @property string $oper_time
 * @property string $oper_code
 * @property string $oper_event
 * @property string $oper_serialno
 */
class TbLogStock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_log_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bookid'], 'required'],
            [['bookid', 'add', 'reduce', 'stock'], 'integer'],
            [['oper_time'], 'safe'],
            [['oper_code'], 'string', 'max' => 255],
            [['oper_event', 'oper_serialno'], 'string', 'max' => 32],
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
            'add' => 'Add',
            'reduce' => 'Reduce',
            'stock' => 'Stock',
            'oper_time' => 'Oper Time',
            'oper_code' => 'Oper Code',
            'oper_event' => 'Oper Event',
            'oper_serialno' => 'Oper Serialno',
        ];
    }
}
