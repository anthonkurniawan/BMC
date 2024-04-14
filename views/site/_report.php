<?php 
			$hasHeader = !empty($model->tagsdata['header']);
			$class = $hasHeader ? "" : "noHeader";
			if($hasHeader){ 
		?>
		<table class="report">
			<?=$model->tagsdata['header']?>
		</table>
		<?php
			}
			if(Yii::$app->session->hasFlash('msg')){
				echo "<div class='report-msg $class'>".Yii::$app->session->getFlash('msg')."</div>";
			}else{
		?>
		<div id="table_div">
			<table class="report">
				<tbody>
					<?php
					$s = "";
					if($model->error){
						$s .="<tr><td style='height:355px;font-weight:bold;text-align:center;font-size:14px'>".Yii::$app->session->getFlash('msg')."</td></tr>";
					}else{
						foreach($model->tagsdata['data'] as $t=>$i){
							$s .="<tr data-time='$model->date $i[tgl]'> <td align='center' style='width:70px'>$i[tgl]</td>";
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
		</div>
		<?php } ?>
