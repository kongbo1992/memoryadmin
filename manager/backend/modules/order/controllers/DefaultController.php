<?php

namespace backend\modules\order\controllers;

use Yii;
use backend\modules\order\models\TbProductOrder;
use backend\modules\order\models\TbProductOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel;
use \PHPExcel_IOFactory;

/**
 * DefaultController implements the CRUD actions for TbProductOrder model.
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
     * Lists all TbProductOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbProductOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbProductOrder model.
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
     * Creates a new TbProductOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbProductOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProductOrder model.
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
     * Deletes an existing TbProductOrder model.
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
     * Finds the TbProductOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProductOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProductOrder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 导出订单
     */
    public function actionExport($id){
        $data = Yii::$app->db->createCommand("SELECT o.money,o.linkname,o.linkphone,o.address,o.dct_type,o.discount,o.auser_id,l.num,l.price,p.`name`,p.unit FROM tb_product_order o INNER JOIN tb_product_order_list l ON l.order_id = o.id INNER JOIN tb_product p ON p.id = l.product_id WHERE o.id = {$id} ")->queryAll();
        if ( $data ) {
            $objectPHPExcel = new PHPExcel();
            $objectPHPExcel->setActiveSheetIndex(0);

            $objectPHPExcel->getActiveSheet()->setCellValue('A1',"无锡市福享天下科技有限公司");
            $objectPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->mergeCells('A1:E1');

            $objectPHPExcel->getActiveSheet()->setCellValue('A2',"产品发货单");
            $objectPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objectPHPExcel->getActiveSheet()->setCellValue('C2',"单据编号：");
            $objectPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objectPHPExcel->getActiveSheet()->mergeCells('A2:B2');
            $objectPHPExcel->getActiveSheet()->mergeCells('C2:E2');

//            $styleThinBlackBorderOutline = array(
//                'borders' => array(
//                    'allborders' => array( //设置全部边框
//                        'style' => \PHPExcel_Style_Border::BORDER_THIN //粗的是thick
//                    ),
//
//                ),
//            );
            $styleThinBlackBorderOutline = array(
                'borders' => array (
//                    'allborders' => array( //设置全部边框
                    'outline' => array ( //设置外边框
                        'style' => \PHPExcel_Style_Border::BORDER_THIN,  'color' => array ('argb' => 'FFCCCCCC'), //设置border颜色
                    ),
                ),)
            ;

            $objectPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);//设置填充颜色
            $objectPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFill()->getStartColor()->setARGB('FFFFFFFF');
            $objectPHPExcel->getActiveSheet()->getStyle( 'A2:E2')->applyFromArray($styleThinBlackBorderOutline);



            $objectPHPExcel->getActiveSheet()->setCellValue('A3',"收货单位或姓名：{$data[0]['linkname']}");

            $objectPHPExcel->getActiveSheet()->mergeCells('A3:C3');
            $objectPHPExcel->getActiveSheet()->setCellValue('D3',date("Y") . "年" .date("m") ."月" . date("d") . "日");
            $objectPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objectPHPExcel->getActiveSheet()->mergeCells('D3:E3');

            $objectPHPExcel->getActiveSheet()->getStyle( 'A3:E3')->applyFromArray($styleThinBlackBorderOutline);


            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A4','产品名称');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B4','单位');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C4','数量');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D4','单价');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E4','金额');
            $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F4','联系邮箱');
//            $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G4','自我描述');
//            $objectPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
            $count = 5;
            $unit = Yii::$app->params['unit'];
            foreach ( $data as $key => $val ) {
                $objectPHPExcel->getActiveSheet()->setCellValue('A'.($count),$val['name']);
                $objectPHPExcel->getActiveSheet()->setCellValue('B'.($count),$unit[$val['unit']]);
                $objectPHPExcel->getActiveSheet()->setCellValue('C'.($count),$val['num']);
                $objectPHPExcel->getActiveSheet()->setCellValue('D'.($count),$val['price']);
                $objectPHPExcel->getActiveSheet()->setCellValue('E'.($count),$val['price'] * $val['num']);
                $count ++;
//                $objectPHPExcel->getActiveSheet()->setCellValue('F'.($count),$val['info']);
//                $objectPHPExcel->getActiveSheet()->setCellValue('G'.($count),$val['info']);
            }
            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($count),"合计金额（大写）：");
            $objectPHPExcel->getActiveSheet()->setCellValue('B'.($count),$data[0]['money']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E'.($count),$data[0]['money']);
            $objectPHPExcel->getActiveSheet()->getStyle('B'.($count))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objectPHPExcel->getActiveSheet()->mergeCells("B{$count}:D{$count}");
            $count ++;
            $count ++;

            $objectPHPExcel->getActiveSheet()->setCellValue('A'.($count),"送货单位经办人：" . Yii::$app->user->identity->nickname);

            $objectPHPExcel->getActiveSheet()->mergeCells("A{$count}:C{$count}");
            $objectPHPExcel->getActiveSheet()->setCellValue("D{$count}","收货单位签名：");
            $objectPHPExcel->getActiveSheet()->getStyle("D{$count}")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objectPHPExcel->getActiveSheet()->mergeCells("D{$count}:E{$count}");

            $objectPHPExcel->getActiveSheet()->getStyle( "A{$count}:E{$count}")->applyFromArray($styleThinBlackBorderOutline);

        } else {
            Yii::$app->session->setFlash("error", "订单导出失败！");
            return $this->redirect(['index']);
        }
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'用户简历表-'.date("Y年m月j日").'.xls"');
//        $objWriter = new PHPExcel();
        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');
        $objWriter->save('php://output');
    }

}
