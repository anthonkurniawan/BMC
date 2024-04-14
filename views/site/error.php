<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name; 
?>
<div class="site-error">

  <h1><?= Html::encode($this->title) ?></h1>

  <div class="alert alert-danger">
    <h4><?= nl2br(Html::encode($message)) ?></h4>
  </div>
  <p>
    <b>Please contact the Administrator for further information.<br>Thank you</b>
  </p>

</div>


<?php 
if(isset($redirect)){
	$this->registerJs("window.setTimeout('window.location=\"$redirect\"', 5000);");
}
?>
