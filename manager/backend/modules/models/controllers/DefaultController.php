<?php

namespace backend\modules\models\controllers;

use backend\modules\models\models\TbModulePhoto;
use backend\modules\models\models\TbModulePhotoSearch;
use Yii;
use backend\modules\models\models\TbModule;
use backend\modules\models\models\TbModuleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for TbModule model.
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
     * Lists all TbModule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbModuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbModule model.
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
     * Creates a new TbModule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbModule();
        if($model->load(Yii::$app->request->post())){
            $model->createtime = date("Y-m-d H:i:s");
            $model->userid = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        else {
            $model->imgurl = '';
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbModule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbModule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TbModule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbModule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbModule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPhotoIndex($pid)
    {
        $data = $this->findModel($pid);
        $searchModel = new TbModulePhotoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$pid);

        return $this->render('photo_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $data
        ]);
    }

    public function actionPhotoCreate($pid)
    {
        $model = new TbModulePhoto();
        if($model->load(Yii::$app->request->post())){
            $model->createtime = date("Y-m-d H:i:s");
            $model->userid = Yii::$app->user->id;
            $model->pid = $pid;
            if ($model->save()) {
                return $this->redirect(['photo-index', 'pid' => $pid]);
            }
        }
        else {
            $model->imgurl = '';
            return $this->render('photo_create', [
                'model' => $model,
            ]);
        }
    }
}
