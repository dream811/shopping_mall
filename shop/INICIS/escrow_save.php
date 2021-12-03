<?
include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 					// 유틸 라이브러리
include "../../inc/oper_info.inc"; 		// 운영 정보

if(!strcmp($ctype, "CFRM")) {

	$sql = "UPDATE wiz_order SET escrow_stats = 'US' where tno = '$tno'";
	$result = mysqli_query($connect, $sql);

} else if(!strcmp($ctype, "CNCL")) {

	$sql = "update wiz_order set status = 'RD', cancelmsg='에스크로 구매취소 요청', escrow_stat = 'UX' where tno = '$tno'";
	$result = mysqli_query($connect, $sql);

}
?>

<script Language="Javascript">
<!--

	alert("구매확인/거절이 성공적으로 완료되었습니다.");
	self.close();

//-->
</script>