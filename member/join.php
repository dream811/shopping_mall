<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/shop_info.inc"; 		// 상점 정보

$now_position = "<a href=/>Home</a> &gt; 회원가입 &gt; <strong>가입약관</strong>";
$page_type = "join";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

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

<div class="AW_ttl clearfix">
	<h2>회원가입 약관</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>회원가입 약관</span>
	</div>
</div>
<form name="frm">

<div class="AW_join_area">
	<h3 class="mem_ttl">회원가입 약관</h3>
	<textarea name="textarea" rows="12" style="width:100%; height:180px;" class="input" readonly><?=$page_info->content?></textarea>
	<div class="agr clearfix">
		<input type="checkbox" name="agree" id="join_agr1"><label for="join_agr1">회원가입약관에 동의합니다.</label>
	</div>
</div>
<div class="AW_join_area">
	<h3 class="mem_ttl">개인정보 취급방침</h3>
	<textarea name="textarea" rows="12" style="width:100%; height:180px;" class="input" readonly><?=$page_info->content2?></textarea>
	<div class="agr clearfix">
		<input type="checkbox" name="agree2" id="join_agr2"><label for="join_agr2">개인정보취급방침에 동의합니다.</label>
	</div>
</div>			

</form>
<? if(!strcmp($shop_info->namecheck_use, "Y")) { ?> 
<table width="97%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
	<td align="center" style="padding:10px; border:3px solid #eaeaea;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="8" background="../images/member/03_left_bg.gif"></td>
			<td align="center">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="left" style="background:url('../images/member/agree_dot.gif') top left repeat-x;"><img src="../images/member/agree_txt_03.gif" ></td>
					</tr>
				</table>
				<table width="300" border="0" cellspacing="6" cellpadding="0">
				<form name="nameCheck" action="join_form.php" method="post">
							<tr>
								<td valign="bottom" class="text_nor">이름</td>
								<td><input type="text" name="name" class="input" size="30"></td>
							</tr>
							<tr>
								<td valign="bottom" class="text_nor">주민번호</td>
								<td><input type="text" size="13" name="resno1" class="input" onKeyup="jfocus(this.form);"> - <input type="password" size="13" name="resno2" class="input"></td>
							</tr>
						</form>
					  </table>
			</td>
			<td width="8" background="../images/member/03_right_bg.gif"></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>

<? } ?>
<div class="AW_btn_area">
	<button type="button" class="submit_btn" onClick="checkAgree();">동의함</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">동의안함</button>
</div>
<?
include "../inc/footer.inc"; 		// 하단디자인
?>