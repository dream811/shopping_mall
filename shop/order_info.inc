<?php
include_once "../inc/shop_info.inc"; 		// 상점 정보
?>

<table width="100%" class="AW_basket_table prd_basket">
	<tbody>
	<tr align="center" bgcolor="#f9f9f9">
		<th width="80">이미지</th>
		<th align="center">구매상품정보</th>
		<th width="10%">제품가격</th>
		<th width="10%">적립금</th>
		<th width="10%">구매수량</th>
	</tr>
	<?
	unset($mall_list);
	unset($mall_no);

	// 각 입점업체별 상품 수 구하기
	$sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket where orderid='".$order_info->orderid."' group by mallid";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_array($result)) {

		$mall_list[$row['mallid']]['cnt'] = $row['cnt'];
		$mall_list[$row['mallid']]['total'] = $row['total'];

	}

	$sql = "select wb.*, wm.com_name, wm.com_tel, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2 from wiz_basket as wb left join wiz_mall as wm on wb.mallid = wm.id where orderid = '$order_info->orderid' order by wb.mallid";
	$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
	$prd_num = mysqli_num_rows($result);

	$no = 0;
	while($row = mysqli_fetch_object($result)){

		$prd_price += ($row->prdprice * $row->amount);
		if($row->prdimg == "") $row->prdimg = "/images/noimage.gif";
		else $row->prdimg = "/data/prdimg/".$row->prdimg;

		$optcode = "";
		if($row->opttitle5 != "") $optcode .= "$row->opttitle5 : $row->optcode5, ";
		if($row->opttitle6 != "") $optcode .= "$row->opttitle6 : $row->optcode6, ";
		if($row->opttitle7 != "") $optcode .= "$row->opttitle7 : $row->optcode7, ";

		if($row->opttitle3 != "") $optcode .= "$row->opttitle3 : $row->optcode3, ";
		if($row->opttitle4 != "") $optcode .= "$row->opttitle4 : $row->optcode4, ";

		if($row->opttitle != "") $optcode .= $row->opttitle;
		if($row->opttitle != "" && $row->opttitle2 != "") $optcode .= "/";
		if($row->opttitle2 != "") $optcode .= $row->opttitle2;
		if($row->opttitle != "" || $row->opttitle2 != "") $optcode .= " : ".$row->optcode.", ";

		if(!strcmp($row->status, "CA")) $basket_status = "<font color='red'>[취소신청]</font>";
		else if(!strcmp($row->status, "CI")) $basket_status = "<font color='red'>[처리중]</font>";
		else if(!strcmp($row->status, "CC")) $basket_status = "<font color='red'>[취소완료]</font>";
		else $basket_status = "";

		$del_type = "";
		if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
			if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)." : ".number_format($row->del_price)."원)";
			else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
		}

		if(empty($row->mallid)) {
			$row->com_name = $shop_info->shop_name;
			$row->com_tel = $shop_info->shop_tel;
		}

		$mall_no[$row->mallid]++;

	?>
	<tr>
		<td align=center><a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>" target="prdview"><img src="<?=$row->prdimg?>" border="0" width="60" height="60"></a></td>
		<td style="text-align:left; padding-left:12px;">
			<p style="font-size:16px; line-heigth:1.5;"><a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>" target="prdview"><?=$row->prdname?></a><?=$basket_status?></p>
			<?=$optcode?><?=$del_type?></td>
		<td class=price align=center><?=number_format($row->prdprice)?>원</td>
		<td align=center><b><?=number_format($row->prdreserve*$row->amount)?>원</b></td>
		<td align=center><b><?=$row->amount?></b></td>
	</tr>
	<?
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
		$coupon_msg_mall[$row->mallid] = " - 쿠폰사용(<b>".number_format($row->coupon_use)."</b>)";
	}

	if(!empty($row->deliver_num)) {
		$d_sql = "select * from wiz_mall where id = '$row->mallid'";
		$d_result = mysqli_query($connect, $d_sql) or error(mysqli_error($connect));
		$d_row = mysqli_fetch_object($d_result);

		echo "
			<tr>
				<td height='30' colspan='7' class='seller_info' style='padding:7px 10px;'>
					<p>판매자 : ".$row->com_name." (".$row->com_tel.")</p>
					<p>배송업체 및 운송장번호 : <b>".$d_row->del_com."</b> <a href='".$d_row->del_trace.$row->deliver_num."' target='_blank'>".$row->deliver_num."</a></p>		
					<span class='del_msg clearfix'>
						<span>배송비 : ".$deliver_msg."</span>
						<span class='price'>
							상품가격 <b>".number_format($mall_list[$row->mallid][total])."원</b> +
							배송비 <b>".number_format($deliver_price_mall[$row->mallid])."원</b>
							".$coupon_msg_mall[$row->mallid]."
						</span>
					</span>
				</td>
			</tr>";
			}
			echo "";
		 }

		$no++;
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
	<tr bgcolor=#f9f9f9>
		<td class="total_price" colspan="7" style="height:60px; text-align:right; padding-right:10px;">
			총결제금액 :  상품가격 <b><?=number_format($order_info->prd_price)?>원</b> <?=$discount_msg?> + 배송비 <b><?=number_format($order_info->deliver_price)?>원</b><?=$coupon_msg?><?=$reserve_msg?> = 
			<span class=price><?=number_format($order_info->total_price)?>원</span>
		</td>
	</tr>
	</tbody>
</table>

<h2 class="order_ttl">주문상품 정보</h2>		
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="AW_order_table">
	<tr>
		<td class="tit" width="20%">주문번호</td>
		<td class="val" width="30%">
			<?=$order_info->orderid?>
		</td>
		<td class="tit" width="20%">주문일</td>
		<td class="val" width="30%">
			<?=$order_info->order_date?>
		</td>
	</tr>
	<tr>
		<td class="tit">결제방법</td>
		<td class="val">
			<?=pay_method($order_info->pay_method)?>
		</td>
		<td class="tit">주문상태</td>
		<td class="val">
			<?=order_status($order_info->status)?>
		</td>
	</tr>
	<? if($order_info->pay_method == "PB"){ ?>
		<tr>
			<td class="tit">입금계좌</td>
			<td class="val">
				<?=$order_info->account?>
			</td>
			<td class="tit">입금자명</td>
			<td class="val">
				<?=$order_info->account_name?>
			</td>
	</tr>
	<? }else if($order_info->pay_method == "PV"){ ?>
	<tr>
		<td class="tit"><b>입금계좌</b></td>
		<td class="val" colspan=3><b><?=$order_info->account?></b>
			<font color=red>(가상계좌번호로 입금하셔야 주문이 완료됩니다.)</font>
		</td>
	</tr>
	<? } ?>

	<? if($order_info->deliver_num != ""){ ?>
	<tr>
		<td class="tit">운송장번호</td>
		<td class="val" colspan=3>
			<?=$order_info->deliver_num?>
		</td>
	</tr>
	<? } ?>

</table>

<h2 class="order_ttl">주문자 정보</h2>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="AW_order_table">
	<tr>
		<td width="20%" class="tit">주문하시는 분</td>
		<td width=80% class="val">
			<?=$order_info->send_name?>
		</td>
	</tr>
	<tr>
		<td class="tit">전화번호</td>
		<td class="val">
			<?=$order_info->send_tphone?>
		</td>
	</tr>
	<tr>
		<td class="tit">휴대전화번호</td>
		<td class="val">
			<?=$order_info->send_hphone?>
		</td>
	</tr>
	<tr>
		<td class="tit">이메일</td>
		<td class="val">
			<?=$order_info->send_email?>
		</td>
	</tr>
	<tr>
		<td class="tit">주소</td>
		<td class="val">[
			<?=$order_info->send_post?>]
				<?=$order_info->send_address?>
		</td>
	</tr>
</table>

<h2 class="order_ttl">배송지 정보</h2>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="AW_order_table">
	<tr>
		<td class="tit" width="20%">받으시는 분</td>
		<td class="val" width=80%>
			<?=$order_info->rece_name?>
		</td>
	</tr>
	<tr>
		<td class="tit">전화번호</td>
		<td class="val">
			<?=$order_info->rece_tphone?>
		</td>
	</tr>
	<tr>
		<td class="tit">휴대전화번호</td>
		<td class="val">
			<?=$order_info->rece_hphone?>
		</td>
	</tr>
	<tr>
		<td class="tit">주소</td>
		<td class="val">[
			<?=$order_info->rece_post?>]
				<?=$order_info->rece_address?>
		</td>
	</tr>

	<? if($order_info->demand != ""){ ?>
		<tr>
			<td class="tit">요청사항</td>
			<td class="val">
				<?=str_replace("\n","<br>&nbsp;&nbsp;",$order_info->demand)?></td>
		</tr>
	<? } ?>

</table>
  