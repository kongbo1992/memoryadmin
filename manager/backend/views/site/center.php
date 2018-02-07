<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\manager\models\ManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '个人中心';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-center">


    <p>
        <?= Html::a('修改资料', ['update'], [
            'class' => ' btn btn-primary',
        ]);
        ?>
        <?= Html::a('修改密码', '#', [
            'data-toggle' => 'modal',
            'data-target' => '#common-modal',
            'data-url' => \yii\helpers\Url::to(['upd-pwd']),
            'data-title' => '修改密码',
            'class' => 'modaldialog btn btn-danger',
        ]);
        ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'nickname',
            'email:email',
            'phone',
            ['label'=>'用户类型','value'=>\backend\models\LookUp::item('manager_type',$model -> role)],
            ['label'=>'状态','value'=>$model->status==10?'正常':"已禁用"],
            ['label'=>'性别','value'=>$model->status==1?'女士':"先生"],
            ['label'=>'是否推荐','value'=>$model->recommend==1?'推荐':"不推荐"],
            'headimg:image',
            'intros:text',
            'teacherintroduction:html',
            ['label'=>'创建时间','value'=>date("Y-m-d H:i:s",$model->created_at)],
            ['label'=>'更新时间','value'=>date("Y-m-d H:i:s",$model->updated_at)],
        ],
    ]) ?>

</div>
