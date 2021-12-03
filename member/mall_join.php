<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$now_position = "<a href=/>Home</a> &gt; 입점신청 &gt; <strong>가입약관</strong>";

$page_type = "malljoin";
include "../inc/page_info.inc"; 		// 이용약관
include "../inc/header.inc"; 				// 상단디자인


?>

<script Language="Javascript">
<!--
function checkAgree(){
	if(document.frm.agree.checked != true){
		alert("이용약관에 동의하셔야 가입할 수 있습니다.");
		return;
	}
	document.location = "mall_form.php";

}
//-->
</script>
<div class="AW_ttl clearfix">
	<h2>입점업체 가입 약관</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>입점업체 가입 약관</span>
	</div>
</div>
<form name="frm">

<div class="AW_join_area">
	<h3 class="mem_ttl">입점업체 약관</h3>
	<textarea name="textarea" rows="20" style="width:100%; height:180px;" class="input" readonly><?=$page_info->content?></textarea>
	<div class="agr clearfix">
		<input type="checkbox" name="agree" id="join_agr1"><label for="join_agr1">입점업체가입 약관에 동의합니다.</label>
	</div>
</div>

</form>

<div class="AW_btn_area">
	<button type="button" class="submit_btn" onClick="checkAgree();">동의함</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">동의안함</button>
</div>
<?
include "../inc/footer.inc"; 		// 하단디자인
?>