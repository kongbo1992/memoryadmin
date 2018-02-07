<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\manager\models\ManagerLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-log-index">

    <?= GridView::widget([
        'id' => 'kv-grid-demo',
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'columns'=>[
            ['class' => 'kartik\grid\SerialColumn'],
            'route',
            [
                'attribute'=>'description',
                'filter'=>false,
            ],
            'user_id',
            'table_name',
            [
                'attribute'=>'ip',
                'content'=>function($model){
                    return long2ip($model->ip);
                },
            ],
            [
                'attribute'=>'created_at',
                'label' => '创建时间',
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
            [
                'attribute'=>'type',
                'label' => '操作类型',
                'filter'=> [1 => '新增',2 => '修改',3=>'删除'],
                'content'=>function($model){
                    if($model->type == 1){
                        return '<span class="btn-success">新增</span>';
                    }else if($model->type == 2){
                        return '<span class="btn-primary">修改</span>';
                    }else if($model->type == 3){
                        return '<span class="btn-danger">删除</span>';
                    }
                },
            ],
        ],
        // set your toolbar
        'toolbar'=> [
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
