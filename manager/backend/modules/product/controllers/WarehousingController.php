<?php

namespace backend\modules\product\controllers;

use Yii;
use backend\modules\product\models\TbProductWarehousing;
use backend\modules\product\models\TbProductStock;
use backend\modules\product\models\TbProductWarehousingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WarehousingController implements the CRUD actions for TbProductWarehousing model.
 */
class WarehousingController extends Controller
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
     * Lists all TbProductWarehousing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbProductWarehousingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbProductWarehousing model.
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
     * Creates a new TbProductWarehousing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbProductWarehousing();

        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $date = date("Y-m-d H:i:s");
            $model_1 = $this->findModel_stock($post['TbProductWarehousing']['product_id']);
            $model_1->total = $model_1->total + $post['TbProductWarehousing']['num'];
            $model_1->stock = $model_1->stock + $post['TbProductWarehousing']['num'];
            $model->createtime = $date;
            $model->oper_code = Yii::$app->user->id;
            if (  $model_1->save() && $model->save() ) {
                Yii::$app->db->createCommand()->insert('tb_log_stock', [
                    'createtime' => $date,
                    'add' => $post['TbProductWarehousing']['num'],
                    'stock' =>$model_1->stock,
                    'oper_code' => Yii::$app->user->id,
                    'oper_event' => 'add',
                    'product_id' => $model_1->product_id,
                ])->execute();
                Yii::$app->session->setFlash("success", "添加库存成功！");
            } else {
                Yii::$app->session->setFlash("error", "添加库存失败！");
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProductWarehousing model.
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
     * Deletes an existing TbProductWarehousing model.
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
     * Finds the TbProductWarehousing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProductWarehousing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProductWarehousing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel_stock($id)
    {
        if (($model = TbProductStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
