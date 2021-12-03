<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   // 유틸 라이브러리

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이쇼핑 &gt; <strong>회원탈퇴</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/mem_info.inc"; 		// 회원 정보

?>
<script language="JavaScript">
<!--
function inputCheck(frm){

	var reason = 0;
	if(frm.out_reason.checked == true) reason++;
	if(frm.out_reason2.checked == true) reason++;
	if(frm.out_reason3.checked == true) reason++;
	if(frm.out_reason4.checked == true) reason++;
	if(frm.out_reason5.checked == true) reason++;
	if(frm.out_reason6.checked == true) reason++;
	if(frm.out_reason7.checked == true) reason++;
	if(frm.out_reason8.checked == true) reason++;
	
	if(reason <= 0){
		alert("미흡했던 점을 선택해주세요");
		frm.out_reason.focus();
		return false;
	}
	if(frm.message.value == ""){
		alert("진심어린 충고 부탁드립니다.");
		frm.message.focus();
		return false;
	}
	if(!confirm("정말 탈퇴하시겠습니까?")) return false;
}
//-->
</script>
<div class="AW_ttl clearfix">
	<h2>회원탈퇴 안내</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>회원탈퇴 안내</span>
	</div>
</div>
<? include $_SERVER['DOCUMENT_ROOT'].'/inc/lnk_nav.php'; // 상단메뉴 ?>

<form name="frm" action="my_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="mode" value="my_out">
       
<div class="my_out_area">
	<p class="txt">고객님께서 사이트 이용 시 불편하셨던 점이나 불만사항을 알려주시면 적극 반영해서 고객님의 불편함을 해결해 드리도록 노력하겠습니다.<br />아울러 회원 탈퇴 시의 아래 사항을 숙지하시기 바랍니다.</p>
	<ul class="notice_list">
		<li>&#42; 회원탈퇴 시 고객님의 정보는 상품 반품 및 A/S를 위해 전자상거래 등에서의 소비자 보호에 관한 법률에 의거한 고객정보정책에 따라 관리됩니다.</li>
		<li>&#42; 탈퇴 시 고객님이 보유하셨던 적립금은 삭제됩니다.</li>
	</ul>
	
	<table width="100%" class="AW_member_table">
		<tr>
			<th width="18%" class="tit">불편하셨던 점</th>
			<td class="val">
				<input name="out_reason" value="고객서비스 불만" type="checkbox" id="out1"><label for="out1">고객서비스 불만</label>
				<input name="out_reason2" value="배송불만" type="checkbox" id="out2"><label for="out2">배송불만 </label>
				<input name="out_reason3" value="교환/환불/반품 불만" type="checkbox" id="out3"><label for="out3">교환/환불/반품 불만</label>
				<input name="out_reason4" value="방문 빈도가 낮음" type="checkbox" id="out4"><label for="out4">방문 빈도가 낮음 </label>
				<br>
				<input name="out_reason5" value="상품가격 불만" type="checkbox" id="out5"><label for="out5">상품가격 불만</label>
				<input name="out_reason6" value="개인 정보유출 우려" type="checkbox" id="out6"><label for="out6">개인 정보유출 우려 </label>
				<input name="out_reason7" value="쇼핑몰의 신뢰도 불만" type="checkbox" id="out7"><label for="out7">쇼핑몰의 신뢰도 불만</label>
				<input name="out_reason8" value="쇼핑 기능 불만" type="checkbox" id="out8"><label for="out8">쇼핑 기능 불만</label>
			</td>
		</tr>
		<tr>
			<th width="18%" class="tit">남기실 말씀</th>
			<td class="val">
				<textarea name="message" class="input" rows="8" style="width:93%; resize:none;"></textarea>
			</td>
		</tr>
	</table>
	<div class="AW_btn_area">
		<button type="submit" class="submit_btn">회원탈퇴</button>
		<button type="button" class="cancle_btn" onclick="history.go(-1);">취소</button>
	</div>
</div>
</form>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>