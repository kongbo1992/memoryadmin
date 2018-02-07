<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
/**
 * Login form
 */
class Kuaidifile extends Model
{
    public $classtype;
    public $kuaiditype;
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
            [['classtype', 'kuaiditype','upfiles'], 'required'],
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
            'classtype' => '订单类型',
            'kuaiditype' => '快递类型',
            'upfiles' => '上传文件',
//            'rememberMe' => '记住密码',

        ];
    }
}
