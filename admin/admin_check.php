<?
include "../inc/common.inc";
include "../inc/util.inc";
include "../inc/shop_info.inc";

//if($shop_info->start_page == "") $start_page = "http://".$_http_host."/admin/main/main.php";
//else $start_page = "http://".$_http_host.$shop_info->start_page;
$port = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
$start_page = "http://".$_http_host.$port."/admin/main/main.php";
if($admin_id == "") error("아이디를 입력하세요");
if($admin_pw == "") error("비밀번호를 입력하세요");

$sql = "select * from wiz_admin where id='$admin_id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$admin_info = mysqli_fetch_object($result);
//global $wiz_admin;
//$wiz_admin = array();

if($admin_info->passwd == $admin_pw){
//if($admin_info->passwd == md5($admin_pw)){

	//방문회수 증가
	$sql = "update wiz_admin set last = now() where id='$admin_id'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 아이디 저장
	if(isset($_POST['saveid']) && $_POST['saveid'] == "Y") setcookie("admin_id", $admin_id, time()+3600*24*365, "/");

	$wiz_admin['id'] = $_SESSION['wiz_admin']['id']	= $admin_info->id;
	$wiz_admin['name'] = $_SESSION['wiz_admin']['name']	= $admin_info->name;
	$wiz_admin['email'] = $_SESSION['wiz_admin']['email']	= $admin_info->email;
	$wiz_admin['permi']	= $_SESSION['wiz_admin']['permi'] = $admin_info->permi;

	Header("Location: $start_page");

}else{

   	//if ($shop_info->designer_id == $admin_id && $shop_info->designer_pw == md5($admin_pw)){
	if ($shop_info->designer_id == $admin_id && $shop_info->designer_pw == $admin_pw){

		$wiz_admin['id'] = $_SESSION['wiz_admin']['id']	= 'admin';
		$wiz_admin['name'] = $_SESSION['wiz_admin']['name']	= $shop_info->shop_name;
		$wiz_admin['email'] = $_SESSION['wiz_admin']['email']	= $shop_info->shop_email;
		$wiz_admin['designer'] = $_SESSION['wiz_admin']['designer']= 'Y';
		$wiz_admin['permi']	= $_SESSION['wiz_admin']['permi'] = "";
		Header("Location: $start_page");

	}else{
		error ("회원정보가 일치하지 않습니다.");
	}
}
?>