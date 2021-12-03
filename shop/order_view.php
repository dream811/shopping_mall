<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$now_position = "<a href=/>Home</a> &gt; <strong>주문/배송조회</strong>";
$page_type = "orderdel";

include "../inc/page_info.inc"; 		// 페이지 정보
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
<div style="margin:30px 0 0;">
	<? include "./order_info.inc"; ?>
	<div class="AW_btn_area clearfix">
		<a href="order_list.php?page=<?=$page?>" class="list_btn">리스트</a>
		<?if($order_info->status!="DI"&&$order_info->status!="DC"){?>
			<?=$cancel_btn?>
		<?}?>
		<?if($order_info->status=="DI"||$order_info->status=="DC"){?>
			<?=$escrow_btn?>
		<?}?>
		<a href="javascript:orderPrint();">프린트 하기</a>
	</div>
</div>      

<?
include "../inc/footer.inc"; 		// 하단디자인
?>