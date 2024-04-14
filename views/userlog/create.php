<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Userlog */

$this->title = Yii::t('app', 'Create Userlog');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Userlogs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userlog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
