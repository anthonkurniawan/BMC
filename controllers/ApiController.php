<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
// use app\models\Report;
use Exception;

/**
 sc start mssqlserver
 sc start ihdataarchiver
 sc start IHSimulationCollector
 psexec \\192.168.56.2 -u admin -p langit -i -w "C:\Program Files\Proficy\Proficy iFIX" launch.exe /D
  Lauch sample system:
  "C:\Program Files\Proficy\Proficy iFIX\launch.exe" /D
  Start ifix collector
  "C:\Program Files\Proficy\Proficy iFIX\ihFIXCollector.exe" NOSERVICE
**/

class ApiController extends BmcController {

  private $sampleData = [
		[
      'tagname' => 'XP-SP3.Simulation00001',
      'time' => '2022-01-18 20:21:15',
      'value' => '690.00',
      'quality' => '100',
      'spec' => [
          0 => '&gt; 15000',
          1 => null
      ]
    ],
    [
      'tagname' => 'SAMPLE.IFIX1_BATCH_REACTORLEVEL.B_CUALM',
      'time' => '2022-01-18 20:21:17',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '!= 267',
          1 => null
      ]
    ],
		[
      'tagname' => 'SAMPLE.IFIX1_BATCH_MIXOUTFLOW.B_CUALM',
      'time' => '2022-01-18 20:21:16',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '!= 267',
          1 => null
      ]
    ],
		[
      'tagname' => 'SAMPLE.IFIX1_BATCH_BULKFLOW.B_CUALM',
      'time' => '2022-01-18 20:21:17',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '!= 267',
          1 => null
      ]
    ],
    [
      'tagname' => 'SAMPLE.IFIX1_BATCH_BULKFLOW.F_CV',
      'time' => '2022-01-18 20:21:16',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '&gt;= 50',
          1 => null
      ]
    ],
		[
      'tagname' => 'SAMPLE.IFIX1_BATCH_BULKLEVEL.F_CV',
      'time' => '2022-01-18 20:21:16',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '&gt;= 900',
          1 => null
      ]
    ],
    [
      'tagname' => 'SAMPLE.IFIX1_BATCH_BULKLEVEL.B_CUALM',
      'time' => '2022-01-18 20:21:16',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '&gt;= 1500',
          1 => null,
      ]
    ],
		[
      'tagname' => 'SAMPLE.IFIX1_BATCH_CIPFLOW.F_CV',
      'time' => '2022-01-18 20:21:17',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '&gt;=15',
          1 => null
      ]
    ],
    [
      'tagname' => 'SAMPLE.IFIX1_BATCH_CIPFLOW.B_CUALM',
      'time' => '2022-01-18 20:21:16',
      'value' => '0.00',
      'quality' => '0',
      'spec' => [
          0 => '!= 267',
          1 => null
      ]
    ],
];

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

  public function actionHistorian(){
    $req = Yii::$app->request;
    if ($req->isPost) {
      $area = $req->post('area');
    }
    $model = new \app\models\Report(1);
    \yii\helpers\VarDumper::dump($model, 10, true);
  }

  public function actionTrend(){
    if(!isset($_GET['unit']) ) return;
    $unit = $_GET['unit'];

    $tags = \Yii::$app->db->createCommand(
			"SELECT
				t1.name as tag, t1.alias as tagname,  t1.spec, t1.spec2
				FROM tagname as t1
				LEFT JOIN  area as t2 ON t1.areaId=t2.id
				WHERE t2.name=:areaName order by areaId asc",
			[':areaName' => $unit]
		)->queryAll();

    $res=[];
    try {
      fsockopen('192.168.56.2', 1433, $errno, $errstr, 10);
      $db = \Yii::$app->db;
      $db->createCommand("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;")->execute();

      foreach($tags as $k=>$v){
        $tagdata = $db->createCommand(
        "SELECT TOP 1 tagname, CONVERT(datetime, timestamp) AS time, CONVERT(decimal(10,2), value) AS value, quality FROM openquery(HISTORIAN,
        'SELECT tagname, timestamp,value,quality FROM IHRAWDATA WHERE tagname=$v[tag]') order by timestamp DESC")->queryAll();
        $tagdata[0]['specs'] = [$v['spec'], $v['spec2']];
        $res = array_merge($tagdata, $res);
      }
      $res = array_reverse($res);

      $data = [['data'=>date('h:i:s', strtotime($res[0]['time']))]];
      foreach($res as $k=>$v){
        $class = $this->valueCheck($v['value'], $v['specs']);
        $tdata = ['data'=>$v['value'], 'class'=>$class];
        array_push($data, $tdata);
      }
    } catch (Exception $e) {
      $err = $e->getMessage();
      $data = ['error'=> $err];
    }

    if(Yii::$app->request->isAjax){
      $response = \Yii::$app->response;
      $response->format = \yii\web\Response::FORMAT_JSON;
      $response->data = $data;
      return;
    }else{
      \yii\helpers\VarDumper::dump($data, 10, true);
      exit(0);
    }

  }

  static function valueCheck($v, Array $spec){ 
    if(!isset($spec[0])) return true;
    $danger_spec = 0.5;  // 50% from setting spec
		$spec1 = html_entity_decode($spec[0]); // html_entity_decode
		if(isset($spec[1])){
			$spec2 = html_entity_decode($spec[1]);
			$str = "$v $spec1 && $v $spec2"; 
		}
		else{
			$str = "$v $spec1";	
		}
		$val = eval("return ($str);"); 
    if(!$val){
      if(preg_match("/^(.*?)([!<>=|]=?)(.*?)$/m", $spec1, $arr)){
        $opr = $arr[2];
        if(preg_match('/>/', $opr)){
          $val = $arr[3] - ($arr[3] * $danger_spec);
        }else if(preg_match('/</', $opr)){
          $val = $arr[3] + ($arr[3] * $danger_spec);
        }

        $val = eval("return ($v $opr $val);");
        return $val ? "warning" : "danger";
      }
    } 
		return "good";
	}

//echo valueCheck(99, ['<100', '']) ."\n";   // < 99 ok
//echo valueCheck(100, ['<100', '']) ."\n";  // < 100 warning
//echo valueCheck(150, ['<100', '']) ."\n";  // < 150 danger

//echo valueCheck(100, ['<= 100', '']) ."\n";   // < 100 ok
//echo valueCheck(101, ['<= 100', '']) ."\n";  // < 100 warning
//echo valueCheck(151, ['<= 100', '']) ."\n";  // < 151 danger

//echo valueCheck(101, ['>100', '']) ."\n";   // < 101 ok
//echo valueCheck(100, ['>100', '']) ."\n";   // > 100 warning
//echo valueCheck(50, ['>100', '']) ."\n";   // > 50 danger

//echo valueCheck(100, ['>=100', '']) ."\n";   // < 100 ok
//echo valueCheck(99, ['>=100', '']) ."\n";   // > 99 warning
//echo valueCheck(49, ['>=100', '']) ."\n";   // > 49 danger

// preg_match('/^(\>|\>=|\<|\<=|\={2})(\s|)[0-9]+(\.|)[0-9]+$/', '== 110.01');  // filter the spec
// preg_replace ('/[^\>\<]\W+[0-9](\.)[0-9]$/', '', '> 100.5 ;');
// preg_replace ('/[^\>\<\=\!0-9\.]/', '', '>100.5 ;');

}
