<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\order\models\TbProductOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '订单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-order-index">

    <?php $gridColumns=[
        [
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=>function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('view', ['model'=>$model]);
            },
            'enableRowClick'=>true,
        ],
        [
            'attribute'=>'linkname',
            'label' => '客户名称',
        ],
        [
            'attribute'=>'linkphone',
            'label' => '客户联系电话',
        ],
        [
            'attribute'=>'discount',
            'label' => '本次折扣',
        ],
        [
            'attribute'=>'money',
            'label' => '实际收款总金额',
        ],

        [
            'attribute'=>'createtime',
            'label' => '创建时间',
            'filter'=>false,
        ],
        [
            'attribute'=>'remarks',
            'label' => '备注',
        ],




        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{subset-index} {update} ',
            'header' => '操作',
            'buttons' => [
                'subset-index' => function ($url, $model, $key) {

                },
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
            Html::a('<i class="glyphicon glyphicon-plus"></i>',["#"], ['data-pjax'=>0, 'class'=>'btn btn-success modaldialog','data-toggle' => 'modal', 'data-title' => '产品添加','data-target' => '#common-modal','data-url' => Url::to("create"), 'title'=>"产品添加"]),
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
