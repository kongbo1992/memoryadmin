<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tb_product".
 *
 * @property integer $id
 * @property string $name
 * @property integer $unit
 * @property string $price
 * @property string $remarks
 * @property integer $author
 * @property integer $type
 * @property integer $pid
 * @property string $createtime
 * @property integer $level
 */
class TbProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit', 'author', 'type', 'pid', 'level'], 'integer'],
            [['price'], 'number'],
            [['createtime'], 'safe'],
            [['name', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '产品名称',
            'unit' => '单位',
            'price' => '单价',
            'remarks' => '备注',
            'author' => '创建者用户id',
            'type' => '1.品牌（包，下面含有多个产品或者品牌），2产品',
            'pid' => '父级id',
            'createtime' => '创建时间',
            'level' => '当前产品等级，最高级为1',
        ];
    }
}
