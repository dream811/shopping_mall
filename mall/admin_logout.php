<?
@extract($_SESSION);
@extract($_COOKIE);

if(!empty($wiz_mall['id'])){
//if(!empty($wiz_mall)){
	unset($_SESSION["wiz_mall"]);
	unset($wiz_mall);
	setcookie("wiz_mall['id']", "", time()-3600, "/");
	setcookie("wiz_mall[name]", "", time()-3600, "/");
	setcookie("wiz_mall[email]", "", time()-3600, "/");
}

echo "<script>parent.document.location='./admin_login.php';</script>";

?>