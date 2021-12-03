<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
// 메일스킨
$sql = "select * from wiz_mailsms where code = 'mem_notice'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
?>
<html>
<head>
<title>:: 메일발송 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
// 주문상세내역 보기
function inputCheck(frm){

	if(frm.seluser.value == ""){
		alert("받는이가 없습니다");
		frm.seluser.focus();
		return false;
	}

	if(frm.subject.value == ""){
		alert("제목을 입력하세요");
		frm.subject.focus();
		return false;
	}

	try{ content.outputBodyHTML(); } catch(e){ }
	if(frm.content.value == ""){
		alert("내용을 입력하세요.");
		return false;
	}
}
//-->
</script>
</head>
<body style="padding:10px; ">
<table width="100%" border="0" cellpadding=6 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 메일발송</td>
  </tr>
</table>
<form name="frm" action="member_save.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="sendemail">
<input type="hidden" name="se_name" value="<?=$shop_info->shop_name?>">
<input type="hidden" name="se_email" value="<?=$shop_info->shop_email?>">
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td height="30" width="20%" align=center class="t_name">보내는이</td>
    <td class="t_value"><?=$shop_info->shop_name?>(<?=$shop_info->shop_email?>)</td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">받는이</td>
    <td class="t_value">
      <textarea rows="3" cols="50" name="seluser" class="textarea" style="width:90%"><?=$seluser?></textarea>
      <table><tr><td>형식) 홍길동:test@test.com,</td></tr></table>
    </td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">제목</td>
    <td class="t_value"><input type="text" name="subject" size="55" class="input" style="width:90%"></td>
  </tr>
  <tr>
    <td colspan="2" class="t_value">
    <?
    $edit_content = $row->email_msg;
    $edit_content = info_replace($shop_info, $re_info, $order_info, $edit_content);
    include "../webedit/WIZEditor.html";
    ?>
    </td>
  </tr>
</table>


<div class="AW-btn-wrap">
    <button type="submit" class="on">발송</button>
    <a onClick="self.close();">닫기</a>
</div><!-- .AW-btn-wrap -->

</form>

</td>
</tr>
</table>
</body>
</html>