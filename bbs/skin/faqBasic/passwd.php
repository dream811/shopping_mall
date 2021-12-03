<script language="javascript">
<!--
function bbsCheck(frm){
	if(frm.passwd != null && frm.passwd.value == ""){
		alert("비밀번호를 입력하세요");
		frm.passwd.focus();
		return false;
	}
}
-->
</script>
<table width="450" align="center" border="0" cellpadding="0" cellspacing="0">
<form action="<?=$act_url?>" method="post" onSubmit="return bbsCheck(this)">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="bbs_idx" value="<?=$bbs_idx?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="category" value="<?=$category?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<input type="hidden" name="mallid" value="<?=$mallid?>">
	<tr><td height="27"></td></tr>
  <tr>
    <td align="center" style="border:2px solid #eaeaea; padding:10px;">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center"><img src="<?=$skin_dir?>/image/icon_pw.gif" width="92" height="63" border="0" /></td>
            <td width="1" bgcolor="#e6e6e6"></td>
            <td align="center">
            	<table border="0" cellpadding="0" cellspacing="0">
            		<tr><td colspan="2"><?=$mode_msg?></td></tr>
                <tr>
                  <td style="padding-left:10px;padding-right:10px;"><?=$input_passwd?></td>
                  <td><?=$confirm_btn?>&nbsp;<?=$cancel_btn?></td>
                </tr>
              </table>
    				</td>
          </tr>
      </table>
    </td>
  </tr>
</form>
</table>
