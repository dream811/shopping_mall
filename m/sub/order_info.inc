<div class="order_list">
<?php
	// 주문상품 정보
	$basket_list = array();

	$b_amount = 0;
	$b_sql = "select * from wiz_basket where orderid='".$orderid."'";
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

		$payment_amount += $b_row->amount;

	}

	// 회원할인
	if($order_info->discount_price > 0){
		$discount_msg = " - 회원할인(<b>".number_format($order_info->discount_price)."원</b>)";
	}

	// 적립금 사용
	if($order_info->reserve_use > 0){
		$reserve_msg = " - 적립금 사용(<b>".number_format($order_info->reserve_use)."원</b>)";
	}

	// 쿠폰사용
	if($order_info->coupon_use > 0){
		$coupon_msg = " - 쿠폰 사용(<b>".number_format($order_info->coupon_use)."원</b>)";
	}
?>
	<div class="info_box">
		<h2><?=$payment_prdname?></h2>
		<div class="prd_info clearfix">
			<span>주문수량 총 <?=number_format($payment_amount)?>개</span>
			<span>지급적립금 <?=number_format($order_info->reserve_price)?>원</span>
		</div>
		<div class="prd_price">
			<span>상품가격 <?=number_format($order_info->prd_price)?>원 +</span>
			<span>배송비 <?=number_format($order_info->deliver_price)?>원</span>
			<span class="total_price">총 결제금액 <strong><?=number_format($order_info->total_price)?>원</strong></span>
		</div>
	</div>
	<div class="detail_id" style="border-top:none;">
	<table width="94%" border="0" cellspacing="0" cellpadding="0" class="join_input_table basket" style="margin:0 auto; border-top:1px solid #ddd;">
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
				if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$b_data->prdimg)) $b_data->prdimg = "/images/noimg_R.gif";
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
	
	<h3 style="margin-top:20px;">배송지 정보</h3>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table address">
	  <tr>
	    <th>보내는 분</th>
	    <td><?=$order_info->send_name?></td>
	  </tr>
	  <tr>
	    <th>받는 분</th>
	    <td><?=$order_info->rece_name?></td>
	  </tr>
	  <tr>
	    <th>받는 분 전화</th>
	    <td><?=$order_info->rece_tphone?></td>
	  </tr>
	  <tr>
	    <th>받는 분 휴대폰</th>
	    <td><?=$order_info->rece_hphone?></td>
	  </tr>
	  <tr>
	    <th>배송지 주소</th>
	    <td>우편번호 : <?=$order_info->rece_post?> 주소 : <?=$order_info->rece_address?> <?=$order_info->rece_address2?></td>
	  </tr>
	  <? if($order_info->demand != "") { ?>
	  <tr>
	    <th>요청사항</th>
	    <td><?=nl2br($order_info->demand)?></td>
	  </tr>
	  <? } ?>
	  <tr>
	    <th>결제방식</th>
	    <td><?=pay_method($order_info->pay_method)?> <? if($order_info->pay_method == "PB"){ ?>: <?=$order_info->account?> <? } ?></td>
	  </tr>
	</table>
	</div>
</div><!-- //order_list -->