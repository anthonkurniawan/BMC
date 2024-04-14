<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Historian;;
use Exception;

class HistorianController extends \yii\web\Controller {
  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['index', 'report', 'report-detail', 'trend'],
        'rules' => [
          [
            'actions' => ['index', 'report', 'report-detail', 'trend'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  public function actionReport($unit=null) {
    $req = \Yii::$app->request;
		$multiDate = false;
    if ($req->isPost) {
      $date = $req->post('Report')['date'];
      $dateTo = $req->post('Report')['dateTo'];
      $print = $req->post('Report')['isPrint'];
    } else {
      $date = $req->get('date');
      $dateTo = null;
      $print = $req->get('isPrint');
    }
		
		$model = new Historian($unit, $date, $dateTo, $print);
		$model->getDataHis();
    if(\Yii::$app->request->isAjax && !$req->isPjax){
      return $this->renderAjax('/site/report', ['model'=>$model, 'tpl'=>'_report_historian.php']);
    }
    return $this->render('/site/report', ['model'=>$model, 'tpl'=>'_report_historian.php']);
  }

  public function actionReportDetail($unit=null){
    $model = new Historian($unit);
    $model->areaName = $unit;

		if($model->load(Yii::$app->request->post())){
			if($model->samplingMode=='trend' && $model->interval=='1m')
				$model->interval='5m';
			$model->getDataHis();
		}
		else $model->getDataHis();

    $req = \Yii::$app->request;
    if($req->isAjax && !$req->isPjax){
      return $this->renderAjax('/site/report-historian-detail', ['model'=>$model, 'isAjax'=>$req->isAjax]);
    }
    return $this->render('/site/report-historian-detail', ['model' => $model, 'isAjax'=>$req->isAjax]);
  }

}
