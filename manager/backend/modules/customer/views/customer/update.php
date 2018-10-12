<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\TbCustomer */

$this->title = 'Update Tb Customer: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tb Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-customer-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
