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

//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}
	else{
		selectEmpty();
	}
}

//체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
}

//체크박스 선택해제
function selectEmpty(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

function su_price(){
	var i;
	var prd_price = 0;
	var deliver_price = 0;
	var price = 0;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdprice != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					prd_price = parseInt(prd_price) + parseInt(document.forms[i].prdprice.value) ;
				}
			}
		}
	}


document.getElementById('total_price').innerHTML=number_fomrat(prd_price);

}

function number_fomrat(str){
    str = str + "";
    if(str == "" || /[^0-9,]/.test(str)) return str;
    str = str.replace(/,/g, "");
    for(var i=0; i<parseInt(str.length/3, 10); i++){
        str = str.replace(/([0-9])([0-9]{3})(,|$)/, "$1,$2$3");
    }
    return str;
}
-->
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="AW_basket_table">
<tr>
  	<td colspan="2">

			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="prd_basket">
				<tbody>
				<form>
				<tr align=center bgcolor="#f9f9f9">
					<th width="40"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)" checked></th>
					<th width="70">이미지</th>
					<th>구매상품정보</th>
					<th width="12%">제품가격</th>
					<th width="12%">수 량</th>
					<th width="12%">적립금</th>
					<th width="12%">기능</th>
				</tr>
				</form>
				<?
				// 각 입점업체별 상품 수 구하기
				$sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket_tmp where uniq_id='".$_COOKIE["uniq_id"]."' group by mallid";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($row = mysqli_fetch_array($result)) {

					$mall_list[$row['mallid']]['cnt'] = $row['cnt'];
					$mall_list[$row['mallid']]['total'] = $row['total'];

				}

				$basket_exist = false;

				if(!isset($prd_price)) $prd_price = 0;
				if(!isset($prd_price_mall)) $prd_price_mall = [];
				if(!isset($mall_no)) $mall_no = [];
				if(!isset($deliver_price)) $deliver_price = 0;

				$no = 0;
				$sql = "SELECT wb.*, wp.del_type, wp.del_price, wm.com_name, wm.com_tel, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2 FROM wiz_basket_tmp as wb inner join wiz_product as wp on wb.prdcode = wp.prdcode left join wiz_mall as wm on wb.mallid = wm.id WHERE wb.uniq_id='".$_COOKIE["uniq_id"]."' order by wb.mallid";
				$btresult = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($brow = mysqli_fetch_array($btresult)){

					$basket_exist = true;
					$optcode = "";
					$prdimg = "";
					$del_type = "";
					$prd_price += ($brow['prdprice'] * $brow['amount']);
					
					if(!isset($prd_price_mall[$brow['mallid']])) $prd_price_mall[$brow['mallid']] = 0;
					
					$prd_price_mall[$brow['mallid']] += ($brow['prdprice'] * $brow['amount']);

					if($brow['prdimg'] == "") $prdimg = "/images/noimage.gif";
					else $prdimg = "/data/prdimg/".$brow['prdimg'];

					if($brow['opttitle5'] != '') $optcode = $brow['opttitle5']." : ".$brow['optcode5'].", ";
					if($brow['opttitle6'] != '') $optcode .= $brow['opttitle6']." : ".$brow['optcode6'].", ";
					if($brow['opttitle7'] != '') $optcode .= $brow[opttitle7]." : ".$brow['optcode7'].", ";

					if($brow['opttitle3'] != '') $optcode .= $brow['opttitle3']." : ".$brow['optcode3'].", ";
					if($brow['opttitle4'] != '') $optcode .= $brow['opttitle4']." : ".$brow['optcode4'].", ";

					if($brow['opttitle'] != '') $optcode .= $brow['opttitle'];
					if($brow['opttitle'] != '' && $brow['opttitle2'] != '') $optcode .= "/";
					if($brow['opttitle2'] != '') $optcode .= $brow['opttitle2'];
					if($brow['opttitle'] != '' || $brow['opttitle2'] != '') $optcode .= " : ".$brow['optcode'].", ";

					if(!empty($brow['del_type']) && strcmp($brow['del_type'], "DA")) {
						if(!strcmp($brow['del_type'], "DC")) $del_type = "<br>(".deliver_name_prd($brow['del_type'])." : ".number_format($brow['del_price'])."원)";
						else $del_type = "<br>(".deliver_name_prd($brow['del_type']).")";
					}
					if(empty($brow['mallid'])) {
						$brow['com_name'] = $shop_info->shop_name;
						$brow['com_tel'] = $shop_info->shop_tel;
					}
					
					if(!isset($mall_no[$brow['mallid']])) $mall_no[$brow['mallid']] = 0;
					$mall_no[$brow['mallid']]++;
				?>
				<form action="prd_save.php" method="post">
				<input type="hidden" name="mode" value="update">
				<input type="hidden" name="idx" value="<?=$brow['idx']?>">
				<input type="hidden" name="prdprice" value="<?=$brow['prdprice']?>">
				<tr>
					<td><input type="checkbox" name="select_checkbox" checked></td>
					<td><a href="/shop/prd_view.php?prdcode=<?=$brow['prdcode']?>" target="prdview"><img src="<?=$prdimg?>" width="60" height="60" border="0"></a></td>
					<td style="text-align:left; padding-left:12px;">
						<p style="font-size:16px; line-height:1.5;"><a href="/shop/prd_view.php?prdcode=<?=$brow['prdcode']?>" target="prdview"><?=$brow['prdname']?></a></p>
						<?=$optcode?><?=$del_type?>
					</td>
					<td class="price" align=center><?=number_format($brow['prdprice'])?>원</td>
					<td align=center>
				  		<div class="count_box clearfix">
							<a href="javascript:decAmount('<?=$no?>');" class="down">-</a>
							<input type=text name=amount value="<?=$brow['amount']?>" onChange="checkAmount(this.form)" onKeyUp="checkAmount(this.form)" class="input"><!-- order_form.php 로 넘어오면 수량 값만 노출 -->
							<a href="javascript:incAmount('<?=$no?>');" class="up">+</a>
							<input type="submit" value="수정" class="edit_btn" />
						</div><!-- //count_box -->
					</td>
					<td align=center><?=number_format($brow['prdreserve']*$brow['amount'])?>원</td>
					<td align=center>
						<a href="javascript:addWish('<?=$brow['idx']?>')" class="btn">관심상품</a><br>
						<a href="prd_save.php?mode=delete&idx=<?=$brow['idx']?>" class="btn">삭제하기</a>
					</td>
				</tr>
				</form>
				<?php
				if(!strcmp($mall_no[$brow['mallid']], $mall_list[$brow['mallid']]['cnt'])) {

					if(!empty($brow['del_method'])) {

						$tmp_oper_info->mallid 				= $brow['mallid'];

						$tmp_oper_info->del_method 		= $brow['del_method'];
						$tmp_oper_info->del_fixprice 	= $brow['del_fixprice'];
						$tmp_oper_info->del_staprice 	= $brow['del_staprice'];
						$tmp_oper_info->del_staprice2 = $brow['del_staprice2'];
						$tmp_oper_info->del_staprice3 = $brow['del_staprice3'];

						$tmp_oper_info->del_prd	 = $brow['del_prd'];
						$tmp_oper_info->del_prd2 = $brow['del_prd2'];

					} else {
						$tmp_oper_info = $oper_info;
					}

					// 배송비
					$deliver_price_mall[$brow['mallid']] = deliver_price($prd_price_mall[$brow['mallid']], $tmp_oper_info, $brow['mallid']);

					$deliver_price += $deliver_price_mall[$brow['mallid']];

				?>
				<tr>
					<td height="30" colspan="7" class="seller_info">
						<p>판매자 : <?=$brow['com_name']?>(<?=$brow['com_tel']?>)</p>
						<span class="del_msg clearfix">
							<span>배송비 : <?=$deliver_msg?></span>
							<span class="price">
								상품가격 <b><?=number_format($prd_price_mall[$brow['mallid']])?>원</b>  +
								배송비 <b><?=number_format($deliver_price_mall[$brow['mallid']])?>원</b>
							</span>
						</span>
					</td>
				</tr>
				<? } ?>

				</form>

				<?php
						$no++;
				}

				if(!$basket_exist){
					echo "<tr><td colspan=7 height=60 align=center>장바구니가 비어있습니다.</td></tr>";
				}

				// 회원할인 [$discount_msg 메세지 생성]
				$discount_price = level_discount($wiz_session['level'],$prd_price);

				// 배송비
				//$deliver_price = deliver_price($prd_price, $oper_info);

				// 전체결제금액
				$total_price = $prd_price + $deliver_price - $discount_price;

			?>
			</tbody>
			</table>

  	</td>
	</tr>

	<tr bgcolor="#f9f9f9">
	  <td height="60" style="padding-left:10px">[배송비 : <?=$deliver_msg?>]</td>
	  <td class="total_price" align="right" style="padding-right:10px">
			상품가격 <b><?=number_format($prd_price)?>원</b>  <?=$discount_msg?> +
		    배송비<b><?=number_format($deliver_price)?>원</b> =
			주문합계 <span class="price"><?=number_format($total_price)?>원</span>
	  </td>
	</tr>

</table>
<script language="JavaScript">
<!--

function goOrder(){

	var i;
	var selidx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					selidx = selidx + document.forms[i].idx.value + "|";
				}
			}
		}
	}

	if(!selidx){
	alert("상품을 선택하세요");
	return ;
	}

	<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else{
	?>
	document.location="order_form.php?selidx="+selidx;
	<?
	}
	?>
}

// 주문삭제하기
function goDellBasketTemp(){
/*
<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else echo "document.location='order_form.php';";
?>
*/
	var i;
	var selidx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					selidx = selidx + document.forms[i].idx.value + "|";
				}
			}
		}
	}

	if(!selidx){
		alert("상품을 선택하세요");
		return ;
	}

	<?
	if(!$basket_exist) echo "alert('삭제할 상품이 없습니다.');";
	else{
	?>
		if(confirm("선택한 장바구니를 삭제하시겠습니까?")){
			document.location="prd_save.php?mode=delete&selected="+selidx;
		}
		else{
			return;
		}

	<?
	}
	?>
}

// 주문전체삭제하기
function goDellBasketTempAll(){
	if(confirm("선택한 장바구니를 삭제하시겠습니까?")){
		selectAll();
		document.location="prd_save.php?mode=delall";
	}
	else{
		return;
	}
}

function printEstimate(){
	var uri = "print_estimate.php";
	window.open(uri, "printEstimate", "width=667,height=600,scrollbars=yes, top=30, left=50");
}

function goallOrder(){
	selectAll();
	var i;
	var selidx = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked){
					selidx = selidx + document.forms[i].idx.value + "|";
				}
			}
		}
	}
	<?
	if(!$basket_exist) echo "alert('주문할 상품이 없습니다.');";
	else{
	?>
	document.location="order_form.php?selidx="+selidx;
	<?
	}
	?>
}
-->
</script>