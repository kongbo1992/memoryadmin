<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProduct */

$this->title = 'Update Tb Product: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tb Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-product-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
