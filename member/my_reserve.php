<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";		// 로그인 체크
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이페이지 &gt; <strong>적립금내역</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인
include "../inc/mem_info.inc"; 			// 회원 정보
?>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
	var url = "/shop/order_view.php?orderid=" + orderid;
	window.open(url, "orderView", "height=700, width=1000, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>
<?
// 적립금
$sql = "select sum(reserve) as reserve from wiz_reserve where memid = '".$wiz_session['id']."'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$total_reserve = $row->reserve;

// 적립예정금액
$sql = "select sum(reserve_price) as pre_reserve from wiz_order where send_id = '".$wiz_session['id']."' and (status = 'OR' or status = 'OY' or status = 'DR' or status = 'DI')";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$pre_reserve = $row->pre_reserve;
?>
<div class="AW_ttl clearfix">
	<h2>내 적립금</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>내 적립금</span>
	</div>
</div>
<? include $_SERVER['DOCUMENT_ROOT'].'/inc/lnk_nav.php'; // 상단메뉴 ?>
<div class="AW_reserve">
	<h2 class="point">P</h2>
	<ul class="reserve_list">
		<li>
			<span class="ttl">사용가능 적립금</span>
			<span class="val"><strong><?=number_format($total_reserve)?></strong>원</span>
		</li>
		<li>
			<span class="ttl">적립 예정금</span>
			<span class="val"><strong><?=number_format($pre_reserve)?></strong>원</span>
		</li>
	</ul>
</div><!-- //AW_reserve -->
<!-- 게시판 리스트 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="prd_basket">
	<tbody>
		<tr>
		  <th width="20%" height="35">적립일자</th>
		  <th>적립내역</th>
		  <th width="18%">주문번호</th>
		  <th width="18%">금액</th>
		</tr>
		<?
			$sql = "select wr.idx from wiz_reserve as wr,wiz_order as wo where wr.orderid=wo.orderid and wr.memid = '".$wiz_session['id']."' order by wdate desc";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 12;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;

			$sql = "select wr.* from wiz_reserve as wr,wiz_order as wo where wr.orderid=wo.orderid and wr.memid = '".$wiz_session['id']."' order by wdate desc limit $start, $rows";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while(($row = mysqli_fetch_object($result)) && $rows){
		?>
		<tr height="30">
		  <td align="center"><?=$row->wdate?></td>
		  <td style="text-align:left; padding-left:10px;"><?=$row->reservemsg?></td>
		  <td align="center"><a href="javascript:orderView('<?=$row->orderid?>');"><?=$row->orderid?></a></td>
		  <td align="right" style="padding-left:10px"><?=number_format($row->reserve)?>원</td>
		</tr>
		<?
			$rows--;
		}
		if($total <= 0){
		?>
		<tr><td colspan="4" align="center" style="height:200px;">현재 적립금내역이 없습니다.</td></tr>
		<? } ?>
	</tbody>			
</table>
<? print_pagelist($page, $lists, $page_count, ""); ?>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>