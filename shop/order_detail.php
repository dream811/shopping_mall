<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/design_info.inc"; 	// 디자인 정보
include "../inc/oper_info.inc"; 		// 운영 정보

$now_position = "<a href=/>Home</a> &gt; 주문조회.배송조회";
$page_type = "orderdel";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인
include "../inc/now_position.inc";	// 현재위치

// 주문정보
$sql = "select * from wiz_order where orderid = '$orderid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$order_info = mysqli_fetch_object($result);

// 주문취소 버튼
get_cancel_btn();

// 에스크로 버튼
get_escrow_btn();

// 세금계산서 버튼
get_tax_btn();

if(!empty($HTTP_REFERER)) {
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
	if($pos === false) error("잘못된 경로 입니다.");
}

include "./prd_ordinfo.inc";			// 주문정보

?>

<table cellpadding=0 cellspacing=0 width="100%" align=center>
	<tr>
		<td align="center">

			<table width="100%">
				<tr><td height="10"></td></tr>
				<tr>
					<td><?=$ordinfo?></td>
				</tr>
			</table>

			<table width="98%">
				<tr><td height="10"></td></tr>
				<tr><td colspan="3" height=1 bgcolor=#dadada></td></tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td width="30%"><a href="javascript:history.go(-1);"><img src="/images/btn_list.gif" border="0"></a></td>
					<td align="center">
						<?=$cancel_btn?>
						<?=$escrow_btn?>
					</td>
					<td width="30%" align="right">
						<?=$tax_btn?>
					</td>
			  </tr>
			</table>

		</td>
	</tr>
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>