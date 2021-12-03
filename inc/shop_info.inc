<?
$sql = "select * from wiz_shopinfo";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$shop_info = mysqli_fetch_object($result);

if(!strcmp($shop_info->ssl_use, "Y")) {
	$ssl = "https://".$HTTP_HOST;
	if(!empty($shop_info->ssl_port)) $ssl .= ":".$shop_info->ssl_port;
} else {
	$hide_ssl_start = "<!--"; $hide_ssl_end = "-->";
}
?>