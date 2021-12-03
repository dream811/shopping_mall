<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc"; 		// 운영 정보
include "../inc/mem_info.inc"; 			// 회원 정보
include "../inc/util.inc";		      // 유틸lib
include "../inc/shop_info.inc"; 		// 상점 정보

$now_position = "<a href=/>Home</a> &gt; 주문하기 &gt; 결제하기";
$page_type = "orderform";
include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

?>
<?
$tab3="on";
include "prd_basket_step.php";
?>
<div style="margin:15px 0 0;">
	<? include "basket_order.inc"; ?>

	<? include Inc_payment($pay_method,$oper_info->pay_agent); ?>
</div>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>