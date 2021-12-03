<?php
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 					// 유틸 라이브러리
include "../../inc/oper_info.inc"; 		// 운영 정보


    $LGD_RESPCODE="";                         // 응답코드: 0000(성공) 그외 실패
    $LGD_RESPMSG="";                          // 응답메세지
    $LGD_HASHDATA="";                         // 해쉬값
    $LGD_TID="";                              // 데이콤이 부여한 거래번호
    $LGD_MID="";                              // 상점아이디
    $LGD_OID="";                              // 주문번호
    $LGD_AMOUNT="";                           // 거래금액
    $LGD_PAYTYPE="";                          // 결제수단코드
    $LGD_PAYDATE="";                          // 거래일시(승인일시/이체일시)
    $LGD_FINANCECODE="";                      // 결제기관코드(카드종류/은행코드)
    $LGD_FINANCENAME="";                      // 결제기관이름(카드이름/은행이름)
    $LGD_TIMESTAMP="";

    $LGD_FINANCEAUTHNUM="";                   // 승인번호(신용카드)
    $LGD_CARDNUM="";                          // 카드번호(신용카드)
    $LGD_CARDINSTALLMONTH="";                 // 할부개월수(신용카드)
    $LGD_CARDNOINTYN="";                      // 무이자할부여부(신용카드) - '1'이면 무이자할부 '0'이면 일반할부
    $LGD_TRANSAMOUNT="";                      // 환율적용금액(신용카드)
    $LGD_EXCHANGERATE="";                     // 환율(신용카드)

    $LGD_ACCOUNTNUM="";                       // 계좌번호(계좌이체, 무통장입금)

    $LGD_PAYTELNUM="";                        // 휴대폰번호(휴대폰)

    $LGD_CASFLAG="";                          // 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
    $LGD_CASTAMOUNT="";                       // 입금총액(무통장입금)
    $LGD_CASCAMOUNT="";                       // 현입금액(무통장입금)
    $LGD_CASSEQNO="";                         // 입금순서(무통장입금)
    $LGD_CASHRECEIPTNUM="";                   // 현금영수증 승인번호

    $LGD_CASHRECEIPTKIND="";                  // 현금영수증종류 (0: 소득공제용 , 1: 지출증빙용)
    $LGD_CASHRECEIPTSELFYN="";                // 현금영수증자진발급제유무 Y: 자진발급제 적용, 그외 : 미적용
    $LGD_ESCROWYN="";

    /*
     * OK캐쉬백
     */
    $LGD_OCBSAVEPOINT = "";                   // OK캐쉬백 적립포인트
    $LGD_OCBTOTALPOINT = "";                  // OK캐쉬백 누적포인트
    $LGD_OCBUSABLEPOINT = "";                 // OK캐쉬백 사용가능 포인트

    $LGD_RESPCODE = $_POST["LGD_RESPCODE"];
    $LGD_RESPMSG = $_POST["LGD_RESPMSG"];
    $LGD_HASHDATA = $_POST["LGD_HASHDATA"];
    $LGD_TID = $_POST["LGD_TID"];
    $LGD_MID = $_POST["LGD_MID"];
    $LGD_OID = $_POST["LGD_OID"];
    $LGD_AMOUNT = $_POST["LGD_AMOUNT"];
    $LGD_PAYTYPE = $_POST["LGD_PAYTYPE"];
    $LGD_PAYDATE = $_POST["LGD_PAYDATE"];
    $LGD_FINANCECODE = $_POST["LGD_FINANCECODE"];
    $LGD_FINANCENAME = $_POST["LGD_FINANCENAME"];
    $LGD_FINANCEAUTHNUM = $_POST["LGD_FINANCEAUTHNUM"];
    $LGD_CARDNUM = $_POST["LGD_CARDNUM"];
    $LGD_CARDNOINTYN = $_POST["LGD_CARDNOINTYN"];
    $LGD_TRANSAMOUNT = $_POST["LGD_TRANSAMOUNT"];
    $LGD_EXCHANGERATE = $_POST["LGD_EXCHANGERATE"];
    $LGD_ACCOUNTNUM = $_POST["LGD_ACCOUNTNUM"];
    $LGD_PAYTELNUM = $_POST["LGD_PAYTELNUM"];
    $LGD_CASFLAG = $_POST["LGD_CASFLAG"];
    $LGD_CASTAMOUNT = $_POST["LGD_CASTAMOUNT"];
    $LGD_CASCAMOUNT = $_POST["LGD_CASCAMOUNT"];
    $LGD_CASSEQNO= $_POST["LGD_CASSEQNO"];
    $LGD_CASHRECEIPTNUM= $_POST["LGD_CASHRECEIPTNUM"];
    $LGD_CASHRECEIPTKIND= $_POST["LGD_CASHRECEIPTKIND"];
    $LGD_CASHRECEIPTSELFYN= $_POST["LGD_CASHRECEIPTSELFYN"];
    $LGD_ESCROWYN= $_POST["LGD_ESCROWYN"];

    $LGD_CARDINSTALLMONTH = $_POST["LGD_CARDINSTALLMONTH"];
    $LGD_TIMESTAMP = $_POST["LGD_TIMESTAMP"];

    $LGD_OCBSAVEPOINT= $_POST["LGD_OCBSAVEPOINT"];
    $LGD_OCBTOTALPOINT= $_POST["LGD_OCBTOTALPOINT"];
    $LGD_OCBUSABLEPOINT= $_POST["LGD_OCBUSABLEPOINT"];

		if(!strcmp($oper_info->pay_test, "Y")) {//테스트
			$oper_info->pay_id = "t".$oper_info->pay_id;
			$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}else{//실거래
			$platform	= "service";
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}
		//$LGD_MID 		= $mid;			//LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		//$LGD_MERTKEY	= $pay_key;		//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)


	$configPath 								=  $_SERVER['DOCUMENT_ROOT']."/shop/dacom/lgdacom";
		$LGD_PLATFORM =$platform;
		$LGD_MID 		= $mid;			//LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		$LGD_MERTKEY	= $pay_key;		//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)

require_once($_SERVER['DOCUMENT_ROOT']."/shop/dacom/lgdacom/XPayClient.php");
    $xpay = &new XPayClient($configPath, $LGD_PLATFORM,$LGD_MID,$LGD_MERTKEY);
   	$xpay->Init_TX($LGD_MID);

	$xpay->Set("LGD_TXNAME", "PaymentByKey");
    $xpay->Set("LGD_PAYKEY", $LGD_PAYKEY);

    $LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY);

    /*
     * 상점 처리결과 리턴메세지
     *
     * OK   : 상점 처리결과 성공
     * 그외 : 상점 처리결과 실패
     *
     * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
     */
    $resultMSG = "결제결과 상점 DB처리(NOTE_URL) 결과값을 입력해 주시기 바랍니다.";



	// 주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid = '$LGD_OID'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

if ($xpay->TX()) {
$LGD_TID = $xpay->Response("LGD_TID",0);
$LGD_FINANCECODE = $xpay->Response("LGD_FINANCECODE",0);
$LGD_ACCOUNTNUM = $xpay->Response("LGD_ACCOUNTNUM",0);
    if ($LGD_HASHDATA2 == $LGD_HASHDATA) {      //해쉬값 검증이 성공하면
        if($xpay->Response_Code() == "0000"){            //결제가 성공이면

         ////////////////////////////////////////////////////////////////////////////
		 	/////////////////////// 주문정보 업데이트 //////////////////////////////////
		 	////////////////////////////////////////////////////////////////////////////

			if($order_info->pay_method == "PV")$status = "OR";// 가상계좌인경우 입금이 안되었으므로 주문접수
			else $status = "OY";

			$_Payment['status'] = $status; //결제상태


			$_Payment['orderid'] = $LGD_OID; //주문번호
			$_Payment[paymethod] = $order_info->pay_method; //결제종류
			$_Payment[ttno] = $LGD_TID; //거래번호
			$_Payment[bankkind] = $LGD_FINANCECODE;  //은행코드(가상계좌일경우)
			$_Payment[accountno] = $LGD_ACCOUNTNUM; //계좌번호(가상계좌일경우)
			$_Payment[pgname] = "dacom";//PG사 종류
			$_Payment[es_check]	= $oper_info[pay_escrow];//에스크로 사용여부
			$_Payment[es_stats]	= "IN";//에스크로 상태(데이콤으로 기본정보 발송)
			$_Payment[tprice]		=	$LGD_AMOUNT; //결제금액
			$_Payment[cash_num] =$LGD_CASHRECEIPTNUM; //현금영수증 승인번호
			$_Payment[cash_type] =$LGD_CASHRECEIPTKIND; //현금영수증 종류
			foreach($_Payment as $key => $value){
						$logs .="$key : $value\r";
					}
					$logs .="발급여부: $LGD_CASHRECEIPTYN\r";
			@make_log("dacom_log.txt","\r---------------------------order_update.php start----------------------------------\r".$logs."\r---------------------------order_update.php END----------------------------------\r");
			//결제처리(상태변경,주문 업데이트)
			Exe_payment($_Payment);
			// 적립금 처리 : 적립금 사용시 적립금 감소
			Exe_reserve();
			// 재고처리
			Exe_stock();
			// 장바구니 삭제
			Exe_delbasket();



			$resultMSG ="OK";



        }else { //결제가 실패이면
            /*
             * 거래실패 결과 상점 처리(DB) 부분
             * 상점결과 처리가 정상이면 "OK"
             */
           //if( 결제실패 상점처리결과 성공 ) resultMSG = "OK";
        }
    } else {                                    //해쉬값 검증이 실패이면
        /*
         * hashdata검증 실패 로그를 처리하시기 바랍니다.
         */
		$resultMSG = "결제결과 상점 DB처리(NOTE_URL) 해쉬값 검증이 실패하였습니다.";
    }

}
 // echo $resultMSG;



?>

<html>
<head>
	<script type="text/javascript">

		function setLGDResult() {
			parent.payment_return();
			try {
			} catch (e) {
				alert(e.message);
			}
		}

	</script>
</head>
<body onload="setLGDResult()">
<?php


  $LGD_RESPCODE = $_POST['LGD_RESPCODE'];
  $LGD_RESPMSG 	= $_POST['LGD_RESPMSG'];
  $LGD_PAYKEY	  = "";

  $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
  $payReqMap['LGD_RESPMSG']	=	$LGD_RESPMSG;


 $payReqMap['ptype'] = "ok";
  $payReqMap['orderid']	=	$_Payment['orderid'];
   $payReqMap['rescode'] = $LGD_RESPCODE;
  $payReqMap['resmsg']	=	$resultMSG;
   $payReqMap['pay_method'] = $_Payment[paymethod];



  if($LGD_RESPCODE == "0000"){
	  $LGD_PAYKEY = $_POST['LGD_PAYKEY'];
	  $payReqMap['LGD_PAYKEY'] = $LGD_PAYKEY;
  }
  else{
	  echo "LGD_RESPCODE:" + $LGD_RESPCODE + " ,LGD_RESPMSG:" + $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
  }
?>
<form method="post" name="LGD_RETURNINFO" id="LGD_RETURNINFO">
<?php
	  foreach ($payReqMap as $key => $value) {
      echo "<input type='hidden' name='$key' id='$key' value='$value'>";

    }
?>
</form>
</body>
</html>
