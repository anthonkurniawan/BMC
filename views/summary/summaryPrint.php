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
table.print.summary{border:none}
table.print tr th, table.print tr td{ border:1px black;border-style:solid;border-left:white;border-top:white }
table.print tr th, table.print tr td{font-size:12px}
table.print tr th{background:#C3DEE4;text-align:center } 
table.print tr th, table.print tr td{padding:1px 5px 1px 5px}
table.print tr th, table.print.summary tr td{padding:3px 5px;}
table.print tr td.txtErr{ text-align:center;color:red }
table.print.summary tr th{background:none}
table.print.summary tr th, table.print.summary tr td{font-size:16px}
input[type='checkbox']{ margin: 0.2ex }
.report_msg{ margin-left:auto;margin-right:auto;text-align:center;width:500px;color:red;font-size:20px}
.approval{float:left;width:100%;}
.approval .col{float:left;width:100%;height:250px;border:1px solid black}
.approval .col:not(:first-child){margin-top:10px;}
.approval .col div:first-child{padding:2px;font-weight:bold;border-bottom:1px solid black;background-Color:#f5f5f5;}

table.log{font-family:Arial;border-spacing:0px;border:1px solid}
table.log th{
	font-weight:bold;font-size:14px;
	background-Color:#f5f5f5;
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
	<div>
		<img src="http://localhost/<?php echo Yii::$app->homeUrl?>/images/logo2.png" style="width:400px;height:35px"/>
	</div>
	<div style="text-align:center;margin-bottom:20px">
		<span style="font-weight:bold;font-size:20px">WAREHOUSE MONITORING TEMPERATURE REPORT MONTHLY</span>
	</div>
	<b>Bulan & Tahun :&nbsp;<?php echo date("F Y", strtotime($model->date)); ?></b>
	<div class="clear" style="margin-top:5px;text-align:center;background-color:#f5f5f5;border:1px solid black">
		<h4>WAREHOUSE MONITORING TEMPERATURE (in °Celcius)</h4>
	</div>
		
	<?php if(isset($model->summary)){ ?>
	<table border=1 width='100%' class="print summary" style="border-left:1px solid;">
		<?=$model->summary['header']; ?>
		<tbody>
		<?php
		foreach($model->summary['keys'] as $k){
			echo "<tr><td><b>".$k."</b></td>";
			foreach($model->summary['data'] as $i=>$v){
				echo "<td>".$v[$k]."</td>";
			}
			echo "</tr>";
		}
		?>
		</tbody>
	</table>
	
	<?php if($model->isPrint=='pdf'){ ?>
		<div style="margin-top:10px;padding:2px;height:600px;border:1px solid black;border-bottom:none">
			<b>Comments :</b>
		</div>
		<table width="100%" class="log">
			<tr style="height:30px">
				<th>Prepared By</th>
				<th>Checked By</th>
				<th>Acknowledge By</th>
			</tr>
			<tr style="height:150px">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php } ?>
	
	<p style="page-break-after: always;">&nbsp;</p>
	<p style="page-break-before: always;">&nbsp;</p>
	
	<div>
		<img src="http://localhost/<?php echo Yii::$app->homeUrl?>/images/logo2.png" style="width:400px;height:35px"/>
	</div>
	
	<p>Laporan ini diedarkan untuk dikaji dan disetujui serta diberikan komentar.</p>
	<div class="approval">
		<div class="col">
			<div>1.Head of Supply Chain</div>
			<div></div>
		</div>
		<div class="col">
			<div>2.Quality Assurance Section Head</div>
			<div></div>
		</div>
		<div class="col">
			<div>3.Head of ME-EHS</div>
			<div></div>
		</div>
		<div class="col">
			<div>4.Head of QO</div>
			<div></div>
		</div>
		<div class="left" style="margin-top:10px">
		<b>Catatan:</b>
		<br>Laporan ini kembali ke Warehouse Supervisor untuk ditindak lanjuti/disiapkan laporan penyimpangan/LPUP/CAPA sesuai kajian diatas.
		</div>
	</div>
	
	<p style="page-break-after: always;">&nbsp;</p>
	<p style="page-break-before: always;">&nbsp;</p>
	<img src="http://localhost/<?php echo Yii::$app->homeUrl?>/images/logo2.png" style="width:400px;height:35px"/>
	
	<table class="report print" width="100%" style="margin-top:10px">
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
<?php }else{ 
		echo '<div class="jumbotron"><h3>No summary available</h3></div>';
	}
?>
	
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>