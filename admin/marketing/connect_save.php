<? include "../../inc/common.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>

<?
if($mode == "dellist"){
	
	$sql = "delete from wiz_contime";
	mysqli_query($connect, $sql);
	
	complete("초기화 되었습니다.","connect_list.php");
	
}else if($mode == "delrefer"){
	
	$sql = "delete from wiz_conrefer";
	mysqli_query($connect, $sql);
	
	complete("초기화 되었습니다.","connect_refer.php");
	
}else if($mode == "delos"){
	
	$sql = "delete from wiz_conother";
	mysqli_query($connect, $sql);
	
	complete("초기화 되었습니다.","connect_osbrowser.php");
	
}
?>