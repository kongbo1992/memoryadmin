<?php

namespace backend\models;

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
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class ManagerUser extends \backend\models\Manager
{
    public $qiniu_img_files;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qiniu_img_files'],'safe'],
            [['email','nickname','intros'], 'trim'],
            [['gender'],'integer'],
            [['nickname','email','gender','phone','headimg'], 'required', 'message' => '{attribute}不能为空'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique','message' => '{attribute}已经存在'],
//            ['phone', 'match', 'pattern' => '/^1[345678]\d{9}$/'],
//            ['phone', 'unique','message' => '{attribute}已经存在'],
            ['teacherintroduction','string']

        ];
    }

}
