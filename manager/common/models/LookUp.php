<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "look_up".
 *
 * @property integer $id
 * @property string $type
 * @property integer $code
 * @property string $name
 * @property integer $order
 * @property integer $city_id
 * @property integer $is_delete
 */
class LookUp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'look_up';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'order', 'city_id', 'is_delete'], 'integer'],
            [['type', 'name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'code' => '值',
            'name' => '名称',
            'order' => '排序',
            'city_id' => '城市ID',
            'is_delete' => '是否删除',
        ];
    }

}
