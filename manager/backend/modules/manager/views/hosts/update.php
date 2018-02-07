<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\Hosts */

$this->title = '修改服务器: ' . $model->hostname;
$this->params['breadcrumbs'][] = ['label' => '服务器列表维护', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hosts-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
