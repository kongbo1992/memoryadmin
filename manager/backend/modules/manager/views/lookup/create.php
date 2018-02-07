<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\manager\models\LookUp */

$this->title = '添加单词';
$this->params['breadcrumbs'][] = ['label' => '字典表管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="look-up-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
