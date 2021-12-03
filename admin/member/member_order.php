<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<html>
<head>
<title>:: <?=$name?>(<?=$id?>) 님의 주문내역 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
   var url = "/shop/order_view.php?orderid=" + orderid;
   window.open(url, "orderView", "height=640, width=671, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
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
    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$name?>(<?=$id?>) 님의 주문내역</td>
  </tr>
</table>
<table width="100%"cellpadding=0 cellspacing=0>
	<tr><td class="t_rd" colspan="20"></td></tr>
  <tr class="t_th">
    <th>주문일자</th>
    <th>주문번호</th>
    <th>결제금액</th>
    <th>결제방법</th>
    <th>배송상태</th>
    <th>운송장번호</th>
    <th>상세보기</th>
  </tr>
  <tr><td class="t_rd" colspan="20"></td></tr>
	<?
		$sql = "select * from wiz_order where send_id = '$id' and status != '' order by order_date desc";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = mysqli_num_rows($result);
		
		$rows = 12;
		$lists = 5;
		if(!$page) $page = 1;
		$page_count = ceil($total/$rows);
		$start = ($page-1)*$rows;
		if($start>1) mysql_data_seek($result,$start);
		
		while(($row = mysqli_fetch_object($result)) && $rows){
	?>
  <tr bgcolor=ffffff align=center>
    <td height="30"><?=substr($row->order_date,0,10)?></td>
    <td><?=$row->orderid?></td>
    <td><?=number_format($row->total_price)?> 원</td>
    <td><?=pay_method($row->pay_method)?></td>
    <td><?=order_status($row->status)?></td>
    <td><?=$row->deliver_num?></td>
    <td><button  class="AW-btn-s modify" value="보기" onClick="orderView('<?=$row->orderid?>');">보기</a></td>
  </tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
		$rows--;
	}
	if($total <= 0){
	?>
	  <tr bgcolor=ffffff align=center><td height="35" colspan="7">구매내역이 없습니다.</td></tr>
	  <tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="5"></td></tr>
  <tr><td><? print_pagelist($page, $lists, $page_count, "&id=$id"); ?></td></tr>
</table>

</td>
</tr>
</table>
</body>
</html>