<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-product-order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'num') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'money') ?>

    <?php // echo $form->field($model, 'linkname') ?>

    <?php // echo $form->field($model, 'linkphone') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'createtime') ?>

    <?php // echo $form->field($model, 'sales_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
