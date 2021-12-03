<?
if(!strcmp($oper_info->pay_test, "Y")) {
	$oper_info->pay_id = "hanatest01";
	$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
}
/////////////////////
//wiz_shopinfo 로드//
/////////////////////
$shop_info = mysqli_fetch_object(mysqli_query($connect, "SELECT * FROM wiz_shopinfo"));

//이니시스 제공 상점아이디 및 테스트 아이디 설정
$mid = $oper_info->pay_id;
$oid = $order_info->orderid;					//주문번호
$amount = $order_info->total_price;		//결제금액

/////////////////
//결제방법 출력//
/////////////////
switch($pay_method){
		case "PC"://신용카드
			$_paymethod = "onlycard";break;
		case "PN"://계좌이체
			$_paymethod = "onlydbank";break;
		case "PV"://가상계좌
			$_paymethod = "onlyvbank";break;
			$_hidden_start2="";$_hidden_end2="";
		case "PH";//휴대폰
			$_paymethod = "onlyhpp";
			$_hidden_start="";$_hidden_end="";
			break;
}
?>

<head>
<script language=javascript src="http://plugin.inicis.com/pay40.js"></script>

<script language=javascript>
StartSmartUpdate();
</script>

<!----------------------------------------------------------------------------------
※ 주의 ※
 상단 자바스크립트는 지불페이지를 실제 적용하실때 지불페이지 맨위에 위치시켜
 적용하여야 만일에 발생할수 있는 플러그인 오류를 미연에 방지할 수 있습니다.

  <script language=javascript src="http://plugin.inicis.com/pay40.js"></script>
  <script language=javascript>
  StartSmartUpdate();	// 플러그인 설치(확인)
  </script>
----------------------------------------------------------------------------------->


<script language=javascript>

var openwin;

function pay(frm)
{
	// MakePayMessage()를 호출함으로써 플러그인이 화면에 나타나며, Hidden Field
	// 에 값들이 채워지게 됩니다. 일반적인 경우, 플러그인은 결제처리를 직접하는 것이
	// 아니라, 중요한 정보를 암호화 하여 Hidden Field의 값들을 채우고 종료하며,
	// 다음 페이지인 INIsecurepay.php로 데이터가 포스트 되어 결제 처리됨을 유의하시기 바랍니다.

	if(document.ini.clickcontrol.value == "enable")
	{

		if(document.INIpay == null || document.INIpay.object == null)  // 플러그인 설치유무 체크
		{
			alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.");
			return false;
		}
		else
		{

			/******
			 * 플러그인이 참조하는 각종 결제옵션을 이곳에서 수행할 수 있습니다.
			 * (자바스크립트를 이용한 동적 옵션처리)
			 */


			if (MakePayMessage(frm))
			{
				disable_click();
				openwin = window.open("/shop/INICIS/childwin.html","childwin","width=299,height=149");
				return true;
			}
			else
			{
				alert("결제를 취소하셨습니다.");
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


function enable_click()
{
	document.ini.clickcontrol.value = "enable"
}

function disable_click()
{
	document.ini.clickcontrol.value = "disable"
}

function focus_control()
{
	if(document.ini.clickcontrol.value == "disable")
		openwin.focus();
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

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 onload="javascript:enable_click()" onFocus="javascript:focus_control()">

<form name=ini method=post action="./INICIS/order_update.php" onSubmit="return pay(this)">

<!--------------------------------------------------->
<!-- 결제를 위한 기본 정보 입력 폼  시작 (수정가능)-->
<!--------------------------------------------------->
<input type=hidden name=pay_method value="<?=$pay_method?>">
<!-- 결제 방법
Card:신용카드 결제 / VCard:인터넷안전 결제 / DirectBank:실시간 은행계좌이체 / HPP:핸드폰 결제 / PhoneBill:받는전화결제
Ars1588Bill:1588 전화 결제 / VBank:무통장 입금 / OCBPoint:OK 캐쉬백포인트 결제 / Culture:문화상품권 결제 / kmerce:K-merce 상품권 결제
TeenCash:틴캐시 결제 / dgcl:게임문화 상품권 결제 / BCSH:도서문화 상품권 결제 / OABK:미니뱅크 결제 / onlycard" >신용카드 결제(전용메뉴)
onlyisp:인터넷안전 결제 (전용메뉴) / onlydbank:실시간 은행계좌이체 (전용메뉴) / onlycid: 신용카드/계좌이체/무통장입금(전용메뉴)
onlyvbank:무통장입금(전용메뉴) / onlyhpp: 핸드폰 결제(전용메뉴) / onlyphone: 전화 결제(전용메뉴) / onlyocb: OK 캐쉬백 결제 - 복합결제 불가능(전용메뉴)
onlyocbplus: OK 캐쉬백 결제- 복합결제 가능(전용메뉴) / onlyculture: 문화상품권 결제(전용메뉴) / onlykmerce: K-merce 상품권 결제(전용메뉴)
onlyteencash:틴캐시 결제(전용메뉴) / onlydgcl:게임문화 상품권 결제(전용메뉴) / onlypoint:LGmyPoint / onlybcsh:도서문화 상품권 결제(전용메뉴)
onlyoabk:미니뱅크 결제(전용메뉴) -->
<input type=hidden name=gopaymethod value="<?=$_paymethod?>">
<!-- 상품명 -->
<input type=hidden name=goodname value="<?=$payment_prdname?>">
<!-- 주문금액 -->
<input type=hidden name=price value="<?=$order_info->total_price?>">
<!-- 주문자명 -->
<input type=hidden name=buyername value="<?=$order_info->send_name?>">
<!-- 주문자 email -->
<input type=hidden name=buyeremail value="<?=$order_info->send_email?>">
<!-- 주문자 연락처 -->
<input type=hidden name=buyertel value="<?=$order_info->send_hphone?>">
<!--
상점아이디.
테스트를 마친 후, 발급받은 아이디로 바꾸어 주십시오.
-->
<input type=hidden name=mid value="<?=$mid?>">
<!--
화폐단위
WON 또는 CENT
주의 : 미화승인은 별도 계약이 필요합니다.
-->
<input type=hidden name=currency value="WON">
<!--
무이자 할부
무이자로 할부를 제공 : yes
무이자할부는 별도 계약이 필요합니다.
카드사별,할부개월수별 무이자할부 적용은 아래의 카드할부기간을 참조 하십시오.
무이자할부 옵션 적용은 반드시 매뉴얼을 참조하여 주십시오.
-->
<input type=hidden name=nointerest value="no">


<!--
카드할부기간
각 카드사별로 지원하는 개월수가 다르므로 유의하시기 바랍니다.

value의 마지막 부분에 카드사코드와 할부기간을 입력하면 해당 카드사의 해당
할부개월만 무이자할부로 처리됩니다 (매뉴얼 참조).
-->
<input type=hidden name=quotabase value="선택:일시불:3개월:4개월:5개월:6개월:7개월:8개월:9개월:10개월:11개월:12개월">


<!-- 기타설정 -->
<!--
SKIN : 플러그인 스킨 칼라 변경 기능 - 6가지 칼라(ORIGINAL, GREEN, ORANGE, BLUE, KAKKI, GRAY)
HPP : 컨텐츠 또는 실물 결제 여부에 따라 HPP(1)과 HPP(2)중 선택 적용(HPP(1):컨텐츠, HPP(2):실물).
Card(0): 신용카드 지불시에 이니시스 대표 가맹점인 경우에 필수적으로 세팅 필요 ( 자체 가맹점인 경우에는 카드사의 계약에 따라 설정) - 자세한 내용은 메뉴얼  참조.
OCB : OK CASH BAG 가맹점으로 신용카드 결제시에 OK CASH BAG 적립을 적용하시기 원하시면 "OCB" 세팅 필요 그 외에 경우에는 삭제해야 정상적인 결제 이루어짐.
no_receipt : 은행계좌이체시 현금영수증 발행여부 체크박스 비활성화 (현금영수증 발급 계약이 되어 있어야 사용가능)
-->
<input type=hidden name=acceptmethod value="SKIN(ORIGINAL):HPP(2):OCB">


<!--
상점 주문번호 : 무통장입금 예약(가상계좌 이체),전화결재 관련 필수필드로 반드시 상점의 주문번호를 페이지에 추가해야 합니다.
결제수단 중에 은행 계좌이체 이용 시에는 주문 번호가 결제결과를 조회하는 기준 필드가 됩니다.
상점 주문번호는 최대 40 BYTE 길이입니다.
-->
<input type=hidden name=oid size=40 value="<?=$oid?>">


<!--
플러그인 좌측 상단 상점 로고 이미지 사용
이미지의 크기 : 90 X 34 pixels
플러그인 좌측 상단에 상점 로고 이미지를 사용하실 수 있으며,
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 상단 부분에 상점 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_logoimage_url  value="http://[사용할 이미지주소]"-->

<!--
좌측 결제메뉴 위치에 이미지 추가
이미지의 크기 : 단일 결제 수단 - 91 X 148 pixels, 신용카드/ISP/계좌이체/가상계좌 - 91 X 96 pixels
좌측 결제메뉴 위치에 미미지를 추가하시 위해서는 담당 영업대표에게 사용여부 계약을 하신 후
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 좌측 결제메뉴 부분에 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_menuarea_url value="http://[사용할 이미지주소]"-->

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
	<button type="submit" class="submit_btn">결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">주문취소</button>
</div>

<!--
플러그인에 의해서 값이 채워지거나, 플러그인이 참조하는 필드들
삭제/수정 불가
uid 필드에 절대로 임의의 값을 넣지 않도록 하시기 바랍니다.
-->
<input type=hidden name=quotainterest value="">
<input type=hidden name=paymethod value="">
<input type=hidden name=cardcode value="">
<input type=hidden name=cardquota value="">
<input type=hidden name=rbankcode value="">
<input type=hidden name=reqsign value="DONE">
<input type=hidden name=encrypted value="">
<input type=hidden name=sessionkey value="">
<input type=hidden name=uid value="">
<input type=hidden name=sid value="">
<input type=hidden name=version value=4000>
<input type=hidden name=clickcontrol value="">
</form>
