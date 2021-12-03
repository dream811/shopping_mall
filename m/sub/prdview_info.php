<script language="javascript">
<!--
// 가격설정
function setSellprice(){
	var tmp_sellprice = document.prdForm.tmp_sellprice.value;
	var opt_price1 = document.prdForm.opt_price1.value;
	var opt_price2 = document.prdForm.opt_price2.value;
	var opt_price3 = document.prdForm.opt_price3.value;

	var tmp_reserve = document.prdForm.tmp_reserve.value;
	var opt_reserve1 = document.prdForm.opt_reserve1.value;
	var opt_reserve2 = document.prdForm.opt_reserve2.value;
	var opt_reserve3 = document.prdForm.opt_reserve3.value;

	var amount = document.prdForm.amount.value;

	if(tmp_sellprice == "") tmp_sellprice = 0;
	if(opt_price1 == "") opt_price1 = 0;
	if(opt_price2 == "") opt_price2 = 0;
	if(opt_price3 == "") opt_price3 = 0;

	if(tmp_reserve == "") tmp_reserve = 0;
	if(opt_reserve1 == "") opt_reserve1 = 0;
	if(opt_reserve2 == "") opt_reserve2 = 0;
	if(opt_reserve3 == "") opt_reserve3 = 0;

	if(amount == "") amount = 0;

	var sellprice = String(eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3));
	var reserve = String(eval(tmp_reserve) + eval(opt_reserve1) + eval(opt_reserve2) + eval(opt_reserve3));

	document.getElementById("sellprice").innerHTML = "<span class='prd_price'>"+ won_Comma(sellprice) +"원";
	document.getElementById("reserve").innerHTML = ""+ won_Comma(reserve) +"원";

	// 총 상품 금액

	var total_price = String(eval(sellprice) * eval(amount));
	document.getElementById("total_price").innerHTML = "<strong>"+ won_Comma(total_price) +"</strong> 원";

	<?php

	if(
	$prd_info->coupon_use == "Y" &&
	$prd_info->coupon_sdate <= date('Y-m-d') &&
	$prd_info->coupon_edate >= date('Y-m-d') &&
	($prd_info->coupon_limit == "N" || ($prd_info->coupon_limit == "" && $prd_info->coupon_amount > 0))
	){

	?>

	sellprice = eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3);

	var coupon_dis = document.prdForm.coupon_dis.value;
	var coupon_type = document.prdForm.coupon_type.value;
	var coupon_price;

	if(coupon_type == "%") {
		coupon_dis = coupon_dis/100;
		coupon_price = sellprice - (sellprice*coupon_dis);
	} else {
		coupon_price = sellprice - coupon_dis;
	}

	coupon_price = String(coupon_price);

	//document.getElementById("coupon").innerHTML = "<font class='coupon'>"+ won_Comma(coupon_price) +"원 &nbsp;<?=number_format($prd_info->coupon_dis)?><?=$prd_info->coupon_type?></font>";

	<?php
	}
	?>

	document.prdForm.tmp_sellprice.value = tmp_sellprice;
}

// 수량 증가
function incAmount(){
	var amount = document.prdForm.amount.value;
	document.prdForm.amount.value = ++amount;
	checkAmount();
}

// 수량 감소
function decAmount(){
   var amount = document.prdForm.amount.value;
	if(amount > 1)
		document.prdForm.amount.value = --amount;
	checkAmount();
}

// 수량체크
function checkAmount(){
	var chk = false;
	var amount = document.prdForm.amount.value;
	if(!Check_Num(amount) || amount < 1){
		document.prdForm.amount.value = "1";
	} else {

   <? if($prd_info->opt_use == "Y" && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))){ ?>
   		if( document.prdForm.amount != null){
			var selvalue = document.prdForm.optcode.value;
			var optlist = selvalue.split("^");
			if( amount > eval(optlist[3])){
				alert("재고량이 부족합니다.");
				document.prdForm.amount.value = "1";
			} else {
				chk = true;
	   	}
   	}
   <? } else if(!strcmp($prd_info->shortage, "S")) { ?>
		if( document.prdForm.amount != null){
			if( amount > <?=$prd_info->stock?>){
				 alert("재고량이 부족합니다.");
				 document.prdForm.amount.value = "1";
			}else{
				chk = true;
			}
		}
   <? } else { ?>

		chk = true;

   <? } ?>
	}

	setSellprice();

	return chk;
}

// 가격변동,품절옵션 체크
function checkOpt01(){

	if(document.prdForm.optcode != null){

		var optval = document.prdForm.optcode.value;
		var optlist = optval.split("^");

		if(optval == ""){

			document.prdForm.opt_price1.value = "";
			document.prdForm.opt_reserve1.value = "";
			setSellprice();

		} else {

			//optlist[0] : 옵션명 optlist[1] : 가격 optlist[2] : 적립금 optlist[3] 재고

			if(optlist[3] == "0" || optlist[3] == ""){
				alert('품절된 상품입니다.');
				document.prdForm.optcode[0].selected = true;
				document.prdForm.opt_price1.value = "";
				document.prdForm.opt_reserve1.value = "";

				setSellprice();

				return false;

			// 옵션별 가격 적용

			} else {

				document.prdForm.opt_price1.value = optlist[1];
				document.prdForm.opt_reserve1.value = optlist[2];
				setSellprice();

			}
		}
	}

	return checkAmount();
}

// 가격변동 체크
function checkOpt03(){
	if(document.prdForm.optcode3 != null){

		if(document.prdForm.optcode3.value == ""){

			document.prdForm.opt_price2.value = "";
			document.prdForm.opt_reserve2.value = "";

			setSellprice();

		}else{

			var optval = document.prdForm.optcode3.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price2.value = optlist[1];
			document.prdForm.opt_reserve2.value = optlist[2];

			setSellprice();

		}

	}

}

// 가격변동 체크
function checkOpt04(){

	if(document.prdForm.optcode4 != null){

		if(document.prdForm.optcode4.value == ""){

			document.prdForm.opt_price3.value = "";
			document.prdForm.opt_reserve3.value = "";

			setSellprice();

		} else {

			var optval = document.prdForm.optcode4.value;
			var optlist = optval.split("^");

			document.prdForm.opt_price3.value = optlist[1];
			document.prdForm.opt_reserve3.value = optlist[2];

			setSellprice();

		}

	}

}

// 옵션체크
function checkOption(){

   if( document.prdForm.optcode5 != null){

      if(document.prdForm.optcode5.value == ""){
         alert("옵션을 선택하세요");

         document.prdForm.optcode5.focus();

         return false;
      }

   }

   if( document.prdForm.optcode6 != null){

      if(document.prdForm.optcode6.value == ""){
         alert("옵션을 선택하세요");
         document.prdForm.optcode6.focus();

         return false;

      }

   }

   if( document.prdForm.optcode7 != null){

      if(document.prdForm.optcode7.value == ""){
         alert("옵션을 선택하세요");
         document.prdForm.optcode7.focus();

         return false;

      }

   }

   if( document.prdForm.optcode3 != null){
      if(document.prdForm.optcode3.value == ""){
         alert("옵션을 선택하세요");

         document.prdForm.optcode3.focus();

         return false;

      }

   }

   if( document.prdForm.optcode4 != null){

      if(document.prdForm.optcode4.value == ""){
         alert("옵션을 선택하세요");
         document.prdForm.optcode4.focus();

         return false;
      }

   }

	if( document.prdForm.optcode != null){

      if(document.prdForm.optcode.value == ""){
         alert("옵션을 선택하세요");
         document.prdForm.optcode.focus();

         return false;

      }

   }

   if( document.prdForm.optcode2 != null){

      if(document.prdForm.optcode2.value == ""){
         alert("옵션을 선택하세요");
         document.prdForm.optcode2.focus();

         return false;
	  }

   }

   return true;

}

// 장바구니에 담기
function saveBasket(direct){
  <?

  if($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) echo "alert('죄송합니다. 품절상품 입니다.');";
  else echo "if(checkOption() && checkOpt01()){ document.prdForm.direct.value = direct; document.prdForm.submit(); }";

  ?>
}

// 관심상품 등록
function saveWish(){
	<? if(!empty($wiz_session['id'])) { ?>
	if($(".wish").hasClass('on')){
		var frm = document.prdForm;
		frm.action = "/member/my_save.php";
		frm.mode.value = "my_wishdel";
		frm.submit();
	}
	else{
		if(checkOption()){
			var frm = document.prdForm;
			frm.action = "/member/my_save.php";
			frm.mode.value = "my_wish";
			frm.submit();
		}
	}
	<? } else { ?>
	alert("로그인 후 이용해주세요.");
	<? } ?>
}
//-->
</script>



<!-- 제품정보 -->
<form name="prdForm" action="/shop/prd_save.php" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="direct" value="">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<input type="hidden" name="idx" value="<?=$wish_idx?>">
<? if(!strcmp($prd_info->opt_use, "Y") && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))) { ?>
<input type="hidden" name="opttitle" value="<?=$prd_info->opttitle?>">
<input type="hidden" name="opttitle2" value="<?=$prd_info->opttitle2?>">
<? } ?>
<input type="hidden" name="opttitle3" value="<?=$prd_info->opttitle3?>">
<input type="hidden" name="opttitle4" value="<?=$prd_info->opttitle4?>">
<input type="hidden" name="opttitle5" value="<?=$prd_info->opttitle5?>">
<input type="hidden" name="opttitle6" value="<?=$prd_info->opttitle6?>">
<input type="hidden" name="opttitle7" value="<?=$prd_info->opttitle7?>">
<input type="hidden" name="tmp_sellprice" value="<?=$prd_info->sellprice?>">
<input type="hidden" name="opt_price1" value="">
<input type="hidden" name="opt_price2" value="">
<input type="hidden" name="opt_price3" value="">
<input type="hidden" name="tmp_reserve" value="<?=$prd_info->reserve?>">
<input type="hidden" name="opt_reserve1" value="">
<input type="hidden" name="opt_reserve2" value="">
<input type="hidden" name="opt_reserve3" value="">

<div class="prd_info">
	<div class="prd_price">
	  <dl>
		<dt>판매가</dt>
		<dd id="sellprice"><font class="prd_price"><?=$sellprice?></font>원</dd>
	  </dl>
		<?php

		if(!empty($wiz_session['id']) && empty($prd_info->strprice)) {
			$level_info = level_info();
			$level = $level_info[$wiz_session['level']]['name'];
			$sql = "select * from wiz_level where idx = '".$wiz_session['level']."'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_object($result);

			if($row->discount > 0) {
				if($row->distype == "W") {
					$row->distype = "원";
					$member_price = $row->discount;
				} else {
					$row->distype = "%";
					$member_dis = $row->discount/100;
					$member_price = floor($prd_info->sellprice*$member_dis);
				}
		?>
		  <dl>
			<dt>등급할인액</dt>
			<dd><?=number_format($member_price)?>원 &nbsp;<?=number_format($row->discount)?><?=$row->distype?> [<?=$level?>]</dd>
		  </dl>
		<?
			}
		}
		?>

		<?
		if(
			$prd_info->coupon_use == "Y" &&
			$prd_info->coupon_sdate <= date('Y-m-d') &&
			$prd_info->coupon_edate >= date('Y-m-d') &&
			($prd_info->coupon_limit == "N" || ($prd_info->coupon_limit == "" && $prd_info->coupon_amount > 0))
			&& empty($prd_info->strprice)
		){
			if($prd_info->coupon_type == "%"){
				$coupon_dis = $prd_info->coupon_dis/100;
				$coupon_price = $prd_info->sellprice*$coupon_dis;
			}else{
				$coupon_price = $prd_info->coupon_dis;
			}
		?>
		<input type="hidden" name="coupon_dis" value="<?=$prd_info->coupon_dis?>">
		<input type="hidden" name="coupon_type" value="<?=$prd_info->coupon_type?>">
		  <dl>
			<dt>쿠폰할인액</dt>
			<dd>

				 <table height=25 border=0 cellpadding=0 cellspacing=0>

				 <tr>

				 <td style="padding: 3 0 0 0" id="coupon"><font class="coupon"><?=number_format($coupon_price)?>원 &nbsp;<?=number_format($prd_info->coupon_dis)?><?=$prd_info->coupon_type?></font></td>

				 <td width="5"></td>

				 <td><a href="/shop/coupon_down.php?prdcode=<?=$prdcode?>"><img src="/images/coupon_down.gif" border="0"></a></td>

				 </tr>

				 </table>

			</dd>
		  </dl>
		<? } ?>

	  <? if($oper_info->reserve_use == "Y" && empty($prd_info->strprice)){ ?>
	  <dl>
		<dt>적립금</dt>
		<dd id="reserve"><?=number_format($prd_info->reserve)?>원</dd>
	  </dl>
	  <? } ?>
	</div><!-- //prd_price -->
	<? if($sp_img != "" || $prd_info->prdcom != "" || $prd_info->origin != "" || $prd_info->info_use == "Y") { ?>
	<div class="prd_status">
		<? if($sp_img != ""){ ?>
			<dl>
				<dt>제품상태</dt>
				<dd>
				<?=$sp_img?>
			</dd>
		</dl>
		<? } ?>

		<? if($prd_info->prdcom != ""){ ?>
			<dl>
				<dt>제조사</dt>
				<dd>
					<?=$prd_info->prdcom?>
				</dd>
			</dl>
		<? } ?>

		<? if($prd_info->origin != ""){ ?>
			<dl>
				<dt>원산지</dt>
				<dd>
					<?=$prd_info->origin?>
				</dd>
			</dl>
		<? } ?>

		<?php
		  if(!strcmp($prd_info->info_use, "Y")) {
			for($ii = 1; $ii <= 6; $ii++) {
				if(!empty($prd_info->{"info_name".$ii})) {
		  ?>
			<dl>
				<dt><?=$prd_info->{"info_name".$ii}?></dt>
				<dd>
					<?=$prd_info->{"info_value".$ii}?>
				</dd>
			</dl>

		<?php
				}
			}
		} ?>
	</div><!-- //prd_status -->
	<? } ?>

	<? if(empty($prd_info->strprice)) { ?>
	<dl class="qty">
		<dt>수량</dt>
		<dd>
			<div class="count_box clearfix">
				<a href="javascript:decAmount();" class="down">-</a>
				<input type=text name=amount value=1 size=2 onChange="checkAmount();" onKeyUp="checkAmount()" class="input" readonly>
				<a href="javascript:incAmount();" class="up">+</a>
			</div><!-- //count_box -->
		</dd>
	</dl>
	<? } ?>

	<div class="prd_opt">
	  <? if($prd_info->opttitle5 != ""){ ?>
	  <dl>
		<dt class="prd_list_option_tit"><?=$prd_info->opttitle5?></dt>
		<dd>
		  <select name="optcode5">
			  <option value=""> 선택하세요 </option>
			<?
			$opt_list = explode(",",$prd_info->optcode5);
			for($ii=0; $ii<count($opt_list); $ii++){
				echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";

			}
			?>
		  </select>
		</dd>
	  </dl>
	  <? } ?>

	  <? if($prd_info->opttitle6 != ""){ ?>
	  <dl>
		<dt class="prd_list_option_tit"><?=$prd_info->opttitle6?></dt>
		<dd>
		  <select name="optcode6">
		  <option value=""> 선택하세요 </option>

			<?
			$opt_list = explode(",",$prd_info->optcode6);
			for($ii=0; $ii<count($opt_list); $ii++){
				echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";

			}
			?>
		  </select>
		</dd>
	  </dl>
	  <? } ?>

	  <? if($prd_info->opttitle7 != ""){ ?>
	  <dl>
		<dt class="prd_list_option_tit"><?=$prd_info->opttitle7?></dt>
		<dd>
		  <select name="optcode7">
			<option value=""> 선택하세요 </option>
			<?

			$opt_list = explode(",",$prd_info->optcode7);
			for($ii=0; $ii<count($opt_list); $ii++){
				echo "<option value='".$opt_list[$ii]."'>".$opt_list[$ii]."\n";
			}
			?>
		  </select>
		</dd>
	   </dl>
	   <? } ?>

	   <? if($prd_info->opttitle3 != ""){ ?>
	   <dl>
		<dt class="prd_list_option_tit"><?=$prd_info->opttitle3?></dt>
		<dd>
		  <select name="optcode3" onChange="checkOpt03()">
			  <option value=""> 선택하세요 </option>
			<?
			$opt_list = explode("^^",$prd_info->optcode3);
			for($ii=0; $ii<count($opt_list) - 1; $ii++){
				list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

				if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";
				else $price_tmp = "";

				echo "<option value='".$opt."^".$price."^".$reserve."'>".$opt.$price_tmp."\n";
			}
			?>
		  </select>
		</dd>
	  </dl>
	  <? } ?>

	  <? if($prd_info->opttitle4 != ""){ ?>
	  <dl>
		<dt class="prd_list_option_tit"><?=$prd_info->opttitle4?></dt>
		<dd>
		   <select name="optcode4" onChange="checkOpt04()">
			  <option value=""> 선택하세요 </option>
			<?

			$opt_list = explode("^^",$prd_info->optcode4);

			for($ii=0; $ii<count($opt_list) - 1; $ii++){
				list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

				if($price > 0) $price_tmp = " : ".number_format($price)."원 추가";

				else $price_tmp = "";

				echo "<option value='".$opt."^".$price."^".$reserve."'>".$opt.$price_tmp."\n";
			}
			?>
		  </select>
		</dd>
	  </dl>
	  <? } ?>

	  <?
	  if($prd_info->opt_use == "Y" && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))){
		if(!empty($prd_info->opttitle) && !empty($prd_info->opttitle2)) $prd_info->opttitle2 = "/".$prd_info->opttitle2;
	  ?>

	  <dl>

		<dt class="prd_list_option_tit"><?=$prd_info->opttitle?><?=$prd_info->opttitle2?></dt>
		<dd>
			<?php

			$opt1_arr = explode("^", $prd_info->optcode);
			$opt2_arr = explode("^", $prd_info->optcode2);
			$opt_tmp = explode("^^", $prd_info->optvalue);

			if(count($opt1_arr)-1 < 1) $opt1_cnt = 1;
			else $opt1_cnt = count($opt1_arr) - 1;

			if(count($opt2_arr)-1 < 1) $opt2_cnt = 1;
			else $opt2_cnt = count($opt2_arr) - 1;

			$no = 0;

			for($ii = 0; $ii < $opt1_cnt; $ii++) {
				for($jj = 0; $jj < $opt2_cnt; $jj++) {
					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

					$optcode[$no]['optcode'] = $opt1_arr[$ii];
					if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode[$no]['optcode'] .= "/";
					$optcode[$no]['optcode'] .= $opt2_arr[$jj];
					$optcode[$no]['price'] = $price;
					$optcode[$no]['reserve'] = $reserve;
					$optcode[$no]['stock'] = $stock;

					$no++;

				}
			}

			?>

		  <select name="optcode" onChange="checkOpt01();">
			<option value=""> 선택하세요 </option>
			<?
				for($ii=0; $ii<count($optcode); $ii++){

					$opt_sub_value = $optcode[$ii]['optcode']."^".$optcode[$ii]['price']."^".$optcode[$ii]['reserve']."^".$optcode[$ii]['stock'];

					if($optcode[$ii]['stock'] <= 0) $optcode[$ii]['stock'] = " [품절]";
					else $optcode[$ii]['stock'] = "";

					if($optcode[$ii]['price'] > 0) $optcode[$ii]['price'] = " : ".number_format($optcode[$ii]['price'])."원 추가  ";
					else $optcode[$ii]['price'] = "";

					$opt_sub_txt = $optcode[$ii]['optcode'].$optcode[$ii]['price'].$optcode[$ii]['stock'];

					echo "<option value='$opt_sub_value'>$opt_sub_txt\n";
				}
			?>
		  </select>
		</dd>
	  </dl>
	  <? } ?>
	</div><!-- //prd_opt -->
	<div class="price_wrap">
		<dl class="amount">
			<dt>총 합계금액</dt>
			<dd id="total_price"><strong><?=str_replace("원", "", $sellprice)?></strong>원</dd>
		</dl>
	</div><!-- //price_wrap -->
	<div class="prd_btn_area">
		<button type="button" class="wish<?=($wish_idx ? ' on' : '')?>" onclick="saveWish();">관심상품</button><!-- 클릭하면 관심상품 담기고 on 클래스 추가 / 추가된 상태에서 다시 클릭하면 관심상품 등록 취소 후 on 클래스 삭제 -->
		<button type="button" class="cart" onclick="saveBasket('basket');">장바구니</button>
		<button type="button" class="buy_btn" onclick="saveBasket('buy');">구매하기</button>
	</div><!-- //prd_btn_area -->
</div><!-- //prd_info -->
</form>
<div class="gry_bar"></div>

