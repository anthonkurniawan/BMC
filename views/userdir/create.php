<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Userdir */

$this->title = Yii::t('app', 'BMC-Create new user');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userdirs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; 
?>
<div class="userdir-create">

  <h1>Create new user</h1>

  <?=
  $this->render('_form', [
    'model' => $model,
  ])
  ?>

</div>
