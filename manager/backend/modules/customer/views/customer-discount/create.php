<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\TbCustomerDiscount */

$this->title = 'Create Tb Customer Discount';
$this->params['breadcrumbs'][] = ['label' => 'Tb Customer Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-customer-discount-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
