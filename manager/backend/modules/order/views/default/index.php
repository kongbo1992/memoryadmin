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
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute'=>'name',
            'label' => '产品名称',
        ],
//        [
//            'attribute'=>'price',
//            'label' => '价格',
//            'content'=>function($model){
//                $unit = Yii::$app->params['unit'];
//                return $model->price . "/" . $unit[$model->unit];
//            }
//        ],
        [
            'attribute'=>'type',
            'label' => '创建时间',
            'filter' => ['1' => "品牌",'2' => "产品"],
            'content'=>function($model){
                return $model->type == 1 ? "品牌" : "产品";
            }
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
                    if ( $model->type == 1 ) {
                        return Html::a('产品管理', ['subset-index','level' => 2,'pid'=>$model->id],['data-pjax'=>'0','class'=>'btn btn-info btn-sm'] );
                    }
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
            Html::a('<i class="glyphicon glyphicon-plus">品牌添加</i>',["#"], ['data-pjax'=>0, 'class'=>'btn btn-primary modaldialog','data-toggle' => 'modal', 'data-title' => '品牌添加','data-target' => '#common-modal','data-url' => Url::to(["ppcreate"]), 'title'=>"品牌添加"]),
            Html::a('<i class="glyphicon glyphicon-plus">产品添加</i>',["#"], ['data-pjax'=>0, 'class'=>'btn btn-success modaldialog','data-toggle' => 'modal', 'data-title' => '产品添加','data-target' => '#common-modal','data-url' => Url::to("create"), 'title'=>"产品添加"]),
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
