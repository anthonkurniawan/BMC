<?php
namespace app\models;

use Yii;

class Table extends yii\base\Model
{
	public $dept;
	public $areaName;
	public $date;
	public $dateTo;
  public $tagnames;
	public $isMultidate=false;
	public $isPrint = false;
	const minDataTime = '01-01-2017';
	private $_tagHeader;
	
	public function getTagnames() {
    $cond = $this->areaName ? "WHERE t2.name=:areaName" : "";
    return \Yii::$app->db->createCommand(
			"SELECT t1.name as tag, t1.alias as tagname, t1.label as tag_label, t1.[desc], t1.spec, t1.spec2, t1.specLabel,
				t2.id as areaId, t2.name as area, t2.label as area_label, t2.headerCount,
				t3.id as deptId, t3.label as dept, t4.id as headerId,
				t4.name as header,t4.specLabelGroup
				FROM tagname as t1
				LEFT JOIN area as t2 ON t1.areaId=t2.id
				LEFT JOIN dept as t3 ON t2.deptId=t3.id
				LEFT JOIN tagheader as t4 ON t1.headerId=t4.id
				$cond order by areaId asc",
			[':areaName' => $this->areaName]
		)->queryAll();
  }
	
	public function getSimpleheader() {
    if(empty($this->tagnames)) return;

    $content = '<table class="report his"><thead> <tr> <th class="time">Time</th>';
    $sizer = "<th></th>";
    foreach($this->tagnames as $k=>$v){
      $content .= isset($v['tag_label']) ? "<th>$v[tag_label]</th>" : "<th>$v[tagname]</th>";
      $sizer .= "<th></th>";
    }
    return $content .="</tr> <tr class='sizer'>$sizer</tr> </thead> </table>";
  }
	
	protected function getHeader($summary=false){
		if(empty($this->tagnames)) return "";
		
		$anyHead = $this->tagnames[0]['headerCount']; 
		self::getArrayColumn($anyHead);
		
		// PRINT HTML TABLE
		if($summary){
			$tr="<thead><tr><th rowspan='".($anyHead ? 2 : 1)."' class='head'>Summary</th>";
		}else{
			$tr="<thead><tr><th rowspan='".($anyHead ? 2 : 1)."' class='head'"
				.($this->isMultidate ? " style='width:100px'>Date/Time</th>":">Time</th>");
		}
			
		$tr2="<tr>";
		$tr3="";
		foreach($this->_tagHeader as $k=>$v){
			if(isset($v['member'])){
				foreach($v['member'] as $i=>$v2){
					$label = $v2['label'];
					if(!$v['specLabelGroup']) $label.=  self::getSpecLabel($v2);
          $tr2.="<th title='$v2[tag]'>$label</th>";
				}
			}
			$title = isset($v['tag']) ? $v['tag'] : '';
			$tr.= "<th colspan='$v[colspan]' rowspan='$v[rowspan]' title='$title'>"
				."$v[label]"
				.self::getSpecLabel($v)
				."</th>";
		}
		$tr.="</tr>";
		$tr2.="</tr>";
		if(!$this->isPrint && !$summary){
      # ADD NEW TREND LIVE FOR AREA "TEST"
      $trend = $this->areaName=='Test' ? "" : "";
			$tr3.="<tr class='sizer'><th style='width:70px'>$trend</th>"; 
			foreach($this->tagnames as $k){
				$tr3.="<th>$trend</th>";
			}
			$tr3.="</tr>";
		}
		return $source = $tr. ($anyHead ? $tr2 : "") . $tr3 . "</thead>"; 
	}
	
	protected function getArrayColumn($anyHead){ 
		$arrHead =[];
		$i=0;
		foreach($this->tagnames as $k=>$v){
			// avoid char "  " at input header
			$head = $v['header'] ? $v['header'] : $v['tag_label'];
			if($v['header']){
				if(!array_key_exists($head, $arrHead)){ 
					//$arrHead[$head] = ['key'=>'H-'.$i, 'count'=>1]; 
					$arrHead[$head] = ['key'=>$i, 'count'=>1]; 
					$this->_tagHeader[$arrHead[$head]['key']]= ['label'=>$head, 'colspan'=>1, 'rowspan'=>1, 'specLabel'=>$v['specLabel'], 'specLabelGroup'=>$v['specLabelGroup']];  
					$this->_tagHeader[$arrHead[$head]['key']]['member'][] = self::getHeaderArrayMember($v);
				}else{ 
					$arrHead[$head]['count']++;  
					$this->_tagHeader[$arrHead[$head]['key']]['colspan'] = $arrHead[$head]['count'];  
					$this->_tagHeader[$arrHead[$head]['key']]['member'][] = self::getHeaderArrayMember($v); 
				}
			}
			else{ 
				$this->_tagHeader[$i]= [
					'tag'=>$v['tag'], 
					'tagname'=>$v['tagname'], 
					'label'=>$head, 
					'spec'=>$v['spec'],
					'spec2'=> isset($v['spec2']) ? $v['spec2'] : null,
					'specLabel'=> isset($v['specLabel']) ? $v['specLabel'] : null,
					'colspan'=>1, 
					'rowspan'=>$anyHead ? 2 : 1,
					//'class'=>'head',
				];  
			}
			$i++;
		}
	}
	
	protected function getHeaderArrayMember($v){
		return [
			'head'=>$v['header'],
			'tag'=>$v['tag'], 
			'tagname'=>$v['tagname'],
			'label'=>$v['tag_label'] ? $v['tag_label'] : $v['tagname'], 
			'spec'=>$v['spec'],
			'spec2'=> isset($v['spec2']) ? $v['spec2'] : null,
			'specLabel'=> isset($v['specLabel']) ? $v['specLabel'] : null,
			//'class'=>'head'
		];
	}
	
	protected function getSpecLabel($tag){
		if(isset($tag['specLabelGroup'])) return "<br><small>".$tag['specLabelGroup']."</small>";
		if($tag['specLabel']) return "<br><small>".$tag['specLabel']."</small>";
		return (isset($tag['spec']) ? "<br><small>$tag[spec]".(isset($tag['spec2']) ? " &amp; $tag[spec2]" : "")."</small>" : '');  // add specLabelGroup
	}
	
	// MAKE SHARED FN LATER WITH Report.php Model
	public function formatValue($v, $c, $c2, $tagname=null){
		$dec= 2;
		if($v===null) return "<td class='txtErr' style='color:#d90000' title='$tagname'>n/a</td>";
		$val = number_format($v, $dec, '.', '');
		$cond = isset($c) ? $this->valueCheck($val, [$c, $c2]) : true;  // htmlentities
		if($cond){
			return "<td class='txtOk' title='$tagname'>$val</td>";
		}else{
			return "<td class='txtErr' style='color:#d90000' title='$tagname'>$val</td>";
		}
	}

	static function valueCheck($v, $spec){ 
		$spec1 = html_entity_decode($spec[0]); // html_entity_decode
		if($spec[1]){
			$spec2 = html_entity_decode($spec[1]);
			$str = "$v $spec1 && $v $spec2"; 
		}
		else{
			$str = "$v $spec1";	
		}
		return eval("return ($str);");
	}
	
	public function validateDate($date) {
		$date = date('Y-m-d', strtotime($date)); 	
		if ($date == '1970-01-01') throw new NotFoundHttpException("Invalid Date");
		if ($date > date('Y-m-d')) throw new NotFoundHttpException("Date is in the future");
		//if ($date < $minDate) throw new NotFoundHttpException("Data not available. (only available on BMS2)");
		return $date;
	}
}
