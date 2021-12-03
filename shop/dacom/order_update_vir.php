<?php
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 					// 유틸 라이브러리
include "../../inc/oper_info.inc"; 		// 운영 정보
    /*
     * [상점 결제결과처리(DB) 페이지]
     *
     * 1) 위변조 방지를 위한 hashdata값 검증은 반드시 적용하셔야 합니다.
     *
     */
    $LGD_RESPCODE            = $_POST["LGD_RESPCODE"];             // 응답코드: 0000(성공) 그외 실패
    $LGD_RESPMSG             = $_POST["LGD_RESPMSG"];              // 응답메세지
    $LGD_MID                 = $_POST["LGD_MID"];                  // 상점아이디
    $LGD_OID                 = $_POST["LGD_OID"];                  // 주문번호
    $LGD_AMOUNT              = $_POST["LGD_AMOUNT"];               // 거래금액
    $LGD_TID                 = $_POST["LGD_TID"];                  // 데이콤이 부여한 거래번호
    $LGD_PAYTYPE             = $_POST["LGD_PAYTYPE"];              // 결제수단코드
    $LGD_PAYDATE             = $_POST["LGD_PAYDATE"];              // 거래일시(승인일시/이체일시)
    $LGD_HASHDATA            = $_POST["LGD_HASHDATA"];             // 해쉬값
    $LGD_FINANCECODE         = $_POST["LGD_FINANCECODE"];          // 결제기관코드(은행코드)
    $LGD_FINANCENAME         = $_POST["LGD_FINANCENAME"];          // 결제기관이름(은행이름)
    $LGD_ESCROWYN            = $_POST["LGD_ESCROWYN"];             // 에스크로 적용여부
    $LGD_TIMESTAMP           = $_POST["LGD_TIMESTAMP"];            // 타임스탬프
    $LGD_ACCOUNTNUM          = $_POST["LGD_ACCOUNTNUM"];           // 계좌번호(무통장입금)
    $LGD_CASTAMOUNT          = $_POST["LGD_CASTAMOUNT"];           // 입금총액(무통장입금)
    $LGD_CASCAMOUNT          = $_POST["LGD_CASCAMOUNT"];           // 현입금액(무통장입금)
    $LGD_CASFLAG             = $_POST["LGD_CASFLAG"];              // 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
    $LGD_CASSEQNO            = $_POST["LGD_CASSEQNO"];             // 입금순서(무통장입금)
    $LGD_CASHRECEIPTNUM      = $_POST["LGD_CASHRECEIPTNUM"];       // 현금영수증 승인번호
    $LGD_CASHRECEIPTSELFYN   = $_POST["LGD_CASHRECEIPTSELFYN"];    // 현금영수증자진발급제유무 Y: 자진발급제 적용, 그외 : 미적용
    $LGD_CASHRECEIPTKIND     = $_POST["LGD_CASHRECEIPTKIND"];      // 현금영수증 종류 0: 소득공제용 , 1: 지출증빙용

    /*
     * 구매정보
     */
    $LGD_BUYER               = $_POST["LGD_BUYER"];                // 구매자
    $LGD_PRODUCTINFO         = $_POST["LGD_PRODUCTINFO"];          // 상품명
    $LGD_BUYERID             = $_POST["LGD_BUYERID"];              // 구매자 ID
    $LGD_BUYERADDRESS        = $_POST["LGD_BUYERADDRESS"];         // 구매자 주소
    $LGD_BUYERPHONE          = $_POST["LGD_BUYERPHONE"];           // 구매자 전화번호
    $LGD_BUYEREMAIL          = $_POST["LGD_BUYEREMAIL"];           // 구매자 이메일
    $LGD_BUYERSSN            = $_POST["LGD_BUYERSSN"];             // 구매자 주민번호
    $LGD_PRODUCTCODE         = $_POST["LGD_PRODUCTCODE"];          // 상품코드
    $LGD_RECEIVER            = $_POST["LGD_RECEIVER"];             // 수취인
    $LGD_RECEIVERPHONE       = $_POST["LGD_RECEIVERPHONE"];        // 수취인 전화번호
    $LGD_DELIVERYINFO        = $_POST["LGD_DELIVERYINFO"];         // 배송지

    /*
     * hashdata 검증을 위한 mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다.
     * LG데이콤에서 발급한 상점키로 반드시변경해 주시기 바랍니다.
     */
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
		$LGD_MID 		= $mid;			//LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		$LGD_MERTKEY	= $pay_key;		//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)


    $LGD_HASHDATA2 			 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY);

    /*
     * 상점 처리결과 리턴메세지
     *
     * OK  : 상점 처리결과 성공
     * 그외 : 상점 처리결과 실패
     *
     * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
     */
    $resultMSG = "결제결과 상점 DB처리(LGD_CASNOTEURL) 결과값을 입력해 주시기 바랍니다.";



	// 주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid = '$LGD_OID'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

    if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) { //해쉬값 검증이 성공이면
        if ( "0000" == $LGD_RESPCODE ){ //결제가 성공이면
        	if( "R" == $LGD_CASFLAG ) {
		         //if( 무통장 할당 성공 상점처리결과 성공 ) $resultMSG = "OK";
		         ////////////////////////////////////////////////////////////////////////////
				 	/////////////////////// 주문정보 업데이트 //////////////////////////////////
				 	////////////////////////////////////////////////////////////////////////////

					$_Payment['status']		= "OR"; //결제상태
					$_Payment['orderid']	= $LGD_OID; //주문번호
					$_Payment[paymethod]	= $order_info->pay_method; //결제종류
					$_Payment[ttno]		= $LGD_TID; //거래번호
					$_Payment[bankkind]	= $LGD_FINANCECODE; //은행코드
					$_Payment[accountno]	= $LGD_ACCOUNTNUM; //계좌번호
					$_Payment[pgname]		= "dacom";//PG사 종류
					$_Payment[es_check]	= $oper_info[pay_escrow];//에스크로 사용여부
					$_Payment[es_stats]	= "IN";//에스크로 상태(데이콤으로 기본정보 발송)
					$_Payment[tprice]		=	$LGD_AMOUNT; //결제금액
					$_Payment[cash_num] =$LGD_CASHRECEIPTNUM; //현금영수증 승인번호
					$_Payment[cash_type] =$LGD_CASHRECEIPTKIND; //현금영수증 종류
					$_Payment[cash_segno] =$LGD_CASSEQNO; //가상계좌 입금순서

					//foreach($_Payment as $key => $value){	$logs .="$key : $value\r";	}
					//@make_log("dacom_log.txt","\r----------order_update_vir.php start--------\r".$logs."\r-------order_update_vir.php start--------\r");
					//결제처리(상태변경,주문 업데이트)
					Exe_payment($_Payment);
					// 적립금 처리 : 적립금 사용시 적립금 감소
					Exe_reserve();
					// 재고처리
					Exe_stock();
					// 장바구니 삭제
			    	Exe_delbasket();
					$resp = true;
					$resultMSG ="OK";

        	}else if( "I" == $LGD_CASFLAG ) {
 	            //if( 무통장 입금 성공 상점처리결과 성공 ) $resultMSG = "OK";
            	////////////////////////////////////////////////////////////////////////////
				 	/////////////////////// 주문정보 업데이트 //////////////////////////////////
				 	////////////////////////////////////////////////////////////////////////////

					$_Payment['status'] = "OY"; //결제상태
					$_Payment['orderid'] = $LGD_OID; //주문번호
					$_Payment[paymethod] = $order_info->pay_method; //결제종류
					$_Payment[ttno] = $LGD_OID; //거래번호
					$_Payment[bankkind] = $LGD_FINANCECODE; //은행코드
					$_Payment[accountno] = $LGD_ACCOUNTNUM; //계좌번호
					$_Payment[pgname] = "dacom";//PG사 종류
					$_Payment[es_check]	= $oper_info[pay_escrow];//에스크로 사용여부
					$_Payment[es_stats]	= "IN";//에스크로 상태(데이콤으로 기본정보 발송)
					$_Payment[tprice]		=	$LGD_AMOUNT; //결제금액
					$_Payment[cash_num] =$LGD_CASHRECEIPTNUM; //현금영수증 승인번호
					$_Payment[cash_type] =$LGD_CASHRECEIPTKIND; //현금영수증 종류
					$_Payment[cash_segno] =$LGD_CASSEQNO; //가상계좌 입금순서

					//결제처리(상태변경,주문 업데이트)
					Exe_payment($_Payment);
					// 적립금 처리 : 적립금 사용시 적립금 감소
					Exe_reserve();
					// 재고처리
					Exe_stock();
					// 장바구니 삭제
					Exe_delbasket();
					$resp = true;
					$resultMSG ="OK";

        	}
        } else { //결제가 실패이면
            /*
             * 거래실패 결과 상점 처리(DB) 부분
             * 상점결과 처리가 정상이면 "OK"
             */
            //if( 결제실패 상점처리결과 성공 ) $resultMSG = "OK";
        }
    } else { //해쉬값이 검증이 실패이면
        /*
         * hashdata검증 실패 로그를 처리하시기 바랍니다.
         */
        $resultMSG = "결제결과 상점 DB처리(LGD_CASNOTEURL) 해쉬값 검증이 실패하였습니다.";
    }

    echo $resultMSG;
?>
