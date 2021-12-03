<?php
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 					// 유틸 라이브러리
include "../../inc/oper_info.inc"; 		// 운영 정보
 /***************************************************************************************************************
 * 올더게이트로 부터 입/출금 데이타를 받아서 상점에서 처리 한 후
 * 올더게이트로 다시 응답값을 리턴한다.
 * 업체에 맞게 수정하여 작업하면 된다.
 * // 위즈?: 가상계좌 자동 입금확인 틀 ///
***************************************************************************************************************/

/*********************************** 올더게이트로 부터 넘겨 받는 값들 시작 *************************************/
$trcode     = trim( $_POST["trcode"] );					    //거래코드
$service_id = trim( $_POST["service_id"] );					//상점아이디
$orderdt    = trim( $_POST["orderdt"] );				    //승인일자
$virno      = trim( $_POST["virno"] );				        //가상계좌번호
$deal_won   = trim( $_POST["deal_won"] );					//입금액
$ordno		= trim( $_POST["ordno"] );                      //주문번호
$inputnm	= trim( $_POST["inputnm"] );					//입금자명
/*********************************** 올더게이트로 부터 넘겨 받는 값들 끝 *************************************/

$rResMsg  = "";
$rSuccYn  = "n";// 정상 : y 실패 : n


	// 주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid = '".trim($ordno)."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

if($trcode == '1'){//입금일경우
	if($order_info->total_price<=$deal_won){//입금금액이 주문가격보다 같거나 클때 결제완료.
		$_Payment['status'] = "OY"; //결제상태
		$_Payment['orderid'] = $ordno; //주문번호
		$_Payment[paymethod] = ""; //결제종류
		$_Payment[otherupdate] = " , pay_date='now()' ";//결제시간
		//결제처리(상태변경,주문 업데이트)
		Exe_payment($_Payment);
		// 재고처리
		Exe_stock();
		$rResMsg  = "";
		$rSuccYn  = "y";// 정상 : y 실패 : n
	}else{
		$rResMsg  = "입금금액이 주문금액보다 적습니다.";
		$rSuccYn  = "n";// 정상 : y 실패 : n
	}
}

/***************************************************************************************************************
 * 상점에서 해당 거래에 대한 처리 db 처리 등....
 *
 * trcode = "1" ☞ 일반가상계좌 입금통보전문 (이지스효성 new 에스크로 포함)
 * trcode = "2" ☞ 일반가상계좌 취소통보전문 (이지스효성 new 에스크로 포함)
 *
 * ※ 에스크로가상계좌의 경우 입금자명 값은 통보전문에 들어가지 않습니다.



***************************************************************************************************************/
/******************************************처리 결과 리턴******************************************************/


//정상처리 경우 거래코드|상점아이디|주문일시|가상계좌번호|처리결과|
$rResMsg .= $trcode."|";
$rResMsg .= $service_id."|";
$rResMsg .= $orderdt."|";
$rResMsg .= $virno."|";
$rResMsg .= $rSuccYn."|";

echo $rResMsg;
/******************************************처리 결과 리턴******************************************************/
?>