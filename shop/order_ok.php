<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 					// 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보


///////////////////////////////////////////
/// PG사 결제 완료시 꼭 반환되어야할 값들//
///////////////////////////////////////////
/* $orderid : 주문번호
/* $resmsg : 오류 및 반환 메세지
/* $rescode : 성공반환 메세지
/* $pay_method : wizshop 결제종류
*/ /////////////////////////////////////////

//if($pay_method != "PB"){
//Pay_result($oper_info->pay_agent);
$presult = Pay_result($oper_info->pay_agent, $rescode);

//////// 쓰레기 장바구니 데이터 삭제 ////////////
@mysqli_query($connect, "delete from wiz_basket_tmp WHERE wdate < (now()- INTERVAL 10 DAY)");
//}
$now_position = "<a href=/>Home</a> &gt; 주문하기 &gt; 주문완료";
$page_type = "ordercom";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인

?>
<script language="JavaScript">
	<!--
	function orderPrint(orderid) {
		var url = "/shop/order_print.php?orderid=" + orderid + "&print=ok";
		window.open(url, "orderPrint", "height=650, width=736, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=0, top=0");
	}
	//
	-->
</script>

<?

// 주문정보
$sql = "SELECT * FROM wiz_order WHERE orderid = '" . $presult['orderid'] . "'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$order_info = mysqli_fetch_object($result);

//echo $orderid;

// 주문성공
if ($presult['rescode'] == "0000" && strlen($presult['rescode']) == 4) {

	// 주문완료 메일/sms발송
	include "./order_mail.inc";		// 메일발송내용

	$re_info['name'] = $order_info->send_name;
	$re_info['email'] = $order_info->send_email;
	$re_info['hphone'] = $order_info->send_hphone;

	// email, sms 발송 체크
	$sql = "update wiz_order set send_mailsms = 'Y' where orderid = '$order_info->orderid'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if ($order_info->send_mailsms != "Y") send_mailsms("order_com", $re_info, $ordmail);
	//api 로 전송
	$sql = "select  
	order_date as strTime, total_price as nTotalPrice,
	wiz_basket.prdcode as strProductID, wiz_basket.prdname as strProductName, wiz_basket.amount as nProductCnt,
	wiz_basket.supprice as nProductBasePrice, wiz_basket.prdprice as nProductSellPrice
	
	from wiz_order
	left join wiz_basket on wiz_order.orderid = wiz_basket.orderid
	left join wiz_cprelation on wiz_basket.prdcode = wiz_cprelation.prdcode
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode
	where wiz_order.orderid = '$order_info->orderid'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

?>
	<?
	$tab4 = "on";
	include "prd_basket_step.php";
	?>
	<div style="margin:30px 0">

		<? include "./order_info.inc"; ?>

		<div class="AW_btn_area">
			<a href="/member/my_order.php" class="submit_btn">주문 &#183; 배송조회</a>
			<a href="javascript:orderPrint('<?= $presult['orderid'] ?>');">프린트하기</a>
		</div>
	</div>
<?

	// 주문실패
} else { ?>
	<div class="order_failed">
		<h2 class="cat_ttl">결제 실패</h2>
		<span class="notice">결제 시 에러가 발생하였습니다.</span>
		<span class="msg">결과메세지 : <?= $presult['resmsg'] ?></span>

		<div class="AW_btn_area">
			<a href="order_pay.php?orderid=<?= $presult['orderid'] ?>&pay_method=<?= $order_info->pay_method ?>" class="submit_btn">다시 결제하기</a>
		</div>
	</div><!-- //order_failed -->
<? } ?>


<?
include "../inc/footer.inc"; 		// 하단디자인
?>