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
class Manager extends \backend\models\Manager
{
    public $old_attributes;
    public $qiniu_img_files;
    public $provinces;//点评省份
    public $exam_level;//点评学段
    public $subject;//点评学科
    public $version;//点评教材版本
    public $interviewtype;//点评面试课类型
    public $qqgroup;//老师默认群号
    public $qqgroupkey;//ioskey
    public $qqgroupkey2;//安卓key
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qiniu_img_files'],'safe'],
            [['username','email','nickname','intros'], 'trim'],
            [['role','recommend','gender'],'integer'],
            [['username','nickname','role','email','gender','phone','recommend','headimg'], 'required', 'message' => '{attribute}不能为空'],
            ['username', 'unique', 'message' => '{attribute}已经存在'],
            [['username','nickname'], 'string', 'min' => 2, 'max' => 30],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique','message' => '{attribute}已经存在'],
            ['phone', 'match', 'pattern' => '/^1[345678]\d{9}$/'],
            ['phone', 'unique','message' => '{attribute}已经存在'],
            ['teacherintroduction','string'],
            [['provinces'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['exam_level'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['subject'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['version'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['qqgroup'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['qqgroupkey'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['qqgroupkey2'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],
            [['interviewtype'], 'required','when' => function ($model) { return $model->role == 4000 ; }, 'whenClient' => "function (attribute, value) { return $('#manager-role').val() == '4000' ;}"],        
        ];
    }

}
