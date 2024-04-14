<?php

namespace app\models;

use Yii;
use app\models\Area;
use yii\web\NotFoundHttpException;
use yii\db\Exception;
use DateTime;

class Report extends Table
{
	public $tagsdata;
	public $summary;
	public $isSummary=false;
	public $error;
	
	
	public function __construct($area, $date, $dateTo, $print, $multiDate, $summary=false) {
    $this->areaName = $area;
    $this->date = $date ? $this->validateDate($date) : date("Y-m-d");
		$this->dateTo = $dateTo ? date("Y-m-d", strtotime($dateTo)) : null;
		$this->isMultidate = $multiDate;
		$this->isPrint = $print;
		$this->isSummary = $summary;
  }
	
	public function getReport(){
		if(!$this->areaName) throw new NotFoundHttpException("Area not provided!");
		$this->tagnames = $this->getTagnames();
		$this->dept = $this->tagnames[0]['dept'];
		$deptId = $this->tagnames[0]['deptId'];
		$areaId = $this->tagnames[0]['areaId'];

		if(!Yii::$app->user->isGuest){
			$_SESSION['identity']['activeMenuDept_id'] = $deptId;
			$_SESSION['identity']['activeMenuDept_name'] = $this->dept;
		}
		
		if(!$this->tagnames){
			Yii::$app->session->setFlash('msg', "<h3>Area \"".$this->areaName."\" doenst have tagname assigment</h3>");
			return;
		}

		$this->tagsdata['header'] = self::getHeader();
		$d1 = new DateTime($this->date);
		$d2 = new DateTime($this->dateTo);
		$y1 = $d1->format('Y');
		$y2 = $d2->format('Y');
				
		if($this->dateTo && ($y1!==$y2)) {
			$d1->modify("last day of $y1");  
			$d2->modify("first day of january");
				
			$rs1 = $this->getTagsData($this->date, $d1->format('Y-m-d'));
			$rs2 = $this->getTagsData($d2->format('Y-m-d'), $this->dateTo);
				
			$data = array_merge($rs1, $rs2);
			$this->tagsdata['data'] = $data;
		}
		else{ 
			$this->tagsdata['data'] = $this->getTagsData($this->date, $this->dateTo); 
			if($this->isSummary && isset($this->tagsdata['data'][0])) $this->getSummary();
		}
		if($this->error) 
			Yii::$app->session->setFlash('msg', $this->error);
		else if(count($this->tagsdata['data'])==0) 
			Yii::$app->session->setFlash('msg', "There is no data to show");
	}
	
	protected function getTagsData($date=null, $dateEnd=null) {
		$dateEnd = $dateEnd ? $dateEnd : $date;
		if(!$this->isSummary) $dateEnd .= $dateEnd < date ('Y-m-d') ? " 23:59" : " ".date('H:m');
		else $dateEnd .= " 23:59";
		$q = "BETWEEN '{$date} 00:00' AND '{$dateEnd}' order by tanggal asc"; 
		/*-------------------- FOR SUMMARY TEST ---------------------------------*/
		//$q = "BETWEEN '2019-01-23 06:00' AND '2019-01-23 08:00'"; 
		$table = self::getTable($date);
    $sql = $this->getQueryTag($q, $table);
		if(!$sql) return;

		try{
			return \Yii::$app->db->createCommand($sql)->queryAll();
		}catch(Exception $e){
			$this->error = preg_replace('/\n/', '<br>', $e->getMessage());
		}
  }
	
  protected function getQueryTag($q, $tbl) {
    if (!$this->tagnames) return;

		$qls = [];
    foreach ($this->tagnames as $i => $v) {
      $qls[] = "CAST($v[tagname] AS DECIMAL(10,2)) as $v[tagname]";
    }
    // MSQL
		$sql = $this->isMultidate ? "SELECT FORMAT (tanggal, 'yyyy-MM-dd HH:mm') as tgl," : "SELECT CONVERT(VARCHAR(5), tanggal, 108) as tgl,";
		return $sql.implode(',', $qls) . " FROM {$tbl} WHERE tanggal {$q}";
  }
	
	protected function getSummary(){
		$this->summary['keys'] = ['Minimum','Maximum','Average','∑ DATA','∑ DATA LOOS'];
		$this->summary['header'] = self::getHeader(1);  
		$keys = array_keys($this->tagsdata['data'][0]);
		foreach($keys as $k){
			if($k!=='tgl'){
				$spec = $this->getSpec($k);
				$arr = array_column($this->tagsdata['data'], $k); 
				$this->summary['data'][$k]['data'] = $arr;
				$this->summary['data'][$k]['Average'] = number_format(array_sum($arr) / count($arr), 2, '.','');
				$this->summary['data'][$k]['Minimum'] = number_format(min($arr), 2, '.','');
				$this->summary['data'][$k]['Maximum'] = number_format(max($arr), 2, '.','');
				$this->summary['data'][$k]['∑ DATA'] = number_format(array_sum($arr), 2, '.','');
				$this->summary['data'][$k]['∑ DATA LOOS'] = $this->countDataLoos($arr, $spec);
			}
		}  
	}
	
  protected function getTable($date) {
    $y = date("Y", strtotime($date));
    return (date("Y") === $y) ? 'tagsdata' : "tagsdata" . $y;
  }
	
	protected function getSpec($tagname){
		foreach($this->tagnames as $k=>$v){
			if($v['tagname']==$tagname) return [$v['spec'], $v['spec2']];
		}
	}
	
	protected function countDataLoos($data, $spec){ 
		$count=0;
		foreach($data as $v){
			if($v && isset($spec[0])){    
				if(!$this->valueCheck($v, $spec)) $count ++;
			}
		}
		return $count;
	}

	protected function timeList($date, $time=1800) {
		$ls_time="";
		$interval = 86400 / $time;  //86400 = 24hour
		$csr = 0;
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

	public function rules() {
    return [
      [['date'], 'required'],
			['areaName', 'validateareaName']
    ];
  }
	
	public function attributeLabels() {
    return [
      'date' => 'Date',
			'dateTo'=> 'To',
    ];
  }
	
	public function validateareaName($attribute, $params, $validator){
		if (!in_array($this->$attribute, ['vst', 'vsc', 'vsd', 'vsd2','wip','warehouse','refrigerator','ws','ahu'])) {
			$this->addError($attribute, 'Invalid area!');
    }
  }
	
	public function getDateLabel(){
		if($this->dateTo){
			return (date("d-m-Y", strtotime($this->date)) ." - ". date("d-m-Y", strtotime($this->dateTo)));
		}else{
			return date("d-m-Y", strtotime($this->date));
		}
	}
	
	public function getminDataTime(){
		return self::minDataTime;
	}
	
	public function getminDataTime2(){
		return self::minDataTime;
	}
}
