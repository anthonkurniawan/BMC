<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TagsDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tags-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'N110_TEMP') ?>

    <?= $form->field($model, 'N110_RH') ?>

    <?= $form->field($model, 'N110___DP_CE_RE') ?>

    <?= $form->field($model, 'N110_DP2') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
