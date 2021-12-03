<?php
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

<div class="review">
상품후기 <small>(총 <?=$review_total?>건)</small>
<hr style="margin-top:5px; margin-bottom:10px;" />
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<?php
$sql = "select idx from wiz_bbs where code = '$code' and prdcode='$prdcode' order by prino desc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

$rows = 30;
$lists = 5;
$total = mysqli_num_rows($result);
$page_count = ceil($total/$rows);
if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
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

		if(
		$mem_level == 0 ||																																				// 전체관리자
		($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_session['id']) !== false)  ||	// 게시판관리자
		($row->memid != "" && $row->memid == $wiz_session['id']) || 												// 자신의글
		($row->passwd != "" && $row->passwd == $passwd && !strcmp($idx, $row->idx)) ||																// 비밀번호일치
		($wiz_session['id'] != "" && strpos($row->memgrp,$wiz_session['id']) !== false) ||				// 그룹의글
		($grp_passwd > 0)																																					// 그룹비번
		){
		}else{
			$subject = "<a href='../bbs/auth.php?mode=view&idx=$row->idx&code=$code&page=$page&$param'>$row->subject</a>";
		}

 		$lock = "<img src='/images/bbsimg/lock.gif' align='absmiddle'>";

 	}

 	$re_space = ""; for($ii=0; $ii < $row->depno; $ii++) $re_space .= "&nbsp;&nbsp;";				// respace
 	if($row->depno != 0) $depno = "<img src='/images/icon_re.gif' align='absmiddle'>";												// re

	for($ii = 1; $ii <= $upfile_max; $ii++) {
		if(img_type($DOCUMENT_ROOT."/data/bbs/$code/M".$row->{upfile.$ii})) ${upimg.$ii} = "<div align='".$bbs_info[img_align]."'><a href=javascript:openImg('".$row->{upfile.$ii}."');><img src='/data/bbs/$code/M".$row->{upfile.$ii}."' border='0'></a></div>";
	}

	if($row->ctype != "H")  $row->content = str_replace("\n", "<br>", $row->content);

	$star = "";
	for($ii = 1; $ii <= $row->star; $ii++) {
		$star .= "★";
	}

?>
	<tr>
		<td style="border-bottom:1px solid #e6e6e6;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td class="review_info"><?=$row->name?> / <?=$row->wdate?> / <span class="star"><?=$star?></span> </td></tr>
				<tr><td class="review_title"><?=$re_space?><?=$depno?> <?=$subject?> <?=$lock?></td></tr>
				<tr>
					<td class="review_con" id="review<?=$no?>" style="display:<?=$review_display?>">
						<? for($ii = 1; $ii <= $upfile_max; $ii++) echo ${upimg.$ii} ?>
						<?=$row->content?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
<?php
	$no--;
	$rows--;
}
if($total <= 0){
?>
	<tr>
		<td style="border-bottom:1px solid #e6e6e6;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr><td class="review_title" height="100" style="text-align:center; font-size:15px; vertical-align:middle;">등록된 상품 후기가 없습니다.</td></tr>
			</table>
		</td>
	</tr>
<?php
}
?>

</table>
</div>