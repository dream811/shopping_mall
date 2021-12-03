<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc"; 		// 운영 정보
include "../inc/util.inc";		      // 유틸lib

if($cancel == "true"){
	
	$sql = "update wiz_order set cancelmsg='$cancelmsg', status='RD' where orderid='$orderid'";
	mysqli_query($connect, $sql);
	
	echo "<script>alert('취소요청이 정상적으로 처리되었습니다.');self.close();opener.document.location.reload();</script>";
	exit;
	
}
?>
<html>
<head>
<title>:: 주문취소 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/css/reset.css">
<link rel="stylesheet" type="text/css" href="/css/common.css">
<link rel="stylesheet" type="text/css" href="/css/style.css">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.cancelmsg.value == ""){
		alert("취소사유를 작성해주세요");
		frm.cancelmsg.focus();
		return false;
	}
}
//-->
</script>
</head>
<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:6px solid #979797;">
  <tr>
    <td width="351" height="54" style="padding-left:37px; vertical-align:middle;"><img src="/images/shop/order_cancel.gif"></td>
    <td width="60" style="vertical-align:middle;"><a href="javascript:window.close();"><img src="/images/member/id_check_close.gif" width="21" height="21" border="0"></a></td>    
  </tr>
  <tr><td colspan="2" height="1" bgcolor="#d2d2d2"></td></tr>
  <tr>
    <td colspan="2" align="center" valign="top" style="padding-top:30px">
    	
    	<table border=0 cellpadding=0 cellspacing=0  width="90%" class="AW_common_table">
			<form name="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
	    <input type="hidden" name="cancel" value="true">
	    <input type="hidden" name="orderid" value="<?=$orderid?>">
			<tr>
				<th class="tit" height=30 width="30%">주문번호</th>
				<td class="val" width="70%" style="text-align:left; padding-left:16px;"><?=$orderid?></td>
			</tr>
			<tr>
				<th class="tit">취소사유</th>
				<td class="val" style="padding:2px"><textarea rows="6" cols="10" name="cancelmsg" class="input" style="width:90%; margin:0 auto; resize:none;"></textarea></td>
			</tr>
			<tr>
				<td colspan=2 align=center height="50">
					<div class="AW_btn_area">
						<button type="submit" class="submit_btn" style="width:110px; height:40px; line-height:38px;">확인</button>
						<button type="button" class="cancle_btn" onclick="self.close();" style="width:110px; height:40px; line-height:38px;">취소</button>
					</div>
<!--					<input type="image" src="/images/shop/btn_confirm.gif"> <img src="/images/shop/btn_cancel.gif" onClick="self.close()" style="cursor:hand">-->
				</td>
			</tr>
			</form>
		</table>
			
    </td>
  </tr>
</table>
</body>
</html>