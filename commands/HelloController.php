<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Units;
use app\models\Tagname;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller {

  /**
   * This command echoes what you have entered as the message.
   * @param string $message the message to be echoed.
   * @return int Exit code
   */
  public function actionIndex($message = 'hello world') {
    echo $message . "\n";

    return ExitCode::OK;
  }

  public function actionTest() {
		$unitName = 'N111';
		$unit = Units::findOne(['name'=>$unitName]); //echo "<pre>";print_r($unitD);echo "</pre>";die();
    $this->getTagsData($unit->id, '2018-10-31');
  }

  protected function getTagsData($unit, $date = null) {
    $rtime = 1800;
    $date = isset($date) ? $date : date('Y-m-d');
    if ($date != date('Y-m-d') && $rtime == 1800) {
      $q = "BETWEEN '{$date} 00:00' AND '{$date} 23:30'";
    } else {
			$time_ls = $this->timeList($date, $rtime);
      $q = "IN (" . $time_ls . ")";
    }
    $table = self::getTable($date);  // echo $table;die();
    $sql = $this->getQueryTag($unit, $table, $q);
    //echo "<br><b>table : </b> $table <br> <b>sql : </b> $sql"; die();
    if (is_array($sql))
      return $sql;
    $rs = \Yii::$app->db->createCommand($sql)->queryAll();  echo "RS TAGS-DATA: <pre>"; print_r($rs);echo"</pre>"; var_dump(!$rs);die();
    // if (!$rs)
      // return array('ferror' => 'Tags data empty');
    // else
      // $tagsData = $rs->getArray();
    echo "<br>count array-->" . count($tagsData) . "<br>";
    echo "<pre>";print_r($tagsData);echo "</pre>";

    if (count($tagsData) == 0) {
      $error = "Tags Data Empty";
      return array('error' => ($_GET['print']) ? "<div class=report_msg>$error</div>" : setMsg($error));
    }
    return $tagsData;
  }
	
  protected function getQueryTag($unit, $table, $q, $export = false) {    //b->cacheFlush("select tagname from units as t1 join tagname as t2 on t1.id=t2.unit where code='{$unit}'");  #TESTTTTT
    //$rs = $db->CacheGetAll(1000, "select alias from tagname as t1 join units as t2 on t1.unitId=t2.id where code='{$unit}'"); // NEW 
    $rs = \Yii::$app->db->createCommand('SELECT * FROM bmc.tagname WHERE unit_id=:unit_id', [':unit_id' => $unit])->queryAll();
    //echo"QUERY TAGS:<PRE>";print_r($rs);echo"</pre>" . count($rs);die();
    if (!$rs)
      return array('error' => (($export) ? "Error getting tagname $unit. Or empty tagname" : setMsg("Error getting tagname $unit. Or empty tagname")));

		$qls = [];
    foreach ($rs as $i => $v) {  //print_r($i); echo "=>"; print_r($v['alias']);
      //$qls[] = "[" . preg_replace("/[-.;]/ ", "_", $v[0]) . "]";
      //$qls[] = preg_replace("/[-.;]/ ", "_", $v[0]); // MYSQL
      //$qls[] = $v[0];  // CONVERT( DECIMAL(10,1), COLUMN )  OR CAST(COLUMN AS DECIMAL(10,1))
      $qls[] = "CAST($v[alias] AS DECIMAL(10,2)) as $v[alias]";
    }  // print_r($qls); die();
		//return 'select CONVERT(VARCHAR(5), tanggal, 108) from tagsdata';
    if (!$export)
			return "SELECT CONVERT(VARCHAR(5), tanggal, 108) as tanggal," . implode($qls, ',') . " FROM {$table} WHERE tanggal {$q}";
      //return "SELECT date_format(tanggal, '%H:%i')," . implode($qls, ',') . " FROM {$table} WHERE tanggal {$q}"; // MYSQL
    else
      return "SELECT tanggal," . implode($qls, ',') . " FROM {$table} WHERE tanggal {$q}";
  }

  protected function getTable($date) {
    $y = date("Y", strtotime($date));
    return (date("Y") === $y) ? 'TAGSDATA' : "TAGSDATA" . $y;
  }

  protected function timeList($date, $time) {
    $interval = 86400 / $time;  //86400 = 24hour
    $csr = 0;
    $ls_time = '';
    for ($i = 1; $i <= $interval; $i++) {
      $clock = self::num_to_time($csr);
      $csr = $csr + $time;
      $dt = $date . " " . $clock;
      if (date("Y-m-d H:i", strtotime($dt)) > date("Y-m-d H:i"))
        break;
      $ls_time .= "'" . $date . " " . $clock . "' ,";
    }
    return substr($ls_time, 0, -1);
  }

  protected function num_to_time($num) {
    $hours = floor($num / 3600);
    $minutes = floor(($num - ($hours * 3600)) / 60);
    $seconds = $num - (($hours * 3600) + ($minutes * 60));
    $time = $hours . ':' . $minutes . ':' . $seconds;
    return $time;
  }

}
