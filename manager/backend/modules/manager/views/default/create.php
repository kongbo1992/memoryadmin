<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\Manager */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = ['label' => '管理用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="manager-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
