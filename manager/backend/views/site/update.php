<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\Manager */

$this->title = '修改资料';
$this->params['breadcrumbs'][] = ['label' => '个人中心', 'url' => ['center']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
