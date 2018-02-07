<?php
namespace backend\models;

use backend\models\Manager;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class UpdPwdForm extends Model
{
    public $pwd;
    public $re_pwd;
    public $old_pwd;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['old_pwd','pwd', 're_pwd'], 'required'],
            [['old_pwd','pwd', 're_pwd'], 'string','min'=>6,'max'=>30],
            ['old_pwd','validatePassword'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['re_pwd','compare','compareAttribute'=>'pwd','message'=>'两次密码不一致'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'old_pwd' => '原密码',
            'pwd' => '新密码',
            're_pwd' => '确认密码',

        ];
    }


    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->old_pwd)) {
                $this->addError($attribute, '原密码输入不正确.');
            }
        }
    }
    public function upd_pwd()
    {
        $model = $this -> getUser();
        if ($this->validate() ){
            $model -> setPassword($this->pwd);
            $model -> save(false);
            return true;
        }else{
            return false;
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Manager::findOne(Yii::$app->user->id);
        }

        return $this->_user;
    }
}
