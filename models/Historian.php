<?php
namespace app\models;

use Yii;

class Historian extends Table
{
  public $startTime;
  public $endTime;
  public $interval = '30m';
  public $samplingMode = 'Calculated'; // Trend, currentvalue, interpolated, Lab, Trend2, RawByTime, RawByNumber, InterpolatedtoRaw, 
	public $data;
  public $time_marker;
	
	public function rules()
  {
    return [
			[['areaName','startTime','endTime','interval','samplingMode'], 'required'],
      // [['areaName','date','dateTo','startTime','endTime','isPrint','interval','samplingMode','data','dept','tagnames','isMultidate'], 'safe'],
			[['time_marker'], 'safe'],
    ];
  }
	
  public function __construct($area, $date = null, $dateTo = null, $print = false){
		$this->areaName = $area;
		$this->date = $date ? $date : date('Y-m-d');
		$this->dateTo = $dateTo ? $dateTo : $this->date;
    $this->startTime = $this->date.' 00:00';
    $this->endTime = date('Y-m-d h:i', strtotime($this->dateTo));
		$this->isPrint = $print;
  }

  public function getDataHis() {
		// ini_set('memory_limit','500M'); // def: 128m
		
		$this->tagnames = $this->getTagnames();
		if(!count($this->tagnames))
			throw new \yii\web\NotFoundHttpException("Area Not Found");
		
		$this->dept = $this->tagnames[0]['dept'];
		$deptId = $this->tagnames[0]['deptId'];
		$areaId = $this->tagnames[0]['areaId'];

		if(!Yii::$app->user->isGuest){
			$_SESSION['identity']['activeMenuDept_id'] = $deptId;
			$_SESSION['identity']['activeMenuDept_name'] = $this->dept;
		}
		
		$query = \Yii::$app->db->createCommand(
			"EXEC HISTORIAN.dbo.SP_GETHISTORIAN @area_id=$areaId,
			@start_time='$this->startTime',
			@end_time='$this->endTime',
			@intv='$this->interval',
			@samplingMode='$this->samplingMode'"
		);
		try{
			$this->data = $query->queryAll();
			// return $this;
		}catch(\yii\db\Exception $e){
			echo "Exception", $e->getMessage(); die();
    } 
		catch(\Throwable $e) {
      // $this->addError('ldap', preg_replace('/[\'\"]/',"\\'", $e->getMessage()));
			echo "Throwable", $e->getMessage(); die();
    }
  }
	
	public function getDateLabel(){
		if($this->dateTo && $this->dateTo != $this->date){
			return (date("d-m-Y", strtotime($this->date)) ." - ". date("d-m-Y", strtotime($this->dateTo)));
		}else{
			return date("d-m-Y", strtotime($this->date));
		}
	}
	
}
