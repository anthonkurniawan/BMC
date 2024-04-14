<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TagsData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tags-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal')->textInput() ?>

    <?= $form->field($model, 'N110_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N110_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N110___DP_CE_RE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N110_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N111_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N111_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N111___DP_CE_RE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N111_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N122_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N122_CE_RE_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N120_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N120_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N120___DP_RE_RE')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N178_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N178_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N179_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N180_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N180_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N180_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N180_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N181_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N181_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N181_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N181_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N182_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N182_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N182_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N182_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N183_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N183_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N183_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N183_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N185_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N185_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLASS_F_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CLASS_F_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'W113_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'W171A_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N176A_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N176A_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N176A_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N176B_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140A_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140A_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140A_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140B_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140B_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N140B_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N142_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N166_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N166_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N166_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N166_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N167_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N167_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N167_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N167_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N168_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N168_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N168_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N168_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N169_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N169_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N169_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N169_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N170_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N170_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N170_DP1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N170_DP2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N171_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N171_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N171_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N145_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N145_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N145_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N147_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N147_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N147_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N148_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N148_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N148_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N153_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N153_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N153_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N154_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N154_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N154_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N155_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N155_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N155_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156A_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156A_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156A_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156B_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156B_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N156B_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N157_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N157_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N157_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N158A_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N158A_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N158A_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N161_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N161_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N161_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N162_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N162_RH')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N162_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'N163_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CORRIDOR_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CORRIDOR_CE_RE_DP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CORRIDOR_C_E_TEMP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CORRIDOR_C_E_DP')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
