<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tagname */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.report-msg {height:350px;text-align:center;padding-top:150px;font-size:24px;margin-top:-11px;background-color:white}
.report-msg.noHeader{height:400px}
</style>
		
<div class="tagname-form clear">

  <?php $form = ActiveForm::begin(['options' => ['class' => '']]); ?>
	
	<div class="left" style="width:100%;border-top:1px solid #9b9a9a;padding-top:8px;">
	<?php echo $form->field($model, 'areaId')->dropDownList($model->listDept,[]); ?>
	<?php echo $form->field($model, 'areaId')->dropDownList($model->listArea, ['prompt'=>'Select Area','style'=>'width:200px']); ?>
	<?php echo $form->field($model, 'headerId')->dropDownList($model->listGroupHeader, ['style'=>'width:300px']); ?>
	<?php if($model->id) echo $form->field($model, 'order_col')->textInput(['style'=>'width:90px']); ?>
	</div>

	<div class="left" style="width:50%">
  <?= $form->field($model, 'name')->textInput(['style'=>'width:400px']) ?>
	<?= Html::hiddenInput('name', $model->name, ['id'=>'_tagname', 'style'=>'width:400px']) ?>
  <?= $form->field($model, 'label')->textInput(['style'=>'width:400px']) ?>
  <?= $form->field($model, 'desc')->textInput(['style'=>'width:400px']) ?>
  </div>
	<div class="left" style="width:50%">
	<?= $form->field($model, 'spec')->textInput(['style'=>'width:400px']) ?>
  <?= $form->field($model, 'spec2')->textInput(['style'=>'width:400px']) ?>
	<?= $form->field($model, 'specLabel')->textInput(['style'=>'width:400px']) ?>
	</div>
  <div class="form-group clear">
    <?= Html::button(Yii::t('app', 'Save'), ['id'=>'save', 'class' => 'btn btn-success']);?>
		<?= Html::a(Yii::t('app', 'Cancel'), '../tagname', ['class' => 'btn btn-warning']) ?>
	</div>
  <?php ActiveForm::end(); ?>
</div>


<?php 
$msg = $model->id ? "rename the tag-name!!" : "create a new tag";
$msg = 'You will '.$msg.'.\nPlease Make sure the tagname is already exist on Historian\n\nClick Ok to confirm';
$this->registerJs('
function konfirm(){
	var r = confirm("'.$msg.'");
	if (r == true) {
		$("form").submit();
	} else { console.log(0);
		return;
	}
}

$(document).ready(function(){  console.log("Report Ready..");
	$("#save").on("click", function(){  console.log("Click save..");
		if( $("#tagname-name").val() != $("#_tagname").val() ){
			konfirm();
		}else if($("#tagname-name").val() && !$("#_tagname").val()) konfirm();
		else $("form").submit();
	});
});
'
//,$this::POS_READY
);
?>
