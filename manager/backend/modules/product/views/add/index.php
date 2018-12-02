<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\web\View;

$question_url = Url::to(['questions']);
$tree_url = Url::to(['allproduct']);

$id = $aid;

$this->registerJsFile("@web/plugin/zTree/js/jquery-migrate-1.4.1.min.js");
$this->registerJsFile("@web/plugin/zTree/js/jquery.ztree.all-3.5.js");
$this->registerCssFile("@web/plugin/zTree/css/zTreeStyle/zTreeStyle.css");
$this->registerCssFile("@web/plugin/Crm_System.css");

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\product\models\TbProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '产品列表';
$this->params['breadcrumbs'][] = $this->title;

$banner_list = <<<JS
    // zTree Start
        $(function(){

            var setting = {
                data: {
                    simpleData: {
                        enable: true,
                    }
                },
                callback: {
                    onClick : renovate,
                }
            };

            my_loading.show();
            $.post("$tree_url",{},function(edm){
                my_loading.hide();
                console.log("数据已返回");
                var zNodes =(new Function("","return "+edm))();
                var treeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
                treeObj.expandAll(true);
                if ( '$id' ) {
                    $("#{$id}_a").addClass("curSelectedNode");
                }
                //background: url(../../../css/zTreeStyle/img/diy/1_open.png) 0 0 no-repeat
                $("#treeDemo_1_ico").css("background","url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAAsT%0AAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqY%0AAAAXb5JfxUYAAAHfSURBVHjapJI/SNxgGIefCxjUWLiCZjhucCqC9EiHDOpw%0A4CIIHW5oHYqdHHodnARPig6BkqMOgsMllA5SoZNeh4IgpR0KlUKFBGtADhQX%0ADzGSDhpbSOHr0Nw113qtpT/4ht/3vu/z/XnfFLEMw6gAxdhar9TbxN76+ODW%0AQy6REOKnMQxDBEEggiAQd5ZeCt12hBsJoduO0G2n0g4gJTd83+fJ+yMOu/qZ%0An9D4cALzExpAsR2kBfDUC3lz9I3ZgsZ+AF++wn4As4X2kCbg07WbvDtJMT2u%0AsedDbwe8eO3S2wF7PkyPXw6RAHTbqRx29TM1quEdR/TJsLzhkpM/s7zh0ieD%0AdxwxNfo7RIpNcXJ4kN36OZlOePbWJa9C5d4IefWHz3TCbv2cyeHBFogEFOfG%0AchychmR7JFa3PEauRywWNGRZZrGgkVdhdcsj2yNxcBoyN5ZrtBgpujhbMTd3%0AGMp2s7ZdI6/C0l295aMakLXtGkPZbszNHaKLs5VGXBlYqK7rtiNmqo74k2aq%0AjtBtRwwsVNcBJTlIimmaLcmWZTVXUqZpCkD5dZDCdDrNVRTnhc0uCCFaZ/oK%0AStZI/KdSwA2AUqn0SFGU+38rCMPweblcfhzfpJZKxDL/cHC98ZTvAwAfngtD%0ATb26xgAAAABJRU5ErkJggg==) 0 0 no-repeat");
            });



        });
        function renovate(event, treeId, treeNode){
            window.location.href="index?id="+treeNode.dataId+"&aid="+treeNode.tId;
        }
JS;
$this->registerJs($banner_list, View::POS_END, 'banner_list');
?>



<div class="tb-product-index" >

    <div class="Crm_System"  style="width: 18%;float: left;margin-right: 2%;">
        <div style="color: #fff;background-color: #337ab7;border-color: #337ab7;width: 100%;text-align: center; padding: 5px 0;font-size: 14px;">
            品牌列表
        </div>
        <ul id="treeDemo" class="ztree"></ul>
    </div>

    <div style="width: 80%;float: right;">
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
                'template' => '{update} {delete}',
                'header' => '操作',
                'buttons' => [
//                    'subset-index' => function ($url, $model, $key) {
//                        if ( $model->type == 1 ) {
//                            return Html::a('产品管理', ['subset-index','level' => 2,'pid'=>$model->id],['data-pjax'=>'0','class'=>'btn btn-info btn-sm'] );
//                        }
//                    },
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

</div>
