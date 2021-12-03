<?

include "../inc/common.inc";
include "../inc/util.inc";

$passwd = md5($passwd);
$sql = "select id,passwd,name,email,tphone,hphone,level from wiz_member where id='$id' and passwd='$passwd'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

$wiz_session = array();
// 일반회원 로그인
if($row = mysqli_fetch_object($result)){

	//방문회수 증가
	$sql = "update wiz_member set visit = visit+1 , visit_time = now() where id='$id'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	global $wiz_session;
	$level_info = level_info();
	$level_value = $level_info[$row->level]['level'];

	$wiz_session['id'] = $_SESSION['wiz_session']['id']			= $row->id;
	$wiz_session['passwd'] = $_SESSION['wiz_session']['passwd']		= $row->passwd;
	$wiz_session['name'] = $_SESSION['wiz_session']['name']		= $row->name;
	$wiz_session['tphone'] = $_SESSION['wiz_session']['tphone']		= $row->tphone;
	$wiz_session['hphone'] = $_SESSION['wiz_session']['hphone']		= $row->hphone;
	$wiz_session['email'] = $_SESSION['wiz_session']['email']		= $row->email;
	$wiz_session['level'] = $_SESSION['wiz_session']['level']		= $row->level;
	$wiz_session['level_value'] = $_SESSION['wiz_session']['level_value']	= $level_value;
	
	if(empty($prev)) $prev = "http://".$_http_host.$HTTP_POST;
	Header("Location: $prev");

// 관리자 로그인
}else{

   $sql = "select * from wiz_admin where id = '$id' and passwd = '$passwd'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

   if($row = mysqli_fetch_object($result)){

		$sql = "update wiz_admin set last = now() where id = '$id'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$wiz_session['id'] = $_SESSION['wiz_session']['id']			= $row->id;
		$wiz_session['passwd'] = $_SESSION['wiz_session']['passwd']		= $row->passwd;
		$wiz_session['name'] = $_SESSION['wiz_session']['name']		= $row->name;
		$wiz_session['tphone'] = $_SESSION['wiz_session']['tphone']		= $row->tphone;
		$wiz_session['email'] = $_SESSION['wiz_session']['email']		= $row->email;
		$wiz_session['level'] = $_SESSION['wiz_session']['level']		= '0';
		$wiz_session['level_value'] = $_SESSION['wiz_session']['level_value']	= '0';
		$wiz_session['permi'] = $_SESSION['wiz_session']['permi']		= $row->permi;

		if(empty($prev)) $prev = "http://".$_http_host.$HTTP_POST;
		Header("Location: $prev");

   }else{
      error("회원정보가 일치하지 않습니다.","");
   }
}
?>