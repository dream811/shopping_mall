<?
include "../inc/common.inc";
include "../inc/util.inc";

$start_page = "./main/main.php";

if($admin_id == "") error("아이디를 입력하세요");
if($admin_pw == "") error("비밀번호를 입력하세요");

$sql = "select * from wiz_mall where id='$admin_id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$admin_info = mysqli_fetch_object($result);

//if($admin_info->passwd == md5($admin_pw)){
if($admin_info->passwd == md5($admin_pw)){

	if(strcmp($admin_info->status, "Y")) {

		error("관리자 승인 후 이용가능합니다.");

	} else {

	   //방문회수 증가
	   $sql = "update wiz_mall set last = now() where id='$admin_id'";
	   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	   // 아이디 저장
	   if(isset($_POST['saveid']) && $_POST['saveid'] == "Y") setcookie("admin_id", $admin_id, time()+3600*24*365, "/");

		// setcookie("wiz_mall['id']", $admin_info->id, false, "/");
		// setcookie("wiz_mall['passwd']", $admin_info->passwd, false, "/");
		// setcookie("wiz_mall['name']", $admin_info->com_name, false, "/");
		// setcookie("wiz_mall['email']", $admin_info->email, false, "/");
		// setcookie("wiz_mall['com_tel']", $admin_info->com_tel, false, "/");
		$wiz_mall= array();
		$wiz_mall['id'] 		= $_SESSION['wiz_mall']['id'] 		= $admin_info->id;
		$wiz_mall['passwd'] 	= $_SESSION['wiz_mall']['passwd'] 	= $admin_info->passwd;
		$wiz_mall['name'] 		= $_SESSION['wiz_mall']['name'] 	= $admin_info->com_name;
		$wiz_mall['email'] 		= $_SESSION['wiz_mall']['email'] 	= $admin_info->email;
		$wiz_mall['com_tel'] 	= $_SESSION['wiz_mall']['com_tel'] 	= $admin_info->com_tel;
		$_SESSION['wiz_mall'] = $wiz_mall;

		Header("Location: $start_page");

	}

}  else{
	error("회원정보가 일치하지 않습니다.");
}

?>