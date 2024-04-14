<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Report;
use app\models\Area;
use Exception;

class ReportController extends BmcController {

  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['index', 'trend'],
        'rules' => [
          [
            'actions' => ['index', 'trend'],
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

  public function actionIndex() {
    $req = \Yii::$app->request;
    $unitP = strtolower($req->get('unit')); 
		if(!$this->areaAccess($unitP)){
			return $this->render('/site/error', [
				'name' => 'Restricted Area',
				'message'=>"You don't have priviledge to access this area"
			]);
		}
		
    $multiDate = false;
    if ($req->isPost) {
      $date = $req->post('Report')['date'];
      $dateTo = $req->post('Report')['dateTo']; 
      $print = $req->post('Report')['isPrint'];
      $multiDate = $req->post('Report')['isMultidate'];
    } else {
      $date = $req->get('date');
      $dateTo = null;
      $print = $req->get('isPrint');
    }

    $model = new Report($unitP, $date, $dateTo, $print, $multiDate);
    $model->getReport();
    $summary = $model->areaName . " " . ($model->dateTo ? $model->date . " - " . $model->dateTo : $model->date);  //echo $summary;die();

    if (!$print) {
      if($req->isAjax && !$req->isPjax){
        return $this->renderAjax('/site/report-dialog', ['model' => $model]);
      }

      return $this->render('/site/report', ['model' => $model, 'tpl'=>'_report.php']);
    } 
		else
    {
      $html = $this->renderFile(Yii::$app->viewPath . '/site/reportPrint.php', ['model' => $model]); 
      if ($model->isPrint == 'pdf') {
        $this->printPdf($summary, $html);
      } else if ($print == 'xls') {
				$date = $model->dateTo ? "$model->date - $model->dateTo" : $model->date;
        $colsAtr = array('A' => array('width' => 'AT', 'align' => 'C'));
				$setting = array('title' =>'BMC Daily Report', 'dept'=>$model->dept, 'area'=>$model->areaName, 'date'=>$date, 'filename'=>$summary);
        $this->printXls($html, true, $setting);
      }
      $this->log("Print $print report $summary");
    }
  }

}
