<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/bbs_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>

  <table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td><img src="../image/ic_tit.gif"></td>
      <td valign="bottom" class="tit">게시물관리</td>
      <td width="2"></td>
      <td valign="bottom" class="tit_alt">게시물을 관리합니다.</td>
    </tr>
  </table>

<?
$wiz_session = $wiz_mall;

include "../../inc/bbs_info.inc"; 	 		// 게시판 정보
include "../../inc/bbs_info_set.inc"; 	 								// 게시판 정보

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 파라미터
$param = "code=$code";
if($grp != "") $param .= "&category=$category";
if($page != "") $param .= "&page=$page";
if($searchopt != "") $param .= "&searchopt=$searchopt";
if($searchkey != "") $param .= "&searchkey=$searchkey";

// 게시물정보
$sql = "select memid from wiz_bbs where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$bbs_row = mysqli_fetch_array($result);

// 코멘트정보
if($mode == "delco"){
	$co_sql = "select id from wiz_comment where idx = '$idx'";
	$co_result = mysqli_query($connect, $co_sql) or error(mysqli_error($connect));
	$co_row = mysqli_fetch_array($co_result);
}

// 버튼설정
$confirm_btn = "<input type='image' src='$skin_dir/image/btn_confirm.gif' border='0'>";

if($mode == "view")
	$cancel_btn = "<img src='$skin_dir/image/btn_cancel.gif' border='0' onClick=document.location='list.php?$param' style='cursor:hand'>";
else
	$cancel_btn = "<img src='$skin_dir/image/btn_cancel.gif' border='0' onClick='history.go(-1);' style='cursor:hand'>";


// 관리자이거나 자기글인경우
if(
	$mem_level == "0" ||																																			// 전체관리자
	($bbs_info['bbsadmin'] != "" && strpos($bbs_info['bbsadmin'],$wiz_session['id']) !== false)  ||	// 게시판관리자
	($bbs_row['memid'] != "" && $bbs_row['memid'] == $wiz_session['id']) ||													// 자신의글
	($co_row['id'] != "" && $co_row['id'] == $wiz_session['id'])															// 자신의코멘드
){
	$input_passwd = "글을 삭제하시겠습니까?";
}else{

	// 상황별 메세지
	if($mode == "view") $mode_msg = "이 글은 비밀글입니다. 비밀번호를 입력하여 주십시요.";
	else if($mode == "delete") $mode_msg = "글을 삭제합니다. 비밀번호를 입력하여 주십시요.";
	else if($mode == "delco") $mode_msg = "댓글을 삭제합니다. 비밀번호를 입력하여 주십시요.";

	$input_passwd = "<input type='password' name='passwd' size='25' class='input'>";

}

if($mode == "view") $act_url = "view.php";
else if($mode == "delete" || $mode == "delco") $act_url = "save.php";

@include "$DOCUMENT_ROOT/$skin_dir/passwd.php";

?>

<? include "../footer.php"; ?>