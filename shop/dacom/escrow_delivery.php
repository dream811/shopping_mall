<?
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 					// 유틸 라이브러리
include "../../inc/oper_info.inc"; 		// 운영 정보

//**************************//
//
// 배송결과 송신 PHP
//
//**************************//

if(!strcmp($oper_info->pay_test, "Y")) {//테스트
	$oper_info->pay_id = "t".$oper_info->pay_id;
	$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
	$mid = $oper_info->pay_id;
	$pay_key = $oper_info->pay_key;
	$tport = ":7085";
	$_htt = "";
}else{//실거래
	$platform	= "service";
	$mid = $oper_info->pay_id;
	$pay_key = $oper_info->pay_key;
	$tport = "";
	$_htt = "s";
}

$service_url = "http".$_htt."://pgweb.dacom.net".$tport."/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp";

$oid = get_param("oid");									// 주문번호
$productid = get_param("productid");			// 상품ID
$dlvtype = "03";													// 등록내용구분
$dlvdate = get_param("dlvdate");					// 발송일자
$dlvcompcode = get_param("dlvcompcode");	// 배송회사코드
$dlvcomp = get_param("dlvcomp");					// 배송회사명
$dlvno = get_param("dlvno");							// 운송장번호

$mertkey = $pay_key;	// 각 상점의 테스트용 상점키와 서비스용 상점키

$hashdate;										// 인증키
$datasize = 1;								// 여러건 전송일대 상점셋팅
//발송정보
$hashdata = md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey);

	// 데이콤의 배송결과등록페이지를 호출하여 배송정보등록함
	/*
	*	아래 URL 을 호출시 파라메터의 값에 공백이 발생하면 해당 URL이 비정상적으로 호출됩니다.
	*	배송사명등을 파라메터로 등록시 공백을 "||" 으로 변경하여 주시기 바랍니다.
	*/
	$str_url = $service_url."?mid=$mid&oid=$oid&productid=$productid&dlvtype=$dlvtype&rcvdate=$rcvdate&rcvname=$rcvname&rcvrelation=$rcvrelation&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvno=$dlvno&dlvworker=$dlvworker&dlvworkertel=$dlvworkertel&hashdata=$hashdata";





$ch = curl_init();

	curl_setopt ($ch, CURLOPT_URL, $str_url);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

	$fp = curl_exec ($ch);

	if(curl_errno($ch)){
		echo "fopen access ERROR";
		// 연결실패시 DB 처리 로직 추가
	}else{
		if(trim($fp)=="OK"){
			$sql = "UPDATE wiz_order SET escrow_stats='DE' WHERE orderid='$oid'";
				mysqli_query($connect, $sql) or die(mysqli_error($connect));
				echo "OK";
				echo "<script language='javascript'>self.close();</script>";
				// 정상처리되었을때 DB 처리
		}else{
			echo "FAILD";
				echo "<br>에스크로 배송정보발송 실패";
				echo "<br>$str_url";
				//echo "<script language='javascript'>alert('에스크로 배송정보발송 실패');</script>$str_url";
				// 비정상처리 되었을때 DB 처리
		}
	}
	curl_close($ch);






//**********************************
// 아래 있는 그대로 사용하십시요.
//**********************************
function get_param($name)
{
	global $_POST, $_GET;
	if (!isset($_POST[$name]) || $_POST[$name] == "") {
		if (!isset($_GET[$name]) || $_GET[$name] == "") {
			return false;
		} else {
			 return $_GET[$name];
		}
	}
	return $_POST[$name];
}

?>
