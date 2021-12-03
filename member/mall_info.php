<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   // 유틸 라이브러리

$now_position = "<a href=/>Home</a> &gt; <strong>입점안내</strong>";
$page_type = "mallinfo";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
?>

<script Language="Javascript">
<!--

function goMall() {
	<? if(!empty($wiz_mall['id'])) { ?>
	if(confirm('이미 입점업체로 로그인한 상태입니다.\n입점업체 관리자 페이지로 이동하시겠습니까?')) {
		document.location = "/mall/";
	}
	<? } else {	?>
		document.location = "mall_join.php";
	<? } ?>
}
//-->
</script>
<div class="AW_ttl clearfix">
	<h2>입점안내</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>입점안내</span>
	</div>
</div>
<div class="page_area">
	<?=$page_info->content?>
	<a href="javascript:goMall()" class="go_mall">입점신청</a>
</div>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>