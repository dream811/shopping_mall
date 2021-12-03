<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
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

<?php
include "../../inc/bbs_info.inc"; 	 		// 게시판 정보
include "../../inc/bbs_info_set.inc"; 	 								// 게시판 정보

$line = $bbs_info[line];

echo "<script Language=\"JavaScript\" src=\"/js/util_lib.js\"></script>";
echo "<link href=\"".$skin_dir."/style.css\" rel=\"stylesheet\" type=\"text/css\">";

// 파라미터
$param = "&code=$code";
if($category != "") $param .= "&category=$category";
if($searchopt != "") $param .= "&searchopt=$searchopt";
if($searchkey != "") $param .= "&searchkey=$searchkey";

// 목록보기 권한체크
if($lpermi < $mem_level) error($bbs_info[permsg],$bbs_info[perurl]);
if($wpermi >= $mem_level && $code != "qna") $write_btn = "<a href=input.php?code=$code&mode=write><img src=/images/bbsimg/btn_write.gif border=0></a>";

// 버튼설정
$list_btn = "<a href='list.php?code=$code'><img src='$skin_dir/image/btn_list.gif' border='0'></a>";
if($wpermi >= $mem_level) {
	$write_btn = "<a href='input.php?mode=write&code=$code'><img src='$skin_dir/image/btn_write.gif' border='0'></a>";
} else {

	if(!strcmp($bbs_info[btn_view], "Y")) {
		if(!empty($bbs_info[perurl])) $perurl = " document.location='$bbs_info[perurl]'; ";
		$write_btn = "<img src='$skin_dir/image/btn_write.gif' border='0' onClick=\"alert('$bbs_info[permsg]'); $perurl \" style='cursor:pointer'>";
	}

}

// 추천기능 사용여부
if($bbs_info[recom] != "Y"){
	$hide_recom_start = "<!--"; $hide_recom_end = "-->";
}

// 게시물 분류
$sql = "select idx, gubun, catname, catimg, catimg_over from wiz_bbscat where code = '$code' order by gubun desc,prior asc,idx asc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
if($total > 0) {

	//$catlist = "<a href='$PHP_SELF?ptype=list&code=".$code."'>전체</a> | ";

	$ii = 0;
	while($row = mysqli_fetch_array($result)) {

		if($total < 2 && !strcmp($row[gubun], "A")) {

		} else {

	  	if(empty($row[catimg_over])) $row[catimg_over] = $row[catimg];

	  	if(empty($category) && !strcmp($row[gubun], "A")) $row[catimg] = $row[catimg_over];

	  	if(!empty($row[catimg])) $catname = "<img src='/data/category/".$code."/".$row[catimg]."' name='bc_".$ii."' border=0 id='bc_".$ii."' onMouseOver=WIZ_swapImage('bc_".$ii."','','/data/category/".$code."/".$row[catimg_over]."',1) onMouseOut=WIZ_swapImgRestore()>";
	  	else $catname = $row[catname];

	    if($category == $row['idx']) {
	    	if(!empty($row[catimg])) $catname = "<img src='/data/category/".$code."/".$row[catimg_over]."' name='bc_".$ii."' border=0 id='bc_".$ii."' onMouseOver=WIZ_swapImage('bc_".$ii."','','/data/category/".$code."/".$row[catimg_over]."',1) onMouseOut=WIZ_swapImgRestore()>";
	    	else $catname = "<b>".$catname."</b>";
	    }

			if(!strcmp($row[gubun], "A")) {
				$catlist .= "<a href='$PHP_SELF?ptype=list&code=".$code."'>".$catname."</a>";
			} else {
	    	$catlist .= "<a href='$PHP_SELF?ptype=list&code=".$code."&category=".$row['idx']."'>".$catname."</a>";
	    }

	    if(empty($row[catimg]))  if($ii < $total-1) $catlist .= " | ";
	    $ii++;
	  }
  }

  /* select박스형태
  $catlist = "<select name=\"category\" onChange=\"document.location='".$PHP_SELF."?ptype=list&category=' + this.value;\">";
  $catlist .= "<option value=\"\">:: 전체목록 ::</option>";
	while($row = mysqli_fetch_array($result)) {
  	$catname = $row[catname];
  	$selected = "";
		if($category == $row['idx']) $selected = "selected";
    $catlist .= "<option value=\"".$row['idx']."\" ".$selected.">".$catname."</option>";

  }
  $catlist .= "</select>";
  */

}

// 상단파일
@include "$DOCUMENT_ROOT/$skin_dir/list_head.php";

if(empty($bbs_info[datetype_list])) $bbs_info[datetype_list] = "%Y-%m-%d";

// 공지사항
$sql = "select wb.idx,wb.name,wb.category,wb.subject,from_unixtime(wb.wdate, '".$bbs_info[datetype_list]."') as wdate,wb.count,wb.comment,wb.recom,wb.upfile1,wb.content, wc.catname, wc.caticon
				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
				where wb.code = '$code' and wb.notice = 'Y'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

while($row = mysqli_fetch_array($result)){

	if($row[caticon] != "") $catname = "<img src='/data/category/".$code."/".$row[caticon]."' align='absmiddle'>";		// category
	else if($row[catname] != "") $catname = "[".$row[catname]."]";

	if($bbs_info[subject_len] > 0) $row['subject'] = cut_str($row['subject'], $bbs_info[subject_len]);

	$no = "<font color=red>[공지]</font>"; $name = $row[name]; $wdate = $row['wdate']; $count = $row[count];
	if($row[comment] > 0) $comment = "(".number_format($row[comment]).")";
	else $comment = "";
	$subject = "<a href='view.php?idx=$row['idx']&page=$page&$param'>$row['subject']</a>";

	$recom = $row[recom];

	$upimg_l = $row[upfile1];
	if(file_exists($DOCUMENT_ROOT."/data/bbs/".$code."/S".$row[upfile1])) $upimg_s = "/data/bbs/$code/S".$row[upfile1];							// img
	else $upimg_s = "$skin_dir/image/noimg.gif";

	$content = $row['content'];
	if($row[ctype] != "H"){
		$content = str_replace("\n", "<br>", $content);
	}

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

	// 글목록파일
	@include "$DOCUMENT_ROOT/$skin_dir/list_body.php";

}

// 게시물 쿼리
if($category) $category_sql = " and category = '$category' ";
if($searchopt) $search_sql = " and $searchopt like '%$searchkey%' ";

if($code == "qna") $mybbs = true;

// 자신이 쓴 글 또는 자신의 글에 달린 답변글
if($mybbs) $my_sql = " and (memid='".$wiz_mall['id']."' or memgrp like '".$wiz_mall['id'].",%' or mallid = '".$wiz_mall['id']."') ";

$sql = "select idx from wiz_bbs where code = '$code' and notice != 'Y' $my_sql $category_sql $search_sql order by prino desc";

$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

$idx = 0;
$rows = $bbs_info[rows];
$lists = $bbs_info[lists];
if($rows == "" || $rows < 1) $rows = "20";
if($lists == "" || $rows < 1) $lists = "5";

$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
$page_count = ceil($total/$rows);
if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;

$sql = "select wb.*,wb.wdate as wtime,from_unixtime(wb.wdate, '".$bbs_info[datetype_list]."') as wdate, wc.catname, wc.caticon
				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
				where wb.code = '$code' and wb.notice != 'Y' $category_sql $search_sql $my_sql
				order by wb.prino desc limit $start, $rows";

$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

while($row = mysqli_fetch_array($result)){

	$catname		="";
	$lock_icon	="";
	$re_icon		="";
	$new_icon		="";
	$hot_icon		="";
	$file_icon	="";
	$re_space		="";
	$upimg_s		="";
	$upimg_size = "";

	$name 		= $row[name];
	$email 		= $row[email];
	$count 		= $row[count];
	if($row[comment] > 0) $comment = "(".number_format($row[comment]).")";
	else $comment = "";
	$recom 		= $row[recom];
	$wdate 		= $row['wdate'];
	//$wdate = str_replace("-",".",$row['wdate']);

	$content = $row['content'];
	if($row[ctype] != "H"){
		$content = str_replace("\n", "<br>", $content);
	}

	if(!empty($row[upfile1])) {
		// 첨부파일
		$file_icon = "<a href='/bbs/down.php?code=$code&idx=$row['idx']&no=1'><img src='$skin_dir/image/file.gif' border='0' align='absmiddle'></a>";			// file
	}

	if($row[caticon] != "") $catname = "<img src='/data/category/".$code."/".$row[caticon]."' align='absmiddle'>";		// category
	else if($row[catname] != "") $catname = "[".$row[catname]."]";

	if($bbs_info[subject_len] > 0) $row['subject'] = cut_str($row['subject'], $bbs_info[subject_len]);

	$subject = "<a href='view.php?idx=$row['idx']&page=$page&$param'>$row['subject']</a>";		// subject
	$viewBbs = "view.php?idx=$row['idx']&page=$page&$param";
	if($row[privacy] == "Y"){																																					// privacy
		if(
			($mem_level == "0") ||																																		// 전체관리자
			($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_session['id']) !== false)  ||	// 게시판관리자
			($row[memid] != "" && $row[memid] == $wiz_session['id'])																 ||	// 자신의글
			($wiz_session['id'] != "" && strpos($row[memgrp],$wiz_session['id']) !== false)								// 그룹의글
		){
		}else{
			$subject = "<a href='auth.php?mode=view&idx=$row['idx']&page=$page&$param'>$row['subject']</a>";
			$viewBbs = "auth.php?mode=view&idx=$row['idx']&page=$page&$param";
			if(!empty($file_icon)) $file_icon = "<a href='auth.php?mode=view&idx=$row['idx']&page=$page&$param'><img src='$skin_dir/image/file.gif' border='0' align='absmiddle'></a>";
		}
		$lock_icon = "<img src='$skin_dir/image/lock.gif' border='0' align='absmiddle'>";
	}

	//$wdate_list = explode(".",$wdate);
	//$wtime = mktime(0,0,0,$wdate_list[1],$wdate_list[2],$wdate_list[0]);
	$wtime = $row[wtime];
	if(($ttime-$wtime)/86400 <= $bbs_info[newc]) 	$new_icon = "<img src='$skin_dir/image/new.gif' border='0' align='absmiddle'>";				// new
	if($row[count] > $bbs_info[hotc]) 						$hot_icon = "<img src='$skin_dir/image/hot.gif' border='0' align='absmiddle'>";				// hot
	if($row[depno] != 0) 													$re_icon = "<img src='$skin_dir/image/re.gif' border='0' align='absmiddle'>";					// re

	for($ii=0; $ii < $row[depno]; $ii++) 					$re_space .= "&nbsp;&nbsp;";																													// respace

	$upimg_l = $row[upfile1];
	if(file_exists($DOCUMENT_ROOT."/data/bbs/".$code."/S".$row[upfile1])) $upimg_s = "/data/bbs/$code/S".$row[upfile1];							// img
	else $upimg_s = "$skin_dir/image/noimg.gif";

	$viewImg = "javascript:viewImg('".$upimg_l."')";

	if(!empty($row[prdcode])) {

	 	// 상품정보
	 	$prd_sql = "select prdcode,prdname,prdimg_S1 from wiz_product where prdcode='$row[prdcode]'";
	 	$prd_result = mysqli_query($connect, $prd_sql);
	 	$prd_row = mysqli_fetch_array($prd_result);

		// 상품 이미지
		if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$prd_row[prdimg_S1])) $prd_row[prdimg_S1] = "/images/noimg_S.gif";
		else $prd_row[prdimg_S1] = "/data/prdimg/".$prd_row[prdimg_S1];

	 	$prdimg = "<a href='/shop/prd_view.php?prdcode=$prd_row[prdcode]'><img src='$prd_row[prdimg_S1]' width='50' height='50' border='0'></a>";
	 	$prdname = cut_str($prd_row[prdname],30);

	} else {

		$prdimg = "";
		$prdname = "";

	}

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

	// 글목록파일
	@include "$DOCUMENT_ROOT/$skin_dir/list_body.php";

	$no--;
	$idx++;

}

if($total <= 0){
	//echo "<tr><td height='25' align='center' colspan='20'>등록된 글이 없습니다.</td></tr>";
}

// 하단파일
@include "$DOCUMENT_ROOT/$skin_dir/list_foot.php";

?>

<? include "../footer.php"; ?>