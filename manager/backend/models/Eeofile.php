<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * Login form
 */
class Eeofile extends Model
{
    public $upfiles;
//    public $rememberMe = true;
//
//    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['upfiles'], 'required'],
            // rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
//            ['password', 'validatePassword'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'upfiles' => '上传文件',
//            'rememberMe' => '记住密码',

        ];
    }
}
