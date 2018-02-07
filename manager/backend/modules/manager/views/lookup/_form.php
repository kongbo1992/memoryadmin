<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\LookUp;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\LookUp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="look-up-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code', ['enableAjaxValidation' => true])->textInput() ?>

    <?= $form->field($model, 'name', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? ' 添 加 ' : ' 修 改 ', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
