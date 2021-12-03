<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";		// 로그인 체크
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$now_position = "<a href=/>Home</a> &gt; <strong>주문/배송조회</strong>";
include "../inc/header.inc"; 			// 상단디자인

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$order_info = mysqli_fetch_object($result);

// 주문취소 버튼
get_cancel_btn();

// 에스크로 버튼
get_escrow_btn();

// 세금계산서 버튼
get_tax_btn();

if(!empty($HTTP_REFERER)) {
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");
}
?>
<script language="javascript">
<!--
function orderPrint(){
	var url = "/shop/order_print.php?orderid=<?=$orderid?>";
	window.open(url, "orderPrint", "height=640, width=736, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
-->
</script>
<div class="AW_ttl clearfix">
	<h2>주문 &#183; 배송조회 내역</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>주문 &#183; 배송조회내역</span>
	</div>
</div>

<? include "../shop/order_info.inc"; ?>
	      			
<div class="AW_btn_area clearfix">
	<a href="my_order.php?page=<?=$page?>" class="list_btn">리스트</a>
	<?=$cancel_btn?> <?=$escrow_btn?> <!-- <?=$tax_btn?> --> <a href="javascript:orderPrint();">프린트 하기</a>
</div>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>