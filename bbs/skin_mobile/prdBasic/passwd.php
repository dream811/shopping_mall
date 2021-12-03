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

<form action="<?=$PHP_SELF?>" method="post" onSubmit="return bbsCheck(this)">
<input type="hidden" name="ptype" value="<?=$ptype?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="cidx" value="<?=$cidx?>"> 
<input type="hidden" name="idx" value="<?=$idx?>"> 
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="category" value="<?=$category?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<div id="M_pw_wrap">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
        	<td align="center" class="mPw_txt"><?=$mode_msg?></td>
        </tr>
		<tr>
        	<td align="center" class="mPw_input"><?=$input_passwd?></td>
        </tr>
    </table>
</div>
		<div id="btnArea"><?=$confirm_btn?> <?=$cancel_btn?></div>
</form>
