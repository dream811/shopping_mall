<?
$sub_tit="장바구니";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<? include "basket_list.inc"; ?>

<script language="JavaScript">
<!--
function goOrder(){
<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else echo "goURL('order_form.php');";
?>
}

function printEstimate(){
	var uri = "/shop/print_estimate.php";
	window.open(uri, "printEstimate", "width=667,height=600,scrollbars=yes, top=30, left=50");
}
-->
</script>


<div class="wish_btn" style="margin:20px auto;">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="70" style="text-align:left;"><input name="" type="button" class="btn_small" value="전체삭제" onClick="goURL('/shop/prd_save.php?mode=delall')" /></td>
			<td width="70" style="padding-left:2px;"><input name="" type="button" class="btn_small" value="선택삭제" onClick="prdDelete()" /></td>
		</tr>
	</table>
</div>
<div class="gry_bar"></div>
<div class="cart_total">
	<h3>결제금액 안내</h3>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th align="left">상품가격</th>
		<td align="right"><?=number_format($prd_price)?>원</td>
	</tr>
	<tr>
		<th align="left">배송비</th>
		<td align="right"><?=number_format($deliver_price)?>원</td>
	</tr>
	<? if($discount_price > 0) { ?>
	<tr>
		<th align="left">회원할인</th>
		<td align="right"><?=number_format($discount_price)?>원</td>
	</tr>
	<? } ?>
	<tr>
		<th align="left">주문총액</th>
		<td align="right" class="total_price"><?=number_format($total_price)?>원</td>
	</tr>
	</table>
</div>

<div class="gry_bar"></div>
<div class="button_common">
	<ul class="cart_del">
		<li>배송정보 : <?=$deliver_msg?></li>
		<li>지역별/상품 개별 배송정책에 따라 변동될 수 있습니다.</li>
	</ul>
	<button type="button" onClick="goOrder();">주문하기</button>
</div>

<? include "../inc/footer.php" ?>
