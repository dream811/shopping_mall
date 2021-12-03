<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? $param = "code=$code&title=$title"; ?>
<html>
<head>
<title>:: 카테고리관리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function catDelete(idx) {
	if(confirm("삭제하시겠습니까?")) {
		document.location = "bbs_pro_save.php?mode=catdelete&<?=$param?>&idx=" + idx;
	}
}
//-->
</script>
</head>
<body style="padding:10px;">
<table width="100%" border="0" cellpadding=10 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 카테고리관리 : <?=$title?></td></tr></table></td>
    <td align="right"><a onclick="document.location='category_input.php?mode=catinsert&<?=$param?>'" class="AW-btn">분류등록</a></td>
  </tr>
  <tr><td colspan="2" height="5"></td></tr>
</table>

<?
	$sql = "select * from wiz_bbscat where code='$code' order by gubun desc, prior asc, idx asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	$rows = 10;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

?>
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
	<tr>
		<td width="30" height="30" class="t_name" align="center">번호</td>
		<td width="100" class="t_name" align="center">분류명</td>
		<td class="t_name" align="center">분류이미지</td>
		<td class="t_name" align="center">롤오버이미지</td>
		<td class="t_name" align="center">분류아이콘</td>
		<td width="130" class="t_name" align="center">링크값</td>
		<td width="50" class="t_name" align="center">우선순위</td>
		<td width="90" class="t_name" align="center">기능</td>
	</tr>
<?php
	$sql = "select * from wiz_bbscat where code='$code' order by gubun desc, prior asc, idx asc limit $start, $rows";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_array($result)){
?>
	<tr>
		<td height="30" class="t_value" align="center"><?=$no?></td>
		<td class="t_value" align="center"><?=$row[catname]?></td>
		<td class="t_value" align="center">
			<?php if(!empty($row[catimg])) { ?> <img src="/data/category/<?=$code?>/<?=$row[catimg]?>"><?php } ?>
		</td>
		<td class="t_value" align="center">
			<?php if(!empty($row[catimg_over])) { ?> <img src="/data/category/<?=$code?>/<?=$row[catimg_over]?>"><?php } ?>
		</td>
		<td class="t_value" align="center">
			<?php if(!empty($row[caticon])) { ?> <img src="/data/category/<?=$code?>/<?=$row[caticon]?>"><?php } ?>
		</td>
		<td class="t_value" align="center">파일명?category=<?=$row['idx']?></td>
		<td class="t_value" align="center"><?=$row[prior]?></td>
		<td  class="t_value" align="center">
            <a onclick="document.location='category_input.php?idx=<?=$row['idx']?>&mode=catupdate&<?=$param?>'" class="AW-btn-s modify">수정</a>
            <a onclick="catDelete('<?=$row['idx']?>')" class="AW-btn-s del">삭제</a>
		</td>
	</tr>
<?
  	$no--;
	}

	if($total <= 0) {
?>
	<tr>
		<td height="30" class="t_value" align="center" colspan="7">등록된 분류가 없습니다.</td>
	</tr>
<?
	}
?>
</table>

<br>

<table align="center" border="0" cellpadding=2 cellspacing=1>
	<tr>
		<td><? print_pagelist($page, $lists, $page_count, $param); ?></td>
	</tr>
</table>


</td>
</tr>
</table>
</body>
</html>