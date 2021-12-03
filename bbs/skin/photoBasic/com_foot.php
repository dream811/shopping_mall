<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>
  <tr><td height="5"></td></tr>
  <tr>
    <td colspan="4">
			<? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?>
		</td>
  </tr>
  <tr>
    <td align="left" colspan="4"><!-- 코멘트 -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form name="comment" action="/bbs/save.php" method="post" onSubmit="return commentCheck(this);">
			<input type="hidden" name="mode" value="comment">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="idx" value="<?=$idx?>">
			<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
			<input type="hidden" name="mallid" value="<?=$mallid?>">
        <tr>
          <td colspan="2">
          	<table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="padding-right:5px;">이름</td>
                  <td style="padding-right:20px;"><input type="text" name="name" value="<?=$writer?>" class="input" size="10" align="absmiddle" /></td>
                  <td style="padding-right:5px;">비밀번호</td>
                  <td><input type="password" name="passwd" onClick="memberCheck();" class="input" size="10" align="absmiddle" /></td>
                </tr>
            </table>
           </td>
        </tr>
        <tr>
          <td style="padding:5px 0;"><textarea name="content" onClick="memberCheck();" rows="4" class="input" style="width:100%"></textarea></td>
          <td width="5%" align="right" style="padding-left:10px;">
          	<input type="image" src="<?=$skin_dir?>/image/btn_comm_ok.gif" width="75" height="56" border="0" align="absmiddle" />
          </td>
        </tr>
        <?=$hide_spam_check_start?>
        <tr>
          <td align="left"><?=$spam_check?></td>
        </tr>
        <?=$hide_spam_check_end?>
    	</form>
    	</table>
  </td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>
  <tr><td height="10"></td></tr>
</table>