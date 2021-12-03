<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<html>
<head>
<title>:: SMS발송 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
// 주문상세내역 보기
function inputCheck(frm){

	if(frm.seluser.value == ""){
		alert("받는이 휴대폰번호를 입력하세요");
		frm.seluser.focus();
		return false;
	}

	if(frm.content.value == ""){
		alert("내용을 입력하세요");
		frm.content.focus();
		return false;
	}
}

function calByte(aquery){

	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}

		frm.sms_byte.value = tcount+"/80 bytes";

		if(tcount > 80) {
			alert("메시지내용은 80 바이트 이상 전송할 수 없습니다.");

			cutText(frm.content.value);

			return;
		}
	}
	if ( temp == 0 ) {

		frm.sms_byte.value = "0/80 bytes";

	}
}

function cutText(aquery) {

	var tmpStr;
	var temp=0;
	var onechar;
	var tcount = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(t=0; t<temp; t++){
		onechar = tmpStr.charAt(t);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}
		if(tcount > 80) {
			tmpStr = tmpStr.substring(0, t);
			break;
		}
	}

	document.frm.content.value = tmpStr;

	calByte(tmpStr);
}

function checkSmsmsg(){

	var tmpStr = document.frm.content.value;

	calByte(tmpStr);

}


//-->
</script>
</head>
<body style="padding:10px;">
<table width="100%" border="0" cellpadding=6 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> SMS발송</td>
  </tr>
</table>

<form name="frm" action="order_save.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="sendsms">
<input type="hidden" name="se_name" value="<?=$shop_info->shop_name?>">
<input type="hidden" name="se_tel" value="<?=$shop_info->shop_hand?>">

<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td height="30" width="30%" align=center class="t_name">보내는이</td>
    <td class="t_value"><?=$shop_info->shop_name?>(<?=$shop_info->shop_hand?>)</td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">받는휴대폰</td>
    <td class="t_value">
      <textarea name="seluser" rows="2" cols="20" class="textarea" style="width:100%"><?=$seluser?></textarea>
      <table><tr><td>형식) 011-123-4567,016-123-4567</td></tr></table>
    </td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">내용</td>
    <td align="center" class="t_value">
    <textarea name="content" rows="8" class="textarea" style="width:100%" onKeyDown="checkSmsmsg();"></textarea>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding=2 cellspacing=1>
<tr><td align="right"><input type="text" name="sms_byte" size="11" style="height:14px; border: 1px solid #91FBFF; ; font-size:8pt; font-family:돋움; background-color:#91FBFF" value="0/80 bytes" onfocus="this.blur()"></td></tr>
</table>



</td>
</tr>
</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">발송</button>
    <a onclick="self.close();">닫기</a>
</div>




</form>

</body>
</html>