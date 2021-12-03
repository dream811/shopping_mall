<?
$sql = "select * from wiz_design";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$design_info = mysqli_fetch_object($result);
?>