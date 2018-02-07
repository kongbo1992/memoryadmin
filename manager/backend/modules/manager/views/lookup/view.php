<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\LookUp */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '字典表维护', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="look-up-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(' 修 改 ', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(' 删 除 ', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'code',
            'name',
            'order',
            'city_id',
            'is_delete',
        ],
    ]) ?>

</div>
