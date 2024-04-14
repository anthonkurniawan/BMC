<?php
use yii\helpers\Html;

$this->beginPage();
$this->title = Yii::t('app', 'Report');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
		<style type="text/css">
.left{ float:left }
.right{ float:right }
.clear{ clear:both }
.report{ border-width:1px 0px 0px 1px; }
table.print{ width:100%;border-spacing:0px;text-align:left;background-color:white;border-top:1px solid;border-left:1px solid;border-bottom:none}
table.print tr th, table.print tr td{ border:1px black;border-style:solid;border-left:white;border-top:white }
table.print tr th{font-size:12px}
table.print tr td{font-size:12px}
table.print tr th{background:#C3DEE4;text-align:center } 
table.print tr th, table.print tr td{padding:1px 1px;}
table.print tr td.txtErr{ text-align:center;color:red }
table.header{border:none;}
table.header tr td{font-weight:bold;padding:0;margin:0;font-size:14px}

table.log{font-family:Arial;border-spacing:0px;border:1px solid}
table.log th{
	font-weight:bold;font-size:11px;
	background-Color:#D4D0C8;
	padding:3px 5px 3px 5px ;	
	white-space:nowrap;
	width:24.8%;
}
table.log th:not(:first-child), table.log td:not(:first-child){ border-left:1px solid black}
table.log td{ border-top:1px solid black}
table.log td.sign{}
table.report tr td.txtOk{text-align:right}
		</style>
    <?php $this->head() ?>
  </head>
  <body>
    <?php $this->beginBody() ?>
		<?php if($model->isPrint=='xls'){ ?>
		<br>
		<br>
		<br>
		<br>
		<?php } else { ?>
		<div>
			<table class="header" width="100%" cellspacing=0 border=0>
				<tr>
					<td width="33.3%" style="vertical-align:bottom">
						<img class="left" src="http://localhost/<?php echo Yii::$app->homeUrl?>/images/logo2.png" style="width:300px;height:25px"/>
					</td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo $model->area->label?></td>
					<td width="33.3%" style="text-align:center">Building Monitoring Control</td>
					<td width="33.3%" style="text-align:right"><?=$model->getDateLabel();?></td>
				</tr>
			</table>
		</div>
		<?php } ?>
		
		<table class="report print" width="100">
			<?=$model->tagsdata['header']?>
			<tbody>
				<?php
				$s = "";
					if(Yii::$app->session->hasFlash('msg')){
						$s .="<tr><td colspan=".(count($model->tagnames)+1)." style='height:400px;font-weight:bold;text-align:center;font-size:14px'>"
							 .Yii::$app->session->getFlash('msg')."</td></tr>";
					}else{
						foreach($model->tagsdata['data'] as $t=>$i){
							$s .="<tr><td align='center'>$i[tgl]</td>";
							foreach($model->tagnames as $k=>$v){
								$col = $v['tagname'];
								$s .= $model->formatValue($i[$col], $v['spec'], $v['spec2']);
							}
							$s .="</tr>";
						}
					}
				echo $s;
				?>
			</tbody>
		</table>
		
		<?php if($model->isPrint=='pdf' && !$model->isMultidate){ ?>
		
		<b style="font-size:12px">Remark :</b>
		<div style="border:1px solid black; height:100px"></div>
		
		<b style="font-size:12px">Approval :</b>
		<table width="100%" class="log">
			<tr>
				<th>Printed By (user)</th>
				<th>Reviewed By (user)</th>
				<th>Reviewed By (ME-EHS)</th>
				<th>Approved BY (QA)</th>
			</tr>
			<tr style="height:40px">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<!--<tr style="height:85px">
				<td class="sign">&nbsp;</td>
				<td class="sign">&nbsp;</td>
				<td class="sign">&nbsp;</td>
				<td class="sign">&nbsp;</td>
			</tr>-->
		</table>
		<?php } ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>