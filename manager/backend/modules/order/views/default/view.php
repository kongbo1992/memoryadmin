<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use com_duobeiyun_api_v3\DuobeiyunApi;
use com_duobeiyun_api_v3\DuobeiUid;
use kartik\file\FileInput;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\modules\order\models\TbProductOrderSearch */
?>
<style>
    .btn-file{
        font-size: 12px;
        padding: 2px;

    }
    .fileinput-upload-button{
        font-size: 12px;
        padding: 2px;
    }
</style>
<div class="class-list-view">
    <div class="col-sm-12">
        <table class="table table-bordered table-condensed table-hover small kv-table">
            <tbody><tr class="success">
                <th colspan="6" class="text-center text-primary">我的系列课</th>
            </tr>
            <tr class="active">
                <th class="text-center">商品名称</th>
                <th class="text-center">商品数量</th>
                <th class="text-center">商品单价</th>
            </tr>
            <?php foreach($model->tbProductOrderList as $chapter){?>
                <tr>
                    <td class="text-center"><?= $chapter->product_name?></td>
                    <td class="text-center"><?= $chapter->num?></td>
                    <td class="text-center"><?= $chapter->price?></td>
                </tr>
            <?php }?>

            </tbody></table>
    </div>


</div>
