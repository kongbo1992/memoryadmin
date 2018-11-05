<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductWarehousing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-product-warehousing-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-sm-12" >
        <?=  $form->field($model, 'product_id')->widget(Select2::classname(), [
            'data' => \backend\modules\product\models\TbProductStock::get_product(),
            'options' => ['placeholder' => '请选择添加库存产品...']
        ])->label('产品名称'); ?>
    </div>

    <div class="col-sm-6" >
        <?= $form->field($model, 'num')->textInput()->label('入库数量') ?>
    </div>

    <div class="col-sm-6" >
        <?= $form->field($model, 'purchase_price')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-6" >
        <?= $form->field($model, 'linkname')->textInput(['maxlength' => true]) ?>
    </div>


    <div class="col-sm-6" >
        <?= $form->field($model, 'linkphone')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-6">
        <?= $form->field($model, 'delivery_time')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => '请输入时间 ...'],
            'pluginOptions' => [
                'autoclose' => true
            ]
        ])->label('进货时间'); ?>
    </div>

    <div class="col-sm-6" >
        <?= $form->field($model, 'channel')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-sm-12" >
        <?= $form->field($model, 'remarks')->textarea(['rows' => 5]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '确认添加' : '确认编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
