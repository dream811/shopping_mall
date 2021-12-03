<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이페이지 &gt; <strong>주문/배송조회</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/mem_info.inc"; 		// 회원 정보

?>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
	var url = "/shop/order_view.php?orderid=" + orderid;
	window.open(url, "orderView", "height=640, width=736, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>
<div class="AW_ttl clearfix">
	<h2>주문 &#183; 배송조회</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>주문 &#183; 배송조회</span>
	</div>
</div>
<? include $_SERVER['DOCUMENT_ROOT'].'/inc/lnk_nav.php'; // 상단메뉴 ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_common_table">
  <tr>
	<th width="10%"><strong>주문일자</strong></th>
	<th width="10%"><strong>주문번호</strong></th>
	<th width="10%"><strong>결제금액</strong></th>
	<th width="10%"><strong>결제방법</strong></th>
	<th width="10%"><strong>배송상태</strong></th>
	<!--th width="10%"><strong>운송장번호</strong></th-->
	<th width="10%"><strong>영수증</strong></th>
	<th width="10%"><strong>자세히보기</strong></th>
  </tr>
  <?
	$sql = "select orderid from wiz_order where send_id = '".$wiz_session['id']."' and status != '' order by order_date desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	$rows = 12;
	$lists = 5;
	$page_count = ceil($total/$rows);
	if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;

	$sql = "select * from wiz_order where send_id = '".$wiz_session['id']."' and status != '' order by order_date desc limit $start, $rows";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while(($row = mysqli_fetch_object($result)) && $rows){
	?>
  <tr height="40">
	<td><?=substr($row->order_date,0,10)?></td>
	<td><?=$row->orderid?></td>
	<td><?=number_format($row->total_price)?>원</td>
	<td><?=pay_method($row->pay_method)?></td>
	<td><?=order_status($row->status)?></td>
	<!--td><a href="<?=$oper_info->del_trace?><?=$row->deliver_num?>" target="_blank"><?=$row->deliver_num?></a></td-->
	<td><?=receipt_link($oper_info, $row)?></td>
	<td><a href="my_orderview.php?orderid=<?=$row->orderid?>&page=<?=$page?>" class="lnk_btn">보기</a></td>
  </tr>
  <?
		$rows--;
	}

	if($total <= 0){
	?>
		<tr><td colspan="8" style="height:300px;"><img src="/images/no_icon.gif" align=absmiddle> 현재 구매내역이 없습니다.</td></tr>
	<?
	}
	?>
</table>

<? print_pagelist($page, $lists, $page_count, ""); ?>

	
<?
include "../inc/footer.inc"; 		// 하단디자인
?>