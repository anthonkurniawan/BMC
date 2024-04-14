<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tagname */

$this->title = Yii::t('app', 'Create Tagname');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tagnames'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tagname-create">
  <b class="left clear" style="font-size:20px"><?= Html::encode($this->title) ?></b>
  <?=
  $this->render('_form', [
    'model' => $model,
  ])
  ?>
</div>
