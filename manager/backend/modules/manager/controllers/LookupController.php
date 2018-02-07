<?php

namespace backend\modules\manager\controllers;

use Yii;
use backend\modules\manager\models\LookUp;
use backend\modules\manager\models\LookUpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\modules\manager\behaviors\LookUpBehavior;
use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * LookupController implements the CRUD actions for LookUp model.
 */
class LookupController extends Controller
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
     * Lists all LookUp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LookUpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LookUp model.
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
     * Creates a new LookUp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LookUp();
        $model -> attachBehavior('lookup',LookUpBehavior::className());
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "添加单词 ".$model->name." 成功！");
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LookUp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model -> old_model = $model -> attributes;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return  ActiveForm::validate($model);
        }
        $model -> attachBehavior('lookup',LookUpBehavior::className());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", "修改单词 ".$model->name." 成功！");
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LookUp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model -> is_delete==0){
            $model -> is_delete = 1;
        }else{
            $model -> is_delete = 0;
        }
        if($model->save(false)){
            Yii::$app->session->setFlash("success", "单词 ".$model->name." 删除/恢复 成功！");
        }else{
            Yii::$app->session->setFlash("error", "单词 ".$model->name." 删除/恢复 失败！");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the LookUp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LookUp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LookUp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
