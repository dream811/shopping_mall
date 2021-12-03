<table width="100%" border="0" cellspacing="0" cellpadding="0" class="prd_basket">
	<?
	// 각 입점업체별 상품 수 구하기
	$sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket where orderid='".$orderid."' group by mallid";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_array($result)) {

		$mall_list[$row[mallid]]['cnt'] = $row['cnt'];
		$mall_list[$row[mallid]][total] = $row[total];

	}

	// 주문정보
	$sql = "select * from wiz_order where orderid='".$orderid."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

	// 주문상품 정보
	$sql = "select wb.*, wm.com_name, wm.com_tel, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2 from wiz_basket as wb left join wiz_mall as wm on wb.mallid = wm.id where orderid='".$orderid."' order by wb.mallid";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	while($row = mysqli_fetch_object($result)){

		$del_type = "";

		///////////////////////////////////////////////////////////////////////////////////
		//결제에 들어갈 상품이름 (1개일경우 :마우스 , 2개이상일경우 마우스 외1개 로 출력)//
		///////////////////////////////////////////////////////////////////////////////////
		if($total>1){//1개 이상일경우
			$payment_prdname = $row->prdname." 외".($total-1)."개";
		}else{//한개일경우
			$payment_prdname = $row->prdname;
		}
		////////////////////////////////////////////////////////////////////////////////////
		$optcode = "";
		/*
		if($row->opttitle != '') $optcode = "$row->opttitle : $row->optcode / ";
		if($row->opttitle2 != '') $optcode .= "$row->opttitle2 : $row->optcode2 / ";
		if($row->opttitle3 != '') $optcode .= "$row->opttitle3 : $row->optcode3 / ";

		if($row->opttitle2 != '') $row->opttitle2 = "/".$row->opttitle2;
		if($row->optcode2 != '') $row->optcode2 = "/".$row->optcode2;

		if($row->opttitle != '') $optcode .= $row->opttitle.$row->opttitle2." : ".$row->optcode.$row->optcode2.", ";

		*/

		if($row->opttitle5 != '') $optcode .= "$row->opttitle5 : $row->optcode5, ";
		if($row->opttitle6 != '') $optcode .= "$row->opttitle6 : $row->optcode6, ";
		if($row->opttitle7 != '') $optcode .= "$row->opttitle7 : $row->optcode7 ";

		if($row->opttitle3 != '') $optcode .= "$row->opttitle3 : $row->optcode3, ";
		if($row->opttitle4 != '') $optcode .= "$row->opttitle4 : $row->optcode4, ";

		if($row->opttitle != '') $optcode .= $row->opttitle;
		if($row->opttitle != '' && $row->opttitle2 != '') $optcode .= "/";
		if($row->opttitle2 != '') $optcode .= $row->opttitle2;
		if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= " : ".$row->optcode.", ";

		if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
			if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)." : ".number_format($row->del_price)."원)";
			else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
		}

		// 상품 이미지
		if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg)) $row->prdimg = "/images/noimg_R.gif";
		else $row->prdimg = "/data/prdimg/".$row->prdimg;

		if(empty($row->mallid)) {
			$row->com_name = $shop_info->shop_name;
			$row->com_tel = $shop_info->shop_tel;
		}

		$mall_no[$row->mallid]++;

		$purl = "prdview.php?prdcode=".$row->prdcode;
	?>
	<tr>
		<td colspan="2" style="padding:12px 3% 0;"></td>
	</tr>
	<tr>
		<td width="75" valign="top"><a href="<?=$purl?>" class="img_box"><img src="<?=$row->prdimg?>" width="75" height="75" /></a></td>
		<td style="padding:12px 3% 12px 12px; vertical-align:top;" valign="top">
			<div class="prd_tit"><a href="<?=$purl?>"><?=$row->prdname?></a></div>
			<div style="padding-top:8px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="basket_inner">
				<tr>
					<th width="65">상품가격</th>
					<td><font class="prd_price"><?=number_format($row->prdprice)?>원</font></td>
				</tr>
				<tr>
					<th>적립금</th>
					<td><?=number_format($row->prdreserve)?>원</td>
				</tr>
				<tr>
					<th>수량</th>
					<td><?=number_format($row->amount)?></td>
				</tr>
				<? if($optcode != "") { ?>
				<tr>
					<th>상품옵션</th>
					<td><?=$optcode?></td>
				</tr>
				<? } ?>
				<? if($del_type != "") { ?>
				<tr>
					<th>배송방법</th>
					<td><?=$del_type?></td>
				</tr>
				<? } ?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor='#E5E5E5'></td></tr>
	<?php
	if(!strcmp($mall_no[$row->mallid], $mall_list[$row->mallid]['cnt'])) {

		if(!empty($row->del_method)) {

			$tmp_oper_info->mallid 				= $row->mallid;

			$tmp_oper_info->del_method 		= $row->del_method;
			$tmp_oper_info->del_fixprice 	= $row->del_fixprice;
			$tmp_oper_info->del_staprice 	= $row->del_staprice;
			$tmp_oper_info->del_staprice2 = $row->del_staprice2;
			$tmp_oper_info->del_staprice3 = $row->del_staprice3;

			$tmp_oper_info->del_prd	 = $row->del_prd;
			$tmp_oper_info->del_prd2 = $row->del_prd2;

		} else {
			$tmp_oper_info = $oper_info;
		}

		// 배송비
		$deliver_price_mall[$row->mallid] = deliver_price($mall_list[$row->mallid][total], $tmp_oper_info, $row->mallid);

		$deliver_price += $deliver_price_mall[$row->mallid];

		if($row->del_price_mall > $deliver_price_mall[$row->mallid]){
		$deliver_msg .= " , 배송비 할증";
	}

	// 쿠폰사용
	if($row->coupon_use > 0){
		$coupon_msg = " - 쿠폰 사용(<b>".number_format($row->coupon_use)."</b>)";
	}
	?>
	<tr>
		<td height="30" colspan="2" style="padding:12px 3%">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="basket_info">
				<tr>
					<th><b>판매자 : <?=$row->com_name?>(<?=$row->com_tel?>)</b></th>
					<td align="right" width="120">
						상품가격 <b><?=number_format($mall_list[$row->mallid][total])?>원</b>
						<?=$coupon_msg?>
					</td>
				</tr>
				<tr>
					<th>[배송비 : <?=$deliver_msg?>]</th>
					<td align="right">
						배송비 <b><?=number_format($deliver_price_mall[$row->mallid])?>원</b>
						<?=$coupon_msg?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor='#E5E5E5'></td></tr>
	<? } ?>
	<?
		$no++;
	}

	if($total <= 0){
		echo "<tr><td colspan=2 height=50 align=center style='vertical-align:middle; font-size:.95rem;'>장바구니가 비어있습니다.</td></tr>";
	}

	// 배송비
	$deliver_price = deliver_price($order_info->prd_price, $oper_info);
	if($order_info->deliver_price > $deliver_price){
		$deliver_msg .= " , 배송비 할증";
	}

	// 회원할인 [$discount_msg 메세지 생성]
	$discount_price = level_discount($wiz_session[level],$order_info->prd_price);

	// 적립금 사용
	if($order_info->reserve_use > 0){
		$reserve_msg = " - 적립금 사용(<b>".number_format($order_info->reserve_use)."</b>)";
	}

	// 쿠폰사용
	if($order_info->coupon_use > 0){
		$coupon_msg = " - 쿠폰 사용(<b>".number_format($order_info->coupon_use)."</b>)";
	}
?>
</table>
<div class="cart_total">
	<h3>결제금액 안내</h3>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th align="left">상품가격</th>
		<td align="right"><?=number_format($order_info->prd_price)?>원
			<span><?=$discount_msg?></span>
		</td>
	</tr>
	<tr>
		<th align="left">배송비</th>
		<td align="right"><?=number_format($order_info->deliver_price)?>원</td>
	</tr>
	<tr>
		<th align="left">주문총액</th>
		<td align="right" class="total_price"><?=number_format($order_info->total_price)?>원</td>
	</tr>
	</table>
</div>
<div class="gry_bar"></div>
