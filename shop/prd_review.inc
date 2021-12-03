<?
// 게시판 정보
$code = "review";
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
$bbs_info = mysqli_fetch_array($result);
?>
<script language="javascript">
<!--
var clickvalue='';
function reviewShow(idnum) {

	var review=eval("review"+idnum+".style");

	if(clickvalue != review) {
		if(clickvalue!='') {
			clickvalue.display='none';
		}
		review.display='block';
		clickvalue=review;
	} else {
		review.display='none';
		clickvalue='';
	}

}

function reviewCheck(frm) {
	if(frm.passwd.value == "") {
		alert("비밀번호를 입력하세요.");
		frm.passwd.focus();
		return false;
	}
}

function openImg(img){
   var url = "../bbs/openimg.php?code=<?=$code?>&img=" + img;
   window.open(url,"openImg","width=300,height=300,scrollbars=yes");
}

-->
</script>
<table border=0 cellpadding="0" cellspacing="0" width="100%" class="AWshop_list_table border">
  <tr>
    <th width="8%" height="30" align="center" bgcolor="#f9f9f9"><strong>번호</strong></th>
    <th align="center" bgcolor="#f9f9f9"><strong>제목</strong></th>
     <th width="15%" align="center" bgcolor="#f9f9f9"><strong>평점</strong></th>
    <th width="10%" align="center" bgcolor="#f9f9f9"><strong>작성자</strong></th>
    <th width="10%" align="center" bgcolor="#f9f9f9"><strong>작성일</strong></th>
	<!--<th width="10%" align="center" bgcolor="#f9f9f9"><strong>조회수</strong></th>-->
  </tr>
	<?
	$sql = "select idx from wiz_bbs where code = '$code' and prdcode='$prdcode' order by prino desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$rows = 2;
	$lists = 5;
	$total = mysqli_num_rows($result);
	$page_count = ceil($total/$rows);
	if(!isset($rpage) || !$rpage || $rpage > $page_count) $rpage = 1;
	$start = ($rpage-1)*$rows;
	$no = $total-$start;

	$sql = "select *, DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y.%m.%d') as wdate from wiz_bbs where code = '$code' and prdcode='$prdcode' order by prino desc limit $start, $rows";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while(($row = mysqli_fetch_object($result)) && $rows){


	 	$catname=""; $re_space=""; $depno=""; $lock=""; $new=""; $hot="";

	 	$review_display = "none";

	 	$subject = "<a href=\"javascript:reviewShow('$no');\">$row->subject</a>";

	 	if($row->privacy == "Y"){

			$grp_sql = "select idx from wiz_bbs where code='$code' and grpno='$row->grpno' and passwd='$passwd' and idx = '$idx'";
			$grp_result = mysqli_query($connect, $grp_sql) or error(mysqli_error($connect));
			$grp_passwd = mysqli_num_rows($grp_result);
			
			$memgrp = explode(',', $row->memgrp);

			if(
			$mem_level == 0 ||																			// 전체관리자
			($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_session['id']) !== false)  ||	// 게시판관리자
			($row->memid != "" && $row->memid == $wiz_session['id']) || 									// 자신의글
			($row->passwd != "" && $row->passwd == $passwd && !strcmp($idx, $row->idx)) ||				// 비밀번호일치
			($wiz_session['id'] != "" && in_array($wiz_session['id'], $memgrp) !== false) ||				// 그룹의글
			($grp_passwd > 0)																			// 그룹비번
			){
			}else{
				$subject = "<a href='minibbs_auth.php?mode=view&idx=$row->idx&code=$code&rpage=$rpage&$param'>$row->subject</a>";
				$row->content = '비밀글입니다.';
			}

	 		$lock = "<img src='/images/bbsimg/lock.gif' align='absmiddle'>";

	 	}

	 	$re_space = ""; for($ii=0; $ii < $row->depno; $ii++) $re_space .= "&nbsp;&nbsp;";				// respace
	 	if($row->depno != 0) $depno = "<img src='/images/icon_re.gif' align='absmiddle'>";												// re
		if(!isset($upfile_max)) $upfile_max = 0;
		for($ii = 1; $ii <= $upfile_max; $ii++) {
			if(img_type($DOCUMENT_ROOT."/data/bbs/$code/M".$row->{'upfile'.$ii})) ${'upimg'.$ii} = "<div align='".$bbs_info['img_align']."'><a href=javascript:openImg('".$row->{'upfile'.$ii}."');><img src='/data/bbs/$code/M".$row->{'upfile'.$ii}."' border='0'></a></div>";
		}

		if($row->ctype != "H")  $row->content = str_replace("\n", "<br>", $row->content);

 ?>
	<tr>
	  <td align="center" height="50"><?=$no?></td>
	  <td>&nbsp;<?=$re_space?><?=$depno?> <?=$subject?> <?=$lock?></td>
	  <td align="center"><img src="/images/icon_star_<?=$row->star?>.gif"></td>
	  <td align="center"><?=$row->name?></td>
	  <td align="center"><?=$row->wdate?></td>
	  <!--<td align="center"><?=$row->count?></td>-->
	</tr>
	<tr>
	  <td colspan="6" style="background:#fafafa; border-bottom:none;">
		  <table width="90%" border="0" class="answer_box" id="review<?=$no?>" style="display:<?=$review_display?>">
		  <tr><td bgcolor="ffffff">
		  	<? for($ii = 1; $ii <= $upfile_max; $ii++) echo ${upimg.$ii} ?>
				<?=$row->content?>
		  </td></tr>
		  </table>
    </td>
  </tr>
	<?
		$no--;
		$rows--;
	}
	if($total <= 0){
	?>
	<tr>
	  <td align="center" colspan="10" height="60">등록된 게시물이 없습니다.</td>
	</tr>
	<?
	}
	?>
</table>
<table border=0 cellpadding="0" cellspacing="0" width="100%" class="AWshop_list_btn">
	<tr><td height="5"></td></tr>
	<tr>
		<td width="100%" align="center"><? print_pagelist($rpage, $lists, $page_count, "&prdcode=$prdcode", "r"); ?></td>
	</tr>
	<tr>
		<td width="100%" style="text-align:right;">
			<a href="minibbs_input.php?code=review&mode=write&prdcode=<?=$prdcode?>&mallid=<?=$mallid?>" class="btn">상품평 쓰기</a>
			<a href="minibbs_list.php?code=review&mallid=<?=$mallid?>" class="btn">전체보기</a>
		</td>
	</tr>
</table>
<br>