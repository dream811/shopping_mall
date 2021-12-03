<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
?>
<html>
<head>
<title>:: 쿠폰목록 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/css/style.css">
<script language="JavaScript">
<!--
function couponCheck(frm){

	if(frm.coupon_check.checked == true){
		if(frm.prd_exsist.value == "false"){
			frm.coupon_check.checked = false;
			alert("장바구니에 해당 상품이 없습니다.");
		}else{
			setCoupon();
		}
	}

}

function setCoupon(){

	var i;
	var discount_price = 0;
	var coupon_idx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].id != null){
			if(document.forms[i].coupon_check){
				if(document.forms[i].coupon_check.checked){
					discount_price = discount_price + (document.forms[i].discount_price.value*1);
					coupon_idx = coupon_idx + document.forms[i].idx.value + "|";
				}
			}
		}
	}
	opener.frm.coupon_use.value = discount_price;
	opener.frm.coupon_idx.value = coupon_idx;

	alert("적용되었습니다.");
}
//-->
</script>
</head>

<body>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" style="border:6px solid #979797;">
	<tr>
	  <td width="351" height="54" style="padding-left:37px;"><img src="/images/shop/coupon_tit.gif"></td>
	  <td width="60"><a href="javascript:window.close();"><img src="/images/member/id_check_close.gif" width="21" height="21" border="0"></a></td>
	</tr>
	<tr><td colspan="2" height="1" bgcolor="#d2d2d2"></td></tr>
	<tr>
	  <td colspan="2" align="center" valign="top" style="padding-top:10px">

	  	<table border="0" width="96%" align="center">
				<tr><td><img src="/images/shop/coupon_02.gif"></td></tr>
			</table>
	  	<table border="0" width="96%" align="center">
				<tr><td colspan="10" height="2" bgcolor="#a9a9a9"></td></tr>
				<tr bgcolor="#f9f9f9">
					<td width="8%" height="35" align="center">번호</td>
					<td align="center">상품명</td>
					<td width="13%" align="right">가격</td>
					<td width="13%" align="right">쿠폰</td>
					<td width="10%" align="right">수량</td>
					<td width="13%" align="right">할인액</td>
					<td width="13%" align="right">쿠폰적용가</td>
					<td width="10%" align="center">사용</td>
				</tr>
				<tr><td colspan="10" height="1" bgcolor="#d7d7d7"></td></tr>
				<?
				$today = date('Y-m-d');
				$memid = $wiz_session['id'];
				//$basket_list = $HTTP_SESSION_VARS["basket_list"];
				//$basket_cnt = count($basket_list);

				$sql = "select prdcode, prdprice, amount from wiz_basket_tmp where uniq_id='".$_COOKIE["uniq_id"]."'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$no = 0;

				while($row = mysqli_fetch_array($result)) {

					$basket_list[$no][prdcode] = $row[prdcode];
					$basket_list[$no][prdprice] = $row[prdprice];
					$basket_list[$no][amount] = $row[amount];

					$no++;
				}

				$basket_cnt = count($basket_list);

				$sql = "select wc.*, wp.prdname, wp.sellprice from wiz_mycoupon wc, wiz_product wp where wc.memid='$memid' and wc.prdcode != '' and wc.coupon_use = 'N' and wc.coupon_sdate <= '$today' and wc.coupon_edate >= '$today' and wc.prdcode = wp.prdcode order by wc.idx desc";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$total = mysqli_num_rows($result);
				$no = $total;
				while($row = mysqli_fetch_object($result)){

					$prd_amount = 0;
					$prd_exsist = "false";
					for($ii = 0; $ii < $basket_cnt; $ii++){
						if($basket_list[$ii][prdcode] == $row->prdcode){
							$prd_amount += $basket_list[$ii][amount];
							//$row->sellprice = $basket_list[$ii][prdprice];	// 옵션추가금액
							$prd_exsist = "true";
							//break;
						}
					}
					if($prd_amount == 0) $prd_amount = 1;

					if($row->coupon_type == "%"){
						$discount_price = $row->sellprice*($row->coupon_dis/100);
					}else{
						$discount_price = $row->coupon_dis;
					}
					$coupon_price = $row->sellprice - $discount_price;

					$discount_price = $discount_price * $prd_amount;
					$coupon_price = $coupon_price * $prd_amount;

				?>
			  <form>
			  <input type="hidden" name="idx" value="<?=$row->idx?>">
			  <input type="hidden" name="prd_exsist" value="<?=$prd_exsist?>">
			  <input type="hidden" name="discount_price" value="<?=$discount_price?>">
				<tr>
					<td align="center" height="25"><?=$no?></td>
					<td><?=$row->prdname?><?=$basket_prd?></td>
					<td align="right"><?=number_format($row->sellprice)?>원</td>
					<td align="right"><?=$row->coupon_dis?><?=$row->coupon_type?></td>
					<td align="right"><?=number_format($prd_amount)?></td>
					<td align="right"><?=number_format($discount_price)?>원</td>
					<td align="right"><?=number_format($coupon_price)?>원</td>
					<td align="center"><input type="checkbox" name="coupon_check" value="<?=$prd_exsist?>" onClick="couponCheck(this.form)"></td>
				</tr>
				<tr><td colspan=10 height=1 bgcolor=#d7d7d7></td></tr>
			  </form>
				<?
					$no--;
				}
				if($total <= 0){
				?>
				<tr><td colspan=10 height="35" align="center">등록된 쿠폰이 없습니다.</td></tr>
				<tr><td colspan=10 height=1 bgcolor=#d7d7d7></td></tr>
				<?
				}
				?>
			</table>
			<br>
			<?
			$basket_cnt = count($basket_list);
			for($ii = 0; $ii < $basket_cnt; $ii++){
				if($basket_list[$ii] != null){
					$prd_price += ($basket_list[$ii][prdprice] * $basket_list[$ii][amount]);
				}
			}
			?>

			<table border="0" width="96%" align="center">
				<tr><td><img src="/images/shop/coupon_03.gif"></td></tr>
			</table>
			<table border="0" width="96%" align="center">
				<tr><td colspan="10" height="2" bgcolor="#a9a9a9"></td></tr>
				<tr bgcolor="#f9f9f9">
					<td height="35" width="8%" align="center">번호</td>
					<td align="center">쿠폰명</td>
					<td width="30%" align="center">기간</td>
					<td width="10%" align="center">할인액</td>
					<td width="10%" align="center">사용</td>
				</tr>
				<tr><td colspan="10" height="1" bgcolor="#d7d7d7"></td></tr>
				<?
				$sql = "select wc.* from wiz_mycoupon wc where wc.memid='$memid' and wc.eventidx != '' and wc.coupon_use = 'N' and wc.coupon_sdate <= '$today' and wc.coupon_edate >= '$today' order by wc.idx desc";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$total = mysqli_num_rows($result);
				$no = $total;
				while($row = mysqli_fetch_object($result)){

					if($row->coupon_type == "%"){
						$discount_price = $prd_price*($row->coupon_dis/100);
					}else{
						$discount_price = $row->coupon_dis;
					}
					$coupon_price = $row->sellprice - $discount_price;
				?>
			  <form>
			  <input type="hidden" name="idx" value="<?=$row->idx?>">
			  <input type="hidden" name="prd_exsist" value="<?=$prd_exsist?>">
			  <input type="hidden" name="discount_price" value="<?=$discount_price?>">
				<tr>
					<td height="25" align="center"><?=$no?></td>
					<td><?=$row->coupon_name?></td>
					<td><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
					<td align="center"><?=$row->coupon_dis?><?=$row->coupon_type?></td>
					<td align="center"><input type="checkbox" name="coupon_check" onClick="setCoupon();"></td>
				</tr>
				<tr><td colspan=10 height=1 bgcolor=#d7d7d7></td></tr>
				</form>
				<?
					$no--;
				}
				if($total <= 0){
				?>
			  <tr><td colspan=10 height="35" align="center">등록된 쿠폰이 없습니다.</td></tr>
				<tr><td colspan=10 height=1 bgcolor=#d7d7d7></td></tr>
				<?
				}
				?>
			</table>

	  </td>
	</tr>
</table>
</body>
</html>