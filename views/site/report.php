<?php
use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\jui\Dialog;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TagsDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Report');
$this->registerJsFile("@web/leader-line.min.js");
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
.report-msg.dialog{height:auto; padding:5px 0; font-size:15px}
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
				$dateClass = $model->dateTo && $model->dateTo != $model->date ? '' : 'date-hide';
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
						'minDate' => $model::minDataTime,
						'maxDate' => date('d-m-Y'),
						'onSelect'=>new yii\web\JsExpression("bms.selectDate")
					],
					'options'=>['class'=>'dateFrom', 'placeholder'=>'Date start']
				]);
				echo $form->field($model, 'dateTo',['options'=>['class'=>"dateR $dateClass", 'style'=>'margin-right:5px;float:left;margin-left:5px']])
					->widget(DatePicker::classname(), [
					'value'  => $model->dateTo,
					'dateFormat' => 'dd-MM-yyyy', 
					'clientOptions'=>[
						'showAnim'=>'show',
						'changeMonth'=>true,
						'changeYear'=>true,
						'minDate' => $model::minDataTime,
						'maxDate' => date('d-m-Y'),
						'onSelect'=>new yii\web\JsExpression("bms.selectDate")
					],
					'options'=>['class'=>'dateFrom', 'placeholder'=>'Date end']
				]);
			?>
			<?= Html::activeHiddenInput($model, 'isPrint') ?>
			<?= Html::activeHiddenInput($model, 'isMultidate') ?>
			<div class="right dateR <?=$dateClass?>">
				<?= Html::button(Yii::t('app', 'Search'), ['onclick'=>'js:bms.getReport();', 'class'=>'btn btn-outline-primary', 'style'=>'padding:2px 12px']) ?>
			</div>
			
			<?php ActiveForm::end();?>
			
			<div class="clear right custom-search" style="width:100%">
				<span class='pick right'><?= $model->dateTo ? "Single Date" : "Range Date"?></span>
				<span class="loader right" style="margin-top:0px;margin-right:5px"></span>
			</div>
		</div>
	</div>
	
	<!-------------------------------------------------->
	<div class="box-bdr clear">
		<?= $this->render($tpl, ['model'=>$model]) ?>
	</div>
	<!-------------------------------------------------->
	
	<div class="right" style="margin-top:10px">
	<?php
		if(!Yii::$app->session->hasFlash('msg')){
			$url = 'report/'.$model->areaName.'/'.$model->date;
			echo Html::a('PDF', [$url.'/pdf'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary','onclick'=>'js:bms.getReport("pdf");']);
			echo Html::a('Excel', [$url.'/xls'], ['data-pjax'=>"0", 'class' => 'btn btn-outline-primary','style'=>'margin-left:7px', 'onclick'=>'js:bms.getReport("xls");']);
		}
	?>
	</div>

	<?php
	$this->registerJs("
	$('form').on('submit', function(el){ console.log('form submit'); 
		bms.dismisMsg();
		if($('#report-isprint').val()){ console.log('remove data-pjax');
			$('#date-form').removeAttr('data-pjax');
		}else{ 	console.log('add data-pjax');
			$('#date-form').attr('data-pjax', 'true');
		}
		if($('#report-ismultidate').val()){ 
			if(!validateDate()) return false;
		}
	});
	
	$('.custom-search span.pick').click(function(e){
		var el = $(this); console.log(el, e, e.target, e.target.innerText);
		$('#date-form :input.hasDatepicker').val('');
		$('#report-isprint').val('');
		$('#report-ismultidate').val('');
		if(e.target.innerText==='Single Date'){
			$('#msg-con').fadeOut('slow').find('.msg').html('');
			$('#date-form .dateR').fadeOut();
			//$('#report-ismultidate').val('');
			e.target.innerText='Range Date';
		}else{
			$('#date-form .dateR').fadeIn();
			e.target.innerText='Single Date';
			$('#report-ismultidate').val(1);
		}
	});
	
	if($('#table_div table tr:first td').length > 1){
    bms.synTable();
    registerRowClick();
		if(bms.MENU_AREA=='Test'){
			getTrendData();
			getTrendDataIntv();
		}
  }
	
	bms.REPORT.AREA = '".$model->areaName."'
	bms.REPORT.DATE = '".$model->date."'
");
	?>
  <?php Pjax::end(); ?>
</div>

<?php
Dialog::begin([
  'options'=>['id'=>'dialog-data', ],
  'clientOptions' => [
    // 'title'=>'Data Expand',
    'autoOpen'=> false,
    'height'=> 'auto',
    'minHeight'=>'fit-content',
		'maxHeight'=>'600px',
		'draggable'=>true,
    'width'=> '1000px',
		// 'minWidth'=>'max-content',
    // 'maxWidth'=>'max-content',
    'modal'=> true,  // true to enable overlay backdrop
    'opacity'=>1,
    'position'=> new JsExpression('{ my: "center", at: "center", of: window }'),
    'open'=> new JsExpression("function() {  console.log('>dialog:open',  bms.connector);
      bms.disableTrend = true;
			// $('.ui-dialog-titlebar')
			// .after('<input type=\"text\" id=\"input_search\" maxlength=\"255\" style=\"position:absolute;top:13px;left:135px;\">');
    }"),
    'close'=> new JsExpression("function() {  console.log('>dialog:close', bms.connector);
			bms.connector.start.parentElement.className='';
      bms.connector = bms.connector.remove();
      $('#dialog-data').html('');
      bms.disableTrend = false;
    }"),
    "drag"=> new JsExpression("function(ev, ui){
      bms.connector.position();
    }"),
    "resize"=> new JsExpression("function(ev, ui){
      bms.connector.position();
			bms.synTable($('#table_div_dialog'));
    }"),
		"focus"=> new JsExpression("function(ev, ui){
      console.log('>dialog:focus', ev, ui);
    }"),
		"create"=> new JsExpression("function(ev, ui){
      console.log('>dialog:create', ev, ui);
    }"),
		"_allowInteraction"=> new JsExpression("function(ev){
      console.log('>dialog:_allowInteraction', ev);
			// return !!$( event.target ).is('.select2-input') || this._super( event );
			return true;
    }"),
  ],
]);
echo 'Loading..';
Dialog::end();
?>
<!-- END -->

<?php
$this->registerJs("
	if(!$('table.report').length){
		$('#report-date').attr('disabled', true);
		$('#report-dateto').attr('disabled', true);
		$('#submit').attr('disabled', true)
	}
	//$('div.container').width(document.body.scrollWidth-33);
"
,$this::POS_READY
);

$this->registerJs("
$(document).on('pjax:complete', function (e, res, status, req) {
  console.log('PJAX COMPLETE:FORM:', $.pjax.state.url.match(/\/create|\/update|\/view(|\/\d+)$/), $.pjax.state.url);
});", $this::POS_END);
?>
