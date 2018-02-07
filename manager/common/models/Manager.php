<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property integer $id
 * @property string $username
 * @property string $nickname
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $gender
 * @property string $phone
 * @property string $headimg
 * @property string $teacherintroduction
 * @property integer $recommend
 * @property string $intros
 * @property integer $eeo_ssid
 */
class Manager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['role', 'status', 'created_at', 'updated_at', 'gender', 'recommend', 'eeo_ssid'], 'integer'],
            [['teacherintroduction', 'intros'], 'string'],
            [['username', 'nickname', 'password_hash', 'password_reset_token', 'email', 'headimg'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 11],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['phone'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'nickname' => 'Nickname',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'headimg' => 'Headimg',
            'teacherintroduction' => 'Teacherintroduction',
            'recommend' => 'Recommend',
            'intros' => 'Intros',
            'eeo_ssid' => 'Eeo Ssid',
        ];
    }
}
