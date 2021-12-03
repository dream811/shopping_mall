<?
include_once $_SERVER['DOCUMENT_ROOT']."/inc/shop_info.inc"; 		// 상점 정보
$ordmail = "
<style>
.tit {text-align:left; font-size:9pt; font-family:Dotum; padding-top:3px; padding-bottom:2px; font-weight:bold; right no-repeat; font-weight:bold; border-right:1px solid #d7d7d7; background-color:#fafaf9; color:#707070; padding-left:10px;}
.val {text-align:left; padding-left:10px;}
</style>
<table border=0 cellpadding=0 cellspacing=0 width=98% align=center>
	<tr><td>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr><td colspan=7 bgcolor=#a9a9a9 height=2></td></tr>
			<tr align=center bgcolor=#f9f9f9>
				<td height=39 width=138>이미지</td>
				<td align=center>구매상품정보</td>
				<td width=100>제품가격</td>
				<td width=80>적립금</td>
				<td width=80>구매수량</td>
			</tr>
			<tr><td colspan=7 height=1 bgcolor=#E5E5E5></td></tr>
";

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
	if(!isset($prd_price)) $prd_price = 0;
	if(!isset($mall_no)) $mall_no = array();
	if(!isset($deliver_price)) $deliver_price = 0;
	if(!isset($deliver_msg)) $deliver_msg = "";
	if(!isset($coupon_msg_mall)) $coupon_msg_mall = array();
	while($row = mysqli_fetch_object($result)){
		
		$prd_price += ($row->prdprice * $row->amount);
		if(!isset($row->prdimg) || $row->prdimg == "") $row->prdimg = "http://$HTTP_HOST/images/noimage.gif";
		else $row->prdimg = "http://$HTTP_HOST/data/prdimg/".$row->prdimg;

		$optcode = "";
		if($row->opttitle5 != '') $optcode .= "$row->opttitle5$row->optcode5, ";
		if($row->opttitle6 != '') $optcode .= "$row->opttitle6$row->optcode6, ";
		if($row->opttitle7 != '') $optcode .= "$row->opttitle7$row->optcode7, ";

		if($row->opttitle3 != '') $optcode .= "$row->opttitle3$row->optcode3, ";
		if($row->opttitle4 != '') $optcode .= "$row->opttitle4$row->optcode4, ";

		if($row->opttitle != '') $optcode .= $row->opttitle;
		if($row->opttitle != '' && $row->opttitle2 != '') $optcode .= "/";
		if($row->opttitle2 != '') $optcode .= $row->opttitle2;
		if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= "".$row->optcode.", ";

		if(!strcmp($row->status, "CA")) $basket_status = "<font color='red'>[취소신청]</font>";
		else if(!strcmp($row->status, "CI")) $basket_status = "<font color='red'>[처리중]</font>";
		else if(!strcmp($row->status, "CC")) $basket_status = "<font color='red'>[취소완료]</font>";
		else $basket_status = "";

		$del_type = "";
		if(!empty($row->del_type) && strcmp($row->del_type, "DA")) {
			if(!strcmp($row->del_type, "DC")) $del_type = "<br>(".deliver_name_prd($row->del_type)."".number_format($row->del_price)."원)";
			else $del_type = "<br>(".deliver_name_prd($row->del_type).")";
		}

		if(empty($row->mallid)) {
			$row->com_name = $shop_info->shop_name;
			$row->com_tel = $shop_info->shop_tel;
		}
		if(!isset($mall_no[$row->mallid])) $mall_no[$row->mallid] = 0;
		$mall_no[$row->mallid]++;

$ordmail .= "
			<tr>
			<td style=padding:10 align=center><a href='http://$HTTP_HOST/shop/prd_view.php?prdcode=$row->prdcode' target='prdview'><img src='$row->prdimg' border='0' width='50' height='50'></a></td>
			<td><a href='http://$HTTP_HOST/shop/prd_view.php?prdcode=$row->prdcode' target='prdview'>$row->prdname</a> $basket_status <br>$optcode$del_type</td>
			<td class=price align=center>".number_format($row->prdprice)."원</td>
			<td align=center>".number_format($row->prdreserve*$row->amount)."원</td>
			<td align=center>".$row->amount."</td>
			</tr>
			<tr><td colspan=7 height=1 bgcolor=#E5E5E5></td></tr>";

			if(!strcmp($mall_no[$row->mallid], $mall_list[$row->mallid]['cnt'])) {

				if(!empty($row->del_method)) {

					$tmp_oper_info->mallid 			= $row->mallid;
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
				$deliver_price_mall[$row->mallid] = deliver_price($mall_list[$row->mallid]['total'], $tmp_oper_info, $row->mallid);

				$deliver_price += $deliver_price_mall[$row->mallid];

				if($row->del_price_mall > $deliver_price_mall[$row->mallid]){
		   		$deliver_msg .= " , 배송비 할증";
		   	}

			if(!isset($coupon_msg_mall[$row->mallid])) $coupon_msg_mall[$row->mallid] = "";
		    // 쿠폰사용
		    if($row->coupon_use > 0){
		    	$coupon_msg_mall[$row->mallid] = " - 쿠폰사용(<b>".number_format($row->coupon_use)."</b>)";
		    }

$ordmail .= "
			<tr>
				<td height='30' colspan='7' style='padding: 5px 0px 5px 0px'>
					<table width='100%' border='0' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='20'><img src='http://".$HTTP_HOST."/images/blue_icon.gif'></td>
							<td colspan='2'>
								<b>판매자 : ".$row->com_name."(".$row->com_tel.")</b>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								[배송비 : ".$deliver_msg."]
							</td>
							<td align='right' width='45%'>
								상품(<b>".number_format($mall_list[$row->mallid]['total'])."원</b>) +
								배송비(<b>".number_format($deliver_price_mall[$row->mallid])."원</b>)
								".$coupon_msg_mall[$row->mallid]."
							</td>
						</tr>";

if(!empty($row->deliver_num)) {

$ordmail .= "
						<tr>
							<td></td>
							<td colspan='2' align='right'>
								<b>운송장번호</b>(<a href='".$oper_info->del_trace.$row->deliver_num."' target='_blank'>".$row->deliver_num."</a>)
							</td>
						</tr>";

}

$ordmail .= "
					</table>
				</td>
			</tr>
			<tr><td colspan=7 height=1 bgcolor='#E5E5E5'></td></tr>";

		 }

		$no++;
	}
if(!isset($discount_msg)) $discount_msg = "";
if(!isset($coupon_msg)) $coupon_msg = "";
if(!isset($reserve_msg)) $reserve_msg = "";
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

$ordmail .= "
			<tr>
				<td height=38 align=center colspan=5 bgcolor=#f9f9f9>
				<b>총결제금액 </b>:  상품(<b>".number_format($order_info->prd_price)."원)</b> ".$discount_msg." + 배송비(<b>".number_format($order_info->deliver_price)."원</b>)".$coupon_msg.$reserve_msg." = <span class=price>".number_format($order_info->total_price)."원</span>
				</td>
			</tr>
			<tr><td colspan=7 height=1 bgcolor=#E5E5E5></td></tr>
			</table>
		</td></tr>
		<tr><td><br></td></tr>
		<tr><td>

		<table border=0 cellpadding=5 cellspacing=0 width=100%>
		<tr><td colspan=4 bgcolor=#a9a9a9 height=2></td></tr>
		<tr>
		  <td width=20% class=tit>주문번호</td><td width=30% class=val>$order_info->orderid</td>
		  <td width=20% class=tit>주문일</td><td width=30% class=val>$order_info->order_date</td>
		</tr>
		<tr><td height=1 colspan=4 bgcolor=#d7d7d7></td></tr>
		<tr>
		  <td class=tit>결제방법</td><td class=val>".pay_method($order_info->pay_method)."</td>
		  <td class=tit>주문상태</td><td class=val>".order_status($order_info->status)."</td>
		</tr>
		<tr><td height=1 colspan=4 bgcolor=#d7d7d7></td></tr>";


if($order_info->pay_method == "PB"){


$ordmail .= "
		<tr>
		  <td class=tit>입금계좌</td><td class=val>$order_info->account</td>
		  <td class=tit>입금자명</td><td class=val>$order_info->account_name</td>
		</tr>
		<tr><td height=1 colspan=4 bgcolor=#d7d7d7></td></tr>";

}else if($order_info->pay_method == "PV"){

$ordmail .= "
		<tr>
		  <td class=tit><b>입금계좌</b></td><td colspan=3 class=val><b>$order_info->account</b> <font color=red>(가상계좌번호로 입금하셔야 주문이 완료됩니다.)</font></td>
		</tr>
		<tr><td height=1 colspan=4 bgcolor=#d7d7d7></td></tr>";

}

if($order_info->deliver_num != ""){

$ordmail .= "
		<tr>
		  <td class=tit>운송장번호</td><td colspan=3 class=val>".$order_info->deliver_num."</td>
		</tr>
		<tr><td height=1 colspan=4 bgcolor=#d7d7d7></td></tr>";

}

$ordmail .= "
    </table>


  </td>
</tr>
<tr>
  <td style=padding-top:30px;>


	<table border=0 cellpadding=5 cellspacing=0 width=100%>
	  <tr>
	    <td>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td><img src=http://$HTTP_HOST/images/shop/order_tit01.gif border=0 /></td>
				</tr>
				<tr><td colspan=2 bgcolor=#a9a9a9 height=2></td></tr>
				<tr><td height=25 width=20% class=tit>주문하시는 분</td><td class=val>$order_info->send_name</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>전화번호</td><td class=val>$order_info->send_tphone</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>휴대전화번호</td><td class=val>$order_info->send_hphone</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>이메일</td><td class=val>$order_info->send_email</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>주소</td><td class=val>[$order_info->send_post] $order_info->send_address</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				</table>
		  </td>
    </tr>
  </table>


  </td>
</tr>
<tr>
  <td style=padding-top:30px;>


    <table border=0 cellpadding=5 cellspacing=0 width=100%>
      <tr>
		   <td valign=top>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td><img src=http://$HTTP_HOST/images/shop/order_tit02.gif border=0 /></td>
				</tr>
				<tr><td colspan=2 bgcolor=#a9a9a9 height=2></td></tr>
				<tr><td height=25 width=20% class=tit>받으시는 분</td><td class=val>$order_info->rece_name</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>전화번호</td><td class=val>$order_info->rece_tphone</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>휴대전화번호</td><td class=val>$order_info->rece_hphone</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>
				<tr><td height=25 class=tit>주소</td><td class=val>[$order_info->rece_post] $order_info->rece_address</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>";

if($order_info->demand != ""){
$ordmail .= "
				<tr><td width=110 valign=top>요청사항</td><td class=val>".str_replace("\n","<br>&nbsp;&nbsp;",$order_info->demand)."</td></tr>
				<tr><td height=1 colspan=2 bgcolor=#d7d7d7></td></tr>";
}
$ordmail .= "
				</table>
		   </td>
      </tr>
    </table>


  </td>
</tr>
</table>
";

?>