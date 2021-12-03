<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
if($mode == 'delmycoupon'){

	$sql = "delete from wiz_mycoupon where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("해당 쿠폰내역을 삭제하였습니다.","member_coupon.php?id=$id&name=$name");
}
?>
<html>
<head>
<title>:: <?=$name?>(<?=$id?>) 님의 주문내역 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function deleteMycoupon(idx){
	if(confirm('해당 쿠폰을 삭제하시겠습니까?')){
		document.location = "member_coupon.php?mode=delmycoupon&id=<?=$id?>&name=<?=$name?>&idx=" + idx;
	}
}
//-->
</script>
</head>
<body>
<table width="100%"cellpadding=6 cellspacing=0>
<tr>
<td>

	<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$name?>(<?=$id?>) 님의 쿠폰내역</td>
		</tr>
	</table>
	<table width="100%"cellpadding=0 cellspacing=0>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<tr class="t_th">
		<th width="5%" height="25">번호</th>
		<th width="*">쿠폰명</th>
		<th width="25%">기간</th>
		<th width="25%">발급시간</th>
		<th width="10%">사용여부</th>
		<th width="10%">기능</th>
	</tr>
	<tr><td class="t_rd" colspan="20"></td></tr>
	<?
	$sql = "select wc.idx from wiz_mycoupon wc, wiz_member wm where wc.memid='$id' and wc.memid = wm.id";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	$rows = 12;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;
	$no = $total-$start;

	$sql = "select wc.idx, wc.coupon_name, wc.wdate, wc.coupon_use, wc.coupon_sdate, wc.coupon_edate, wm.id, wm.name from wiz_mycoupon wc, wiz_member wm where wc.memid='$id' and wc.memid = wm.id order by wc.wdate desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while(($row = mysqli_fetch_array($result)) && $rows){
	?>
	<tr bgcolor=ffffff align=center>
		<td height="30"><?=$no?></td>
		<td><?=$row[coupon_name]?></td>
		<td><?=$row[coupon_sdate]?>~<?=$row[coupon_edate]?></td>
		<td><?=$row['wdate']?></td>
		<td><?=$row[coupon_use]?></td>
		<td><button type="button" onClick="deleteMycoupon('<?=$row['idx']?>')">삭제</button></td>
	</tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
		$no--;
		$rows--;
	}
	if($total <= 0){
	?>
	  <tr bgcolor=ffffff align=center><td height="35" colspan="7">발급내역이 없습니다.</td></tr>
	  <tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
  <tr><td><? print_pagelist($page, $lists, $page_count, "&id=$id&name=$name"); ?></td></tr>
</table>

</td>
</tr>
</table>
</body>
</html>