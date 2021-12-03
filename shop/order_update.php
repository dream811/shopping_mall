<?
include "../inc/common.inc";				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 					// 운영 정보
include "../inc/oper_info.inc"; 			// 운영 정보

$status = "OR";								// 주문상태
$rescode = "0000";							// 결제결과
$resmsg = "정상적으로 결제되었습니다.";		// 결제메세지

// 주문정보
$sql = "select * from wiz_order where orderid='$orderid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$order_info = mysqli_fetch_object($result);

$_Payment['status']		= "OR"; //결제상태

// 적립금으로 모두 결제시 결제완료[OY]
if($order_info->reserve_use >= $order_info->total_price){
	$status = "OY";
	$paydate = date('Y-m-d h:i:s');
}

////////////////////////////////////////////////////////////////////////////
/////////////////////// 주문정보 업데이트 //////////////////////////////////
////////////////////////////////////////////////////////////////////////////

$_Payment['orderid']	= $orderid; //주문번호
$_Payment[paymethod]	= "PB"; //결제종류
$_Payment[accountno]	= $account; //계좌번호
$_Payment[accountname] = $account_name; // 예금주
$_Payment[pgname]		= "PB";//PG사 종류
//$_Payment[tprice]		=	$LGD_AMOUNT; //결제금액

//결제처리(상태변경,주문 업데이트)
Exe_payment($_Payment);
// 적립금 처리 : 적립금 사용시 적립금 감소
Exe_reserve();
// 재고처리(결제완료[OY]인 경우에만 재고 감소 -> 재고가 마이너스되는 경우때문에 주문완료 시 재고 감소)
// if(!strcmp($status, "OY")) Exe_stock();
Exe_stock();
// 장바구니 삭제
Exe_delbasket();
$resp = true;
$resultMSG ="OK";

// 세금계산서 업데이트
if(!strcmp($oper_info->tax_use, "Y")) {
	$sql = "update wiz_tax set tax_date = now() where orderid = '$orderid'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));
}

// 주문상태 변경
//$sql = "update wiz_order set account='$account', account_name='$account_name', status='$status' where orderid='$orderid'";
//	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

// 장바구니 삭제
//@mysqli_query($connect, "DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'");
//session_unregister("basket_list");

if(mobile_check() == true) {
	$go_url = "/".$mobile_path."/sub/order_ok.php";
} else {
	$go_url = "order_ok.php";
}
?>
<form name="frm" action="<?=$go_url?>" method="post">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="rescode" value="<?=$rescode?>">
<input type="hidden" name="resmsg" value="<?=$resmsg?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">
</form>
<script>document.frm.submit();</script>