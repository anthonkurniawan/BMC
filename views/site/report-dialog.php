<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
?>
<style>
@media (min-width: 768px) {
  .container {
    width: auto;
  }
}
@media (min-width: 992px) {
  .container {
    width: auto;
  }
}
@media (min-width: 1200px) {
  .container {
    width: auto;
  }
}
.report-msg {height:380px;text-align:center;padding-top:160px;font-size:24px;margin-top:-11px;background-color:white;border-top: 1px solid black;}
.report-msg.noHeader{height:400px}
</style>

	<div class="box-bdr clear">
		<?php
			$hasHeader = !empty($model->tagsdata['header']);
			$class = $hasHeader ? "" : "noHeader";
			if($hasHeader){
		?>
		<table class="report dialog">
			<?php echo $model->tagsdata['header']?>
			<tbody>
					<?php
					$s = "";
					if($model->error){
						$s .="<tr><td style='height:355px;font-weight:bold;text-align:center;font-size:14px'>".Yii::$app->session->getFlash('msg')."</td></tr>";
					}else{
						foreach($model->tagsdata['data'] as $t=>$i){
							$s .="<tr><td align='center'>$i[tgl]</td>";
							foreach($model->tagnames as $k=>$v){
								$col = $v['tagname'];
								$s .= $model->formatValue($i[$col], $v['spec'], $v['spec2'], $v['tag']);
							}
							$s .="</tr>";
						}
					}
					echo $s;
					?>
				</tbody>
		</table>
		<?php
			}

			if(Yii::$app->session->hasFlash('msg')){
				echo "<div class='report-msg $class'>".Yii::$app->session->getFlash('msg')."</div>";
			}
		?>
	</div>

