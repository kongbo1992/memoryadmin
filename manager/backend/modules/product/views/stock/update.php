<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductStock */

$this->title = 'Update Tb Product Stock: ' . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Tb Product Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-product-stock-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
