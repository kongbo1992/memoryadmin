<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\manager\models\HostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务器列表维护';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hosts-index">

    <?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'rowOptions' => function($model){
            return $model->status == 0?[]:['class' => GridView::TYPE_DANGER ];
        },
        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'ip',
                'label'=>'IP地址',
                'editableOptions'=>[
                    'header'=>'IP地址',
                    'asPopover' => false,
                    'inputType' =>'textArea'
                ],
                'value'=>function($model){
                    $model->ip;
                },
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'hostname',
                'label'=>'主机名',
                'editableOptions'=>[
                    'header'=>'主机名',
                    'asPopover' => false,
                    'inputType' =>'textArea'
                ],
                'value'=>function($model){
                    $model->hostname;
                },
            ],
            [
                'class'=>'kartik\grid\EditableColumn',
                'attribute'=>'comment',
                'label'=>'备注',
                'editableOptions'=>[
                    'header'=>'备注',
                    'asPopover' => false,
                    'inputType' =>'textArea'
                ],
                'value'=>function($model){
                    $model->comment;
                },
            ],
            [
                'attribute'=>'create_time',
                'label' => '创建时间',
                'headerOptions' => ['width' => '200'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'create_time',
                    'language'=>'zh-CN',
                    "layout"=>'{picker}{input}',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ]
                ]),
            ],
            [
                'attribute'=>'update_time',
                'headerOptions' => ['width' => '200'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'update_time',
                    'language'=>'zh-CN',
                    "layout"=>'{picker}{input}',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ]
                ]),
            ],
            [
                'attribute'=>'status',
                'label' => '状态',
                'headerOptions' => ['width' => '200'],
                'filter'=> [0 => '启用',1 => '禁用'],
                'content'=>function($model){
                    if($model->status == 1){
                        return '<span class="btn-danger">禁用</span>';
                    }else{
                        return '<span class="btn-success">启用</span>';
                    }
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {lock} {delete}',
                'header' => '操作',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('修改', '#', [
                            'data-toggle' => 'modal',
                            'data-target' => '#common-modal',
                            'data-url' => $url,
                            'data-title' => '修改',
                            'class' => 'modaldialog btn btn-primary btn-sm',
                            'data-id' => $key,
                        ]);
                    },
                    'lock' => function ($url, $model, $key) {
                        if($model->status == 1){
                            return  Html::a('启用', $url,[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-success  btn-sm',
//                                'data-confirm' => '确定启用该项？', //添加确认框
                            ] ) ;
                        }else{
                            return  Html::a('禁用', $url,[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-danger  btn-sm',
//                                'data-confirm' => '确定禁用该项？', //添加确认框
                            ] ) ;
                        }
                    },
                    'delete'=> function ($url, $model, $key){
                        return  Html::a('删除', $url,[
                            'data-method'=>'post',              //POST传值
                            'class'=>'btn btn-danger  btn-sm',
//                            'data-confirm' => '确定删除该项？', //添加确认框
                        ] ) ;

                    }
                ],
            ],
        ],
        // set your toolbar
        'toolbar'=> [
            Html::a('<i class="glyphicon glyphicon-plus"> 添加服务器 </i>', '#', [
                'data-toggle' => 'modal',
                'data-target' => '#common-modal',
                'data-url' => \yii\helpers\Url::to(['create']),
                'data-title' => '添加服务器',
                'class' => 'modaldialog btn btn-success',
            ]),
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
