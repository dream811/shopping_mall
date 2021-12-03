<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보
include "../inc/shop_info.inc"; 		// 상점 정보

$now_position = "<a href=/>Home</a> &gt; 장바구니";
$page_type = "basket";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

?>

<?
$tab1="on";
include "prd_basket_step.php";
?>
<div style="margin:15px 0 0;"><? include "basket_list.inc"; ?></div>


<div class="AW_member_btn" style="text-align:center; margin:30px 0 70px;">
	<a href="javascript:goallOrder();" class="on">전체 주문</a>
	<a href="javascript:goOrder();" class="on">선택 주문</a>
	<a href="javascript:goDellBasketTemp();">선택 삭제</a>
	<a href="prd_save.php?mode=delall">비우기</a>
    <a href="javascript:history.go(-1);">쇼핑 계속하기</a>
    <? if(!strcmp($shop_info->estimate_use, "Y")) { ?>
    <a href="javascript:printEstimate();">견적서 출력</a>
    <? } ?>
</div><!-- .AW_member_btn -->

<!--
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td><a href="javascript:goOrder();"><img src="/images/shop/but_cart_buy.gif" border=0></a></td>
<td><a href="javascript:history.go(-1);"><img src="/images/shop/but_cart_prew.gif" border=0></a></td>
<td><a href="prd_save.php?mode=delall"><img src="/images/shop/but_cart_delete.gif" border=0></a></td>
<td><a href="javascript:history.go(-1);"><img src="/images/shop/but_cart_shop.gif" border=0></a></td>
<? if(!strcmp($shop_info->estimate_use, "Y")) { ?>
<td><a href="javascript:printEstimate();"><img src="/images/shop/but_cart_print.gif" border=0></a></td>
<? } ?>
</tr>
</table>
-->


<!-- 장바구니 안내메세지 -->
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
<td><?=$page_info->content?></td>
</tr>
</table>




<?
include "../inc/footer.inc"; 		// 하단디자인
?>