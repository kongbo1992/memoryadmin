<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\models\models\TbModulePhoto */

$this->title = '照片添加';
$this->params['breadcrumbs'][] = ['label' => '照片列表', 'url' => ['photo-index','pid' => $_GET['pid']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-module-photo-create">


    <?= $this->render('photo_form', [
        'model' => $model,
    ]) ?>

</div>
