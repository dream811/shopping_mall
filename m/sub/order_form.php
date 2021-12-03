<?
$sub_tit="주문상품결제";
?>
<? include "../inc/header.php" ?>
<body onUnload="cuponClose();">
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<?php

// 로그인 하지 않은경우 로그인 페이지로 이동
if(empty($wiz_session['id']) && empty($order_guest)){
	echo "<script>goURL('login.php?prev=$PHP_SELF&order=true');</script>";
	exit;
}

include "../../inc/mem_info.inc"; 		// 회원 정보

// 회원적립금 가져오기
if($oper_info->reserve_use == "Y" && $wiz_session['id'] != ""){

	$sql = "select sum(reserve) as reserve from wiz_reserve where memid = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);
	if($row->reserve == "") $mem_info->reserve = 0;
	else $mem_info->reserve = $row->reserve;

}else{
	$mem_info->reserve = 0;
}

?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="JavaScript" src="/js/lib.js"></script>
<script language="javascript">
<!--

// 주문자 정보와 동일
function sameCheck(frm){

	if(frm.same_check.checked == true){
		frm.rece_name.value = frm.send_name.value;

		frm.rece_tphone.value = frm.send_tphone.value;
		frm.rece_tphone2.value = frm.send_tphone2.value;
		frm.rece_tphone3.value = frm.send_tphone3.value;

		frm.rece_hphone.value = frm.send_hphone.value;
		frm.rece_hphone2.value = frm.send_hphone2.value;
		frm.rece_hphone3.value = frm.send_hphone3.value;

		frm.rece_post.value = frm.send_post.value;
		frm.rece_address.value = frm.send_address.value;
		frm.rece_address2.value = frm.send_address2.value;

	}else{

		frm.rece_name.value = "";
		frm.rece_tphone.value = "";
		frm.rece_tphone2.value = "";
		frm.rece_tphone3.value = "";
		frm.rece_hphone.value = "";
		frm.rece_hphone2.value = "";
		frm.rece_hphone3.value = "";
		frm.rece_post.value = "";
		frm.rece_address.value = "";
		frm.rece_address2.value = "";

	}

}

function inputCheck(frm){

	if(!frm.basket_exist.value) {
		alert("주문할 상품이 없습니다.");
		return false;
	}

	if(frm.send_name.value == ""){
		alert("고객 성명을 입력하세요");
		frm.send_name.focus();
		return false;
	}else{
		if(!Check_nonChar(frm.send_name.value)){
			alert("고객 성명에는 특수문자가 들어갈 수 없습니다");
			frm.send_name.focus();
			return false;
		}
	}

	if(frm.send_tphone.value == ""){
		alert("고객 전화번호를 입력하세요.");
		frm.send_tphone.focus();
		return false;
	}else if(!Check_Num(frm.send_tphone.value)){
		alert("지역번호는 숫자만 가능합니다.");
		frm.send_tphone.focus();
		return false;
	}

	if(frm.send_tphone2.value == ""){
		alert("고객 전화번호를 입력하세요.");
		frm.send_tphone2.focus();
		return false;
	}else if(!Check_Num(frm.send_tphone2.value)){
		alert("국번은 숫자만 가능합니다.");
		frm.send_tphone2.focus();
		return false;
	}

	if(frm.send_tphone3.value == ""){
		alert("고객 전화번호를 입력하세요.");
		frm.send_tphone3.focus();
		return false;
	}else if(!Check_Num(frm.send_tphone3.value)){
		alert("전화번호는 숫자만 가능합니다.");
		frm.send_tphone3.focus();
		return false;
	}


	if(frm.send_email.value == ""){
		alert("고객 이메일을 입력하세요.");
		frm.send_email.focus();
		return false;
	}else if(!check_Email(frm.send_email.value)){
		return false;
	}

	if(frm.send_address.value == ""){
		alert("주문하시는분 주소를 입력하세요");
		frm.send_address.focus();
		return false;
	}
	if(frm.send_address2.value == ""){
		alert("주문하시는분 상세주소를 입력하세요");
		frm.send_address2.focus();
		return false;
	}

	if(frm.rece_name.value == ""){
		alert("받으시는분 성명을 입력하세요");
		frm.rece_name.focus();
		return false;
	}else{
		if(!Check_nonChar(frm.rece_name.value)){
			alert("받으시는분 성명에는 특수문자가 들어갈 수 없습니다");
			frm.rece_name.focus();
			return false;
		}
	}

	if(frm.rece_tphone.value == ""){
		alert("받으시는분 전화번호를 입력하세요.");
		frm.rece_tphone.focus();
		return false;
	}else if(!Check_Num(frm.rece_tphone.value)){
		alert("지역번호는 숫자만 가능합니다.");
		frm.rece_tphone.focus();
		return false;
	}
	if(frm.rece_tphone2.value == ""){
		alert("받으시는분 전화번호를 입력하세요.");
		frm.rece_tphone2.focus();
		return false;
	}else if(!Check_Num(frm.rece_tphone2.value)){
		alert("국번은 숫자만 가능합니다.");
		frm.rece_tphone2.focus();
		return false;
	}
	if(frm.rece_tphone3.value == ""){
		alert("받으시는분 전화번호를 입력하세요.");
		frm.rece_tphone3.focus();
		return false;
	}else if(!Check_Num(frm.rece_tphone3.value)){
		alert("전화번호는 숫자만 가능합니다.");
		frm.rece_tphone3.focus();
		return false;
	}

	if(frm.rece_address.value == ""){
		alert("받으시는분 주소를 입력하세요");
		frm.rece_address.focus();
		return false;
	}
	if(frm.rece_address2.value == ""){
		alert("받으시는분 상세주로를 입력하세요");
		frm.rece_address2.focus();
		return false;
	}

	var pay_checked = false;
	var pay_checked_val = "";
	for(ii=0;ii<frm.pay_method.length;ii++){
		if(frm.pay_method[ii].checked == true){
			pay_checked = true;
			pay_checked_val = frm.pay_method[ii].value;
		}
	}

	if(pay_checked == false){
		alert("결제방법을 선택하세요");
		return false;
	}

	<? if(!strcmp($oper_info->tax_use, "Y")) { ?>

	if(pay_checked_val == "PC" && frm.tax_type[0].checked != true) {
		alert("신용카드 결제 시 세금계산서 및 현금영수증 발급이 불가능합니다.");
		frm.tax_type[0].checked = true;
		qclick("");
		return false;
	}

	// 세금계산서
	if(frm.tax_type[1].checked == true) {

		if(frm.com_num.value == ""){
			alert("사업자 번호를 입력하세요");
			frm.com_num.focus();
			return false;
		}
		if(frm.com_name.value == ""){
			alert("상호를 입력하세요");
			frm.com_name.focus();
			return false;
		}
		if(frm.com_owner.value == ""){
			alert("대표자를 입력하세요");
			frm.com_owner.focus();
			return false;
		}
		if(frm.com_address.value == ""){
			alert("사업장 소재지를 입력하세요");
			frm.com_address.focus();
			return false;
		}
		if(frm.com_kind.value == ""){
			alert("업태를 입력하세요");
			frm.com_kind.focus();
			return false;
		}
		if(frm.com_class.value == ""){
			alert("종목을 입력하세요");
			frm.com_class.focus();
			return false;
		}
		if(frm.com_tel.value == ""){
			alert("전화번호를 입력하세요");
			frm.com_tel.focus();
			return false;
		}
		if(frm.com_email.value == ""){
			alert("이메일을 입력하세요");
			frm.com_email.focus();
			return false;
		}
	}

	// 현금영수증
	if(frm.tax_type[2].checked == true) {

		var cash_type_check = false;
		for(ii = 0; ii < frm.cash_type.length; ii++) {
			if(frm.cash_type[ii].checked == true) {
				cash_type_check = true;
				break;
			}
		}
		if(cash_type_check == false) {
			alert("발급사유를 선택하세요.");
			return false;
		}

		var cash_type2_check = false;
		for(ii = 0; ii < frm.cash_type2.length; ii++) {
			if(frm.cash_type2[ii].checked == true) {
				cash_type2_check = true;
				break;
			}
		}
		if(cash_type2_check == false) {
			alert("신청정보를 선택하세요.");
			return false;
		}

		for(ii = 0; ii < document.forms["frm"].elements["cash_info_arr[]"].length; ii++) {
			if(document.forms["frm"].elements["cash_info_arr[]"][ii].value == "") {
				alert("신청정보를 입력하세요.");
				document.forms["frm"].elements["cash_info_arr[]"][ii].focus();
				return false;
			}
		}

		if(frm.cash_name.value == "") {
			alert("신청자명을 입력하세요.");
			frm.cash_name.focus();
			return false;
		}

	}

	<? } ?>
	if(!reserveUse(frm)){
		return false;
	}

}

// 우편번호
function zipSearch(kind){
	if(kind == undefined) kind = '';
	new daum.Postcode({
	oncomplete: function(data) {
	//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
	//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
	//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
	//document.getElementById('addr').value = addr;

	var frm;
	for(i=0;i<document.forms.length;i++){
	frm = document.forms[i];

	if(eval('frm.'+kind+'post')){
	eval('frm.'+kind+'post').value = data.zonecode;
	if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

	eval('frm.'+kind+'address').value = data.roadAddress;

	} else { // 사용자가 지번 주소를 선택했을 경우(J)

	eval('frm.'+kind+'address').value = data.jibunAddress;

	}
	//eval('frm.'+kind+'address1').value = data.address;
	if(eval('frm.'+kind+'address2') != null) eval('frm.'+kind+'address2').focus();
	}

	if(eval('frm.'+kind+'address')){
	if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

	eval('frm.'+kind+'address').value = data.roadAddress;

	} else { // 사용자가 지번 주소를 선택했을 경우(J)

	eval('frm.'+kind+'address').value = data.jibunAddress;

	}
	//eval('frm.'+kind+'address').value = data.address;
	}
	}
	}
	}).open();
}

// 적립금 사용
function reserveUse(frm){

	if(frm.reserve_use != null){

		var reserve_use = frm.reserve_use.value;
		var total_price = frm.total_price.value;

		if(reserve_use != ""){

		   if(reserve_use != "" && !Check_Num(reserve_use)){

		      alert("적립금은 숫자만 가능합니다.");
		      frm.reserve_use.value = "";
		      frm.reserve_use.focus();
		      return false;

		   }else{

		      reserve_use = eval(reserve_use);
		      total_price = eval(total_price);

		   }

		   if(reserve_use > <?=$mem_info->reserve?>){

		      alert("사용가능액 보다 많습니다.");
		      frm.reserve_use.value = "";
		      frm.reserve_use.focus();
		      return false;

		   }else if(reserve_use > total_price){

		   	alert("주문금액 보다 많습니다.");
		      frm.reserve_use.value = "";
		      frm.reserve_use.focus();
		      return false;

		   }else if(reserve_use < <?=$oper_info->reserve_min?>){

		   	alert("최소사용 적립금 보다 작습니다. <?=number_format($oper_info->reserve_min)?>원 이상 사용가능합니다.");
		      frm.reserve_use.value = "";
		      return false;

		   }else if(reserve_use > <?=$oper_info->reserve_max?>){

		   	alert("최대사용 적립금 보다 큽니다. <?=number_format($oper_info->reserve_max)?>원 이하 사용가능합니다.");
		      frm.reserve_use.value = "";
		      return false;

		   }

		}

	}

	return true;

}

var couponWin;

// 쿠폰사용
function couponUse(){

	if(couponWin != null) couponWin.close();

	var url = "../../shop/coupon_list.php";
  couponWin = window.open(url, "couponUse", "height=450, width=650, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
}

function cuponClose() {

	if(couponWin != null) couponWin.close();
}

// 세금계산서발행
function qclick(idnum) {

  tax01.style.display='none';
  tax02.style.display='none';

  if(idnum != ""){
	  tax=eval("tax"+idnum+".style");
	  tax.display='table';
	}
}

// 현금영수증발행 - 발급사유
function qclick2(idnum) {

	var type1 = "<span><input type=\"radio\" name=\"cash_type2\" value=\"CARDNUM\" onclick=\"qclick3('01')\" id=\"cash_type1\"><label for=\'cash_type1\'>현금영수증 카드번호</label></span>";
	var type2 = "<span><input type=\"radio\" name=\"cash_type2\" value=\"COMNUM\" onclick=\"qclick3('02')\" id=\"cash_type2\"><label for=\'cash_type2\'>사업자 등록번호</label></span>";
	var type3 = "<span><input type=\"radio\" name=\"cash_type2\" value=\"HPHONE\" onclick=\"qclick3('03')\" id=\"cash_type3\"><label for=\'cash_type3\'>휴대전화번호</label></span>";
	var type4 = "<span><input type=\"radio\" name=\"cash_type2\" value=\"RESNO\" onclick=\"qclick3('04')\" id=\"cash_type4\"><label for=\'cash_type4\'>주민등록번호</label></span>";

	// 사업자 지출증빙용
	if(idnum == "01") {
		document.getElementById("cash_info01").innerHTML = type1 + " " + type2;
	// 개인소득 공제용
	} else if(idnum == "02") {
		document.getElementById("cash_info01").innerHTML = type1 + " " + type3 + " " + type4;
	}

	document.getElementById("cash_info02").innerHTML = "";

}

// 현금영수증발행 - 신청정보
function qclick3(idnum) {

	var cash_info01 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input_style\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input_style\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input_style\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input_style\">";
	var cash_info02 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"3\" maxlength=\"3\" class=\"input_style\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"2\" maxlength=\"2\" class=\"input_style\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input_style\">";
	var cash_info03 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"3\" maxlength=\"3\" class=\"input_style\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input_style\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input_style\">";
	var cash_info04 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"6\" maxlength=\"6\" class=\"input_style\" style=\"width:90px\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input_style\" style=\"width:90px\">";

	var cash_info = eval("cash_info"+idnum);
	document.getElementById("cash_info02").innerHTML = cash_info;

}
-->
</script>

<? include "basket_listing.inc"; ?>

<?php
if($wiz_session['id'] != "") {
	$level_info = level_info();
	$level = $level_info[$wiz_session['level']]['name'];
?>
<div style="margin:10px; background:#444444; color:#ffffff; font-weight:bold; text-align:center; padding:10px"><b><?=$wiz_session['name']?></b>님은 <b>[<?=$level?>]</b>입니다.</div>
<? } ?>

<div style="padding:0 3%; box-sizing:border-box;">
	<ul class="cart_del">
		<li>배송정보 :
			<?=$deliver_msg?>
		</li>
		<li>지역별/상품 개별 배송정책에 따라 변동될 수 있습니다.</li>
	</ul>
</div>

<form name="frm" action="<?=$ssl?>/shop/order_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="total_price" value="<?=$total_price?>">
<input type="hidden" name="coupon_idx" value="">
<input type="hidden" name="basket_exist" value="<?=$basket_exist?>">
<input type="hidden" name="selidx" value="<?=$selidx?>">

<div class="gry_bar"></div>
<div class="agree_box">
	<h3>주문 고객 정보</h3>
	<dl class="data-table">
		<dt>주문하시는 분</dt>
		<dd><input name="send_name" value="<?=$mem_info->name?>" type="text" style="width:120px;" class="input_style" /></dd>
	</dl>

	<dl class="data-table">
		<dt>전화번호</dt>
		<dd>
			<select name="send_tphone">
			  <option value="">선택</option>
			  <?php
			  $num_str = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					$sel = ($num == $mem_tphone[0]) ? "selected" : "";
					echo "<option value='".$num."' ".$sel.">".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="send_tphone2" value="<?=$mem_tphone[1]?>" type="text" class="input_style" style="width:80px;" /> -
			<input name="send_tphone3" value="<?=$mem_tphone[2]?>" type="text" class="input_style" style="width:80px;" />
		</dd>
	</dl>

	<dl class="data-table">
		<dt>휴대폰</dt>
		<dd>
			<select name="send_hphone">
			  <option value="">선택</option>
			  <?php
			  $num_str = "010,011,016,017,018,019";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					$sel = ($num == $mem_hphone[0]) ? "selected" : "";
					echo "<option value='".$num."' ".$sel.">".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="send_hphone2" value="<?=$mem_hphone[1]?>" type="text" class="input_style" style="width:80px;" /> -
			<input name="send_hphone3" value="<?=$mem_hphone[2]?>" type="text" class="input_style" style="width:80px;" />
		</dd>
	</dl>

	<dl class="data-table">
		<dt>이메일</dt>
		<dd><input name="send_email" value="<?=$mem_info->email?>" type="text" class="input_style" style="width:95%;" /></dd>
	</dl>

	<dl class="data-table">
		<dt>주소</dt>
		<dd>
			<input name="send_post" value="<?=$mem_post[0]?>" type="text" style="width:80px;" class="input_style" />
			<button type="button" onclick="zipSearch('send_')">우편번호 찾기</button>
			<div style="margin:5px 0 0;">
				<input name="send_address" value="<?=$mem_info->address?>" type="text" style="width:95%;" class="input_style" />
				<input name="send_address2" value="<?=$mem_info->address2?>" type="text" style="width:95%; margin-top:5px;" class="input_style" />
			</div>
		</dd>
	</dl>
</div>

<div class="gry_bar"></div>
<div class="agree_box">
	<div class="ttl clearfix">
		<h3>수령 고객 정보</h3>
		<span>
			<input name="same_check" type="checkbox" onClick="sameCheck(this.form);" id="same_chk" />
			<label for="same_chk">주문 고객 정보와 동일</label>
		</span>
	</div>

	<dl class="data-table">
		<dt>받으시는 분</dt>
		<dd><input name="rece_name" type="text" style="width:120px;" class="input_style" /></dd>
	</dl>

	<dl class="data-table">
		<dt>전화번호</dt>
		<dd>
			<select name="rece_tphone">
			  <option value="">선택</option>
			  <?php
			  $num_str = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					echo "<option value='".$num."'>".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="rece_tphone2" type="text" style="width:80px;" class="input_style" /> -
			<input name="rece_tphone3" type="text" style="width:80px;" class="input_style" />
		</dd>
	</dl>

	<dl class="data-table">
		<dt>휴대폰</dt>
		<dd>
			<select name="rece_hphone">
			  <option value="">선택</option>
			  <?php
			  $num_str = "010,011,016,017,018,019";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					echo "<option value='".$num."'>".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="rece_hphone2" type="text" style="width:80px;" class="input_style" /> -
			<input name="rece_hphone3" type="text" style="width:80px;" class="input_style" />
		</dd>
	</dl>

	<dl class="data-table">
		<dt>주소</dt>
		<dd>
			<input name="rece_post" type="text" style="width:80px;" class="input_style" />
			<button type="button" onclick="zipSearch('rece_');">우편번호 찾기</button>
			<div style="margin:5px 0 0;">
				<input name="rece_address" type="text" style="width:95%;" class="input_style" />
				<input name="rece_address2" type="text" style="width:95%; margin:5px 0 0;" class="input_style" />
			</div>
		</dd>
	</dl>

	<dl class="data-table">
		<dt>주문메세지</dt>
		<dd><textarea name="demand" rows="2" style="resize:none;"></textarea></dd>
	</dl>
</div>
<div class="gry_bar"></div>

<div class="agree_box">
	<h3>결제 정보</h3>
	<? if($oper_info->coupon_use == "Y"){ ?>
	<dl class="data-table">
		<dt>쿠폰 사용</dt>
		<dd>
			<input name="coupon_use" type="text" class="input_style" style="width:100px; text-align:right" readonly /> 원
			<button type="button" onClick="couponUse()">쿠폰조회</button>
		</dd>
	</dl>
	<? } ?>
	<? if($oper_info->reserve_use == "Y"){ ?>
	<dl class="data-table">
		<dt>적립금 사용 <small>최소 1,000원부터 최대 50,000원까지 사용 가능합니다.</small></dt>
		<dd>
			<input name="reserve_use" type="text" class="input_block" style="width:100px; text-align:right" onchange="reserveUse(this.form);" /> 원
			<span>(사용가능 적립금 <?=number_format($mem_info->reserve)?>원)</span>
		</dd>
	</dl>
	<? } ?>
	<dl class="data-table">
		<dt>총 결제금액</dt>
		<dd class="total">
			<span>상품 <?=number_format($prd_price)?>원 <?=$discount_msg?> + 배송비 <?=number_format($deliver_price)?>원</span>
			<span class="prd_price"><?=number_format($total_price)?>원</span>
		</dd>
	</dl>

	<dl class="data-table">
		<dt>결제수단</dt>
		<dd>
			<input type="radio" name="pay_method" value="" style="display:none">
			<?
			$pay_method = explode("/",$oper_info->pay_method);
			for($ii=0; $ii<count($pay_method)-1; $ii++){

				$pay_title = pay_method($pay_method[$ii]);

				if($ii == 0) $checked = "checked";
				else $checked = "";

				if($oper_info->pay_escrow == "Y" && ($pay_method[$ii] == "PN" || $pay_method[$ii] == "PV")) $pay_title .= " (에스크로)";

				//if($oper_info->pay_escrow == "Y" && $pay_method[$ii] == "PB" && $total_price >= 100000){
				//}else{
			?>
			<span class="method">
				<input name="pay_method" type="radio" value="<?=$pay_method[$ii]?>" id="<?=$pay_method[$ii]?>" <?=$checked?> /><label for="<?=$pay_method[$ii]?>"><?=$pay_title?></label>
			</span>

			<?
				//}
			}
			?>
		</dd>
	</dl>
	<? if(!strcmp($oper_info->tax_use, "Y")) { ?>
	<dl class="data-table">
		<dt>증빙서류</dt>
		<dd>
			<span class="method">
				<input type="radio" name="tax_type" value="N" checked onClick="qclick('');" id="tax1"><label for="tax1">발행안함</label>
			</span>
			<span class="method">
				<input type="radio" name="tax_type" value="T" onClick="qclick('01');" id="tax2"><label for="tax2">세금계산서 신청</label>
			</span>
			<span class="method">

				<input type="radio" name="tax_type" value="C" onClick="qclick('02');" id="tax3"><label for="tax3">현금영수증 신청</label>
			</span>

			<table id="tax01" style="display:none" class="inner_table" width="100%">
				<tr>
					<th>사업자 번호</th>
					<td colspan="3"><input type="text" name="com_num" value="<?=$mem_info->com_num?>"></td>
				</tr>
				<tr>
					<th>상 호</th>
					<td><input type="text" name="com_name" value="<?=$mem_info->com_name?>"></td>
					<th>대표자</th>
					<td><input type="text" name="com_owner" value="<?=$mem_info->com_owner?>"></td>
				</tr>
				<tr>
					<th bgcolor="#F9F9F9">사업장 소재지</th>
					<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="com_address" value="<?=$mem_info->com_address?>"></td>
				</tr>
				<tr>
					<th bgcolor="#F9F9F9">업 태</th>
					<td bgcolor="#FFFFFF"><input type="text" name="com_kind" value="<?=$mem_info->com_kind?>"></td>
					<th bgcolor="#F9F9F9">종 목</th>
					<td bgcolor="#FFFFFF"><input type="text" name="com_class" value="<?=$mem_info->com_class?>"></td>
				</tr>
				<tr>
					<th bgcolor="#F9F9F9">전화번호</th>
					<td bgcolor="#FFFFFF"><input type="text" name="com_tel" value="<?=$mem_info->tphone?>"></td>
					<th bgcolor="#F9F9F9">이메일</th>
					<td bgcolor="#FFFFFF"><input type="text" name="com_email" value="<?=$mem_info->email?>"></td>
				</tr>
			</table>

			<table id="tax02" style="display:none;" class="inner_table" width="100%">
			<tr>
				<th style="width:22%; height:40px;">발급사유</th>
				<td style="width:78%;">
					<span><input type="radio" name="cash_type" value="C" onClick="qclick2('01');" id="cash1"><label for="cash1">사업자 지출증빙용</label></span>
					<span><input type="radio" name="cash_type" value="P" onClick="qclick2('02');" id="cash2"><label for="cash2">개인소득 공제용</label></span>
				</td>
			</tr>
			<tr>
				<th bgcolor="#F9F9F9" style="height:40px;">신청정보</th>
				<td bgcolor="#FFFFFF">
					<div id="cash_info01"></div>
					<div id="cash_info02" style="padding:3px;"></div>
				</td>
			</tr>
			<tr>
				<th bgcolor="#F9F9F9">신청자명</th>
				<td bgcolor="#FFFFFF">
					<input type="text" name="cash_name" value="" class="input_style" style="width:110px;">
				</td>
			</tr>
			</table>
		</dd>
	</dl>
	<? } ?>
</div>

<div class="button_common">
	<ul class="cart_del">
		<li>"결제방식"별 할인/적립 및 쿠폰은 결제 시 반영됩니다.</li>
		<li>모바일샵 구매시 PC버전과 혜택 부분이 다를 수 있습니다.</li>
	</ul>
	<button type="submit">결제하기</button>
</div>

</form>

<? include "../inc/footer.php" ?>
