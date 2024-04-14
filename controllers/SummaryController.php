<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use app\models\Report;
use DateTime;

class SummaryController extends BmcController //\yii\web\Controller
{
	public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index'],
            'roles' => ['@'],
          ],
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
		if($unitP!='warehouse') throw new \yii\web\NotFoundHttpException('Not valid request!');
			
		$print=0;
    if ($req->isPost) {
      $date = $req->post('Report')['date'];
      $dateTo = $req->post('Report')['dateTo']; 
      $print = $req->post('Report')['isPrint'];
    }
		if(!isset($date)) $date = date("Y-m-d");
		$d = new DateTime($date);
		$date = $d->modify('first day of this month')->format('Y-m-d');
		$dateTo = $d->modify('last day of this month')->format('Y-m-d');
		
    $model = new Report($unitP, $date, $dateTo, $print, 1, 1);
    $model->getReport();
    $summary = "Summary ".$model->area->name." ". date("F Y", strtotime($model->date)); 

    if ($print) {
      $html = $this->renderFile(Yii::$app->viewPath . '/summary/summaryPrint.php', ['model' => $model]); 
      if ($model->isPrint == 'pdf') {
        $descriptorspec = array(
          0 => array('pipe', 'r'), // stdin
          1 => array('pipe', 'w'), // stdout
          2 => array('pipe', 'w'), // stderr
        );
        //$process=proc_open("C:\wkhtmltopdf\bin\wkhtmltopdf.exe -q -L 4 -R 4 -T 4 -B 4 --javascript-delay 1000 - -", $descriptorspec, $pipes);
				$iden = Yii::$app->user->getIdentity();
				$username = $iden ? $iden->username : 'Guest';
				$process = proc_open('C:\wkhtmltopdf\bin\wkhtmltopdf.exe -q --footer-font-size 7 --footer-font-name italic --footer-right "Page [page] of [toPage]" --footer-left "Printed by : '.$username.' '.date('d.m.Y h:i:s').'" --javascript-delay 1000 - -', $descriptorspec, $pipes);
        // Send the HTML on stdin
        fwrite($pipes[0], $html);
        fclose($pipes[0]);

        // Read the outputs
        $pdf = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        // Close the process
        fclose($pipes[1]);
        $return_value = proc_close($process);
        if ($errors) {
          throw new Exception('PDF generation failed: ' . $errors);
        } else {
          header('Content-Type: application/pdf');
          header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
          header('Pragma: public');
          header('Expires: 0'); // Date in the past
          //header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
          header('Content-Length: ' . strlen($pdf));
          header('Content-Transfer-Encoding: binary');
          header('Content-Disposition: attachment; filename="' . $summary . '.pdf";');
          echo $pdf;
        }
      } else if ($print == 'xls') { 
        $date = date("F j, Y", strtotime($date));
        $colsAtr = array('A' => array('width' => 'AT', 'align' => 'C'));
        $setting = array('label' => $summary, 'headRows' => 1, 'colsAtr' => $colsAtr, 'colsize' => 13);
        $this->printXls($html, $date, $setting);
      }
      $this->log("Print $print report $summary");
    } 
		else {
      return $this->render('index', ['model' => $model]);
    }
  }
}
