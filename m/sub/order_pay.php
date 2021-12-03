<?
$sub_tit="주문상품결제";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>

<?php
$page_type = "orderform";
include "../../inc/page_info.inc"; 		// 페이지 정보
include "../../inc/mem_info.inc"; 		// 회원 정보

include "basket_order.inc";

include Inc_payment($pay_method,$oper_info->pay_agent);
?>

<? include "../inc/footer.php" ?>
