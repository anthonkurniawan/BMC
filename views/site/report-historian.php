<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->registerJsFile("@web/leader-line.min.js");
$this->title = 'Historian Report';
$data = $model->getData();
$dateClass = $model->dateTo && $model->dateTo != $model->date ? '' : 'date-hide';
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
</style>

<div class="report-index">
<?php Pjax::begin(); ?>

<div class="left" style="width:100%;margin-bottom:5px">
		<div class="left" style="width:40%">
			<table class="summary">
				<tr>
					<td>BMC Report</td><td>:</td>
					<td><?=$model->getDateLabel();?></td>
				</tr>
				<tr>
					<td>Departement</td><td>:</td><td><?=$model->dept?></td>
				</tr>
				<tr>
					<td>Area</td><td>:</td><td><?=$model->areaName?></td>
				</tr>
			</table>
		</div>
		
		<div class="right cari-tanggal">
			<?php
				$form = ActiveForm::begin([
					'id' => 'date-form',
					'options'=>['autocomplete'=>'off', 'data-pjax'=>true, 'class'=>'right','style'=>''], 
					'requiredCssClass'=>'',
					'enableClientScript'=>false
				]);
				echo $form->field($model, 'date', ['options'=>['style'=>'margin:0;float:left']])
					->widget(DatePicker::classname(), [
					'value'  => $model->date,
					'dateFormat' => 'dd-MM-yyyy', //'yyyy-MM-dd',
					'clientOptions'=>[
						'showAnim'=>'show',
						'changeMonth'=>true,
						'changeYear'=>true,
						'maxDate' => date('d-m-Y'),
						'onSelect'=>new yii\web\JsExpression("bms.selectDate")
					],
					'options'=>['class'=>'dateFrom', 'placeholder'=>'Date start']
				]);
				echo $form->field($model, 'dateTo',['options'=>['class'=>"dateR $dateClass", 'style'=>'margin-right:5px;float:left;margin-left:5px']])
					->widget(DatePicker::classname(), [
					'value'  => $model->dateTo,
					'dateFormat' => 'dd-MM-yyyy', //'yyyy-MM-dd',
					'clientOptions'=>[
						'showAnim'=>'show',
						'changeMonth'=>true,
						'changeYear'=>true,
						'maxDate' => date('d-m-Y'),
						'onSelect'=>new yii\web\JsExpression("bms.selectDate")
					],
					'options'=>['class'=>'dateFrom', 'placeholder'=>'Date end']
				]);
			?>
			<?= Html::activeHiddenInput($model, 'isPrint') ?>
			<div class="right dateR <?=$dateClass?>">
				<?= Html::button(Yii::t('app', 'Search'), ['onclick'=>'js:bms.getReport();', 'class'=>'btn btn-outline-primary', 'style'=>'padding:2px 12px']) ?>
			</div>
			
			<?php ActiveForm::end();?>
			
			<div class="clear right custom-search" style="width:100%">
				<span class='pick right'><?= !$dateClass ? "Single Date" : "Range Date"?></span>
				<span class="loader right" style="margin-top:0px;margin-right:5px"></span>
			</div>
		</div>
</div>

<div class="box-bdr clear">
<table class="report">
<?=$model->header?>
</table>

<?php
if(empty($data)){
  echo "<div class='report-msg dialog'>Data Not Available</div>";
}
else {
	echo "<div id='table_div_dialog'> <table class='report'><tbody>";
  foreach( $data as $k=>$rows ) {
    $time = strtotime($rows['time']);
    $time_title = date('Y-m-d H:i', $time);
    echo "<tr>";
    $c=0;
    foreach($rows as $i=>$v){
      if($i=='time'){
        echo "<td class='time' title='$time_title' style='width:70px'>".date('H:i', $time)."</td>";
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

<?php Pjax::end(); ?>
</div>

<?php
$this->registerJs("
  bms.synTable($('#table_div_dialog'));
"
,$this::POS_READY
);
?>
