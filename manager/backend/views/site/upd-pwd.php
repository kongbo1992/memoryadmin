<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\Manager */
/* @var $form yii\widgets\ActiveForm */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['center']];;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="manager-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'old_pwd', ['enableAjaxValidation' => true])->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pwd')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 're_pwd')->passwordInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton(' 修 改 ', ['class' =>  'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
