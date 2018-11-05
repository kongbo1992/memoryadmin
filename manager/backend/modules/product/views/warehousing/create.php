<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProductWarehousing */

$this->title = '添加库存';
$this->params['breadcrumbs'][] = ['label' => '入库列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-warehousing-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
