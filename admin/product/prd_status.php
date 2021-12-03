<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc" ?>
<html>
<head>
<title>:: 승인여부 일괄처리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language='javascript'>
<!--
function inputCheck(frm) {

}
-->
</script>
</head>
<body onLoad="resizeTo(250,190);">
<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품이동</td>
        </tr>
      </table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<form action="prd_save.php" name="frm" onsubmit="return inputCheck(this)">
			<input type="hidden" name="tmp">
			<input type="hidden" name="mode" value="batchStatus">
			<input type="hidden" name="selprd" value="<?=$selprd?>">
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="40%" class="t_name">승인여부</td>
			          <td width="60%" class="t_value">
							    <select name="status" style="width:90">
							    <option value="Y">승인</option>
							    <option value="N">미승인</option>
							    </select>
			          </td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center" style="padding:7px">
						<input type="submit" value=" 저장 " class="btn_m">
						<input type="button" value=" 닫기 " class="btn_m" onClick="self.close();">
					</td>
				</tr>
			</form>
			</table>
		</td>
	</tr>
</table>
</body>
</html>