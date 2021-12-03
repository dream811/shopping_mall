<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$page_type = "malljoin";
$now_position = "<a href=/>Home</a> &gt; 입점신청 &gt; <strong>신청완료</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

?>
<div class="join_ok_area">
	<h3>입점업체 가입해주셔서 감사합니다.</h3>
	<p>입점업체 관리자 페이지는 다음과 같습니다. </p>
	
	<a href="/mall/" target="_blank">입점업체 관리자 페이지 바로가기</a>
</div>
<?

include "../inc/footer.inc"; 				// 하단디자인

?>