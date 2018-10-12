<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_customer".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property integer $grade
 * @property string $createtime
 */
class TbCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade'], 'integer'],
            [['createtime'], 'safe'],
            [['name'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '客户姓名',
            'phone' => '联系电话',
            'grade' => '客户等级',
            'createtime' => '添加时间',
        ];
    }
}
