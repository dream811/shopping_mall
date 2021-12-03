<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc"; ?>
<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/util.inc"; ?>
<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/admin_check.inc"; ?>


<table width="100%">
<form action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="" value="ok">
  <tr><td><b>쿼리실행</b> <?=mktime();?></td></tr>
  <tr><td><hr></td></tr>
  <tr><td><textarea name="query" rows="10" cols="80"><?=stripslashes($query)?></textarea><input type="submit"></td></tr>
</form>
</table>
<? if($query != "") mysqli_query($connect, stripslashes($query)) or die(mysqli_error($connect)); ?>
<br><br>



<table>
<form action="<?=$PHP_SELF?>">
	<tr>
		<td><b>쿼리생성하기</b> 테이블</td>
		<td>
		<select name="table_name" onChange="this.form.submit();">
		<option value="">- 선택 -</option>
		<?
		$sql = "show tables";
		$result = mysqli_query($connect, $sql);
		while($row = mysqli_fetch_array($result)){
		?>
		<option value="<?=$row[0]?>" <? if($table_name == $row[0]) echo "selected"; ?>><?=$row[0]?></option>
		<?
		}
		?>
		</select>
		</td>
	</tr>
</form>
</table>
<hr>
<br><br>
<b># 테이블구조</b><br>
<table width=98% border=0 cellspacing=1 cellpadding=3 bgcolor=#000000>
	<tr>
		<td bgcolor=#efefef width=10%>Field</td>
		<td bgcolor=#efefef width=20%>Type</td>
		<td bgcolor=#efefef width=10%>Null</td>
		<td bgcolor=#efefef width=10%>Key</td>
		<td bgcolor=#efefef width=10%>Default</td>
	</tr>
	<?
	$sql = "desc ".$table_name;
	$result = mysqli_query($connect, $sql);

	$no = 0;
	while($row = mysqli_fetch_array($result)){
		$field = $row[0];
	?>
	<tr>
		<td bgcolor=#ffffff><?=$row[0]?></td>
		<td bgcolor=#ffffff><?=$row[1]?></td>
		<td bgcolor=#ffffff><?=$row[2]?></td>
		<td bgcolor=#ffffff><?=$row[3]?></td>
		<td bgcolor=#ffffff><?=$row[4]?></td>
	</tr>
<?
		$no++;
	}
?>
</table>
<br><br>

<b># 데이타</b><br>
<table width=98% border=0 cellspacing=1 cellpadding=3 bgcolor=#000000>
	<tr>
	<?
	$sql = "desc ".$table_name;
	$result = mysqli_query($connect, $sql);
	$total = mysqli_num_rows($result);
	while($row = mysqli_fetch_array($result)){
	?>
		<td bgcolor=#efefef width=10%><?=$row[0]?></td>
	<?
	}
	?>
	</tr>
	<?
	$sql = "select * from ".$table_name." limit 20";
	$result = mysqli_query($connect, $sql);
	while($row = mysqli_fetch_array($result)){
	?>
	<tr bgcolor=#ffffff>
		<? for($ii=0;$ii<$total;$ii++){ ?>
			<td><?=$row[$ii]?></td>
		<? } ?>
	</tr>
	<?
	}
	?>

</table>


<br><br>
<b># 데이타 저장</b><br><br>
<?
if($table_name != ""){

	$no = 1;
	$sql = "desc ".$table_name;
	$result = mysqli_query($connect, $sql);
	$total = mysqli_num_rows($result);

	while($row = mysqli_fetch_array($result)){

		$insert_name .= "".$row[0]."";
		$insert_value .= "'$".$row[0]."'";

		$update_sql .= $row[0]."='$".$row[0]."'";

		if($total > $no){
			$insert_name .= ",";
			$insert_value .= ",";
			$update_sql .= ",";
		}



		$no++;
	}

?>
if($mode == "insert"){<br><br>

&nbsp; &nbsp; $sql = "insert into <?=$table_name?> (<?=$insert_name?>) values(<?=$insert_value?>)";<br>
&nbsp; &nbsp; mysqli_query($connect, $sql) or die(mysqli_error($connect));

<br><br>

}else if($mode == "update"){<br><br>

&nbsp; &nbsp; $sql = "update <?=$table_name?> set <?=$update_sql?> where idx = '$idx'";<br>
&nbsp; &nbsp; mysqli_query($connect, $sql) or die(mysqli_error($connect));

<br><br>

}else if($mode == "delete"){<br><br>

&nbsp; &nbsp; $sql = "delete from <?=$table_name?> where idx = '$idx'";<br>
&nbsp; &nbsp; mysqli_query($connect, $sql) or die(mysqli_error($connect));

<br><br>

}

<?
}
?>