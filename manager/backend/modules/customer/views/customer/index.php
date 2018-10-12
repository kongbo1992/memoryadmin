<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\customer\models\TbCustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客户维护';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-customer-index">

    <?php $gridColumns=[
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute'=>'name',
            'label' => '客户姓名',
        ],
        [
            'attribute'=>'phone',
            'label' => '客户联系电话',
        ],
        [
            'attribute'=>'grade',
            'label' => '客户等级',
            'content'=>function($model){
                $grade = \backend\modules\customer\models\TbCustomer::get_grade();
                return !empty($grade[$model->grade])?$grade[$model->grade]:'<span class="not-set" style="color: red">(出错)</span>';
            }
        ],

        [
            'attribute'=>'createtime',
            'label' => '创建时间',
            'filter'=>false,
        ],


        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => ' {update} ',
            'header' => '操作',
            'buttons' => [
                'delete'=> function ($url, $model, $key){
                    return  Html::a('删除', $url,[
                        'data-method'=>'post',              //POST传值
                        'class'=>'btn btn-danger  btn-sm',
                        'data-confirm' => '确定删除该项？', //添加确认框
                    ] ) ;
                },
                'update'=> function ($url, $model, $key){
                    return Html::a('编辑', '#', [
                        'data-toggle' => 'modal',
                        'data-target' => '#common-modal',
                        'data-url' => $url,
                        'data-title' => '编辑',
                        'class' => 'modaldialog btn btn-success btn-sm',
                        'data-id' => $key,
                    ]);
                },
            ],
        ],
    ];
    ?>


    <?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'pager'=>[
            'class'=>'\backend\widgets\GoLinkPager',
        ],
        'filterModel'=>$searchModel,
        'columns'=>$gridColumns,
        // set your toolbar
        'toolbar'=> [
            Html::a('<i class="glyphicon glyphicon-plus">客户添加</i>',["#"], ['data-pjax'=>0, 'class'=>'btn btn-success modaldialog','data-toggle' => 'modal', 'data-title' => '客户添加','data-target' => '#common-modal','data-url' => Url::to("create"), 'title'=>"客户添加"]),
//            Html::a('<i class="glyphicon glyphicon-plus"> 客户添加</i>',['create'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title'=>"客户添加"]),
            Html::a('<i class="glyphicon glyphicon-repeat"> 重置</i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>"重置"]),
        ],
        // set export properties
        'export'=>[
            'fontAwesome'=>true
        ],
        'hover'=>true,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=>true,
        ],
    ]);
    ?>
</div>
