<?php

namespace backend\modules\product\controllers;

use Yii;
use backend\modules\product\models\TbProduct;
use backend\modules\product\models\TbProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for TbProduct model.
 */
class AddController extends Controller
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
     * Lists all TbProduct models.
     * @return mixed
     */
    public function actionIndex($id=null,$aid=null)
    {
        $searchModel = new TbProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'aid' => $aid,
        ]);
    }

    public function actionSubsetIndex($pid)
    {
        $searchModel = new TbProductSearch();
        $dataProvider = $searchModel->search_subset(Yii::$app->request->queryParams,$pid);

        return $this->render('subset_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbProduct model.
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
     * Creates a new TbProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($level=1,$pid=null)
    {
        $model = new TbProduct();
        $model->setScenario('create');
        if ($model->load(Yii::$app->request->post())) {
            $model->createtime = date("Y-m-d H:i:s");
            $model->author = Yii::$app->user->id;
            $model->type = 2;
            $model->pid = $pid;
            $model->level = $level;
            if ( $model->save() ) {
                Yii::$app->db->createCommand()->insert('tb_product_stock', [
                    'product_id' =>  $model->id
                ])->execute();

                Yii::$app->session->setFlash("success", "添加产品信息成功！");
            } else {
                Yii::$app->session->setFlash("error", "添加产品信息失败！");
            }
            if ( empty($pid) ) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['subset-index','pid' => $pid,'level' => $level]);
            }
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /*
     * 品牌添加
     */
    public function actionPpcreate($level=1,$pid=null)
    {
        $model = new TbProduct();
        $model->setScenario('ppcreate');
        if ($model->load(Yii::$app->request->post())) {
            $model->createtime = date("Y-m-d H:i:s");
            $model->author = Yii::$app->user->id;
            $model->type = 1;
            $model->pid = $pid;
            $model->level = $level;
            if ( $model->save() ) {
                Yii::$app->db->createCommand()->insert('tb_product_stock', [
                    'product_id' =>  $model->id
                ])->execute();

                Yii::$app->session->setFlash("success", "添加产品信息成功！");
            } else {
                Yii::$app->session->setFlash("error", "添加产品信息失败！");
            }
            if ( empty($pid) ) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['subset-index','pid' => $pid,'level' => $level]);
            }
        } else {
            return $this->renderAjax('pp_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ( $model->save() ) {
                Yii::$app->session->setFlash("success", "编辑产品信息成功！");
            } else {
                Yii::$app->session->setFlash("error", "编辑产品信息失败！");
            }
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbProduct model.
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
     * Finds the TbProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    // 调取树节点
    public function actionAllproduct(){
        $product = TbProduct::find()
            ->select('id,pid,name')
            ->andWhere(['type' => 1])
            ->all();
        $arr = [];
        $arr[0]['id'] = 0;
        $arr[0]['pId'] = 0;
        $arr[0]['dataId'] = 0;
        $arr[0]['name'] = "单个产品";
        foreach($product as $i =>  $li){
            $i++;
            $arr[$i]['id'] = intval($li['id']);
            $arr[$i]['pId'] = $li['pid'];
            $arr[$i]['dataId'] = $li['id'];
//            $arr[$i]['orders'] = $li['orders'];
            //$arr[$i]['name'] = $li['name'];
            $arr[$i]['name'] = $li['name'];
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return json_encode($arr);
        }
    }
}
