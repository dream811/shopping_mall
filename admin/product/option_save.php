<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>

<?

if($mode == "insert"){

   $sql = "insert into wiz_option(idx,opttitle,optcode) values('', '$opttitle', '$optcode')";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	echo "<script>alert('등록되었습니다.');self.close();opener.location='prd_option.php';</script>";
	
}else if($mode == "update"){
	
	$sql = "update wiz_option set opttitle='$opttitle', optcode='$optcode' where idx = '$idx'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	echo "<script>alert('수정되었습니다..');self.close();opener.location='prd_option.php';</script>";
	
}else if($mode == "delete"){
	
	$sql = "delete from wiz_option where idx = '$idx'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	echo "<script>alert('삭제되었습니다..');document.location='prd_option.php';</script>";
	
}

?>