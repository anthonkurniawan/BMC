<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Userdir */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userdir-form">

  <?php $form = ActiveForm::begin([
		'id'=>'user-form',
		'enableAjaxValidation' => true,
    'enableClientValidation' => false]); ?>

  <?= $form->field($model, 'username')->textInput(['style'=>'width:400px']) ?>
	<?= $form->field($model, 'email')->textInput(['style'=>'width:400px']) ?>
	<?= $form->field($model, 'dept')->dropDownList($model->listDept, ['prompt'=>'Select Dept','style'=>'width:200px']); ?>
	<?= $form->field($model, 'role')->dropDownList(
		['opr'=>'Operator', 'spv'=>'Supervisor', 'adm'=>'Administrator'], ['style'=>'width:200px']) ?>

  <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
		<?= Html::a(Yii::t('app', 'Cancel'), '../userdir', ['class' => 'btn btn-warning']) ?>
  </div>

  <?php ActiveForm::end(); ?>
</div>