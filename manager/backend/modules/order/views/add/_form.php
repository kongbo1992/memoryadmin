<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-product-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="from-group" style="clear: both;">
        <div class="col-sm-12">
            <?=  $form->field($model, 'customer_id')->widget(Select2::classname(), [
                'data' => \backend\modules\order\models\TbProductOrder::get_users(),
                'options' => ['placeholder' => '请选择客户...']
            ])->label('客户选择（支持电话-姓名搜索）'); ?>
        </div>
    </div>

<!--    --><?//= $form->field($model, 'product_id')->textInput() ?>

<!--    --><?//= $form->field($model, 'num')->textInput() ?>

<!--    --><?//= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'linkphone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'createtime')->textInput() ?>

    <?= $form->field($model, 'sales_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
