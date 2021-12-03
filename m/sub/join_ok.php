<?
$sub_tit="회원가입";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<style>
.join_ok_note{text-align:center; margin:3.125rem 0 1.25rem; font-size:.8rem; line-height:1.4; color:#888; letter-spacing:-0.05em;}
.join_ok_note strong{display:block; font-size:1.25rem; font-weight:bold; letter-spacing:-0.07em; color:#111; margin:0 0 .4rem;}
</style>

<div class="join_ok_note">
    <strong>축하합니다. 회원가입이 완료 되셨습니다.</strong>
    모바일 샵은 개인 핸드폰 및 브라우저에 따라<br />
    서비스의 제약이 있을 수 있습니다.<br /><br />

    추가 및 기타 입력 및 수정은 PC버전 사이트의<br />
    회원정보 수정 페이지를 이용해 주세요.
</div><!-- .join_ok_note -->

<div class="button_common">
	<button type="button" onClick="location.href='/m/'">확인</button>
</div>

<? include "../inc/footer.php" ?>
