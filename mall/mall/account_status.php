<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc" ?>
<html>
<head>
<title>:: 진행상태 일괄처리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language='javascript'>
<!--
function inputCheck(frm) {

	if(!confirm("상태를 변경하시겠습니까?")) {
		return false;
	}

}
-->
</script>
</head>
<body onLoad="resizeTo(250,270);">
<table width="100%" cellpadding=0 cellspacing=10><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 진행상태 일괄처리</td>
  </tr>
</table>
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
<form action="account_save.php" name="frm" onsubmit="return inputCheck(this)">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="batchStatus">
<input type="hidden" name="selvalue" value="<?=$selvalue?>">
  <tr align=center>
    <td height="23" class="t_name" width=100><b>진행상태</b></td>
    <td class="t_value">
			<select name="chg_status" style="width:90px">
				<option value="AA" <? if(!strcmp($row[acc_status], "AA")) echo "selected"?>>정산요청</option>
			</select>
    </td>
  </tr>
</table>
<br>
<table align="center">
  <tr>
    <td><input type="image" src="../image/btn_confirm_l.gif"></td>
  </tr>
</form>
</table>
<table width="100%">
  <tr>
  	<td align="center"><br>정산대기중인 내역은<br>상태가 변경되지 않습니다.</td>
  </tr>
</table>
</td></tr></table>
</body>