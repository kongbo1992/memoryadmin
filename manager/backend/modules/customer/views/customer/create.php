<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\TbCustomer */

$this->title = 'Create Tb Customer';
$this->params['breadcrumbs'][] = ['label' => 'Tb Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-customer-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
