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
table.summary tr td:first-child {
    width: 155px;
}
table.print{ width:100%;border-spacing:0px;text-align:left;background-color:white;border-top:1px solid;border-left:1px solid;border-bottom:none}
table.print.summary{border:none}
table.print tr th, table.print tr td{ border:1px black;border-style:solid;border-left:white;border-top:white }
table.print tr th, table.print tr td{font-size:12px}
table.print tr th{background:#C3DEE4;text-align:center } 
table.print tr th, table.print tr td{padding:1px 1px;}
table.print tr th, table.print.summary tr td{padding:3px 5px;}
table.print tr td.txtErr{ text-align:center;color:red }
</style>

<div class="report-index">
<?php Pjax::begin(['timeout'=>500, 'enablePushState'=>true,'enableReplaceState'=>true]); ?>
	
<?php 
$this->registerJs("
getReport = function(print){	console.log('GET REPORT..', print);
	//$('#msg-con').html('');
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
				<td>BMC Summary Report</td><td>:</td>
				<td>
					<?php echo date("F Y", strtotime($model->date)); ?>
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
					'maxDate' => '0y', //date('Y-m-d',strtotime("+1 day")),
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
							//inst.input.val(year+'-'+month+'-01');
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
	
<div class="clear" style="text-align:center;background-color:#f5f5f5;border:1px solid black;margin-top:5px">
	<h4>WAREHOUSE MONITORING TEMPERATURE (in °Celcius)</h4>
</div>

<?php if(isset($model->summary)){ ?>
	<table border=1 width='100%' class="print summary" style="border-left:1px solid;">
		<?=$model->summary['header']; ?>
		<tbody>
		<?php
		foreach($model->summary['keys'] as $k){
			echo "<tr><td>".$k."</td>";
			foreach($model->summary['data'] as $i=>$v){
				echo "<td>".$v[$k]."</td>";
			}
			echo "</tr>";
		}
		?>
		</tbody>
	</table>
	
	<div class="right" style="margin-top:10px;margin-bottom:10px">
	<?php
		if(!Yii::$app->session->hasFlash('msg')){
			$url = 'report/'.$model->areaName.'/'.$model->date;
			echo Html::a('PDF', [$url.'/pdf'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary','onclick'=>'js:getReport("pdf");']);
			echo Html::a('Excel', [$url.'/xls'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary','style'=>'margin-left:7px', 'onclick'=>'js:getReport("xls");']);
		}
	?>
	</div>
	
	<div class="box-bdr clear">
		<table class="report first" border=1 onload="js:console.log('LOADED..')">
			<?=$model->tagsdata['header']?>
		</table>
		<?php $error = Yii::$app->session->hasFlash('msg'); ?>
		<div id="table_div">
			<table class="report">
				<tbody>
					<?php
					$s = "";
					if($error){
						$s .="<tr><td style='height:355px;font-weight:bold;text-align:center;font-size:14px'>".Yii::$app->session->getFlash('msg')."</td></tr>";
					}else{
						foreach($model->tagsdata['data'] as $t=>$i){
							$s .="<tr><td align='center'>$i[tgl]</td>";
							foreach($model->tagnames as $k=>$v){
								$col = $v['tagname'];
								$s .= $model->formatValue($i[$col], $v['spec'], $v['spec2']);
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
<?php }else{ 
		echo '<div class="jumbotron" style="margin-top:100px"><h3>No summary available</h3></div>';
	}
?>

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
	//$('div.container').width($('body').width()-33);
});
"
//,$this::POS_READY
);
?>