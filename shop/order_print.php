<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$order_info = mysqli_fetch_object($result);

if(!empty($HTTP_REFERER)) {
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");
}

?>
<html>
<head>
<title>:: 주문내역 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body onLoad="window.print();">
<table width="100%" cellpadding=20 cellspacing=0>
  <tr>
    <td>

			<? include "./order_info.inc"; ?>

    </td>
  </tr>
</table>
</body>
</html>