<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manager_log".
 *
 * @property integer $id
 * @property string $route
 * @property string $description
 * @property string $created_at
 * @property integer $user_id
 * @property integer $ip
 * @property integer $type
 * @property string $table_name
 */
class ManagerLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manager_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['created_at'], 'required'],
            [['created_at'], 'safe'],
            [['user_id', 'ip', 'type'], 'integer'],
            [['route'], 'string', 'max' => 255],
            [['table_name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => '路由',
            'description' => '描述',
            'created_at' => '创建时间',
            'user_id' => '用户ID',
            'ip' => 'IP',
            'type' => '操作类型',
            'table_name' => '表名',
        ];
    }
}
