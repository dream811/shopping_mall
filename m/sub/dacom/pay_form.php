<?
	$oper_info->pay_test="Y";
	$oper_info->pay_id="smt_nbm2013";
	$oper_info->pay_key="5859a2b4d6f5b97b0023d913066995df";
	if(!strcmp($oper_info->pay_test, "Y")) {//테스트
		$oper_info->pay_id = "".$oper_info->pay_id;
		$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		$mid = $oper_info->pay_id;
		$pay_key = $oper_info->pay_key;
	}else{//실거래
		$platform	= "service";
		$mid = $oper_info->pay_id;
		$pay_key = $oper_info->pay_key;
	}

	/////////////////
	//결제방법 출력//
	/////////////////
	switch($order_info->pay_method){
		case "PC"://신용카드
			$_paymethod = "SC0010";break;
		case "PN"://계좌이체
			$_paymethod = "SC0030";break;
		case "PV"://가상계좌
			$_paymethod = "SC0040";break;
		case "PH";//휴대폰
			$_paymethod = "SC0060";break;
	}

	/*
	 * [결제 인증요청 페이지(STEP2-1)]
	 *
	 * 샘플페이지에서는 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가 하시기 바랍니다.
	 */

	/*
	 * 1. 기본결제 인증요청 정보 변경
	 *
	 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
	 */
	$CST_PLATFORM               = $platform;      //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
	$CST_MID                    = $mid;           //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
	                                                                    				//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
	$LGD_MID                    = (("test" == $CST_PLATFORM)?"t":"").$CST_MID;  //상점아이디(자동생성)
	$LGD_OID                    = $order_info->orderid;           //주문번호(상점정의 유니크한 주문번호를 입력하세요)
	$LGD_AMOUNT                 = $order_info->total_price;        //결제금액("," 를 제외한 결제금액을 입력하세요)
	$LGD_BUYER                  = $order_info->send_name;         //구매자명
	$LGD_PRODUCTINFO            = $payment_prdname;   //상품명
	$LGD_BUYEREMAIL             = $order_info->send_email;    //구매자 이메일
	$LGD_CUSTOM_FIRSTPAY        = $_paymethod;    //상점정의 초기결제수단
	$LGD_CUSTOM_USABLEPAY				= $_paymethod;    //상점정의 결제가능수단

	$LGD_TIMESTAMP              = date(YmdHms);                         //타임스탬프
	$LGD_CUSTOM_SKIN            = "blue";                               //상점정의 결제창 스킨 (red, blue, cyan, green, yellow)

	$configPath 								= $_SERVER['DOCUMENT_ROOT']."/".$mobile_path."/sub/dacom/lgdacom"; 						//LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf") 위치 지정.

	/*
	 * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다.
	 */
	$LGD_CASNOTEURL				= "http://".$_SERVER["HTTP_HOST"]."/".$mobile_path."/sub/dacom/order_update_vir.php";

	/*
	 * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
	 */
	$LGD_RETURNURL				= "http://".$_SERVER["HTTP_HOST"]."/".$mobile_path."/sub/dacom/order_update.php";

	/*
	 * ISP 카드결제 연동중 모바일ISP방식(고객세션을 유지하지않는 비동기방식)의 경우, LGD_KVPMISPNOTEURL/LGD_KVPMISPWAPURL/LGD_KVPMISPCANCELURL를 설정하여 주시기 바랍니다.
	 */
	$LGD_KVPMISPNOTEURL       	= "http://".$_SERVER["HTTP_HOST"]."/".$mobile_path."/sub/dacom/order_update.php";
	$LGD_KVPMISPWAPURL					= "http://".$_SERVER["HTTP_HOST"]."/".$mobile_path."/sub/order_ok.php";
	$LGD_KVPMISPCANCELURL     	= "http://".$_SERVER["HTTP_HOST"]."/".$mobile_path."/sub/order_ok.php?orderid=".$LGD_OID."&pay_method=".$order_info->pay_method."&rescode=1111&resmsg=".urlencode("사용자가 ISP(국민/BC) 카드결제을 중단하였습니다.");

	/*
	 *************************************************
	 * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
	 *
	 * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다.
	 *************************************************
	 *
	 * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
	 * LGD_MID          : 상점아이디
	 * LGD_OID          : 주문번호
	 * LGD_AMOUNT       : 금액
	 * LGD_TIMESTAMP    : 타임스탬프
	 * LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
	 *
	 * MD5 해쉬데이터 암호화 검증을 위해
	 * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
	 */
	require_once("./dacom/lgdacom/XPayClient.php");
	$xpay = &new XPayClient($configPath, $LGD_PLATFORM);
	$xpay->Init_TX($LGD_MID);
	$LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$xpay->config[$LGD_MID]);
	$LGD_CUSTOM_PROCESSTYPE = "TWOTR";
	/*
	 *************************************************
	 * 2. MD5 해쉬암호화 (수정하지 마세요) - END
	 *************************************************
	 */
?>
<!--script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.lgdacom.net<?=($platform == "test")?":7080":""?>/xpay/js/xpay_crossplatform.js" type="text/javascript"></script-->
<script language="javascript" src="http://xpay.lgdacom.net/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<script language = 'javascript'>
<!--

/*
* iframe으로 결제창을 호출하시기를 원하시면 iframe으로 설정 (변수명 수정 불가)
*/
	var LGD_window_type = "iframe";
/*
* 수정불가
*/
function launchCrossPlatform(){
      lgdwin = open_paymentwindow(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type);
	  $(document).scrollTop(0);
}
/*
* FORM 명만  수정 가능
*/
function getFormObject() {
        return document.getElementById("LGD_PAYINFO");
}
/*
* 일반용 수정가능(함수명은 수정 불가)
*/
function setLGDResult(){
	if( LGD_window_type == 'iframe' ){
		document.getElementById('LGD_PAYMENTWINDOW').style.display = "none";
		document.getElementById('LGD_RESPCODE').value = lgdwin.contentWindow.document.getElementById('LGD_RESPCODE').value;
		document.getElementById('LGD_RESPMSG').value = lgdwin.contentWindow.document.getElementById('LGD_RESPMSG').value;
		if(lgdwin.contentWindow.document.getElementById('LGD_PAYKEY') != null){
			document.getElementById('LGD_PAYKEY').value = lgdwin.contentWindow.document.getElementById('LGD_PAYKEY').value;
		}
	}  else {
		document.getElementById('LGD_RESPCODE').value = lgdwin.document.getElementById('LGD_RESPCODE').value;
		document.getElementById('LGD_RESPMSG').value = lgdwin.document.getElementById('LGD_RESPMSG').value;
		if(lgdwin.document.getElementById('LGD_PAYKEY') != null){
			document.getElementById('LGD_PAYKEY').value = lgdwin.document.getElementById('LGD_PAYKEY').value;
		}
	}

	if(document.getElementById('LGD_RESPCODE').value == '0000' ){
		getFormObject().target = "_self";
		getFormObject().action = "order_update.php";
		getFormObject().submit();
	} else {
		alert(document.getElementById('LGD_RESPMSG').value);
	}

}
/*
* 스마트폰용 수정가능(함수명은 수정 불가)
*/

function doSmartXpay(){

        var LGD_RESPCODE        = dpop.getData('LGD_RESPCODE');       //결과코드
        var LGD_RESPMSG         = dpop.getData('LGD_RESPMSG');        //결과메세지

        if( "0000" == LGD_RESPCODE ) { //인증성공
            var LGD_PAYKEY      = dpop.getData('LGD_PAYKEY');         //LG유플러스 인증KEY
            document.getElementById('LGD_PAYKEY').value = LGD_PAYKEY;
            getFormObject().submit();
        } else { //인증실패
            alert("인증이 실패하였습니다. " + LGD_RESPMSG);
        }

}

//-->
</script>

<!--  수정 불가(IFRAME 방식시 사용)   -->
<div id="LGD_PAYMENTWINDOW" style="position:absolute; display:none; top:0; width:100%; height:100%; z-index:1000;background-color:#D3D3D3; font-size:small; ">
     <iframe id="LGD_PAYMENTWINDOW_IFRAME" name="LGD_PAYMENTWINDOW_IFRAME" height="100%" width="100%" scrolling="no" frameborder="0">
     </iframe>
</div>

<form method="post" id="LGD_PAYINFO" action="order_update.php">

<input type="hidden" name="CST_PLATFORM"                value="<?= $CST_PLATFORM ?>">                   <!-- 테스트, 서비스 구분 -->
<input type="hidden" name="CST_MID"                     value="<?= $CST_MID ?>">                        <!-- 상점아이디 -->
<input type="hidden" name="LGD_MID"                     value="<?= $LGD_MID ?>">                        <!-- 상점아이디 -->
<input type="hidden" name="LGD_OID"                     value="<?= $LGD_OID ?>">                        <!-- 주문번호 -->
<input type="hidden" name="LGD_BUYER"                   value="<?= $LGD_BUYER ?>">           			<!-- 구매자 -->
<input type="hidden" name="LGD_PRODUCTINFO"             value="<?= $LGD_PRODUCTINFO ?>">     			<!-- 상품정보 -->
<input type="hidden" name="LGD_AMOUNT"                  value="<?= $LGD_AMOUNT ?>">                     <!-- 결제금액 -->
<input type="hidden" name="LGD_BUYEREMAIL"              value="<?= $LGD_BUYEREMAIL ?>">                 <!-- 구매자 이메일 -->
<input type="hidden" name="LGD_CUSTOM_SKIN"             value="<?= $LGD_CUSTOM_SKIN ?>">                <!-- 결제창 SKIN -->
<input type="hidden" name="LGD_CUSTOM_PROCESSTYPE"      value="<?= $LGD_CUSTOM_PROCESSTYPE ?>">         <!-- 트랜잭션 처리방식 -->
<input type="hidden" name="LGD_TIMESTAMP"               value="<?= $LGD_TIMESTAMP ?>">                  <!-- 타임스탬프 -->
<input type="hidden" name="LGD_HASHDATA"                value="<?= $LGD_HASHDATA ?>">                   <!-- MD5 해쉬암호값 -->
<input type="hidden" name="LGD_RETURNURL"   						value="<?= $LGD_RETURNURL ?>">      			<!-- 응답수신페이지-->
<input type="hidden" name="LGD_VERSION"         				value="PHP_SmartXPay_1.0">				   	    <!-- 버전정보 (삭제하지 마세요) -->
<input type="hidden" name="LGD_CUSTOM_FIRSTPAY"  				value="<?= $LGD_CUSTOM_FIRSTPAY ?>">								    <!-- 디폴트 결제수단 -->
<input type="hidden" name="LGD_CUSTOM_USABLEPAY"  			value="<?= $LGD_CUSTOM_USABLEPAY ?>">								    <!-- 결제가능 결제수단 -->

<!-- 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 텔레콤에 전송해야 합니다 . -->
<input type="hidden" name="LGD_CASNOTEURL"          	value="<?= $LGD_CASNOTEURL ?>">			<!-- 가상계좌 NOTEURL -->

<!--
****************************************************
* 안드로이드폰 신용카드 ISP(국민/BC)결제에만 적용 (시작)*
****************************************************

(주의)LGD_CUSTOM_ROLLBACK 의 값을  "Y"로 넘길 경우, LG U+ 전자결제에서 보낸 ISP(국민/비씨) 승인정보를 고객서버의 note_url에서 수신시  "OK" 리턴이 안되면  해당 트랜잭션은  무조건 롤백(자동취소)처리되고,
LGD_CUSTOM_ROLLBACK 의 값 을 "C"로 넘길 경우, 고객서버의 note_url에서 "ROLLBACK" 리턴이 될 때만 해당 트랜잭션은  롤백처리되며  그외의 값이 리턴되면 정상 승인완료 처리됩니다.
만일, LGD_CUSTOM_ROLLBACK 의 값이 "N" 이거나 null 인 경우, 고객서버의 note_url에서  "OK" 리턴이  안될시, "OK" 리턴이 될 때까지 3분간격으로 2시간동안  승인결과를 재전송합니다.
-->

<input type="hidden" name="LGD_CUSTOM_ROLLBACK"         value="">				   	   				   <!-- 비동기 ISP에서 트랜잭션 처리여부 -->
<input type="hidden" name="LGD_KVPMISPNOTEURL"  		value="<?= $LGD_KVPMISPNOTEURL ?>">			   <!-- 비동기 ISP(ex. 안드로이드) 승인결과를 받는 URL -->
<input type="hidden" name="LGD_KVPMISPWAPURL"  			value="<?= $LGD_KVPMISPWAPURL ?>">			   <!-- 비동기 ISP(ex. 안드로이드) 승인완료후 사용자에게 보여지는 승인완료 URL -->
<input type="hidden" name="LGD_KVPMISPCANCELURL"  		value="<?= $LGD_KVPMISPCANCELURL ?>">		   <!-- ISP 앱에서 취소시 사용자에게 보여지는 취소 URL -->

<!--
****************************************************
* 안드로이드폰 신용카드 ISP(국민/BC)결제에만 적용    (끝) *
****************************************************
-->

<!-- input type="hidden" name="LGD_KVPMISPAUTOAPPYN"         value="Y"> -->
<!-- Y: 아이폰에서 ISP신용카드 결제시, 고객사에서 'App To App' 방식으로 국민, BC카드사에서 받은 결제 승인을 받고 고객사의 앱을 실행하고자 할때 사용-->


<!-- 수정 불가 ( 인증 후 자동 셋팅 ) -->
<input type="hidden" name="LGD_RESPCODE" id="LGD_RESPCODE">
<input type="hidden" name="LGD_RESPMSG" id="LGD_RESPMSG">
<input type="hidden" name="LGD_PAYKEY"  id="LGD_PAYKEY">

<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="pay_table">
  <tr>
    <th width="110">결제방법</th>
    <td><?=pay_method($pay_method)?></td>
  </tr>
  <tr>
    <th>결제금액</th>
    <td><span class="price_a"><?=number_format($order_info->total_price)?>원</span></td>
  </tr>
</table>
</div>

<div class="button_common">
	<button type="button" onClick="launchCrossPlatform()">결제하기</button>
</div>

</form>