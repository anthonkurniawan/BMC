<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserdirSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userdir-search">

  <?php
  $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
          'data-pjax' => 1
        ],
  ]);
  ?>

  <?= $form->field($model, 'idx') ?>

  <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'role') ?>

  <div class="form-group">
<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
  <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
  </div>

<?php ActiveForm::end(); ?>

</div>
