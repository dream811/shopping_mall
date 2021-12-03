<?php
include "../../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../../inc/util.inc"; 					// 유틸 라이브러리
include "../../../inc/oper_info.inc"; 		// 운영 정보

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

    /*
     * [최종결제요청 페이지(STEP2-2)]
     *
     * LG유플러스으로 부터 내려받은 LGD_PAYKEY(인증Key)를 가지고 최종 결제요청.(파라미터 전달시 POST를 사용하세요)
     */

		$configPath	= $_SERVER['DOCUMENT_ROOT']."/".$mobile_path."/sub/dacom/lgdacom"; 						//LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.

    /*
     *************************************************
     * 1.최종결제 요청 - BEGIN
     *  (단, 최종 금액체크를 원하시는 경우 금액체크 부분 주석을 제거 하시면 됩니다.)
     *************************************************
     */

		/*
    $CST_PLATFORM               = $HTTP_POST_VARS["CST_PLATFORM"];
    $CST_MID                    = $HTTP_POST_VARS["CST_MID"];
    $LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;
    */
    $LGD_PAYKEY                 = $HTTP_POST_VARS["LGD_PAYKEY"];

    $CST_PLATFORM               = $platform;
    $LGD_MID										= $mid;
		/*
    echo "<pre>";
    print_r($HTTP_POST_VARS);
    echo "</pre>";

    echo $configPath."<br>";

		echo "CST_MID - ".$HTTP_POST_VARS["CST_MID"]."<Br>";
    echo "LGD_MID - ".$HTTP_POST_VARS["LGD_MID"]."<Br>";

		echo "CST_MID - ".$CST_MID."<Br>";
    echo "LGD_MID - ".$LGD_MID."<Br>";
		*/

    require_once("./lgdacom/XPayClient.php");
    $xpay = &new XPayClient($configPath, $CST_PLATFORM);
    $xpay->Init_TX($LGD_MID);

    //echo $LGD_MID."<Br>";

    $xpay->Set("LGD_TXNAME", "PaymentByKey");
    $xpay->Set("LGD_PAYKEY", $LGD_PAYKEY);

		$orderid = $LGD_OID;
    $sql = "select * from wiz_order where orderid = '".$orderid."'";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    $order_info = mysqli_fetch_object($result);

    //echo "orderid = ".$orderid." - ".$xpay->Response("LGD_OID",0)."<br>";

    $pay_method = $order_info->pay_method;

    //금액을 체크하시기 원하는 경우 아래 주석을 풀어서 이용하십시요.
		$DB_AMOUNT = $order_info->total_price; //반드시 위변조가 불가능한 곳(DB나 세션)에서 금액을 가져오십시요.
		$xpay->Set("LGD_AMOUNTCHECKYN", "Y");
		$xpay->Set("LGD_AMOUNT", $DB_AMOUNT);

		echo $xpay->Response_Code()."<br>";
		echo $xpay->Response_Msg()."<br>";

    /*
     *************************************************
     * 1.최종결제 요청(수정하지 마세요) - END
     *************************************************
     */

    /*
     * 2. 최종결제 요청 결과처리
     *
     * 최종 결제요청 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
     */
    if ($xpay->TX()) {
    	/*
        //1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
        echo "결제요청이 완료되었습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";

        echo "거래번호 : " . $xpay->Response("LGD_TID",0) . "<br>";
        echo "상점아이디 : " . $xpay->Response("LGD_MID",0) . "<br>";
        echo "상점주문번호 : " . $xpay->Response("LGD_OID",0) . "<br>";
        echo "결제금액 : " . $xpay->Response("LGD_AMOUNT",0) . "<br>";
        echo "결과코드 : " . $xpay->Response("LGD_RESPCODE",0) . "<br>";
        echo "결과메세지 : " . $xpay->Response("LGD_RESPMSG",0) . "<p>";

        $keys = $xpay->Response_Names();
        foreach($keys as $name) {
            echo $name . " = " . $xpay->Response($name, 0) . "<br>";
        }

        echo "<p>";
			*/
				// 주문정보
				$sql = "SELECT * FROM wiz_order WHERE orderid = '$LGD_OID'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$order_info = mysqli_fetch_object($result);

        if( "0000" == $xpay->Response_Code() ) {
         	//최종결제요청 결과 성공 DB처리
          //echo "최종결제요청 결과 성공 DB처리하시기 바랍니다.<br>";

					////////////////////////////////////////////////////////////////////////////
					/////////////////////// 주문정보 업데이트 //////////////////////////////////
					////////////////////////////////////////////////////////////////////////////

					$_Payment['status'] = "OY"; //결제상태
					$_Payment['orderid'] = $LGD_OID; //주문번호
					$_Payment[paymethod] = $order_info->pay_method; //결제종류
					$_Payment[ttno] = $LGD_TID; //거래번호
					$_Payment[bankkind] = $LGD_FINANCECODE; //은행코드(가상계좌일경우)
					$_Payment[accountno] = $LGD_ACCOUNTNUM; //계좌번호(가상계좌일경우)
					$_Payment[pgname] = "dacom";//PG사 종류
					$_Payment[es_check]	= $oper_info->pay_escrow;//에스크로 사용여부
					$_Payment[es_stats]	= "IN";//에스크로 상태(데이콤으로 기본정보 발송)
					$_Payment[tprice]		=	$LGD_AMOUNT; //결제금액
					foreach($_Payment as $key => $value){
								$logs .="$key : $value\r";
							}
					//@make_log("dacom_log.txt","\r---------------------------order_update.php start----------------------------------\r".$logs."\r---------------------------order_update.php END----------------------------------\r");
					//결제처리(상태변경,주문 업데이트)
					Exe_payment($_Payment);
					// 적립금 처리 : 적립금 사용시 적립금 감소
					Exe_reserve();
					// 재고처리
					Exe_stock();
					// 장바구니 삭제
			    Exe_delbasket();
					$resultMSG ="OK";

          //최종결제요청 결과 성공 DB처리 실패시 Rollback 처리
        	$isDBOK = true; //DB처리 실패시 false로 변경해 주세요.
        	if( !$isDBOK ) {
         		echo "<p>";
         		$xpay->Rollback("상점 DB처리 실패로 인하여 Rollback 처리 [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");

              echo "TX Rollback Response_code = " . $xpay->Response_Code() . "<br>";
              echo "TX Rollback Response_msg = " . $xpay->Response_Msg() . "<p>";

              if( "0000" == $xpay->Response_Code() ) {
                	echo "자동취소가 정상적으로 완료 되었습니다.<br>";
              }else{
        			echo "자동취소가 정상적으로 처리되지 않았습니다.<br>";
              }
        	}
        }else{
          	//최종결제요청 결과 실패 DB처리
         	echo "최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>";
        }
    }else {
        //2)API 요청실패 화면처리
        echo "결제요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
        echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";

        //최종결제요청 결과 실패 DB처리
        echo "최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>";
    }
?>
<form name="frm" action="/<?=$mobile_path?>/sub/order_ok.php" method="post" target="_parent">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="rescode" value="<?=$xpay->Response_Code()?>">
<input type="hidden" name="resmsg" value="<?=$xpay->Response_Msg()?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">
</form>
<script>document.frm.submit();</script>