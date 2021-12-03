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
include "../../inc/bbs_info.inc"; 	 		// 게시판 정보
include "../../inc/bbs_info_set.inc"; 	 								// 게시판 정보

// 자동등록글체크
get_spam_check();

echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 검색 파라미터
$param = "code=$code";
if($page != "") $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

// 버튼설정
$list_btn = "<a href='list.php?$param'><img src='$skin_dir/image/btn_list.gif' border='0'></a>";
$confirm_btn = "<input type='image' src='$skin_dir/image/btn_confirm.gif' border='0'>";
$cancel_btn = "<img src='$skin_dir/image/btn_cancel.gif' border='0' onClick='history.go(-1)' style='cursor:hand'>";

// 선호도 숨김
if(strcmp($code, "review")) {
	$hide_star_start = "<!--"; $hide_star_end = "-->";
}

// 작성
if($mode == "") $mode = "write";
if($mode == "write"){

	if($wpermi < $mem_level) {

		// 구매회원 체크
		if(!strcmp($wpermi, "-1")) {

			$sql = "select count(idx) as cnt from wiz_basket as wb left join wiz_order as wo on wb.orderid = wo.orderid
							where wb.prdcode = '$prdcode' and wo.status = 'DC'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_array($result);

			if($row['cnt'] <= 0) {
				error($bbs_info[permsg],$bbs_info[perurl]);
			}

		} else {
			error($bbs_info[permsg],$bbs_info[perurl]);
		}
	}

	$name = $wiz_mall[name];
	$email = $wiz_mall[email];
	if($bbs_info[privacy] == "Y") $privacy_checked = "checked";

	// 비밀번호 숨김
	if($wiz_mall['id'] != ""){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

// 수정
}else if($mode == "modify"){

	// 게시물 정보
	$sql = "select * from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	$name = $bbs_row[name];
	$email = $bbs_row[email];
	$subject = $bbs_row['subject'];
	$content = $bbs_row['content'];

	$name = xss_check($name);
	$email = xss_check($email);
	$tphone = xss_check($tphone);
	$hphone = xss_check($hphone);
	$zipcode = xss_check($zipcode);
	$address = xss_check($address);
	$subject = xss_check($subject);
	$content = xss_check($content);
	$reply = xss_check($reply);

	$addinfo1 = xss_check($addinfo1);
	$addinfo2 = xss_check($addinfo2);
	$addinfo3 = xss_check($addinfo3);
	$addinfo4 = xss_check($addinfo4);
	$addinfo5 = xss_check($addinfo5);

	for($ii = 1; $ii <= $upfile_max; $ii++) {
		if(!empty($bbs_row[upfile.$ii])) {
			${upfile.$ii} = "<input type='checkbox' name='delupfile[]' value='upfile".$ii."'> 삭제 (".$bbs_row[upfile.$ii._name].")";
		}
	}
	if(!empty($bbs_row[movie1])) {
		$movie1 = "<input type='checkbox' name='delupfile[]' value='movie1'> 삭제 ($bbs_row[movie1])";
	}

	$movie2 = $bbs_row[movie2];
	$movie3 = $bbs_row[movie3];

	// 비밀번호 숨김
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
	($bbs_row[memid] != "" && $wiz_mall['id'] == $bbs_row[memid])														// 자신에글
	){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

	if($bbs_row[ctype] == "H") $ctype_checked = "checked";
	if($bbs_row[privacy] == "Y") $privacy_checked = "checked";
	if($bbs_row[notice] == "Y") $notice_checked = "checked";

	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row[star])) ${"star".$ii."_checked"} = "checked";
	}

// 답변
}else if($mode == "reply"){

	$sql = "select category,mallid,subject,content,privacy,passwd from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);


	$category = $bbs_row[category];
	$subject = $bbs_row['subject'];
	$content = $bbs_row['content']."\n\n==================== 답 변 ====================\n\n";
	$name = $wiz_mall[name];
	$email = $wiz_mall[email];

	$name = xss_check($name);
	$email = xss_check($email);
	$tphone = xss_check($tphone);
	$hphone = xss_check($hphone);
	$zipcode = xss_check($zipcode);
	$address = xss_check($address);
	$subject = xss_check($subject);
	$content = xss_check($content);
	$reply = xss_check($reply);

	$addinfo1 = xss_check($addinfo1);
	$addinfo2 = xss_check($addinfo2);
	$addinfo3 = xss_check($addinfo3);
	$addinfo4 = xss_check($addinfo4);
	$addinfo5 = xss_check($addinfo5);

	$mallid = $bbs_row[mallid];

	if($bbs_info[privacy] == "Y" || $bbs_row[privacy] == "Y") $privacy_checked = "checked";

	// 비밀번호 숨김
	if($wiz_mall['id'] != ""){
		$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
	}

	// 공지글 표시
	if(
	$mem_level == "0" || 																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)			// 게시판관리자
	){
	}else{
		$hide_notice_start = "<!--"; $hide_notice_end = "-->";
	}

}

// 게시물 분류
$sql = "select idx, catname, catimg from wiz_bbscat where code = '$code' and gubun != 'A' order by prior asc, idx asc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
if($total > 0) {

  /* select박스형태 */
  $catlist = "<select name=\"category\">";
  $catlist .= "<option value=\"\">:: 전체목록 ::</option>";
	while($row = mysqli_fetch_array($result)) {
  	$catname = $row[catname];
  	$selected = "";
		if($bbs_row[category] == $row['idx']) $selected = "selected";
    $catlist .= "<option value=\"".$row['idx']."\" ".$selected.">".$catname."</option>";

  }
  $catlist .= "</select> ";

}

// 첨부파일 사용여부
if($bbs_info[upfile] != "Y"){
	$hide_upfile_start = "<!--"; $hide_upfile_end = "-->";
}

// 동영상 사용여부
if($bbs_info[movie] != "Y"){
	$hide_movie_start = "<!--"; $hide_movie_end = "-->";
}

// 스팸글체크기능 사용여부
if(!strcmp($bbs_info[spam_check], "N") || !strcmp($mode, "modify")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

if($prdcode != ""){
	$prd_sql = "select prdcode,prdname,sellprice,strprice,prdimg_R from wiz_product where prdcode='$prdcode'";
	$prd_result = mysqli_query($connect, $prd_sql);
	$prd_info = mysqli_fetch_object($prd_result);

	if(!empty($prd_info->strprice)) $prd_info->sellprice = $prd_info->strprice;
	else $prd_info->sellprice = number_format($prd_info->sellprice)."원";

	// 상품 이미지
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$prd_info->prdimg_R)) $prd_info->prdimg_R = "/images/noimg_M.gif";
	else $prd_info->prdimg_R = "/data/prdimg/".$prd_info->prdimg_R;
?>
<table><tr><td height="20"></td></table>
<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#dfdfdf">
  <tr>
    <td bgcolor="#f7f7f7" style="padding:15px 24px;">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="<?=$prd_info->prdimg_R?>" width="100" height="100"></td>
          <td>
          	<table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td><?=$prd_info->prdname?><br></td>
              </tr>
              <tr>
                <td class="11red_01"><font class="price"><?=$prd_info->sellprice?></font></td>
              </tr>
              <!--
              <tr>
                <td><table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>평점</td>
                      <td><table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"><img src="<?=$skin_dir?>/image/prd_star_over.gif"></td>
                          </tr>
                      </table></td>
                    </tr>
                </table></td>
              </tr>
              -->
          	</table>
          </td>
          <td align="right"><a href="/shop/prd_view.php?prdcode=<?=$prd_info->prdcode?>"><img src="<?=$skin_dir?>/image/btn_prdview.gif" border="0"></a></td>
        </tr>
    	</table>
    </td>
  </tr>
</table>
<?
}

// 첨부파일 사용여부
if($bbs_info[upfile] < 5) { $hide_upfile5_start = "<!--"; $hide_upfile5_end = "-->"; }
if($bbs_info[upfile] < 4) { $hide_upfile4_start = "<!--"; $hide_upfile4_end = "-->"; }
if($bbs_info[upfile] < 3) { $hide_upfile3_start = "<!--"; $hide_upfile3_end = "-->"; }
if($bbs_info[upfile] < 2) { $hide_upfile2_start = "<!--"; $hide_upfile2_end = "-->"; }
if($bbs_info[upfile] < 1) { $hide_upfile1_start = "<!--"; $hide_upfile1_end = "-->"; }

// 동영상 사용여부
if($bbs_info[movie] < 3) { $hide_movie3_start = "<!--"; $hide_movie3_end = "-->"; }
if($bbs_info[movie] < 2) { $hide_movie2_start = "<!--"; $hide_movie2_end = "-->"; }
if($bbs_info[movie] < 1) { $hide_movie1_start = "<!--"; $hide_movie1_end = "-->"; }

// 입력스킨 인클루드
@include "$DOCUMENT_ROOT/$skin_dir/input.php";
?>

<? include "../footer.php"; ?>