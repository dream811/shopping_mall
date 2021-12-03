<?
////////////////////////////////////////////////////////////////////////////////////////////////////
//  mertkey 확인 방법
//  1. 상점관리자(서비스: http://pgweb.dacom.net  테스트: http://pgweb.dacom.net/tmert) 접속
//  2. Dacom 에서 받은 테스트 아이디나 서비스 아이디로 로그인
//  3. 계약정보 -> 상점정보관리 -> 결과전송정보 에서 확인이 가능
////////////////////////////////////////////////////////////////////////////////////////////////////

if(!strcmp($oper_info->pay_test, "Y")) {
	$oper_info->pay_id = "aegis";
	$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
}
/////////////////////
//wiz_shopinfo 로드//
/////////////////////
$shop_info = mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM wiz_shopinfo"));

//올더게이트 제공 상점아이디 및 테스트 아이디 설정
$mid = $oper_info->pay_id;
$oid = $order_info->orderid;					//주문번호
$amount = $order_info->total_price;		//결제금액
$mertkey = $oper_info->pay_key;				//올더게이트에서 발급받은 키값

//////////////////////
//사용자 아이디 추출//
//////////////////////
if(empty($wiz_session['id'])){//아이디가 있을경우
	$_userid = $wiz_session['id'];
}else{
	$_userid = "NonUserId";
}
/////////////////
//결제방법 출력//
/////////////////

//필요없는정보 주석처리를 위해(휴대폰결제)
$_hidden_start	="<!--";$_hidden_end	="-->";

//필요없는정보 주석처리를 위해(가상계죄결제)
$_hidden_start2	="<!--";$_hidden_end2	="-->";

switch($pay_method){
		case "PC"://신용카드
			$_paymethod = "onlycard";break;
		case "PN"://계좌이체
			if($oper_info->pay_escrow=="Y"&&$amount>100000){//에스크로 사용여부
				$_paymethod = "onlyicheselfescrow";break;//에스크로 사용/계좌이체
			}else{
				$_paymethod = "onlyiche";break;//일반 계좌이체
			}
		case "PV"://가상계좌
			$_hidden_start2="";$_hidden_end2="";
			if($oper_info->pay_escrow=="Y"&&$amount>100000){//에스크로 사용여부
				$_paymethod = "onlyvirtualselfescrow";break;
			}else{
				$_paymethod = "onlyvirtual";break;
			}
		case "PH";//휴대폰
			$_paymethod = "onlyhp";
			$_hidden_start="";$_hidden_end="";
			break;
}
?>
<script language=javascript src="http://www.allthegate.com/plugin/AGSWallet.js"></script>
<script language=javascript>
<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 올더게이트 플러그인 설치를 확인합니다.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

StartSmartUpdate();

function Pay(form){
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// MakePayMessage() 가 호출되면 올더게이트 플러그인이 화면에 나타나며 Hidden 필드
	// 에 리턴값들이 채워지게 됩니다.
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	if(form.Flag.value == "enable"){
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 입력된 데이타의 유효성을 검사합니다.
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////

		if(Check_Common(form) == true){
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 올더게이트 플러그인 설치가 올바르게 되었는지 확인합니다.
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////

			if(document.AGSPay == null || document.AGSPay.object == null){
				alert("플러그인 설치 후 다시 시도 하십시오.");
			}else{
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// 올더게이트 플러그인 설정값을 동적으로 적용하기 JavaScript 코드를 사용하고 있습니다.
				// 상점설정에 맞게 JavaScript 코드를 수정하여 사용하십시오.
				//
				// [1] 일반/무이자 결제여부
				// [2] 일반결제시 할부개월수
				// [3] 무이자결제시 할부개월수 설정
				// [4] 인증여부
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [1] 일반/무이자 결제여부를 설정합니다.
				//
				// 할부판매의 경우 구매자가 이자수수료를 부담하는 것이 기본입니다. 그러나,
				// 상점과 올더게이트간의 별도 계약을 통해서 할부이자를 상점측에서 부담할 수 있습니다.
				// 이경우 구매자는 무이자 할부거래가 가능합니다.
				//
				// 예제)
				// 	(1) 일반결제로 사용할 경우
				// 	form.DeviId.value = "9000400001";
				//
				// 	(2) 무이자결제로 사용할 경우
				// 	form.DeviId.value = "9000400002";
				//
				// 	(3) 만약 결제 금액이 100,000원 미만일 경우 일반할부로 100,000원 이상일 경우 무이자할부로 사용할 경우
				// 	if(parseInt(form.Amt.value) < 100000)
				//		form.DeviId.value = "9000400001";
				// 	else
				//		form.DeviId.value = "9000400002";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				form.DeviId.value = "9000400001";

				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [2] 일반 할부기간을 설정합니다.
				//
				// 일반 할부기간은 2 ~ 12개월까지 가능합니다.
				// 0:일시불, 2:2개월, 3:3개월, ... , 12:12개월
				//
				// 예제)
				// 	(1) 할부기간을 일시불만 가능하도록 사용할 경우
				// 	form.QuotaInf.value = "0";
				//
				// 	(2) 할부기간을 일시불 ~ 12개월까지 사용할 경우
				//		form.QuotaInf.value = "0:3:4:5:6:7:8:9:10:11:12";
				//
				// 	(3) 결제금액이 일정범위안에 있을 경우에만 할부가 가능하게 할 경우
				// 	if((parseInt(form.Amt.value) >= 100000) || (parseInt(form.Amt.value) <= 200000))
				// 		form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";
				// 	else
				// 		form.QuotaInf.value = "0";
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////

				//결제금액이 5만원 미만건을 할부결제로 요청할경우 결제실패
				if(parseInt(form.Amt.value) < 50000)
					form.QuotaInf.value = "0";
				else
					form.QuotaInf.value = "0:2:3:4:5:6:7:8:9:10:11:12";

				////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				// [3] 무이자 할부기간을 설정합니다.
				// (일반결제인 경우에는 본 설정은 적용되지 않습니다.)
				//
				// 무이자 할부기간은 2 ~ 12개월까지 가능하며,
				// 올더게이트에서 제한한 할부 개월수까지만 설정해야 합니다.
				//
				// 100:BC
				// 200:국민
				// 300:외환
				// 400:삼성
				// 500:엘지
				// 600:신한
				// 800:현대
				// 900:롯데
				//
				// 예제)
				// 	(1) 모든 할부거래를 무이자로 하고 싶을때에는 ALL로 설정
				// 	form.NointInf.value = "ALL";
				//
				// 	(2) 국민카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "200-2:3:4:5:6";
				//
				// 	(3) 외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "300-2:3:4:5:6";
				//
				// 	(4) 국민,외환카드 특정개월수만 무이자를 하고 싶을경우 샘플(2:3:4:5:6개월)
				// 	form.NointInf.value = "200-2:3:4:5:6,300-2:3:4:5:6";
				//
				//	(5) 무이자 할부기간 설정을 하지 않을 경우에는 NONE로 설정
				//	form.NointInf.value = "NONE";
				//
				//	(6) 전카드사 특정개월수만 무이자를 하고 싶은경우(2:3:6개월)
				//	form.NointInf.value = "100-2:3:6,200-2:3:6,300-2:3:6,400-2:3:6,500-2:3:6,600-2:3:6,800-2:3:6,900-2:3:6";
				//
				////////////////////////////////////////////////////////////////////////////////////////////////////////////////

				if(form.DeviId.value == "9000400002")
					form.NointInf.value = "ALL";

				if(MakePayMessage(form) == true){
					Disable_Flag(form);

					var openwin = window.open("/shop/allthegate/AGS_progress.html","popup","width=300,height=160"); //"지불처리중"이라는 팝업창연결 부분

					form.submit();
				}else{
					alert("지불에 실패하였습니다.");// 취소시 이동페이지 설정부분
				}
			}
		}
	}
}

function Enable_Flag(form){
        form.Flag.value = "enable"
}

function Disable_Flag(form){
        form.Flag.value = "disable"
}

function Check_Common(form){
	if(form.StoreId.value == ""){
		alert("상점아이디를 입력하십시오.");
		return false;
	}
	else if(form.StoreNm.value == ""){
		alert("상점명을 입력하십시오.");
		return false;
	}
	else if(form.OrdNo.value == ""){
		alert("주문번호를 입력하십시오.");
		return false;
	}
	else if(form.ProdNm.value == ""){
		alert("상품명을 입력하십시오.");
		return false;
	}
	else if(form.Amt.value == ""){
		alert("금액을 입력하십시오.");
		return false;
	}
	else if(form.MallUrl.value == ""){
		alert("상점URL을 입력하십시오.");
		return false;
	}
	return true;
}

-->

</script>
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 onload="javascript:Enable_Flag(frmAGS_pay);">
<form name=frmAGS_pay method=post action="./allthegate/order_update.php">



<!--------------------------------------------------->
<!-- 결제를 위한 기본 정보 입력 폼  시작 (수정가능)-->
<!--------------------------------------------------->
<input type=hidden name=pay_method value="<?=$pay_method?>">
<!-- 결제 방법
card:신용카드 / iche:계좌이체 / hp:핸드폰결제 / ars:ARS결제 / virtual:가상계좌 / onlycard:신용카드 (전용)
onlyiche:계좌이체 (전용) / onlyhp:핸드폰결제 (전용) / onlyars:ARS결제 (전용) / onlyvirtual:가상계좌 (전용)
-->
<input type=hidden name=Job value="<?=$_paymethod?>">
<!-- 상점 아이디 -->
<input type=hidden name=StoreId value="<?=$mid?>">
<!-- 주문번호 -->
<input type=hidden name=OrdNo value="<?=$oid?>">
<!-- 주문금액 -->
<input type=hidden name=Amt value="<?=$order_info->total_price?>">
<!-- 상점명 -->
<input type=hidden name=StoreNm value="<?=$shop_info->shop_name?>">
<!-- 상품명 -->
<input type=hidden name=ProdNm value="<?=$payment_prdname?>">
<!-- 상점 URL -->
<input type=hidden name=MallUrl value="<?=$shop_info->shop_url?>">
<!-- 주문자 email -->
<input type=hidden name=UserEmail value="<?=$order_info->send_email?>">
<!-- 사용자 아이디 (카드결제,핸드폰결제와 [현금영수증자동발급]에 필수) -->
<input type=hidden style=width:100px name=UserId value="<?=$_userid?>">
<!-- 카드결제 + 가상계좌 필수 변수 -->
<!-- 주문자명 -->
<input type=hidden name=OrdNm value="<?=$order_info->send_name?>">
<!-- 주문자 연락처 -->
<input type=hidden name=OrdPhone value="<?=$order_info->send_hphone?>">
<!-- 주문자 주소 -->
<input type=hidden name=OrdAddr value="<?=$order_info->send_address?>">
<!-- 수신자명 -->
<input type=hidden name=RcpNm value="<?=$order_info->rece_name?>">
<!-- 수신자 연락처 -->
<input type=hidden name=RcpPhone value="<?=$order_info->rece_hphone?>">
<!-- 수신자 주소 -->
<input type=hidden name=DlvAddr value="<?=$order_info->rece_address?>">
<!-- 기타사항 -->
<input type=hidden name=Remark value="<?=$order_info->message?>">
<!-- 요기까지 카드결제 + 가상계좌 필수 변수 -->

<!-- 핸드폰 결제시 중요한 -->
<!--CP아이디 -->
<!-- CP아이디를 핸드폰 결제 실거래 전환후에는 발급받으신 CPID로 변경하여 주시기 바랍니다. -->
<?=$_hidden_start?><input type=hidden name=HP_ID value=""><?=$_hidden_end?>
<!--CP비밀번호 -->
<!-- CP비밀번호를 핸드폰 결제 실거래 전환후에는 발급받으신 비밀번호로 변경하여 주시기 바랍니다. -->
<?=$_hidden_start?><input type=hidden name=HP_PWD value=""><?=$_hidden_end?>
<!-- SUB-CP아이디 -->
<!-- SUB-CPID는 핸드폰 결제 실거래 전환후에 발급받으신 상점만 입력하여 주시기 바랍니다. -->
<?=$_hidden_start?><input type=hidden name=HP_SUBID value=""><?=$_hidden_end?>
<!-- 상품코드 -->
<!-- 상품코드를 핸드폰 결제 실거래 전환후에는 발급받으신 상품코드로 변경하여 주시기 바랍니다. -->
<?=$_hidden_start?><input type=hidden name=ProdCode value=""><?=$_hidden_end?>
<!--상품종류-->
<!-- 상품종류를 핸드폰 결제 실거래 전환후에는 발급받으신 상품종류로 변경하여 주시기 바랍니다. -->
<!-- 판매하는 상품이 디지털(컨텐츠)일 경우 = 1, 실물(상품)일 경우 = 2 -->
<?=$_hidden_start?><input type=hidden name=HP_UNITType value="1"><?=$_hidden_end?>
<!-- 가상계좌 결제에서 입/출금 통보를 위한 필수 입력 사항 입니다. -->
<!-- 페이지주소는 도메인주소를 제외한 '/'이후 주소를 적어주시면 됩니다. -->
<?=$_hidden_start2?><input type=hidden name=MallPage value="shop/allthegate/auto_vbankresult.php"><?=$_hidden_end2?>

    
<table width=100% class="AW_order_table" style="margin:30px 0;">
	<tr>
		<td width="20%" class="tit">결제방법</td>
		<td width="80%" class="val"><?=pay_method($pay_method)?></td>
	</tr>
	<tr>
		<td width="20%" class="tit">결제금액</td>
		<td width="80%" class="val"><span class="price"><?=number_format($order_info->total_price)?>원</span></td>
	</tr>
</table>
<div class="AW_btn_area">
	<button type="button" class="submit_btn"onclick="javascript:Pay(frmAGS_pay);" >결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">주문취소</button>
</div>
    
<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->

<!-- 각 결제 공통 사용 변수 -->
<input type=hidden name=Flag value="">				<!-- 스크립트결제사용구분플래그 -->
<input type=hidden name=AuthTy value="">			<!-- 결제형태 -->
<input type=hidden name=SubTy value="">				<!-- 서브결제형태 -->

<!-- 신용카드 결제 사용 변수 -->
<input type=hidden name=DeviId value="">			<!-- (신용카드공통)		단말기아이디 -->
<input type=hidden name=QuotaInf value="0">			<!-- (신용카드공통)		일반할부개월설정변수 -->
<input type=hidden name=NointInf value="NONE">		<!-- (신용카드공통)		무이자할부개월설정변수 -->
<input type=hidden name=AuthYn value="">			<!-- (신용카드공통)		인증여부 -->
<input type=hidden name=Instmt value="">			<!-- (신용카드공통)		할부개월수 -->
<input type=hidden name=partial_mm value="">		<!-- (ISP사용)			일반할부기간 -->
<input type=hidden name=noIntMonth value="">		<!-- (ISP사용)			무이자할부기간 -->
<input type=hidden name=KVP_RESERVED1 value="">		<!-- (ISP사용)			RESERVED1 -->
<input type=hidden name=KVP_RESERVED2 value="">		<!-- (ISP사용)			RESERVED2 -->
<input type=hidden name=KVP_RESERVED3 value="">		<!-- (ISP사용)			RESERVED3 -->
<input type=hidden name=KVP_CURRENCY value="">		<!-- (ISP사용)			통화코드 -->
<input type=hidden name=KVP_CARDCODE value="">		<!-- (ISP사용)			카드사코드 -->
<input type=hidden name=KVP_SESSIONKEY value="">	<!-- (ISP사용)			암호화코드 -->
<input type=hidden name=KVP_ENCDATA value="">		<!-- (ISP사용)			암호화코드 -->
<input type=hidden name=KVP_CONAME value="">		<!-- (ISP사용)			카드명 -->
<input type=hidden name=KVP_NOINT value="">			<!-- (ISP사용)			무이자/일반여부(무이자=1, 일반=0) -->
<input type=hidden name=KVP_QUOTA value="">			<!-- (ISP사용)			할부개월 -->
<input type=hidden name=CardNo value="">			<!-- (안심클릭,일반사용)	카드번호 -->
<input type=hidden name=MPI_CAVV value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=MPI_ECI value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=MPI_MD64 value="">			<!-- (안심클릭,일반사용)	암호화코드 -->
<input type=hidden name=ExpMon value="">			<!-- (일반사용)			유효기간(월) -->
<input type=hidden name=ExpYear value="">			<!-- (일반사용)			유효기간(년) -->
<input type=hidden name=Passwd value="">			<!-- (일반사용)			비밀번호 -->
<input type=hidden name=SocId value="">				<!-- (일반사용)			주민등록번호/사업자등록번호 -->

<!-- 계좌이체 결제 사용 변수 -->
<input type=hidden name=ICHE_OUTBANKNAME value="">	<!-- 이체계좌은행명 -->
<input type=hidden name=ICHE_OUTACCTNO value="">	<!-- 이체계좌예금주주민번호 -->
<input type=hidden name=ICHE_OUTBANKMASTER value=""><!-- 이체계좌예금주 -->
<input type=hidden name=ICHE_AMOUNT value="">		<!-- 이체금액 -->

<!-- 핸드폰 결제 사용 변수 -->
<input type=hidden name=HP_SERVERINFO value="">		<!-- 서버정보 -->
<input type=hidden name=HP_HANDPHONE value="">		<!-- 핸드폰번호 -->
<input type=hidden name=HP_COMPANY value="">		<!-- 통신사명(SKT,KTF,LGT) -->
<input type=hidden name=HP_IDEN value="">			<!-- 인증시사용 -->
<input type=hidden name=HP_IPADDR value="">			<!-- 아이피정보 -->

<!-- ARS 결제 사용 변수 -->
<input type=hidden name=ARS_PHONE value="">			<!-- ARS번호 -->
<input type=hidden name=ARS_NAME value="">			<!-- 전화가입자명 -->

<!-- 가상계좌 결제 사용 변수 -->
<input type=hidden name=ZuminCode value="">			<!-- 가상계좌입금자주민번호 -->
<input type=hidden name=VIRTUAL_CENTERCD value="">	<!-- 가상계좌은행코드 -->
<input type=hidden name=VIRTUAL_DEPODT value="">	<!-- 가상계좌입금예정일 -->
<input type=hidden name=VIRTUAL_NO value="">		<!-- 가상계좌번호 -->

<input type=hidden name=mTId value="">

<!-- 에스크로 결제 사용 변수 -->
<input type=hidden name=ES_SENDNO value="">			<!-- 에스크로전문번호 -->

<!-- 텔래뱅킹-계좌이체 결제 사용 변수 -->
<input type=hidden name=ICHEARS_ADMNO value="">
<input type=hidden name=ICHEARS_POSMTID value="">
<input type=hidden name=ICHEARS_CENTERCD value="">
<input type=hidden name=ICHEARS_HPNO value="">

<!-- 계좌이체(소켓) 결제 사용 변수 -->
<input type=hidden name=ICHE_SOCKETYN value="">		<!-- 계좌이체(소켓) 사용 여부 -->
<input type=hidden name=ICHE_POSMTID value="">		<!-- 계좌이체(소켓) 이용기관주문번호 -->
<input type=hidden name=ICHE_FNBCMTID value="">		<!-- 계좌이체(소켓) FNBC거래번호 -->
<input type=hidden name=ICHE_APTRTS value="">		<!-- 계좌이체(소켓) 이체 시각 -->
<input type=hidden name=ICHE_REMARK1 value="">		<!-- 계좌이체(소켓) 기타사항1 -->
<input type=hidden name=ICHE_REMARK2 value="">		<!-- 계좌이체(소켓) 기타사항2 -->
<input type=hidden name=ICHE_ECWYN value="">		<!-- 계좌이체(소켓) 에스크로여부 -->
<input type=hidden name=ICHE_ECWID value="">		<!-- 계좌이체(소켓) 에스크로ID -->
<input type=hidden name=ICHE_ECWAMT1 value="">		<!-- 계좌이체(소켓) 에스크로결제금액1 -->
<input type=hidden name=ICHE_ECWAMT2 value="">		<!-- 계좌이체(소켓) 에스크로결제금액2 -->
<input type=hidden name=ICHE_CASHYN value="">		<!-- 계좌이체(소켓) 현금영수증발행여부 -->
<input type=hidden name=ICHE_CASHGUBUN_CD value="">	<!-- 계좌이체(소켓) 현금영수증구분 -->
<input type=hidden name=ICHE_CASHID_NO value="">	<!-- 계좌이체(소켓) 현금영수증신분확인번호 -->

<!-- 스크립트 및 플러그인에서 값을 설정하는 Hidden 필드  !!수정을 하시거나 삭제하지 마십시오-->
</form>
