<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\TbCustomerDiscount */

$this->title = 'Update Tb Customer Discount: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Tb Customer Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-customer-discount-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
