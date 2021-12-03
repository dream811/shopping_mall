<?
$sub_tit="주문내역";
?>
<? include "../inc/header.php" ?>

<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<div style="font-size:.75rem; color:#999; line-height:1.3; margin:.625rem 1rem 0;">※ 상세 배송정보는 PC의 주문내역에서 확인가능합니다.</div>

<script language="JavaScript">
<!--

// 주문취소
function orderCancel(orderid, status) {
	if(status == "OR") {
		alert("결제완료된 주문만 취소요청이 가능합니다.");
	} else if(status == "RD") {
		alert("이미 취소요청한 상태입니다.");
	} else if(status == "RC" || status == "OC") {
		alert("최소처리가 완료된 상태입니다.");
	} else {
		var url = "/shop/order_cancel.php?orderid=" + orderid;
	  window.open(url, "orderCancel", "height=270, width=470, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, left=300, top=300");
	}
}

// 주문취소 해제
function orderRemoval(orderid)
{
	if(confirm("주문취소를 해제하시겠습니까?")){
		goURL('order_status.php?orderid=' + orderid);
	}
}

// 상세내역보기
function showOrderDetail(orderid) {

	document.getElementById("order_detail_"+orderid).style.display = "block";
	document.getElementById("order_detail_btn_"+orderid).style.display = "none";

}

// 상세내역닫기
function hideOrderDetail(orderid) {

	document.getElementById("order_detail_"+orderid).style.display = "none";
	document.getElementById("order_detail_btn_"+orderid).style.display = "block";

}

//-->
</script>
<div class="order_list_wrap">
<?php
// 로그인 하지 않은경우 로그인 페이지로 이동
if(empty($wiz_session['id']) && empty($order_guest)){
	echo "<script>goURL('login.php?prev=$PHP_SELF&orderlist=true');</script>";
	exit;
}

if($wiz_session['id'] != ""){
	$search_sql = " send_id = '".$wiz_session['id']."' ";
} else {
	$search_sql = " orderid = '$orderid' and send_name = '$send_name' ";
}

$sql = "select orderid from wiz_order where $search_sql and status != '' order by order_date desc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

$rows = 3;
$lists = 5;
$page_count = ceil($total/$rows);
if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
$start = ($page-1)*$rows;

$sql = "select * from wiz_order where $search_sql and status != '' order by order_date desc limit $start, $rows";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

while($row = mysqli_fetch_object($result)){

	// 주문상품 정보
	$basket_list = array();

	$b_sql = "select * from wiz_basket where orderid='".$row->orderid."'";
	$b_result = mysqli_query($connect, $b_sql) or error(mysqli_error($connect));
	$b_total = mysqli_num_rows($b_result);
	while($b_row = mysqli_fetch_object($b_result)){

		$basket_list[] = $b_row;

		///////////////////////////////////////////////////////////////////////////////////
		//결제에 들어갈 상품이름 (1개일경우 :마우스 , 2개이상일경우 마우스 외1개 로 출력)//
		///////////////////////////////////////////////////////////////////////////////////
		if($b_total>1){//1개 이상일경우
			$payment_prdname = $b_row->prdname." 외".($b_total-1)."개";
		}else{//한개일경우
			$payment_prdname = $b_row->prdname;
		}

	}

?>
<div class="order_list">
	<h2><?=$payment_prdname?></h2>

	<div class="info_box">
		<div class="order_info clearfix">
			<span class="date"><?=$row->order_date?></span>
			<span class="id"><?=$row->orderid?></span>
		</div>
		<div class="order_status clearfix">
			<span class="status"><?=order_status($row->status)?></span>
			<input type="button" value="주문취소" class="cancle_btn" onClick="orderCancel('<?=$row->orderid?>', '<?=$row->status?>')" />
		</div>
	</div>

	<div id="order_detail_<?=$row->orderid?>" class="detail_id" style="display:none">
		<!-- >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>상세내역보기 추가부분 시작<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< -->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table basket">
		  <tr>
			<th height="40">구매상품정보</th>
			<th style="width:12%">금액</th>
			<th style="width:12%">적립</th>
			<th style="width:8%">수량</th>
		  </tr>
		  <?php
		  if(is_array($basket_list)) {
			foreach($basket_list as $b_idx => $b_data) {
			if($b_data->opttitle5 != '') $optcode .= "$b_data->opttitle5 : $b_data->optcode5, ";
			if($b_data->opttitle6 != '') $optcode .= "$b_data->opttitle6 : $b_data->optcode6, ";
			if($b_data->opttitle7 != '') $optcode .= "$b_data->opttitle7 : $b_data->optcode7 ";

			if($b_data->opttitle3 != '') $optcode .= "$b_data->opttitle3 : $b_data->optcode3, ";
			if($b_data->opttitle4 != '') $optcode .= "$b_data->opttitle4 : $b_data->optcode4, ";

			if($b_data->opttitle != '') $optcode .= $b_data->opttitle;
			if($b_data->opttitle != '' && $b_data->opttitle2 != '') $optcode .= "/";
			if($b_data->opttitle2 != '') $optcode .= $b_data->opttitle2;
			if($b_data->opttitle != '' || $b_data->opttitle2 != '') $optcode .= " : ".$b_data->optcode.", ";

			if(!empty($b_data->del_type) && strcmp($b_data->del_type, "DA")) {
				if(!strcmp($b_data->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($b_data->del_type)." : ".number_format($b_data->del_price)."원)";
				else $del_type = "<br>(".deliver_name_prd($b_data->del_type).")";
			}

			// 상품 이미지
			if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$b_data->prdimg)) $b_data->prdimg = "/images/noimg_R.gif";
			else $b_data->prdimg = "/data/prdimg/".$b_data->prdimg;

			$purl = "prdview.php?prdcode=".$b_data->prdcode;
		  ?>
		  <tr>
			<td style="padding:6px; text-align:left;"><a href="<?=$purl?>" target="_blank"><?=$b_data->prdname?></a> <?=$optcode?><?=$del_type?></td>
			<td><?=number_format($b_data->prdprice)?>원</td>
			<td><?=number_format($b_data->prdreserve)?></td>
			<td><?=number_format($b_data->amount)?></td>
		  </tr>
		  <?php
			}
		  } else {
		  ?>
		  <tr>
			<td colspan="4" align="center" sylte="height:80px; text-align:center;">주문내역이 없습니다.</td>
		  </tr>
		  <?php
			}
			?>
		</table>
		<div style="padding:8px 3%;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
		  <tr>
			<th>결제금액</th>
			<td style="text-align:right; font-size:1rem; font-weight:500;"><font class="prd_price"><?=number_format($row->total_price)?></font>원</td>
		  </tr>
		</table>
		</div>

		<h3>배송지정보</h3>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table address">
		  <tr>
			<th>보내는 분</th>
			<td><?=$row->send_name?></td>
		  </tr>
		  <tr>
			<th>받는 분</th>
			<td><?=$row->rece_name?></td>
		  </tr>
		  <tr>
			<th>받는 분 전화</th>
			<td><?=$row->rece_tphone?></td>
		  </tr>
		  <tr>
			<th>받는 분 휴대폰</th>
			<td><?=$row->rece_hphone?></td>
		  </tr>
		  <tr>
			<th>배송지 주소</th>
			<td>우편번호 : <?=$row->rece_post?> 주소 : <?=$row->rece_address?> <?=$row->rece_address2?></td>
		  </tr>
		  <tr>
			<th>결제방식</th>
			<td><?=pay_method($row->pay_method)?> <? if($row->pay_method == "PB"){ ?>: <?=$row->account?> <? } ?></td>
		  </tr>
		  <? if($row->demand != "") { ?>
		  <tr>
			<th>요청사항</th>
			<td><?=nl2br($row->demand)?></td>
		  </tr>
		  <? } ?>
		</table>
		<!-- >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>상세내역보기 추가부분 끝<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< -->

		<!-- 상세내역'보기' 에서 상세내역'닫기'로 변경 -->
		<div class="order_detail clearfix">
			<input name="" type="button" class="more_btn" value="상세내역닫기" onClick="hideOrderDetail('<?=$row->orderid?>')" />
		</div>
	</div>

	<div id="order_detail_btn_<?=$row->orderid?>">
		<div class="order_detail clearfix">
			<span class="price"><?=number_format($row->total_price)?>원</span>
			<input type="button" class="more_btn" value="상세내역보기" onClick="showOrderDetail('<?=$row->orderid?>')" />
		</div>
	</div>
</div><!-- //order_list -->
<?
	$rows--;
}

if($total <= 0){
?>
<div style="padding-left:10px; padding-right:10px; padding-top:20px; text-align:center;"><b>구매내역이 없습니다.</b></div>
<?php
}
?>
</div><!-- //order_list_wrap -->
<div class="page_no"><? print_pagelist($page, $lists, $page_count, ""); ?></div>

<? include "../inc/footer.php" ?>
