<?php

namespace backend\modules\manager\models;

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
class LookUp extends \common\models\LookUp
{
    public $old_model;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(),[
                [['code','type','name'],'required'],
                ['code','validateCode'],
                ['name','validateName'],
                [['code', 'order', 'city_id', 'is_delete'], 'integer'],
                [['type', 'name'], 'string', 'max' => 30],
            ]
        );
    }
    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $word = self::find()
                ->where(['type'=>$this->type,'code'=>$this->code]);
            if(!empty($this->id)){
                $word = $word -> andWhere('id <> '.$this->id);
            }
            $word = $word ->one();
            if ($word) {
                $this->addError($attribute, '该项值已存在.');
            }
        }
    }
    public function validateName($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $word = self::find()
                ->where(['type'=>$this->type,'name'=>$this->name]);
            if(!empty($this->id)){
                $word = $word -> andWhere('id <> '.$this->id);
            }
            $word = $word ->one();
            if ($word) {
                $this->addError($attribute, '该项名称已存在.');
            }
        }
    }
}
