<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\tagnameSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tagname-search">

  <?php
  $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
          'data-pjax' => 1
        ],
  ]);
  ?>

  <?= $form->field($model, 'id') ?>

  <?= $form->field($model, 'areaId') ?>

  <?= $form->field($model, 'headerId') ?>

  <?= $form->field($model, 'name') ?>

  <?= $form->field($model, 'alias') ?>

  <div class="form-group">
<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
  <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
  </div>

<?php ActiveForm::end(); ?>

</div>
