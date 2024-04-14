<?php
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TagsDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Report');
//$this->params['breadcrumbs'][] = $this->title;
//$this->registerCss(".wrap > .container{ padding-top:30px}");
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
.ui-datepicker-calendar {
  display: none;
}
</style>

<div class="report-index">
<?php Pjax::begin(); ?>
	
<?php 
$this->registerJs("
getReport = function(print){	console.log('GET REPORT..', print);
	$('#msg-con').html('');
	event.preventDefault();
	print = (print=='pdf' || print=='xls') ? print : '';
	$('#report-isprint').val(print);
	$('form').submit();
}
"
//,$this::POS_READY
);
?>

<div class="left" style="width:60%">
		<table class="summary">
			<tr>
				<td>BMC Report</td><td>:</td>
				<td>
					<?php 
						$date = $model->dateTo ? "$model->date - $model->dateTo" : $model->date;
						echo $date;
					?>
				</td>
			</tr>
			<tr>
				<td>Departement</td><td>:</td><td><?=$model->dept?></td>
			</tr>
			<tr>
				<td>Area</td><td>:</td><td><?=$model->area->name?></td>
			</tr>
		</table>
	</div>
	
	<div class="right">
		<div>
		<label class="control-label" for="report-date">Month</label>
		<?php
			echo DatePicker::widget([
				'model' => $model,
				'attribute' => 'date',
				'dateFormat' => 'MM-yyyy',
				'options' =>['style'=>'text-align:center'],
				'clientOptions'=>[
					'showAnim'=>'show',
					'changeMonth'=>true,
					'changeYear'=>true,
					'minDate' => new yii\web\JsExpression("new Date('01-01-2017')"),
					'maxDate' => '0y',
					'dateFormat'=> "MM/yy",
          'showButtonPanel'=>true,
					'currentText'=>'This month',
					'closeText'=>'Submit',
					//'onSelect'=>new yii\web\JsExpression("selectDate"),
					'beforeShow'=>new yii\web\JsExpression("function(input, inst) {  //console.log('beforeShow:', input, $(this).val(), inst.dpDiv);
						inst.dpDiv.addClass('month_year_datepicker')
            if ((datestr = $(this).val()).length > 0) {  console.log('datestr:'+datestr);
							year = datestr.substring(datestr.length-4, datestr.length);
							month = datestr.substring(0, 2); 
							$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
              $(this).datepicker('setDate', new Date(year, month-1, 1));
              $('.ui-datepicker-calendar').hide();  console.log('year:'+year+' month:'+month, $(this));
            }
          }"),
					'onClose'=>new yii\web\JsExpression("function(dateText, inst) {  //console.log('close datePicker', dateText, inst, inst.input.val());
						function isDonePressed(){
              return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
            }
            if (isDonePressed()){
							var month = $('#ui-datepicker-div .ui-datepicker-month :selected').val();
              var year = $('#ui-datepicker-div .ui-datepicker-year :selected').val();
							var yearMonth = year+'-'+(parseInt(month)+1);
							var lastday = new Date(year, month +1, 0).getDate() + ' 23.59';
              $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');  //console.log('year:'+year+' month:'+month, inst);
              $('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
							$('form input#report-date').val(yearMonth + '-01'); 
							$('form input#report-dateto').val(yearMonth+'-'+lastday);   //2019-09-14
							getReport();
						}
          }")
				],
			]);
		?>
		<?php
			$form = ActiveForm::begin([
				'id' => 'date-form',
				'options'=>['autocomplete'=>'off', 'data-pjax'=>true, 'class'=>'right','style'=>''], 
				'requiredCssClass'=>'',
				'enableClientScript'=>false
			]);
		?>
		<?= Html::activeHiddenInput($model, 'date') ?>
		<?= Html::activeHiddenInput($model, 'dateTo') ?>
		<?= Html::activeHiddenInput($model, 'isPrint') ?>
		<?php ActiveForm::end();?>
		</div>
		<div>
			<span class="loader right" style="margin-top:0px;margin-right:5px"></span>
		</div>
	</div>
	
<table width="100%" border="1">
	<thead>
		<tr>
			<th colspan='7'><h4>WAREHOUSE MONITORING TEMPERATURE (in °Celcius)</h4></th>
		</tr>
		<tr>
			<th>Area</th>
			<th>Ambient</th>
			<th colspan="3">Room Temperature<br>( <u>&lt;</u> 30 °C )</th>
			<th>Uncontrolled Room</th>
			<th>AC Room<br>( <u>&lt;</u> 25 °C )</th>
		</tr>
		<tr>
			<th>Sensor Location</th>
			<th>WH Outside</th>
			<th>31-35</th>
			<th>62-63</th>
			<th>64-67</th>
			<th>51-54</th>
			<th>61</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Minimum</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Maximum</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Average</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>MKT</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&sum; DATA</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>&sum; DATA LOOS</td>
			<td>xx</td>
			<td>xx</td>
			<td>xx</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>

<?php 
	if(isset($model->summary)){
		echo "<table border=1 width='100%'>";
		echo $model->summary['header'];
		echo "<tbody>";
		foreach($model->summary['keys'] as $k){
			echo "<tr><td>".$k."</td>";
			foreach($model->summary['data'] as $i=>$v){
				echo "<td>".$v[$k]."</td>";
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}else{
		echo '<div style="border:1px solid black"><div class="alert alert-info" role="alert">No summary available</div></div>';
	}
?>

<div class="box-bdr clear">
		<table class="report first" border=1 onload="js:console.log('LOADED..')">
			<?=$model->tagsdata['header']?>
		</table>
		<?php $error = Yii::$app->session->hasFlash('msg'); ?>
		<div id="table_div">
			<table class="report" border=<?=$error ? 0 : 1?>>
				<tbody>
					<?php
					$s = "";
					if($error){
						$s .="<tr><td style='height:355px;font-weight:bold;text-align:center;font-size:14px'>".Yii::$app->session->getFlash('msg')."</td></tr>";
					}else{
						foreach($model->tagsdata['data'] as $t=>$i){
							$s .="<tr><td align='center'>$i[tanggal]</td>";
							foreach($model->tagnames as $k=>$v){
								$col = $v['tagname'];
								$s .= $model->condValue($i[$col], $v['spec'], $v['spec2']);
							}
							$s .="</tr>";
						}
					}
					echo $s;
					?>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="right">
	<?php
		if(!Yii::$app->session->hasFlash('msg')){
			$url = 'report/'.$model->areaName.'/'.$model->date;
			echo Html::a(Yii::t('app', 'PDF'), [$url.'/pdf'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary', 'onclick'=>'js:getReport("pdf");']);
			echo Html::a(Yii::t('app', 'Excel'), [$url.'/xls'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary', 'onclick'=>'js:getReport("xls");']);
		}
	?>
	</div>
	
	
	<?php
	$this->registerJs("
	$('form').on('submit', function(){ console.log('ON-SUBMIT..');
		if($('#report-isprint').val()){ console.log('remove data-pjax');
			$('#date-form').removeAttr('data-pjax');
		}else{ 	console.log('add data-pjax');
			$('#date-form').attr('data-pjax', 'true');
		}
	});
");
	?>
	
	<?php Pjax::end(); ?>
</div>

<?php 
$this->registerJs("
var loader= $('#loader');
$(document).ready(function(){  console.log('Report Ready..');
	if($('#table_div table tr:first td').length > 1) bms.synTable();
	$('div.container').width($('body').width()-33);
});
"
//,$this::POS_READY
);
?>