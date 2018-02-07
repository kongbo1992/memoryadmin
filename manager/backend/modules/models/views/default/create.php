<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\models\models\TbModule */

$this->title = '相册添加';
$this->params['breadcrumbs'][] = ['label' => '模块管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-module-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
