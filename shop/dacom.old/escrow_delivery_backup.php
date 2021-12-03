<?php
	include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
	include "../../inc/util.inc"; 					// 유틸 라이브러리
	include "../../inc/oper_info.inc"; 		// 운영 정보
//**************************//
//
// 배송결과 송신 PHP 예제
//
//**************************//
		if(!strcmp($oper_info->pay_test, "Y")) {//테스트
			$oper_info->pay_id = "tanywiz";
			$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
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
		//$mid;			//LG데이콤 결제서비스 선택(test:테스트, service:서비스)
		//$pay_key;		//상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)

// 테스트용
$service_url = "http".$_htt."://pgweb.dacom.net".$tport."/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp";

// 서비스용
//$service_url = "https://pgweb.dacom.net/pg/wmp/mertadmin/jsp/escrow/rcvdlvinfo.jsp";

//$mid = get_param("mid");						// 상점ID

$oid = get_param("oid");						// 주문번호
$productid = get_param("productid");			// 상품ID
$orderdate = str_replace(" ",'',get_param("orderdate"));			// 주문일자
//$dlvtype = get_param("dlvtype");				// 등록내용구분
$dlvtype = "03";				// 등록내용구분
//$rcvdate = get_param("rcvdate");				// 실수령일자
//$rcvname = get_param("rcvname");				// 실수령인명
//$rcvrelation = get_param("rcvrelation");		// 관계
$dlvdate = get_param("dlvdate");				// 발송일자
$dlvcompcode = get_param("dlvcompcode");		// 배송회사코드
$dlvcomp = get_param("dlvcomp");				// 배송회사명
$dlvno = get_param("dlvno");					// 운송장번호
$dlvworker = get_param("dlvworker");			// 배송자명
$dlvworkertel = get_param("dlvworkertel");		// 배송자전화번호

//$mertkey = "28cab5722dcbb2109c8fd80604574084";	// 각 상점의 테스트용 상점키와 서비스용 상점키
$mertkey = $pay_key;	// 각 상점의 테스트용 상점키와 서비스용 상점키
												// 테스트상점관리자와 서비스상점관리자의 계약정보관리에서 조회 가능

$hashdate;										// 인증키
$datasize = 1;									// 여러건 전송일대 상점셋팅
//발송정보
$hashdata = md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey);
// DB에서 배송정보 쿼리
/*
$query = "select * from temp";
$result = mysqli_query($connect, $query);
while($row=FetchNext($result))
{

	$mid =  $row["mid"];
	$oid =  $row["oid"];
	$dlvtype =  $row["dlvtype"];

	if("03"==$dlvtype)
	{
		// 발송정보
		$dlvdate =  $row["dlvdate"];
		$dlvcomp =  $row["dlvcomp"];
		$dlvno =  $row["dlvno"];
		$dlvworker =  $row["dlvworker"];
		$dlvworkertel =  $row["dlvworkertel"];

		$hashdata = md5($mid.$oid.$dlvdate.$dlvcompcode.$dlvno.$mertkey);
	}
	else if("01"==$dlvtype)
	{
		// 수령정보
		$rcvdate =  $row["rcvdate"];
		$rcvname =  $row["rcvname"];
		$rcvrelation =  $row["rcvrelation"];

		$hashdata = md5($mid.$oid.$dlvtype.$rcvdate.$mertkey);
	}

	// 데이콤의 배송결과등록페이지를 호출하여 배송정보등록함
	/*
	*	아래 URL 을 호출시 파라메터의 값에 공백이 발생하면 해당 URL이 비정상적으로 호출됩니다.
	*	배송사명등을 파라메터로 등록시 공백을 "||" 으로 변경하여 주시기 바랍니다.
	*/
	$str_url = $service_url."?mid=$mid&oid=$oid&productid=$productid&orderdate=$orderdate&dlvtype=$dlvtype&rcvdate=$rcvdate&rcvname=$rcvname&rcvrelation=$rcvrelation&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvno=$dlvno&dlvworker=$dlvworker&dlvworkertel=$dlvworkertel&hashdata=$hashdata";

	$fp = fopen($str_url,"r");

	if(!$fp)
	{
		echo "fopen access ERROR";
		// 연결실패시 DB 처리 로직 추가
	}
	else
	{
		// 해당 페이지 return값 읽기
		while(!feof($fp))
		{
				$res .= fgets($fp,3000);
		}

		if(trim($res) == "OK")
		{
				echo "OK";
				// 정상처리되었을때 DB 처리
		}
		else
		{
				echo "FAILD";
				// 비정상처리 되었을때 DB 처리
		}
	}


//**********************************
// 아래 있는 그대로 사용하십시요.
//**********************************
function get_param($name)
{
	global $HTTP_POST_VARS, $HTTP_GET_VARS;
	if (!isset($HTTP_POST_VARS[$name]) || $HTTP_POST_VARS[$name] == "") {
		if (!isset($HTTP_GET_VARS[$name]) || $HTTP_GET_VARS[$name] == "") {
			return false;
		} else {
			 return $HTTP_GET_VARS[$name];
		}
	}
	return $HTTP_POST_VARS[$name];
}

?>