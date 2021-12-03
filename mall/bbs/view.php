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

// 게시판 위에서 해당 변수명을 쓸경우 에러 발생 방지
$idx = $_REQUEST['idx'];
$category = $_REQUEST[category];
$searchopt = $_REQUEST[searchopt];
$searchkey = $_REQUEST[searchkey];

// 검색 파라미터
$param = "code=$code";
if($idx != "") $param .= "&idx=$idx";
if($page != "") $param .= "&page=$page";
if($category != "") $param .= "&category=$category";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";


if(empty($bbs_info[datetype_view])) $bbs_info[datetype_view] = "%Y-%m-%d";

// 게시물 정보
$sql = "select wb.*,from_unixtime(wb.wdate, '".$bbs_info[datetype_view]."') as wdate, wc.catname, wc.caticon
			from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
			where wb.idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$bbs_row = mysqli_fetch_array($result);

$memid 		= $bbs_row[memid];
$name 		= $bbs_row[name];
$email 		= $bbs_row[email];
$tphone 	= $bbs_row[tphone];
$hphone 	= $bbs_row[hphone];
$zipcode 	= $bbs_row[zipcode];
$address 	= $bbs_row[address];
$subject 	= $bbs_row['subject'];
$content 	= $bbs_row['content'];
$wdate 		= $bbs_row['wdate'];
$count 		= $bbs_row[count];
$recom 		= $bbs_row[recom];
$ip 			= $bbs_row[ip];

$addinfo1 = $bbs_row[addinfo1];
$addinfo2 = $bbs_row[addinfo2];
$addinfo3 = $bbs_row[addinfo3];
$addinfo4 = $bbs_row[addinfo4];
$addinfo5 = $bbs_row[addinfo5];

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

if($bbs_row[caticon] != "") $catname = "<img src='/data/category/".$code."/".$bbs_row[caticon]."' align='absmiddle'> ";		// category
else if($bbs_row[catname] != "") $catname = "[".$bbs_row[catname]."] ";

if($bbs_row[ctype] != "H"){
	$content = htmlspecialchars($content);
	$content = str_replace("\n", "<br>", $content);
}

$_ResizeCheck = false;
// 첨부파일 이미지인경우 보여주기
if(strcmp($bbs_info[imgview], "N")) {

	for($ii = 1; $ii <= 12; $ii++) {
		if(img_type($DOCUMENT_ROOT."/data/bbs/$code/M".$bbs_row[upfile.$ii])) {
			${upimg.$ii} = "<div align='".$bbs_info[img_align]."'><a href=javascript:openImg('".$bbs_row[upfile.$ii]."');><img src='/data/bbs/$code/M".$bbs_row[upfile.$ii]."' border='0'></a></div>";
			$_ResizeCheck = true;
		}
	}
}

// 이미지 리사이즈를 위해서 처리하는 부분
if(strpos(strtolower($content), "<img") !== false || $_ResizeCheck == true) {
	$content = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $content);
	$content = "<table border=0 cellspacing=0 cellpadding=0 style='width:".$bbs_info[mimgsize]."px;height:0px;' id='wiz_get_table_width'>
								<col width=100%></col>
								<tr>
									<td><img src='' border='0' name='wiz_target_resize' width='0' height='0'></td>
								</tr>
							</table>
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
								<col width=100%></col>
								<tr><td valign=top>".$content."</td></tr>
							</table>";
	$_ResizeCheck = true;
}

// 글보기 권한체크
if($rpermi < $mem_level) error($bbs_info[permsg],$bbs_info[perurl]);

// 선호도 숨김
if(strcmp($code, "review")) {
	$hide_star_start = "<!--"; $hide_star_end = "-->";
}

// 선호도
$star = "<img src='/images/icon_star_".$bbs_row[star].".gif'>";

// 비밀글인경우 체크
if($bbs_row[privacy] == "Y"){

	$sql = "select idx from wiz_bbs where code='$code' and grpno='$bbs_row[grpno]' and passwd='$passwd'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$grp_passwd = mysqli_num_rows($result);

	if(
	$mem_level == 0 ||																																				// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
	($bbs_row[memid] != "" && $bbs_row[memid] == $wiz_mall['id']) || 												// 자신의글
	($bbs_row[passwd] != "" && $bbs_row[passwd] == $passwd) ||																// 비밀번호일치
	($wiz_mall['id'] != "" && strpos($bbs_row[memgrp],$wiz_mall['id']) !== false) ||				// 그룹의글
	($grp_passwd > 0)																																					// 그룹비번
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.","?auth.php?&mode=view&$param");
		else  error("권한이 없습니다.","auth.php?mode=view&$param");
	}

}

// 조회수 증가
$sql = "update wiz_bbs set count = count+1 where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

// 버튼설정
$list_btn = "<a href='list.php?$param'><image src='$skin_dir/image/btn_list.gif' border='0'></a>";
if($wpermi >= $mem_level){
	$write_btn = "<a href='input.php?mode=write&$param'><image src='$skin_dir/image/btn_write.gif' border='0'></a>";
	$modify_btn = "<a href='input.php?mode=modify&$param'><image src='$skin_dir/image/btn_modify.gif' border='0'></a>";
	$delete_btn = "<a href='auth.php?mode=delete&$param'><image src='$skin_dir/image/btn_delete.gif' border='0'></a>";
} else {
	if(!strcmp($bbs_info[btn_view], "Y")) {
		if(!empty($bbs_info[perurl])) $perurl = " document.location='$bbs_info[perurl]'; ";
		$write_btn = "<img src='$skin_dir/image/btn_write.gif' border='0' onClick=\"alert('$bbs_info[permsg]'); $perurl \" style='cursor:pointer'>";
	}
}
if($apermi >= $mem_level){
	$reply_btn = "<a href='input.php?mode=reply&$param'><image src='$skin_dir/image/btn_reply.gif' border=0></a>";
} else {
	if(!strcmp($bbs_info[btn_view], "Y")) {
		if(!empty($bbs_info[perurl])) $perurl = " document.location='$bbs_info[perurl]'; ";
		$reply_btn = "<img src='$skin_dir/image/btn_reply.gif' border='0' onClick=\"alert('$bbs_info[permsg]'); $perurl \" style='cursor:pointer'>";
	}
}

if($bbs_info[recom] == "Y"){
	$recom_btn = "<a href='/bbs/save.php?mode=recom&prev=view.php&$param'><image src='$skin_dir/image/btn_recom.gif' border=0></a>";
}

// 첨부파일
for($ii = 1; $ii <= 12; $ii++) {
	if($bbs_row[upfile.$ii] != "") ${upfile.$ii}  = "<a href='/bbs/down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row[upfile.$ii._name]."</a>";
}

if($bbs_row[movie1] != "") $movie1 = "<embed src='/data/bbs/$code/".$bbs_row[movie1]."' autostart=false></embed><br>";
if($bbs_row[movie2] != "") $movie2 = "<embed src='".$bbs_row[movie2]."' autostart=false></embed><br>";
if($bbs_row[movie3] != "") $movie3 = "<embed src='".$bbs_row[movie3]."' autostart=false></embed><br>";

if($code == "qna") $mybbs = true;

// 자신이 쓴 글 또는 자신의 글에 달린 답변글
if($mybbs) $my_sql = " and (memid='$wiz_mall['id']' or memgrp like '".$wiz_mall['id'].",%' or mallid = '".$wiz_mall['id']."')";

// 이전글
$sql = "select idx,subject, privacy, memid from wiz_bbs where code = '$code' and prino > '$bbs_row[prino]' $my_sql order by prino asc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if($row = mysqli_fetch_array($result)) {
	$prev = "<a href='view.php?code=$code&idx=$row['idx']'>$row['subject']</a>";

	if($row[privacy] == "Y"){
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle'>";																																	// privacy
		if(
			($mem_level == "0") ||																																		// 전체관리자
			($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
			($row[memid] != "" && $row[memid] == $wiz_mall['id'])																 ||	// 자신의글
			($wiz_mall['id'] != "" && strpos($row[memgrp],$wiz_mall['id']) !== false)								// 그룹의글
		){
		}else{
			$prev = "<a href='auth.php?mode=view&code=$code&idx=$row['idx']'>$row['subject']</a> ".$lock_icon;
		}
	}

}

// 다음글
$sql = "select idx,subject, privacy, memid from wiz_bbs where code = '$code' and prino < '$bbs_row[prino]' $my_sql order by prino desc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if($row = mysqli_fetch_array($result)) {
	$next = "<a href='view.php?ptype=view&code=$code&idx=$row['idx']'>$row['subject']</a>";

	if($row[privacy] == "Y"){
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle'>";																																	// privacy
		if(
			($mem_level == "0") ||																																		// 전체관리자
			($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
			($row[memid] != "" && $row[memid] == $wiz_mall['id'])																 ||	// 자신의글
			($wiz_mall['id'] != "" && strpos($row[memgrp],$wiz_mall['id']) !== false)								// 그룹의글
		){
		}else{
			$next = "<a href='auth.php?mode=view&code=$code&idx=$row['idx']'>$row['subject']</a> ".$lock_icon;
		}
	}

}

// 댓글 작성 비밀번호 숨김
if($wiz_mall['id'] != ""){
	$hide_passwd_start = "<!--"; $hide_passwd_end = "-->";
}

// 첨부파일 사용여부
if($bbs_info[upfile] != "Y"){
	$hide_upfile_start = "<!--"; $hide_upfile_end = "-->";
}

// 추천기능 사용여부
if($bbs_info[recom] != "Y"){
	$hide_recom_start = "<!--"; $hide_recom_end = "-->";
}

// 스팸글체크기능 사용여부
if(!strcmp($bbs_info[spam_check], "N")){
	$hide_spam_check_start = "<!--"; $hide_spam_check_end = "-->";
}

?>
<script language="javascript">
<!--
function openImg(img){
   var url = "/bbs/openimg.php?code=<?=$code?>&img=" + img;
   window.open(url,"openImg","width=300,height=300,scrollbars=yes");
}
//-->
</script>

<?
if($bbs_row[prdcode] != ""){
	$prd_sql = "select prdcode,prdname,sellprice,strprice,prdimg_R from wiz_product where prdcode='$bbs_row[prdcode]'";
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
?>

<?php

// 뷰스킨 인클루드
@include "$DOCUMENT_ROOT/$skin_dir/view_head.php";
@include "$DOCUMENT_ROOT/bbs/comment.inc";
@include "$DOCUMENT_ROOT/$skin_dir/view_foot.php";

view_img_resize();

if(!strcmp($bbs_info[view_list], "Y")) {
	$mybbs = true;
	$view_idx = $idx;
	include "$_SERVER[DOCUMENT_ROOT]/bbs/view_list.php";
}

?>

<? include "../footer.php"; ?>