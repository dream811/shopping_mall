<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<html>
<head>
<title>:: <?=$name?>(<?=$id?>) 님의 적립금내역 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../js/util_lib.js"></script>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
   var url = "/shop/order_view.php?orderid=" + orderid;
   window.open(url, "orderView", "height=640, width=671, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}

function inputCheck(frm){
	
	if(frm.reserve_gubun.value == ""){
		alert("적립금 +,- 를 선택하세요.");
		frm.reserve_gubun.focus();
		return false;
	}
	if(frm.reserve.value == ""){
		alert("적립금을 입력하세요.");
		frm.reserve.focus();
		return false;
	}else{
		if(!Check_Num(frm.reserve.value)){
			alert("적립금은 숫자이어야 합니다.");
			frm.reserve.value = "";
			frm.reserve.select();
			frm.reserve.focus();
			return false;
		}
	}
	if(frm.reservemsg.value == "" || frm.reservemsg.value == "적립내용"){
		alert("적립내용을 입력하세요.");
		frm.reservemsg.value = "";
		frm.reservemsg.focus();
		return false;
	}
}

function inputEmpty(obj,msg){
	if(obj.value == msg){
		obj.value = "";
	}
}

function deleteReserve(idx,memid){
	if(confirm('해당 적립내역을 삭제하시겠습니까?')){
		document.location = "member_save.php?mode=delreserve&idx=" + idx + "&memid=" + memid;
	}
}
//-->
</script>
</head>
<body style="padding:10px;">
<table width="100%"cellpadding=6 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$name?>(<?=$id?>) 님의 적립금내역</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><td class="t_rd" colspan="20"></td></tr>
  <tr class="t_th">
    <th width="25%">적립일자</th>
    <th>적립내역</th>
    <th width="15%">금액</th>
    <th width="15%">상세보기<th/td>
  </tr>
  <tr><td class="t_rd" colspan="20"></td></tr>
	<?
	$sql = "select sum(reserve) as total_reserve from wiz_reserve where memid = '$id'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);
	$total_reserve = $row->total_reserve;
	
	
	$sql = "select * from wiz_reserve where memid = '$id' order by wdate desc";
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
	<td height="30"><?=$row->wdate?></td>
	<td><?=$row->reservemsg?><? if(!empty($row->orderid)) echo "<a href=javascript:orderView('$row->orderid');>($row->orderid)</a>"; ?></td>
	<td><?=number_format($row->reserve)?>원</td>
	<td>
	  <? if(!empty($row->orderid)) echo "<a href=javascript:orderView('$row->orderid');><img src='../image/btn_view_s.gif' border='0'></a>"; ?>
	  <a href="javascript:deleteReserve('<?=$row->idx?>','<?=$row->memid?>');"><img src='../image/btn_delete_s.gif' border='0'></a>
	</td>
  </tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
		$rows--;
	}
	if($total <= 0){
	?>
  <tr bgcolor=ffffff align=center><td height="35" colspan="4">적립내역이 없습니다.</td></tr>
  <tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td height="30">
  	<table width="100%">
  		<tr>
  			<td width="33%"></td>
  			<td width="33%"><? print_pagelist($page, $lists, $page_count, "&id=$id"); ?></td>
  			<td width="33%" align="right"><font color="red"><b>총 적립금 : <?=number_format($total_reserve)?>원</b></font>&nbsp; &nbsp; </td>
  		</tr>
  	</table>
  </td>
</tr>
<tr>
<td align="right">

<table border="0" cellpadding="0" cellspacing="0" class="t_style">
<form name="frm" action="member_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="mode" value="reserve">
<input type="hidden" name="memid" value="<?=$id?>">
  <tr>
    <td align="right" class="t_value">
    <select name="reserve_gubun" style="padding:0 0 0 10px; width:50px">
    <option value="+"> + 
    <option value="-"> - 
    </select>
    <input type="text" name="reserve" value="적립금" size="12" class="input" onClick="inputEmpty(this,'적립금');">
    <input type="text" name="reservemsg" value="적립내용" size="35" class="input" onClick="inputEmpty(this,'적립내용');">
    <input type="submit" value="확인" />
    </td>
  </tr>
</form>
</table>

</td>
</tr>
</table>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
	<tr>
  	<td align="right" height="25"><strong>* 상품구입 적립금은 주문관리에서 "배송완료" 처리후에 적립됩니다.</strong></td>
  </tr>
</table>

<br><br>

</td>
</tr>
</table>
</body>
</html>