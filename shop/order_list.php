<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악

// 로그인 하지 않은경우 로그인 페이지로 이동
if(empty($wiz_session['id']) && empty($order_guest)){
	echo "<script>document.location='/member/login.php?prev=$PHP_SELF&orderlist=true';</script>";
	exit;
}

if(!empty($send_name)) $param = "send_name=$send_name&orderid=$orderid&order_guest=$order_guest";

include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$now_position = "<a href=/>Home</a> &gt; <strong>주문/배송조회</strong>";
$page_type = "orderdel";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인

?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">

        <table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tr>
             <td bgcolor="#a9a9a9" height="2"></td>
           </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="10%" height="35" align="center" bgcolor="#f9f9f9"><strong>주문일자</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>주문번호</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>결재금액</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>결재방법</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>배송상태</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>운송장번호</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>상세보기</strong></td>
            <td width="10%" align="center" bgcolor="#f9f9f9"><strong>영수증</strong></td>
          </tr>
          <tr>
             <td colspan="8" bgcolor="#d7d7d7" height="1"></td>
          </tr>
				<?
		    if($wiz_session['id'] != ""){
		    	$search_sql = " send_id = '".$wiz_session['id']."' ";
		    } else {
		    	$search_sql = " orderid = '$orderid' and send_name = '$send_name' ";
		    }

		    $sql = "select orderid from wiz_order where $search_sql and status != '' order by order_date desc";
		    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		    $total = mysqli_num_rows($result);

				$no = 0;
				$rows = 12;
				$lists = 5;
				$page_count = ceil($total/$rows);
				if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;

		    $sql = "select * from wiz_order where $search_sql and status != '' order by order_date desc limit $start, $rows";
		    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		    while(($row = mysqli_fetch_object($result)) && $rows){

		    	$stacolor = "259D28";
					if($row->status == "OC" || $row->status == "RC" || $row->status == "RD") $stacolor = "ED1C24";

		    ?>
				<tr height="30">
          <td align="center"><?=substr($row->order_date,0,10)?></td>
          <td align="center"><?=$row->orderid?></td>
          <td align="center"><?=number_format($row->total_price)?>원</td>
          <td align="center"><?=pay_method($row->pay_method)?></td>
          <td align="center"><?=order_status($row->status)?></td>
          <td align="center"><a href="order_view.php?orderid=<?=$row->orderid?>&page=<?=$page?>"><img src="../images/member/btn_detail.gif" border="0" align="absmiddle" /></a></td>
          <td align="center"><a href="order_view.php?orderid=<?=$row->orderid?>&page=<?=$page?>"><img src="../images/member/btn_detail.gif" border="0" align="absmiddle" /></a></td>
          <td align="center"><?=receipt_link($oper_info, $row)?></td>
        </tr>
        <tr>
           <td colspan="8" bgcolor="#d7d7d7" height="1"></td>
        </tr>
				<?
					$rows--;
				}
				if($total <= 0){
				?>
				<tr><td colspan="8" align="center" height="50">구매내역이 없습니다.</td></tr>
				<tr><td colspan="8" bgcolor="#d7d7d7" height="1"></td></tr>
				<?
				}
				?>
			</table>

			<table width="100%" height="50"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
			  	<td width="30%"></td>
			    <td width="30%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
			    <td width="30%"></td>
			  </tr>
			</table>
		</td>
	</tr>
</table>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>