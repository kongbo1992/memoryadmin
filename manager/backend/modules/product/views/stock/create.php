<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductStock */

$this->title = 'Create Tb Product Stock';
$this->params['breadcrumbs'][] = ['label' => 'Tb Product Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-stock-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
