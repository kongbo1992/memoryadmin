<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\product\models\TbProductStockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品库存';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-product-stock-index">

    <?php $gridColumns=[
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute'=>'name',
            'label' => '产品名称',
        ],
        [
            'attribute'=>'stock',
            'label' => '当前库存',
            'content'=>function($model){
                $unit = Yii::$app->params['unit'];
                $result = $model->stock;
                if (!empty( $unit[$model->unit]) ) {
                    $result .= "/" . $unit[$model->unit];
                }
                return $result;
            }
        ],
        [
            'attribute'=>'sales',
            'label' => '销量',
            'content'=>function($model){
                $unit = Yii::$app->params['unit'];
                $result = $model->sales;
                if (!empty( $unit[$model->unit]) ) {
                    $result .= "/" . $unit[$model->unit];
                }
                return $result;
            }
        ],

        [
            'attribute'=>'total',
            'label' => '总量',
            'content'=>function($model){
                $unit = Yii::$app->params['unit'];
                $result = $model->total;
                if (!empty( $unit[$model->unit]) ) {
                    $result .= "/" . $unit[$model->unit];
                }
                return $result;
            }
        ],


//        [
//            'class' => 'kartik\grid\ActionColumn',
//            'template' => '  ',
//            'header' => '操作',
//            'buttons' => [
//                'delete'=> function ($url, $model, $key){
//                    return  Html::a('删除', $url,[
//                        'data-method'=>'post',              //POST传值
//                        'class'=>'btn btn-danger  btn-sm',
//                        'data-confirm' => '确定删除该项？', //添加确认框
//                    ] ) ;
//                },
//                'update'=> function ($url, $model, $key){
//                    return Html::a('编辑', '#', [
//                        'data-toggle' => 'modal',
//                        'data-target' => '#common-modal',
//                        'data-url' => $url,
//                        'data-title' => '编辑',
//                        'class' => 'modaldialog btn btn-success btn-sm',
//                        'data-id' => $key,
//                    ]);
//                },
//            ],
//        ],
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
//            Html::a('<i class="glyphicon glyphicon-plus">库存添加</i>',["#"], ['data-pjax'=>0, 'class'=>'btn btn-success modaldialog','data-toggle' => 'modal', 'data-title' => '库存添加','data-target' => '#common-modal','data-url' => Url::to("create"), 'title'=>"库存添加"]),
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
