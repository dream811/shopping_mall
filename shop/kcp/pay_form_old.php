<meta http-equiv="Cache-Control" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>
<meta http-equiv="Pragma" content="no-cache"/>
<?
/*
 * [상점결제요청 페이지(ActiveX)]
 *
 * 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가하시기 바랍니다.
 * hashdata 암호화는 거래 위변조를 막기위한 방법입니다.
 *
 */


/*
 * 1. 기본결제정보 변경
 *
 * 결제기본정보를 변경하여 주시기 바랍니다.
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

$LGD_OID				=	$order_info->orderid;
$LGD_AMOUNT			=	$order_info->total_price;
$LGD_TIMESTAMP		=	time();
$LGD_BUYER        = $order_info->send_name;            //구매자명
	$LGD_PRODUCTINFO  = $payment_prdname;      //상품명
	$LGD_BUYEREMAIL   = $order_info->send_email;       //구매자 이메일
	$LGD_CUSTOM_SKIN  = "red";      //상점정의 결제창 스킨 (red, blue, cyan, green, yellow)
	$LGD_BUYERID		= $order_info->send_id; //구매자 아이디
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
	$LGD_CUSTOM_FIRSTPAY = $_paymethod; //초기 결제 방법
	$LGD_CUSTOM_USABLEPAY = $_paymethod;	//선택 결제 수단

//10만원 이상시 에스크로 적용,단 관리자단에서 에스크로 사용여부 Y 로 정할경우
if($oper_info->pay_escrow=='Y'){
	$payescrow="Y";
	if($order_info->pay_method=='PC'||$order_info->pay_method=='PH'){
		$payescrow="N";
	}
}

/*
 * 2. 결제결과 DB처리 페이지 링크 변경
 *
 * LGD_NOTEURL : 상점결제결과 처리(DB) 페이지 URL을 넘겨주세요.
 * LGD_CASNOTEURL : 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다.
 */

$LGD_NOTEURL            = "http://".$_SERVER["HTTP_HOST"]."/shop/dacom/order_update.php";          //상점결제결과 처리(DB) 페이지(URL을 변경해 주세요)
$LGD_CASNOTEURL			= "http://".$_SERVER["HTTP_HOST"]."/shop/dacom/order_update_vir.php";		//무통장일경우에 출력되는 페이지

/*
 * 3. hashdata 암호화 (수정하지 마세요)
 *
 * hashdata 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
 * LGD_MID : 상점아이디
 * LGD_OID : 주문번호
 * LGD_AMOUNT : 금액
 * LGD_TIMESTAMP : 타임스탬프
 * LGD_MERTKEY : 상점키(mertkey)
 *
 * hashdata 검증을 위한
 * LG데이콤에서 발급한 상점키(MertKey)를 반드시 입력해 주시기 바랍니다.
 */
$LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$LGD_MERTKEY);
?>
<script language = 'javascript'>
<!--
/*
 * 결제요청 및 결과화면 처리
 */

function doPay_ActiveX(){
	ret = xpay_check(document.getElementById('LGD_PAYINFO'), '<?= $platform ?>');

	if (ret=="00"){     //ActiveX 로딩 성공
	  var LGD_RESPCODE        = dpop.getData('LGD_RESPCODE');       //결과코드
	  var LGD_RESPMSG         = dpop.getData('LGD_RESPMSG');        //결과메세지

	  if( "0000" == LGD_RESPCODE ) { //결제성공
	    var LGD_TID             = dpop.getData('LGD_TID');            //LG데이콤 거래KEY
	    var LGD_PAYTYPE         = dpop.getData('LGD_PAYTYPE');        //결제수단
	    var LGD_PAYDATE         = dpop.getData('LGD_PAYDATE');        //결제일자
	    var LGD_FINANCECODE     = dpop.getData('LGD_FINANCECODE');    //결제기관코드
	    var LGD_FINANCENAME     = dpop.getData('LGD_FINANCENAME');    //결제기관이름
	    var LGD_NOTEURL_RESULT  = dpop.getData('LGD_NOTEURL_RESULT'); //상점DB처리(LGD_NOTEURL)결과 ('OK':정상,그외:실패)
	    var LGD_OID    			  = dpop.getData('LGD_OID');    //주문번호
	    var LGD_FINANCEAUTHNUM  = dpop.getData('LGD_FINANCEAUTHNUM');    //결제기관이름

	    //메뉴얼의 결제결과 파라미터내용을 참고하시어 필요하신 파라미터를 추가하여 사용하시기 바랍니다.

	      var msg = "결제결과 : " + LGD_RESPMSG + "\n";
	      msg += "LG데이콤거래TID : " + LGD_TID +"\n\n";
	      if( LGD_NOTEURL_RESULT != "null" ) msg += LGD_NOTEURL_RESULT +"\n";
	      //alert(msg);
	      /*
	       * 결제성공 화면 처리
	       */
	       document.location.replace("/shop/order_ok.php?orderid=" + LGD_OID + "&rescode=0000&pay_method=<?=$order_info->pay_method?>&resmsg=" + LGD_NOTEURL_RESULT);
	  } else { //결제실패
	       alert("결제가 실패하였습니다. " + LGD_RESPMSG);
	      /*
	       * 결제실패 화면 처리
	       */
	  }
	} else {
	    alert("LG데이콤 전자결제를 위한 ActiveX 설치 실패");
	    xpay_showInstall();
	}
}

//-->
</script>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<form method="post" id="LGD_PAYINFO">
<input type="hidden" name="LGD_CUSTOM_FIRSTPAY" value="<?= $LGD_CUSTOM_FIRSTPAY ?>">         	<!-- 초기결제수단 -->
<input type="hidden" name="LGD_CUSTOM_USABLEPAY" value="<?= $LGD_CUSTOM_USABLEPAY ?>">       	<!-- 선택결제수단 -->
<input type="hidden" name="LGD_MID"             value="<?= $LGD_MID ?>">                			<!-- 상점아이디 -->
<input type="hidden" name="LGD_OID"             value="<?= $LGD_OID ?>">                			<!-- 주문번호 -->
<input type="hidden" name="LGD_BUYER"           value="<?= $LGD_BUYER ?>">           					<!-- 구매자 -->
<input type="hidden" name="LGD_BUYERID"         value="<?= $LGD_BUYERID ?>">           				<!-- 구매자ID -->
<input type="hidden" name="LGD_PRODUCTINFO"     value="<?= $LGD_PRODUCTINFO ?>">     					<!-- 상품정보 -->
<input type="hidden" name="LGD_AMOUNT"          value="<?= $LGD_AMOUNT ?>">             			<!-- 결제금액 -->
<input type="hidden" name="LGD_BUYEREMAIL"      value="<?= $LGD_BUYEREMAIL ?>">         			<!-- 구매자 이메일 -->
<input type="hidden" name="LGD_CUSTOM_SKIN"     value="<?= $LGD_CUSTOM_SKIN ?>">        			<!-- 결제창 SKIN -->
<input type="hidden" name="LGD_TIMESTAMP"       value="<?= $LGD_TIMESTAMP ?>">          			<!-- 타임스탬프 -->
<input type="hidden" name="LGD_HASHDATA"        value="<?= $LGD_HASHDATA ?>">           			<!-- 해쉬값 -->
<input type="hidden" name="LGD_NOTEURL"         value="<?= $LGD_NOTEURL ?>">         					<!-- 결제결과처리_URL(LGD_NOTEURL) -->
<input type="hidden" name="LGD_VERSION"         value="PHP_XPay_Lite_1.0">										<!-- 버전정보 (삭제하지 마세요) -->

<!-- 에스크로 전용 파라미터 정의 시작 -->
<input type=hidden name=LGD_ESCROW_GOODID			value='<?=$order_info->orderid?>'>
<input type=hidden name=LGD_ESCROW_GOODNAME 		value='<?=$order_info->orderid?>'>
<input type=hidden name=LGD_ESCROW_GOODCODE 		value='<?=$order_info->orderid?>'>
<input type=hidden name=LGD_ESCROW_UNITPRICE		value='<?=$order_info->total_price?>'>
<input type=hidden name=LGD_ESCROW_QUANTITY 		value='1'>

<input type=hidden name=LGD_ESCROW_ZIPCODE 		value='<?=$order_info->rece_post?>'>
<input type=hidden name=LGD_ESCROW_ADDRESS1 		value='<?=$order_info->rece_address?>' >
<input type=hidden name=LGD_ESCROW_ADDRESS2 		value='' >
<input type=hidden name=LGD_ESCROW_BUYERPHONE 	value='<?=$order_info->rece_hphone?>' >

<input type=hidden name=escrowflag					value='<?=$oper_info->pay_escrow?>'>
<!-- 에스크로 전용 파라미터 정의 끝 -->

<!-- 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 데이콤에 전송해야 합니다 . -->
<input type="hidden" name="LGD_CASNOTEURL"     	value="<?= $LGD_CASNOTEURL ?>">
<!-- 가상계좌 NOTEURL -->

	<tr>
    <td style="padding:15 0 10 0">
			<table border=0 cellpadding=6 cellspacing=0 width=100% class="order_form">
				<tr><td colspan="2" bgcolor="#a9a9a9" height="2"></td></tr>
			  <tr>
				 <td width="20%" class="tit">결제방법</td>
				 <td width="80%" class="val"><?=pay_method($pay_method)?></td>
				</tr>
			  <tr>
	      	<td height="1" colspan="2" bgcolor="d7d7d7" ></td>
	    	</tr>
			  <tr>
				 <td class="tit">결제금액</td>
				 <td class="val"><span class="price_a"><?=number_format($order_info->total_price)?>원</span></td>
			  </tr>
			  <tr>
	      	<td height="1" colspan="2" bgcolor="d7d7d7" ></td>
	    	</tr>
			</table>
    </td>
  </tr>
  <tr>
    <td align=center>
	    <img src="/images/shop/but_pay.gif" onclick="javascript:doPay_ActiveX()" style="cursor:hand"> <a href="/"><img src="/images/shop/but_cancel.gif" border=0></a>
		</td>
  </tr>
</form>
</table>

<!--  xpay.js는 반드시 body 밑에 두시기 바랍니다. -->
<script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.lgdacom.net<?=($platform == "test")?":7080":""?>/xpay/js/xpay_ub.js" type="text/javascript"></script>