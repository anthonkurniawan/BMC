<style>
table.report > tbody > tr {cursor:pointer}
#table_div_dialog table.report > tbody > tr {cursor:auto}
table.report > tbody > tr:hover{background-color: #ffa5009e;}
</style>

<table class="report">
<?=$model->header?>
</table>

<?php
if(empty($model->data)){
  echo "<div class='report-msg'>Data Not Available</div>";
}
else {
	echo "<div id='table_div'> <table class='report'> <tbody>";
  foreach( $model->data as $k=>$rows ) {
    $time = strtotime($rows['time']);
    $time_title = date('Y-m-d H:i', $time);
		echo "<tr data-time='$time_title'>";
    $c=0;
    foreach($rows as $i=>$v){
      if($i=='time'){
        echo "<td class='time' title='$time_title' style='width:70px'>".date('H:i', $time)."</td>";
      }else{
        $tag = $model->tagnames[$c];
        echo $model->formatValue($v, $tag['spec'], $tag['spec2']);
        $c++;
      }
    }
    echo "</tr>";
  }
  echo "</tbody></table></div>";
}
?>