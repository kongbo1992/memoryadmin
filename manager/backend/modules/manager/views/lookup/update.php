<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\LookUp */

$this->title = '修改单词: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '字典表管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="look-up-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
