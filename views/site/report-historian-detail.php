<?php
use yii\helpers\Html;
$this->title = 'Historian Report';
?>

<style>
@media (min-width: 768px) {
  .container {
    width: auto;
  }
}
@media (min-width: 992px) {
  .container {
    width: auto;
  }
}
@media (min-width: 1200px) {
  .container {
    width: auto;
  }
}
.report-msg {height:380px;text-align:center;padding-top:160px;font-size:24px;margin-top:-2px;background-color:white;border-top: 1px solid black;}
.report-msg.noHeader{height:400px}
#dialog-data table th, #dialog-data table td{font-size:65%}
.ui-dialog #form-his{position:absolute;top:9px;left:150px;font-size:12px}
.ui-dialog .ui-dialog-title{width:auto}
</style>

<div class="box-bdr clear">

<?php 
if(!$isAjax){
	$this->registerCss('form{display:flex} label{margin-right:5px} input, select, button{height:20px;margin-right:10px;font-size:12px;text-align:center}');
	echo Html::beginForm(['','unit' => 'historian'], 'post', []);
	echo Html::activeLabel($model, 'startTime');
	echo Html::activeInput('text', $model, 'startTime');
	echo Html::activeLabel($model, 'endTime');
	echo Html::activeInput('text', $model, 'endTime');
	echo Html::activeLabel($model, 'interval');
	echo Html::activeDropDownList($model, 'interval', ['1m'=>'1 min','5m'=>'5 min','10m'=>'10 min','30m'=>'30 min']);
	echo Html::activeLabel($model, 'samplingMode');
	echo Html::activeDropDownList($model, 'samplingMode', ['Calculated'=>'Calculated','trend'=>'Trend', 'currentvalue'=>'currentvalue', 'interpolated'=>'interpolated', 'lab'=>'Lab', 'trend2'=>'Trend2', 'RawByTime'=>'RawByTime', 'RawByNumber'=>'RawByNumber']);
	echo Html::submitButton('Submit', ['class' => 'submit']);

	echo Html::endForm();
} ?>

<?php
echo $model->simpleHeader;
if(empty($model->data)){
  echo "<div class='report-msg dialog'>Data Not Available</div>";
}
else {
	echo "<div id='table_div_dialog'> <table class='report'><tbody>";
  foreach( $model->data as $k=>$rows ) {
    $time = strtotime($rows['time']);
    $time_title = date('Y-m-d H:i', $time);
    $class_time_mark = ($time_title==$model->time_marker) ? 'time_marker' : '';
    echo "<tr class='$class_time_mark'>";
    $c=0;
    foreach($rows as $i=>$v){
      if($i=='time'){
        echo "<td class='time' title='$time_title'>".date('H:i', $time)."</td>";
      }else{
        $tag = $model->tagnames[$c];
        echo $model->formatValue($v, $tag['spec'], $tag['spec2']);
        $c++;
      }
    }
    echo "</tr>";
  }
  echo "</tbody></table></div>";
}
?>
</div>


<?php
if(!$isAjax){
	$this->registerJs("
		bms.synTable($('#table_div_dialog'));
	"
	,$this::POS_READY
	);
}
?>
