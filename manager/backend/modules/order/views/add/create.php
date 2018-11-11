<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrder */

$this->title = '订单添加';
$this->params['breadcrumbs'][] = ['label' => '订单列表', 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-order-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
