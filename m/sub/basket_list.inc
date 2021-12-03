<script language="JavaScript" src="/js/lib.js"></script>
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
	goURL('/member/my_save.php?mode=my_wish&idx='+idx);
<? } ?>
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].action.indexOf("/shop/prd_save.php") != -1){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function prdDelete(){

	var i;
	var selected = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].action.indexOf("/shop/prd_save.php") != -1){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selected = selected + document.forms[i].idx.value + "|";
				}
			}
	}

	if(selected == ""){
		alert("삭제할 상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 상품을 정말 삭제하시겠습니까?")){
			goURL("/shop/prd_save.php?mode=delete&page=<?=$page?>&<?=$param?>&selected=" + selected);
		}else{
			selectEmpty();
			return;
		}
	}
	return;

}

-->
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="prd_basket">
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

		$purl = "prdview.php?prdcode=".$brow[prdcode];
	?>
	<form action="/shop/prd_save.php" method="post">
	<input type="hidden" name="mode" value="update">
	<input type="hidden" name="idx" value="<?=$brow['idx']?>">
	<tr>
		<td colspan="2" style="padding:12px 3% 0;">
			<input type="checkbox" name="select_checkbox">
		</td>
	</tr>
	<tr>
		<td valign="top" width="75">
			<a href="<?=$purl?>" target="_blank" class="img_box"><img src="<?=$prdimg?>" width="75" height="75" /></a>
		</td>
		<td style="vertical-align:top;" valign="top">
			<div class="prd_tit"><a href="<?=$purl?>"><?=$brow[prdname]?></a></div>
			<div style="padding:8px 6px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="basket_inner">
					<tr>
						<th width="65">상품가격</th>
						<td><font class="prd_price"><?=number_format($brow[prdprice])?>원</font></td>
					</tr>
					<tr>
						<th>적립금</th>
						<td><?=number_format($brow[prdreserve])?>원</td>
					</tr>
					<tr>
						<th>수량</th>
						<td class="amount">
							<a href="javascript:decAmount('<?=$no?>');">-</a>
							<input name="amount" value="<?=$brow[amount]?>" onChange="checkAmount(this.form);" onKeyUp="checkAmount(this.form)" type="text" class="input_style" />
							<a href="javascript:incAmount('<?=$no?>');">+</a>
							
							<input type="submit" value="수정" class="count_btn" />
						</td>
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
		<td height="30" colspan="2" style="padding:12px 3%">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="basket_info">
				<tr>
					<th><b>판매자 : <?=$brow[com_name]?>(<?=$brow[com_tel]?>)</b></th>
					<td align="right" width="120">
					상품가격 <b><?=number_format($prd_price_mall[$brow[mallid]])?>원</b>
					</td>
				</tr>
				<tr>
					<th>[배송비 : <?=$deliver_msg?>]</th>
					<td align="right">
						배송비<b><?=number_format($deliver_price_mall[$brow[mallid]])?>원</b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor='#E5E5E5'></td></tr>
	<? } ?>
	</form>
	<?
		$no++;
	}

	if(!$basket_exist){
		echo "<tr><td colspan=2 height=50 align=center style='vertical-align:middle; font-size:.95rem;'>장바구니가 비어있습니다.</td></tr>";
	}

	// 회원할인 [$discount_msg 메세지 생성]
	$discount_price = level_discount($wiz_session[level],$prd_price);

	// 배송비
	//$deliver_price = deliver_price($prd_price, $oper_info);

	// 전체결제금액
	$total_price = $prd_price + $deliver_price - $discount_price;
?>
</table>
