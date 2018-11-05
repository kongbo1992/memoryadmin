<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrder */

$this->title = 'Create Tb Product Order';
$this->params['breadcrumbs'][] = ['label' => 'Tb Product Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
