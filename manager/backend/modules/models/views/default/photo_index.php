<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\models\models\TbModulePhotoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '图片管理:'.$data->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-module-photo-index">

    <?php $gridColumns=[
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute'=>'imgurl',
            'label' => '封面图片',
            'filter' => false,
            'content'=>function($model){
                return !empty($model -> imgurl)?"<img src='".$model->imgurl."' width='100px' />":'<span class="not-set">(未设置)</span>';
            }
        ],
        [
            'attribute'=>'title',
            'label' => '相册名称',
        ],
//        [
//            'attribute'=>'content',
//            'label' => '相册描述',
//        ],
        [
            'attribute'=>'createtime',
            'label' => '创建时间',
            'filter'=>false,
        ],


        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{statis} {update} {delete}',
            'header' => '操作',
            'buttons' => [
//                'statis' => function ($url, $model, $key) {
//                    return Html::a('阅读统计', ['statis','id'=>$model->id],['data-pjax'=>'0','class'=>'btn btn-success btn-sm'] );
//                },
                'delete'=> function ($url, $model, $key){
                    return  Html::a('删除', $url,[
                        'data-method'=>'post',              //POST传值
                        'class'=>'btn btn-danger  btn-sm',
                        'data-confirm' => '确定删除该项？', //添加确认框
                    ] ) ;
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('编辑', ['update','id'=>$model->id,'p'=>!empty($_GET['page'])?$_GET['page']:1],['data-pjax'=>'0','class'=>'btn btn-primary btn-sm'] );
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
//            Html::a('<i class="glyphicon glyphicon-refresh"> 更新缓存</i>', ['push'], ['data-pjax'=>0, 'class'=>'btn btn-danger',  'data-confirm' => '确认更新缓存？',  'title'=>"更新缓存"]),
            Html::a('<i class="glyphicon glyphicon-plus"> 图片添加</i>',['photo-create','pid' => $_GET['pid']], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title'=>"广告添加"]),
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
