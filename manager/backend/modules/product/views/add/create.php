<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\product\models\TbProduct */

$this->title = 'Create Tb Product';
$this->params['breadcrumbs'][] = ['label' => 'Tb Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
