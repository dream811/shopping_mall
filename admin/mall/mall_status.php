<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc" ?>
<html>
<head>
<title>:: 업체상태 일괄처리 ::</title>
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
<body onLoad="resizeTo(400,300);" style="padding:10px;">
<table width="100%" cellpadding=0 cellspacing=10><tr><td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 업체상태 일괄처리</td>
  </tr>
</table>
<form action="mall_save.php" name="frm" onsubmit="return inputCheck(this)">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="batchStatus">
<input type="hidden" name="selvalue" value="<?=$selvalue?>">
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
  <tr align=center>
    <td height="23" class="t_name" width=100><b>승인여부</b></td>
    <td class="t_value">
	    <select name="chg_status" style="width:90">
		     <option value="Y"> 승인 </option>
		     <option value="N"> 미승인 </option>
	    </select>
    </td>
  </tr>
</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
</div><!-- .AW-btn-wrap -->


</form>
</td></tr></table>
</body>