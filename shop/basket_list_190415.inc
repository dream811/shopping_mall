<script language="JavaScript" src="/js/util_lib.js"></script>
<script language="javascript">
<!--

// 수량 증가
function incAmount(idx){

	var ii = 0;
	for(ii=0; document.forms[ii].amount == null; ii++){}
	idx = eval(idx) + eval(ii);

	var amount = document.forms['idx'].amount.value;
	document.forms['idx'].amount.value = ++amount;

}

// 수량 감소
function decAmount(idx){

	var ii = 0;
	for(ii=0; document.forms[ii].amount == null; ii++){}
	idx = eval(idx) + eval(ii);

   var amount = document.forms['idx'].amount.value;
	if(amount > 1)
		document.forms['idx'].amount.value = --amount;

}

// 수량체크
function checkAmount(frm){

	var amount = frm.amount.value;
   if(!Check_Num(amount) || amount < 1){
   	frm.amount.value = "1";
	}

}

// 위시리스트 추가
function addWish(idx){
<? if(empty($wiz_session['id'])){ ?>
  alert('로그인이 필요합니다.\n\n회원가입을 하지 않으신 분은 가입후 이용하시기 바랍니다.');
<? }else{ ?>
  document.location = '/member/my_save.php?mode=my_wish&idx='+idx;
<? } ?>
}
-->
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
  	<td colspan="2">

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td colspan="7" bgcolor="#a9a9a9" height="2"></td></tr>
				<tr align=center bgcolor="#f9f9f9">
					<td height="39" width="12%">이미지</td>
					<td>구매상품정보</td>
					<td width="12%">제품가격</td>
					<td width="12%">수 량</td>
					<td width="12%">포인트</td>
					<td width="12%">기능</td>
				</tr>
				<tr>
          <td colspan="7" bgcolor="#d7d7d7" height="1"></td>
        </tr>
				<?
				// 각 입점업체별 상품 수 구하기
				$sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket_tmp where uniq_id='".$_COOKIE["uniq_id"]."' group by mallid";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($row = mysqli_fetch_array($result)) {

					$mall_list[$row[mallid]]['cnt'] = $row['cnt'];
					$mall_list[$row[mallid]][total] = $row[total];

				}

				$basket_exist = false;
				$no = 0;
				$sql = "SELECT wb.*, wp.del_type, wp.del_price, wm.com_name, wm.com_tel, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2 FROM wiz_basket_tmp as wb inner join wiz_product as wp on wb.prdcode = wp.prdcode left join wiz_mall as wm on wb.mallid = wm.id WHERE wb.uniq_id='".$_COOKIE["uniq_id"]."' order by wb.mallid";
				$btresult = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($brow = mysqli_fetch_array($btresult)){

					$basket_exist = true;
					$optcode = "";
					$prdimg = "";
					$del_type = "";
					$prd_price += ($brow[prdprice] * $brow[amount]);

					$prd_price_mall[$brow[mallid]] += ($brow[prdprice] * $brow[amount]);

					if($brow[prdimg] == "") $prdimg = "/images/noimage.gif";
					else $prdimg = "/data/prdimg/".$brow[prdimg];

					if($brow[opttitle5] != '') $optcode = $brow[opttitle5]." : ".$brow[optcode5].", ";
					if($brow[opttitle6] != '') $optcode .= $brow[opttitle6]." : ".$brow[optcode6].", ";
					if($brow[opttitle7] != '') $optcode .= $brow[opttitle7]." : ".$brow[optcode7].", ";

					if($brow[opttitle3] != '') $optcode .= $brow[opttitle3]." : ".$brow[optcode3].", ";
					if($brow[opttitle4] != '') $optcode .= $brow[opttitle4]." : ".$brow[optcode4].", ";

					if($brow[opttitle] != '') $optcode .= $brow[opttitle];
					if($brow[opttitle] != '' && $brow[opttitle2] != '') $optcode .= "/";
					if($brow[opttitle2] != '') $optcode .= $brow[opttitle2];
					if($brow[opttitle] != '' || $brow[opttitle2] != '') $optcode .= " : ".$brow[optcode].", ";

					if(!empty($brow[del_type]) && strcmp($brow[del_type], "DA")) {
						if(!strcmp($brow[del_type], "DC")) $del_type = "<br>(".deliver_name_prd($brow[del_type])." : ".number_format($brow[del_price])."원)";
						else $del_type = "<br>(".deliver_name_prd($brow[del_type]).")";
					}
					if(empty($brow[mallid])) {
						$brow[com_name] = $shop_info->shop_name;
						$brow[com_tel] = $shop_info->shop_tel;
					}

					$mall_no[$brow[mallid]]++;
				?>
				<form action="prd_save.php" method="post">
				<input type="hidden" name="mode" value="update">
				<input type="hidden" name="idx" value="<?=$brow['idx']?>">
				<tr>
					<td style="padding:5" align=center><a href="/shop/prd_view.php?prdcode=<?=$brow[prdcode]?>" target="prdview"><img src="<?=$prdimg?>" width="50" height="50" border="0"></a></td>
					<td><a href="/shop/prd_view.php?prdcode=<?=$brow[prdcode]?>" target="prdview"><?=$brow[prdname]?></a><br><?=$optcode?><?=$del_type?></td>
					<td class="price" align=center><?=number_format($brow[prdprice])?>원</td>
					<td align=center>
					  <table border=0 cellpadding=0 cellspacing=0>
					  <tr>
					    <td valign=top>
							<table border=0 cellpadding=0 cellspacing=0>
							<tr>
							  <td rowspan=3><input type=text name="amount" value="<?=$brow[amount]?>" onChange="checkAmount(this.form);" onKeyUp="checkAmount(this.form)" size=2 class="input">&nbsp;</td>
							  <td><a href="javascript:incAmount('<?=$no?>');"><img src="/images/shop/but_vol_up.gif" border=0></a></td>
							</tr>
							<tr>
							  <td><a href="javascript:decAmount('<?=$no?>');"><img src="/images/shop/but_vol_down.gif" border=0></a></td>
							</tr>
							</table>
					    </td>
					    <td>&nbsp;<input type="image" src="/images/shop/but_modify.gif"></td>
					  </tr>
					  </table>
					</td>
					<td align=center><?=number_format($brow[prdreserve]*$brow[amount])?>원</td>
					<td align=center>
						<a href="javascript:addWish('<?=$brow['idx']?>')"><img src="/images/shop/but_cart_custody.gif" border=0></a><br>
						<a href="prd_save.php?mode=delete&idx=<?=$brow['idx']?>"><img src="/images/shop/but_cart_del.gif" border=0></a>
					</td>
				</tr>
				<tr><td colspan=7 height=1 bgcolor='#E5E5E5'></td></tr>
				</form>
				<?php
				if(!strcmp($mall_no[$brow[mallid]], $mall_list[$brow[mallid]]['cnt'])) {

					if(!empty($brow[del_method])) {

						$tmp_oper_info->mallid 				= $brow[mallid];

						$tmp_oper_info->del_method 		= $brow[del_method];
						$tmp_oper_info->del_fixprice 	= $brow[del_fixprice];
						$tmp_oper_info->del_staprice 	= $brow[del_staprice];
						$tmp_oper_info->del_staprice2 = $brow[del_staprice2];
						$tmp_oper_info->del_staprice3 = $brow[del_staprice3];

						$tmp_oper_info->del_prd	 = $brow[del_prd];
						$tmp_oper_info->del_prd2 = $brow[del_prd2];

					} else {
						$tmp_oper_info = $oper_info;
					}

					// 배송비
					$deliver_price_mall[$brow[mallid]] = deliver_price($prd_price_mall[$brow[mallid]], $tmp_oper_info, $brow[mallid]);

					$deliver_price += $deliver_price_mall[$brow[mallid]];

				?>
				<tr>
					<td height="30" colspan="7" style="padding: 5px 0px 5px 0px">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="20"></td>
								<td colspan="2">
									<b>판매자 : <?=$brow[com_name]?>(<?=$brow[com_tel]?>)</b>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									[배송비 : <?=$deliver_msg?>]
								</td>
								<td align="right" width="30%">
									상품(<b><?=number_format($prd_price_mall[$brow[mallid]])?>원</b>) +
									배송비(<b><?=number_format($deliver_price_mall[$brow[mallid]])?>원</b>)
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan=7 height=1 bgcolor='#E5E5E5'></td></tr>
				<? } ?>

				</form>
				<?php
						$no++;
				}

				if(!$basket_exist){
					echo "<tr><td colspan=7 height=30 align=center>장바구니가 비어있습니다.</td></tr>";
				}

				// 회원할인 [$discount_msg 메세지 생성]
				$discount_price = level_discount($wiz_session[level],$prd_price);

				// 배송비
				//$deliver_price = deliver_price($prd_price, $oper_info);

				// 전체결제금액
				$total_price = $prd_price + $deliver_price - $discount_price;

			?>
			</table>

  	</td>
	</tr>
	<tr><td colspan="2" height="10"></td></tr>
	<tr>
    <td colspan="2" bgcolor="#d7d7d7" height="1"></td>
  </tr>
	<tr bgcolor="#f9f9f9">
	  <td height="50" style="padding-left:10px">[배송비 : <?=$deliver_msg?>]</td>
	  <td align="right" style="padding-right:10px">
			상품(<b><?=number_format($prd_price)?>원</b>)  <?=$discount_msg?> +
			배송비(<b><?=number_format($deliver_price)?>원</b>) =
			주문합계 <span class="price"><?=number_format($total_price)?>원</span>&nbsp;
	  </td>
	</tr>
	<tr>
    <td colspan="2" bgcolor="#d7d7d7" height="1"></td>
  </tr>
</table>
<script language="JavaScript">
<!--
function goOrder(){
<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else echo "document.location='order_form.php';";
?>
}

function printEstimate(){
	var uri = "print_estimate.php";
	window.open(uri, "printEstimate", "width=667,height=600,scrollbars=yes, top=30, left=50");
}
-->
</script>