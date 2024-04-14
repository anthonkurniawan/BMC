<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Userdir */

$this->title = Yii::t('app', 'Update User id: ' . $model->idx, [
      'nameAttribute' => '' . $model->idx,
    ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userdirs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idx, 'url' => ['view', 'idx' => $model->idx]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="userdir-update">
  <h1><?= Html::encode($this->title) ?></h1>
  <?=$this->render('_form', ['model' => $model])?>
</div>
