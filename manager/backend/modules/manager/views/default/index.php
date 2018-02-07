<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use backend\models\LookUp;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\manager\models\ManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-index">


<?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'username',
            'nickname',
            'phone',
            [
                'attribute'=>'role',
                'filter'=> LookUp::items('manager_type'),
                'content'=>function($model){
                    return LookUp::item('manager_type',$model->role);
                },
            ],
//            'email:email',
            [
                'attribute'=>'status',
                'filter'=>[10=>'正常',0=>'已禁用'],
                'content'=>function($model){
                    return $model->status==10?"<span style='color:green'>正常</span>":"<span style='color:red'>已禁用</span>";
                },
            ],
            [
                'attribute'=>'created_at',
                'content'=>function($model){
                    return date("Y-m-d H:i:s",$model->created_at);
                },
                'headerOptions' => ['width' => '200'],
                'filter'=>DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'language'=>'zh-CN',
                    "layout"=>'{picker}{input}',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ]
                ]),
            ],
//            [
//                'attribute'=>'updated_at',
//                'label'=>'更新时间',
//                'headerOptions' => ['width' => '200'],
//                'filter'=>DatePicker::widget([
//                    'model' => $searchModel,
//                    'attribute' => 'updated_at',
//                    'language'=>'zh-CN',
//                    "layout"=>'{picker}{input}',
//                    'pluginOptions' => [
//                        'autoclose' => true,
//                        'format' => 'yyyy-mm-dd',
//                        'todayHighlight' => true,
//                    ]
//                ]),
//                'content'=>function($model){
//                    return date("Y-m-d H:i:s",$model->updated_at);
//                }
//            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {reset-pwd} {lock}',
                'header' => '操作',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('修改', $url, [
                            'class' => ' btn btn-primary btn-sm',
                        ]);
                    },
                    'reset-pwd' => function ($url, $model, $key) {
                        return Html::a('重置密码', $url,['data-method' => 'post','class'=>'btn btn-warning btn-sm','data-confirm' => '确定要重置该项密码吗？',] );
                    },
                    'lock'=> function ($url, $model, $key){
                        if($model->status == 10){
                            return  Html::a('禁用', ['lock', 'id'=>$model->id],[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-danger  btn-sm',
                                'data-confirm' => '确定禁用该项？', //添加确认框
                            ] ) ;
                        }else{
                            return  Html::a('启用', ['lock', 'id'=>$model->id],[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-success  btn-sm',
                                'data-confirm' => '确定启用该项？', //添加确认框
                            ] ) ;
                        }

                    }
                ],
            ],
        ],
        // set your toolbar
        'toolbar'=> [
            Html::a('<i class="glyphicon glyphicon-plus"> 创建用户</i>', ['create'], [
                'class' => ' btn btn-success',
            ]),
            Html::a('<i class="glyphicon glyphicon-repeat"> 重置</i>', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>"重置"]),
//            '{export}',
//            '{toggleData}',
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
