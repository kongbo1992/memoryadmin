<?php

namespace backend\modules\manager\controllers;

use Yii;
use backend\modules\manager\models\Manager;
use backend\modules\manager\models\ManagerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\tools\DistStorage;

use backend\modules\manager\behaviors\ManagerBehavior;
/**
 * DefaultController implements the CRUD actions for Manager model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Manager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManagerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Manager model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Manager model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Manager();
        $model -> headimg = '';
        $model -> attachBehavior('manager',ManagerBehavior::className());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash("success", "新增用户 ".$model->username."成功！");
                return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Manager model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model -> role > Yii::$app->user->identity->role){
            throw new \yii\web\UnauthorizedHttpException('你没有操作权限');
        }
        $model -> old_attributes = $model->attributes;
        $model -> attachBehavior('manager',ManagerBehavior::className());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "修改用户 ".$model->username."成功！");
            return $this->redirect(['index']);
        } else {
            $model -> headimg = !empty($model -> headimg)?$model -> headimg:'';
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Manager model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLock($id)
    {
        $model = $this->findModel($id);
        if($model -> role > Yii::$app->user->identity->role){
            throw new \yii\web\UnauthorizedHttpException('你没有操作权限');
        }
        if($model -> status == 10 ){
            $model->status=0;
        }else{
            $model->status=10;
        }
        if($model->save(false)){
            Yii::$app->session->setFlash("success", "用户 ".$model->username." 禁用/启用 成功！");
        }else{
            Yii::$app->session->setFlash("error", "用户 ".$model->username." 禁用/启用 失败！");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Manager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manager::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 重置密码
     */
    public function actionResetPwd($id)
    {
        $model = $this->findModel($id);
        if($model -> role > Yii::$app->user->identity->role){
            throw new \yii\web\UnauthorizedHttpException('你没有操作权限');
        }
        $model -> setPassword("boRn_345");
        if($model->save(false)){
            Yii::$app->session->setFlash("success", "用户:".$model->username." 密码重置成功！");
        }else{
            Yii::$app->session->setFlash("error", "用户:".$model->username." 密码重置失败！");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}
