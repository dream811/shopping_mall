<?

if(!empty($_REQUEST["code"])) $code = $_REQUEST["code"];

if($bbs_info[titleimg] != "") $subimg = "<img src=/data/bbs/$code/$bbs_info[titleimg]>";

$upfile_max = 3;	// 파일첨부 : 최대12까지가능, 사용자페이지 skin에는 적용되지 않음

// 게시판 접근권한
$level_info = level_info();
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
if($btype=='M')	$skin_dir = "/bbs/skin_mobile/".$bbs_info[skin];
else			$skin_dir = "/bbs/skin/".$bbs_info[skin];

// 게시판 위에서 해당 변수명을 쓸경우 에러 발생 방지
$idx = $_REQUEST['idx'];
$category = $_REQUEST['category'];
$searchopt = $_REQUEST['searchopt'];
$searchkey = $_REQUEST['searchkey'];

// 게시판관리자 체크
$bbsadmin_list = explode(",", $bbs_info['bbsadmin']);
for($ii = 0; $ii < count($bbsadmin_list); $ii++) {
	if(!empty($wiz_session['id']) && !strcmp($bbsadmin_list[$ii], $wiz_session['id'])) {
		$mem_level = 0; break;
	}
}

?>