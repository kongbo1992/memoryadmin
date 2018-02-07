<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\models\models\TbModule */

$this->title = '编辑相册: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '相册列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-module-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
