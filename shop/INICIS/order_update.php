<?php
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/design_info.inc"; 	// 디자인 정보
include "../../inc/oper_info.inc"; 		// 운영 정보
include "../../inc/mem_info.inc"; 			// 회원 정보
include "../../inc/util.inc";		      // 유틸lib
include "../../inc/shop_info.inc"; 		// 페이지 정보

/* INIsecurepay.php
 *
 * 이니페이 플러그인을 통해 요청된 지불을 처리한다.
 * 지불 요청을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis Co., Ltd. All rights reserved.
 */

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require("INIpay41Lib.php");



	if(!strcmp($oper_info->pay_test, "Y")) {
		//$oper_info->pay_id = "INIpayTest";
		$oper_info->pay_id = "hanatest01";
		$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
	}
	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay41;


	/*********************
	 * 3. 지불 정보 설정 *
	 *********************/
	$inipay->m_inipayHome = getcwd(); 		// 이니페이 홈디렉터리
	$inipay->m_type = "securepay"; 				// 고정 (절대 수정 불가)
	$inipay->m_pgId = "INIpay".$pgid; 			// 고정 (절대 수정 불가)
	$inipay->m_subPgIp = "203.238.3.10"; 			// 고정 (절대 수정 불가)
	$inipay->m_keyPw = "1111"; 				// 키패스워드(상점아이디에 따라 변경)
	$inipay->m_debug = "true"; 				// 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->m_mid = $mid; 					// 상점아이디
	$inipay->m_uid = $uid; 					// INIpay User ID (절대 수정 불가)
	$inipay->m_uip = getenv("REMOTE_ADDR"); 		// 고정 (절대 수정 불가)
	$inipay->m_goodName = $goodname;			// 상품명
	$inipay->m_currency = $currency;			// 화폐단위

	$inipay->m_price = $price;				// 결제금액

	$inipay->m_buyerName = $buyername;			// 구매자 명
	$inipay->m_buyerTel = $buyertel;			// 구매자 연락처(휴대폰 번호 또는 유선전화번호)
	$inipay->m_buyerEmail = $buyeremail;			// 구매자 이메일 주소
	$inipay->m_payMethod = $paymethod;			// 지불방법 (절대 수정 불가)
	$inipay->m_encrypted = $encrypted;			// 암호문
	$inipay->m_sessionKey = $sessionkey;			// 암호문
	$inipay->m_url = $shop_info->shop_url; 	// 실제 서비스되는 상점 SITE URL로 변경할것
	$inipay->m_cardcode = $cardcode; 			// 카드코드 리턴
	$inipay->m_ParentEmail = $parentemail; 			// 보호자 이메일 주소(핸드폰 , 전화결제시에 14세 미만의 고객이 결제하면  부모 이메일로 결제 내용통보 의무, 다른결제 수단 사용시에 삭제 가능)

	/*-----------------------------------------------------------------*
	 * 수취인 정보 *                                                   *
	 *-----------------------------------------------------------------*
	 * 실물배송을 하는 상점의 경우에 사용되는 필드들이며               *
	 * 아래의 값들은 INIsecurepay.html 페이지에서 포스트 되도록        *
	 * 필드를 만들어 주도록 하십시요.                                  *
	 * 컨텐츠 제공업체의 경우 삭제하셔도 무방합니다.                   *
	 *-----------------------------------------------------------------*/
	$inipay->m_recvName = $recvname;	// 수취인 명
	$inipay->m_recvTel = $recvtel;		// 수취인 연락처
	$inipay->m_recvAddr = $recvaddr;	// 수취인 주소
	$inipay->m_recvPostNum = $recvpostnum;  // 수취인 우편번호
	$inipay->m_recvMsg = $recvmsg;		// 전달 메세지

	/****************
	 * 4. 지불 요청 *
	 ****************/
	$inipay->startAction();

	/****************************************************************************************************************
	 * 5. 결제  결과
	 *
	 *  1 모든 결제 수단에 공통되는 결제 결과 데이터
	 * 	거래번호 : $inipay->m_tid
	 * 	결과코드 : $inipay->m_resultCode ("00"이면 지불 성공)
	 * 	결과내용 : $inipay->m_resultMsg (지불결과에 대한 설명)
	 * 	지불방법 : $inipay->m_payMethod (매뉴얼 참조)
	 * 	상점주문번호 : $inipay->m_moid
	 *	   결제완료금액 : $inipay->m_resultprice
	 *
	 * 결제 되는 금액 =>원상품가격과  결제결과금액과 비교하여 금액이 동일하지 않다면
	 * 결제 금액의 위변조가 의심됨으로 정상적인 처리가 되지않도록 처리 바랍니다. (해당 거래 취소 처리) 																									*
	******************************************************************************************************************/
?>
<html>
<head>
<script>
	var openwin=window.open("/shop/INICIS/childwin.html","childwin","width=299,height=149");
	openwin.close();

	function show_receipt(tid) // 영수증 출력
	{
		if("<?php echo ($inipay->m_resultCode); ?>" == "00")
		{
			var receiptUrl = "https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid=" + "<?php echo($inipay->m_tid); ?>" + "&noMethod=1";
			window.open(receiptUrl,"receipt","width=430,height=700");
		}
		else
		{
			alert("해당하는 결제내역이 없습니다");
		}
	}

	function errhelp() // 상세 에러내역 출력
	{
		var errhelpUrl = "http://www.inicis.com/ErrCode/Error.jsp?result_err_code=" + "<?php echo($inipay->m_resulterrcode); ?>" + "&mid=" + "<?php echo($inipay->m_mid); ?>" + "&tid=<?php echo($inipay->m_tid); ?>" + "&goodname=" + "<?php echo($inipay->m_goodName); ?>" + "&price=" + "<?php echo($inipay->m_price); ?>" + "&paymethod=" + "<?php echo($inipay->m_payMethod); ?>" + "&buyername=" + "<?php echo($inipay->m_buyerName); ?>" + "&buyertel=" + "<?php echo($inipay->m_buyerTel); ?>" + "&buyeremail=" + "<?php echo($inipay->m_buyerEmail); ?>" + "&codegw=" + "<?php echo($inipay->m_codegw); ?>";
		window.open(errhelpUrl,"errhelp","width=520,height=150, scrollbars=yes,resizable=yes");
	}

</script>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body>
<?
if($inipay->m_resultCode=="00"){            //결제가 성공이면
	 				////////////////////////////////////////////////////////////////////////////
				 	/////////////////////// 주문정보 업데이트 //////////////////////////////////
				 	////////////////////////////////////////////////////////////////////////////

					$orderid = $inipay->m_moid; //주문번호 넘어온값

					// 주문정보
					$sql = "SELECT * FROM wiz_order WHERE orderid='$inipay->m_moid'";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$order_info = mysqli_fetch_object($result);

					$_Payment['status']		= "OY"; //결제상태
					if($order_info->pay_method == "PV" && $inipay->m_payMethod == "VBank"){
						$_Payment['status']		= "OR"; //결제상태
					}


					$_Payment['orderid']	= $inipay->m_moid; //주문번호
					$_Payment[paymethod]	= $order_info->pay_method; //결제종류
					$_Payment[ttno]		= $inipay->m_tid; //거래번호
					$_Payment[bankkind]	= $inipay->m_vcdbank; //은행코드
					$_Payment[accountno]	= $inipay->m_vacct; //계좌번호
					$_Payment[accountname]	= $inipay->m_nminput; //예금주
					$_Payment[pgname]		= "inicis";//PG사 종류
					$_Payment[es_check]	= $oper_info->pay_escrow;//에스크로 사용여부
					$_Payment[es_stats]	= "IN";//에스크로 상태(데이콤으로 기본정보 발송)
					$_Payment[tprice]		=	$inipay->m_price; //결제금액

					//foreach($_Payment as $key => $value){	$logs .="$key : $value\r";	}
					//@make_log("dacom_log.txt","\r----------order_update_vir.php start--------\r".$logs."\r-------order_update_vir.php start--------\r");

					//결제처리(상태변경,주문 업데이트)
					Exe_payment($_Payment);
					// 적립금 처리 : 적립금 사용시 적립금 감소
					Exe_reserve();
					// 재고처리(결제완료[OY]인 경우에만 재고 감소 -> 재고가 마이너스되는 경우때문에 주문완료 시 재고 감소)
					// if(!strcmp($_Payment['status'], "OY")) Exe_stock();
					Exe_stock();
					// 장바구니 삭제
			    	Exe_delbasket();
					$resp = true;
					$resultMSG ="OK";



			$resp = true;
		}else { //결제가 실패이면
			$orderid = $inipay->m_moid; //주문번호 넘어온값
			$resp = true;
		}
?>
<form name="frm" action="/shop/order_ok.php" method="post">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="rescode" value="<?=$inipay->m_resultCode?>">
<input type="hidden" name="resmsg" value="<?=$inipay->m_resultMsg?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">
</form>
<script>document.frm.submit();</script>