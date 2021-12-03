<?

if(!empty($_REQUEST["code"])) $code = $_REQUEST["code"];

// 게시판 정보
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
$bbs_info = mysqli_fetch_array($result);
if($total <= 0 || $bbs_info['usetype'] != "Y") error("해당게시판이 존재하지 않습니다.");

if($bbs_info['titleimg'] != "") $subimg = "<img src=/data/bbs/$code/".$bbs_info['titleimg'].">";

$upfile_max = $bbs_info['upfile'];	// 파일첨부 : 최대12까지가능, 사용자페이지 skin에는 적용되지 않음

// 게시판 접근권한
$level_info = level_info();
if(!isset($wiz_session['level']))$wiz_session['level'] = ""; 
$mem_level = $level_info[$wiz_session['level']]['level'];

$lpermi = $level_info[$bbs_info['lpermi']]['level'];
$rpermi = $level_info[$bbs_info['rpermi']]['level'];
$wpermi = $level_info[$bbs_info['wpermi']]['level'];
$apermi = $level_info[$bbs_info['apermi']]['level'];
$cpermi = $level_info[$bbs_info['cpermi']]['level'];

// 파일업로드 설정
$upfile_path = $DOCUMENT_ROOT."/data/bbs/".$code;		// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
$imgsize_s = $bbs_info['simgsize'];
$imgsize_m = $bbs_info['mimgsize'];

// 스킨위치
$skin_dir = "/bbs/skin/".$bbs_info['skin'];

// 게시판 위에서 해당 변수명을 쓸경우 에러 발생 방지
$idx = isset($_REQUEST['idx']) ? $_REQUEST['idx'] : "";
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "";
$searchopt = isset($_REQUEST['searchopt']) ? $_REQUEST['searchopt'] : "";
$searchkey = isset($_REQUEST['searchkey']) ?$_REQUEST['searchkey'] : "";

// 게시판관리자 체크
$bbsadmin_list = explode(",", $bbs_info['bbsadmin']);
for($ii = 0; $ii < count($bbsadmin_list); $ii++) {
	if(!empty($wiz_session['id']) && !strcmp($bbsadmin_list[$ii], $wiz_session['id'])) {
		$mem_level = 0; break;
	}
}

// 게시판 url
if(isset($btype) && $btype=='M'){
	$bbs_info['input_url']= "/m/bbs/input.php";
	$bbs_info['list_url'] = "/m/bbs/list.php";
	$bbs_info['view_url'] = "/m/bbs/view.php";
	$bbs_info['auth_url'] = "/m/bbs/auth.php";
}
else{
	$bbs_info['input_url']= "/bbs/input.php";
	$bbs_info['list_url'] = "/bbs/list.php";
	$bbs_info['view_url'] = "/bbs/view.php";
	$bbs_info['auth_url'] = "/bbs/auth.php";
}
if(!isset($mallid)) $mallid = "";
if(!isset($mallbbs)) $mallbbs = "";
if($mallbbs == true || $mallid != "") {

	$bbs_info['header'] = "&nbsp;";
	$bbs_info['footer'] = "&nbsp;";

	$bbs_info['input_url'] = "/shop/minibbs_input.php";
	$bbs_info['list_url'] = "/shop/minibbs_list.php";
	$bbs_info['view_url'] = "/shop/minibbs_view.php";
	$bbs_info['auth_url'] = "/shop/minibbs_auth.php";

}

?>