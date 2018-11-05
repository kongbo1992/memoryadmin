<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductWarehousingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-product-warehousing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'purchase_price') ?>

    <?= $form->field($model, 'delivery_time') ?>

    <?php // echo $form->field($model, 'linkname') ?>

    <?php // echo $form->field($model, 'linkphone') ?>

    <?php // echo $form->field($model, 'channel') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'oper_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
