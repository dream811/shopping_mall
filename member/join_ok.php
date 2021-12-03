<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$page_type = "join";
$now_position = "<a href=/>Home</a> &gt; 회원가입 &gt; <strong>가입완료</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

?>
<div class="join_ok_area">
	<h3>회원으로 가입해주셔서 감사합니다.</h3>
	<p>입력하신 고객님의 정보는 개인정보취급방침에 따라 보호됩니다.</p>
	
	<a href="/">메인페이지 바로가기</a>
</div>
<?

include "../inc/footer.inc"; 				// 하단디자인

?>