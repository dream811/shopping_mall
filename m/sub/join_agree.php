<?
$sub_tit="회원가입";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>

<?php
$page_type = "join";
include "../../inc/page_info.inc"; 		// 페이지 정보

$page_info->content		= cut_str(nl2br($page_info->content), 250);
$page_info->content2	= cut_str(nl2br($page_info->content2), 250);
?>

<script language="javascript">
<!--
function checkAgree(){

	if(document.frm.agree.checked != true){
		alert("이용약관에 동의하셔야 가입할 수 있습니다.");
		return;
	}
	if(document.frm.agree2.checked != true){
		alert("개인정보보호정책에 동의하셔야 가입할 수 있습니다.");
		return;
	}

	<? if($shop_info->namecheck_use == "Y"){ ?>

		var frm = document.nameCheck;
		var name = frm.name.value;
		var resno1 = frm.resno1.value;
		var resno2 = frm.resno2.value;

		if(name == ""){
			alert("이름을 입력하세요");
			frm.name.focus();
			return;
		}
		if(resno1 == ""){
			alert("주민번호를 입력하세요");
			frm.resno1.focus();
			return;
		}
		if(resno2 == ""){
			alert("주민번호를 입력하세요");
			frm.resno2.focus();
			return;
		}

		document.nameIframe.location = "/member/name_check.php?name=" + name + "&resno1=" + resno1 + "&resno2=" + resno2;

	<? } else { ?>

		document.location = "join_form.php";

	<? } ?>
}

// 주민번호 자동포커스
function jfocus(frm){
	if(frm.resno2 != null){
		var str = frm.resno1.value.length;
		if(str == 6) frm.resno2.focus();
	}
}
-->
</script>

<div class="gry_bar"></div>
<form name="frm">

<div style="padding:20px 10px 5px 10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><b>이용약관</b></td>
    <td align="right"><a href="agreement.php" target="_blank"><img src="../img/sub/btn_join_agree_view.gif" /></a></td>
  </tr>
</table>
</div>
<div style="margin:0 10px 10px 10px; background:#f9f9f9; border:1px solid #d5d5d5; padding:14px; font-size:13px; color:#555;"><?=$page_info->content?></div>

<div style="padding:20px 10px 5px 10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><b>개인정보 수집 이용안내</b></td>
    <td align="right"><a href="privacy.php" target="_blank"><img src="../img/sub/btn_join_agree_view.gif" /></a></td>
  </tr>
</table>
</div>
<div style="margin:0 10px 10px 10px; background:#f9f9f9; border:1px solid #d5d5d5; padding:14px; font-size:13px; color:#555;"><?=$page_info->content2?></div>

<div style="padding:10px;" class="member_area">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5"><input name="agree" type="checkbox" value="" id="agr1" /></td>
    <td style="font-size:11px;"><label for="agr1">위의 이용약관에 동의합니다.</label></td>
  </tr>
  <tr>
    <td width="5"><input name="agree2" type="checkbox" value="" id="agr2" /></td>
    <td style="font-size:11px;"><label for="agr2">개인정보 수집 및 이용안내에 동의합니다.</label></td>
  </tr>
</table>
</div>

</form>

<div class="button_common">
	<button type="button" onClick="checkAgree();">다음</button>
</div>


<? include "../inc/footer.php" ?>
