<? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?>

<form name="comment" action="/bbs/save.php" method="post" onSubmit="return commentCheck(this);">
<input type="hidden" name="mode" value="comment">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
<input type="hidden" name="mallid" value="<?=$mallid?>">	
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_comment_input">
	<tr>
		<td colspan="2" style="padding:16px 40px;">
			이름 <input type="text" name="name" value="<?=$writer?>" class="input" size="10" align="absmiddle" />&#160;
			비밀번호 <input type="password" name="passwd" onClick="memberCheck();" class="input" size="10" align="absmiddle" />
		</td>
	</tr>
	<tr>
		<td style="padding:0 10px 0 40px"><textarea name="content" onClick="memberCheck();" rows="4" class="input" style="width:100%"></textarea></td>
		<td width="120" style="padding-right:40px;">
			<button type="submit" class="submit_btn">등록하기</button>
		</td>
	</tr>
	<?=$hide_spam_check_start?>
	<tr>
		<td align="left" colspan="2" style="padding:12px 40px;">
			<?=$spam_check?>
		</td>
	</tr>
	<?=$hide_spam_check_end?>
</table>
</form>