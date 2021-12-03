<meta http-equiv="Cache-Control" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>
<meta http-equiv="Pragma" content="no-cache"/>

<?

    /*
     * [결제 인증요청 페이지(STEP2-1)]
     *
     * 샘플페이지에서는 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가 하시기 바랍니다.
	*/

    /* 1. 기본결제정보 변경
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


$CST_PLATFORM               = $platform;      //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
$CST_MID                    = $mid;           //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)

$LGD_MID 			= $mid;			//LG데이콤 결제서비스 선택(test:테스트, service:서비스)
$LGD_MERTKEY	= $pay_key;		//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)

$LGD_OID					=	$order_info->orderid;
$LGD_AMOUNT				=	$order_info->total_price;
$LGD_TIMESTAMP		=	time();
$LGD_BUYER        = $order_info->send_name;            //구매자명
$LGD_PRODUCTINFO  = $payment_prdname;      //상품명
$LGD_BUYEREMAIL   = $order_info->send_email;       //구매자 이메일
$LGD_CUSTOM_SKIN  = "red";      //상점정의 결제창 스킨 (red, blue, cyan, green, yellow)
$LGD_BUYERID			= $order_info->send_id; //구매자 아이디


$LGD_WINDOW_VER		        = "2.5";										 //결제창 버젼정보
$LGD_WINDOW_TYPE            ="iframe";                //결제창 호출방식 (수정불가)
$LGD_CUSTOM_SWITCHINGTYPE   = "IFRAME";                //신용카드 카드사 인증 페이지 연동 방식 (수정불가)
$configPath 								= $_SERVER['DOCUMENT_ROOT']."/shop/dacom/lgdacom";

$LGD_CUSTOM_PROCESSTYPE     = "TWOTR";                                       //수정불가


$LGD_NOTEURL        = "http://".$_SERVER["HTTP_HOST"]."/shop/dacom/order_update.php";          //상점결제결과 처리(DB) 페이지(URL을 변경해 주세요)


	/*
     * 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다.
	*/


$LGD_CASNOTEURL				= "http://".$_SERVER["HTTP_HOST"]."/shop/dacom/order_update_vir.php";

	/*
	 * LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
	*/
$LGD_RETURNURL				= "http://".$_SERVER["HTTP_HOST"]."/shop/dacom/order_update.php";



//현금영수증 사용여부
$LGD_CASHRECEIPTYN="N";
if($order_info->pay_method=="PN" || $order_info->pay_method=="PV"){
	if(!strcmp($oper_info->tax_use, "Y") && $order_info->tax_type== "C") {
		$LGD_CASHRECEIPTYN="Y";
	}
}

/////////////////
//결제방법 출력//
/////////////////
switch($order_info->pay_method){
	case "PC"://신용카드
	$_paymethod = "SC0010";
	break;
	case "PN"://계좌이체
	$_paymethod = "SC0030";
	break;
	case "PV"://가상계좌
	$_paymethod = "SC0040";
	break;
	case "PH";//휴대폰
	$_paymethod = "SC0060";
	break;
}
$LGD_CUSTOM_FIRSTPAY = $_paymethod; //초기 결제 방법
$LGD_CUSTOM_USABLEPAY = $_paymethod;	//선택 결제 수단
$LGD_ESCROW_USEYN= $oper_info->pay_escrow; //에스크로 사용여부





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
require_once($_SERVER['DOCUMENT_ROOT']."/shop/dacom/lgdacom/XPayClient.php");
$xpay = &new XPayClient($configPath, $LGD_PLATFORM,$LGD_MID,$LGD_MERTKEY);
$xpay->Init_TX($LGD_MID);
$LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$LGD_MERTKEY);














    /*
     *************************************************
     * 2. MD5 해쉬암호화 (수정하지 마세요) - END
     *************************************************
	*/

$payReqMap['CST_PLATFORM']           = $CST_PLATFORM;              // 테스트, 서비스 구분
$payReqMap['LGD_WINDOW_TYPE']        = $LGD_WINDOW_TYPE;           // 수정불가
$payReqMap['CST_MID']                = $CST_MID;                   // 상점아이디
$payReqMap['LGD_MID']                = $LGD_MID;                   // 상점아이디
$payReqMap['LGD_OID']                = $LGD_OID;                   // 주문번호
$payReqMap['LGD_BUYER']              = $LGD_BUYER;            	   // 구매자
$payReqMap['LGD_PRODUCTINFO']        = $LGD_PRODUCTINFO;     	   // 상품정보
$payReqMap['LGD_AMOUNT']             = $LGD_AMOUNT;                // 결제금액
$payReqMap['LGD_BUYEREMAIL']         = $LGD_BUYEREMAIL;            // 구매자 이메일
$payReqMap['LGD_CUSTOM_SKIN']        = $LGD_CUSTOM_SKIN;           // 결제창 SKIN
$payReqMap['LGD_CUSTOM_PROCESSTYPE'] = $LGD_CUSTOM_PROCESSTYPE;    // 트랜잭션 처리방식
$payReqMap['LGD_TIMESTAMP']          = $LGD_TIMESTAMP;             // 타임스탬프
$payReqMap['LGD_HASHDATA']           = $LGD_HASHDATA;              // MD5 해쉬암호값
$payReqMap['LGD_RETURNURL']   		 = $LGD_RETURNURL;      	   // 응답수신페이지


$payReqMap['LGD_NOTEURL']   		 = $LGD_NOTEURL;
$payReqMap['LGD_ESCROW_USEYN']  =$LGD_ESCROW_USEYN; //에스크로 사용여부


$payReqMap['LGD_VERSION']         	 = "PHP_2.5";		   // 버전정보 (삭제하지 마세요)
$payReqMap['LGD_CUSTOM_USABLEPAY']  	 = $LGD_CUSTOM_USABLEPAY;	   // 디폴트 결제수단
$payReqMap['LGD_CUSTOM_SWITCHINGTYPE']  = $LGD_CUSTOM_SWITCHINGTYPE;	       // 신용카드 카드사 인증 페이지 연동 방식
$payReqMap['LGD_WINDOW_VER']  = $LGD_WINDOW_VER;
$payReqMap['LGD_CUSTOM_FIRSTPAY']  = $LGD_CUSTOM_FIRSTPAY;
$payReqMap['LGD_CUSTOM_USABLEPAY']  = $LGD_CUSTOM_USABLEPAY;

//$payReqMap['LGD_ENCODING']  = "UTF-8"; //언어설정

$payReqMap['LGD_ESCROW_GOODID']             = $order_info->orderid;
$payReqMap['LGD_ESCROW_GOODNAME']             = $order_info->orderid;
$payReqMap['LGD_ESCROW_GOODCODE']             = $order_info->orderid;
$payReqMap['LGD_ESCROW_UNITPRICE']             = $order_info->total_price;
$payReqMap['LGD_ESCROW_QUANTITY']             = "1";
$payReqMap['LGD_ESCROW_ZIPCODE']             = $order_info->rece_post;
$payReqMap['LGD_ESCROW_ADDRESS1']             = $order_info->rece_address;
$payReqMap['LGD_ESCROW_ADDRESS2']             = "";
$payReqMap['LGD_ESCROW_BUYERPHONE']             = $order_info->rece_hphone;
$payReqMap['escrowflag']             = $oper_info->pay_escrow;
//$payReqMap['LGD_CASNOTEURL']             = $LGD_CASNOTEURL;
$payReqMap['LGD_CASHRECEIPTYN']             = $LGD_CASHRECEIPTYN;

// 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 유플러스에 전송해야 합니다 .
$payReqMap['LGD_CASNOTEURL'] = $LGD_CASNOTEURL;               // 가상계좌 NOTEURL

//Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
$payReqMap['LGD_RESPCODE']           = "";
$payReqMap['LGD_RESPMSG']            = "";
$payReqMap['LGD_PAYKEY']             = "";


$_SESSION['PAYREQ_MAP'] = $payReqMap;
?>




<script language="javascript" src="http://xpay.uplus.co.kr/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<script type="text/javascript">

/*
* 수정불가.
*/
var LGD_window_type = '<?= $LGD_WINDOW_TYPE ?>';

/*
* 수정불가
*/
function launchCrossPlatform(){
	lgdwin = openXpay(document.getElementById('LGD_PAYINFO'), '<?= $CST_PLATFORM ?>', LGD_window_type, null, "", "");
}
/*
* FORM 명만  수정 가능
*/
function getFormObject() {
	return document.getElementById("LGD_PAYINFO");
}

/*
 * 인증결과 처리
*/
function payment_return() {
	var fDoc;

	fDoc = lgdwin.contentWindow || lgdwin.contentDocument;


	if (fDoc.document.getElementById('LGD_RESPCODE').value == "0000") {

		document.getElementById("LGD_PAYKEY").value = fDoc.document.getElementById('LGD_PAYKEY').value;
		fDoc.document.getElementById("LGD_RETURNINFO").target = "_top";
		fDoc.document.getElementById("LGD_RETURNINFO").action = "/shop/order_ok.php";
		fDoc.document.getElementById("LGD_RETURNINFO").submit();
	}
	else {
		alert("결제가 취소되었습니다."); //"LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value
		closeIframe();
	}
}

</script>

<form method="post"  name="LGD_PAYINFO" id="LGD_PAYINFO" >

<?
foreach ($payReqMap as $key => $value) {
	echo "<input type='hidden' name='$key' id='$key' value='$value'>";
}
// var_dump($_SESSION);
?>
<table width="100%" class="AW_order_table" style="margin:30px 0;">
	<tr>
		<td width="20%" class="tit">결제방법</td>
		<td width="80%" class="val">
			<?=pay_method($pay_method)?>
		</td>
	</tr>
	<tr>
		<td class="tit">결제금액</td>
		<td class="val"><span class="price_a"><?=number_format($order_info->total_price)?>원</span></td>
	</tr>
</table>
<div class="AW_btn_area">
	<button type="button" class="submit_btn" onclick="launchCrossPlatform()">결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.back();">주문취소</button>
</div>
</form>