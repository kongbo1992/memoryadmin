<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductStock */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-product-stock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=  $form->field($model, 'product_id')->widget(Select2::classname(), [
        'data' => \backend\modules\product\models\TbProductStock::get_product(),
        'options' => ['placeholder' => '请选择添加库存产品...']
    ])->label('产品名称'); ?>

    <?= $form->field($model, 'stock')->textInput()->label('新增库存') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
