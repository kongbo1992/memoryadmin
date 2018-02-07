<?php

use yii\helpers\Html;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\manager\models\LookUpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '字典表维护';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="look-up-index">

    <?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            'type',
            'code',
            'name',
            'order',
//            'city_id',
            [
                'attribute'=>'is_delete',
                'filter'=>[0=>'正常',1=>'已删除'],
                'content'=>function($model){
                    return $model->is_delete==0?"<span style='color:green'>正常</span>":"<span style='color:red'>已删除</span>";
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
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
                    'delete'=> function ($url, $model, $key){
                        if($model->is_delete == 0){
                            return  Html::a('删除', $url,[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-danger  btn-sm',
                                'data-confirm' => '确定删除该项？', //添加确认框
                            ] ) ;
                        }else{
                            return  Html::a('恢复', $url,[
                                'data-method'=>'post',              //POST传值
                                'class'=>'btn btn-success  btn-sm',
                                'data-confirm' => '确定恢复该项？', //添加确认框
                            ] ) ;
                        }

                    }
                ],
            ],
        ],
        // set your toolbar
        'toolbar'=> [
             Html::a('<i class="glyphicon glyphicon-plus"> 添加单词 </i>', '#', [
                'data-toggle' => 'modal',
                'data-target' => '#common-modal',
                'data-url' => \yii\helpers\Url::to(['create']),
                'data-title' => '添加单词',
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
