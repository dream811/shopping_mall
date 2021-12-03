<?
$sub_tit="주문상품결제";
?>
<? include "../inc/header.php" ?>
<body onUnload="cuponClose();">
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>
<? include "../inc/sub_title.php" ?>

<?php

// 로그인 하지 않은경우 로그인 페이지로 이동
if(empty($wiz_session['id']) && empty($order_guest)){
	echo "<script>goURL('login.php?prev=$PHP_SELF&order=true');</script>";
	exit;
}

include "../../inc/mem_info.inc"; 		// 회원 정보

// 회원적립금 가져오기
if($oper_info->reserve_use == "Y" && $wiz_session['id'] != ""){

	$sql = "select sum(reserve) as reserve from wiz_reserve where memid = '$wiz_session['id']'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);
	if($row->reserve == "") $mem_info->reserve = 0;
	else $mem_info->reserve = $row->reserve;

}else{
	$mem_info->reserve = 0;
}

?>

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
		//frm.rece_post2.value = frm.send_post2.value;
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
		//frm.rece_post2.value = "";
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
	var address = eval("document.frm."+kind+"address");
	address.focus();
	var url = "join_address.php?kind="+kind;
	window.open(url, "zipSearch", "width=427, height=400, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
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
	  tax.display='block';
	}
}

// 현금영수증발행 - 발급사유
function qclick2(idnum) {

	var type1 = "<input type=\"radio\" name=\"cash_type2\" value=\"CARDNUM\" onclick=\"qclick3('01')\"> 현금영수증 카드번호";
	var type2 = "<input type=\"radio\" name=\"cash_type2\" value=\"COMNUM\" onclick=\"qclick3('02')\"> 사업자 등록번호";
	var type3 = "<input type=\"radio\" name=\"cash_type2\" value=\"HPHONE\" onclick=\"qclick3('03')\"> 휴대전화번호";
	var type4 = "<input type=\"radio\" name=\"cash_type2\" value=\"RESNO\" onclick=\"qclick3('04')\"> 주민등록번호";

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
	var cash_info04 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"6\" maxlength=\"6\" class=\"input_style\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input_style\">";

	var cash_info = eval("cash_info"+idnum);
	document.getElementById("cash_info02").innerHTML = cash_info;

}
-->
</script>

<? include "basket_list.inc"; ?>

<?php
if($wiz_session['id'] != "") {
	$level_info = level_info();
	$level = $level_info[$wiz_session[level]][name];
?>
<div style="margin:10px; background:#444444; color:#ffffff; font-weight:bold; text-align:center; padding:10px"><b><?=$wiz_session[name]?></b>님은 <b>[<?=$level?>]</b>입니다.</div>
<? } ?>

<div style="padding-left:10px;"><ul class="gray_list">
<li>배송정보 : <?=$deliver_msg?></li>
<li>지역별/상품 개별 배송정책에 따라 변동될 수 있습니다.</li>
</ul></div>

<form name="frm" action="<?=$ssl?>/shop/order_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="total_price" value="<?=$total_price?>">
<input type="hidden" name="coupon_idx" value="">
<input type="hidden" name="basket_exist" value="<?=$basket_exist?>">

<div style="padding-top:20px; padding-left:10px;"><b>주문 고객 정보</b></div>
<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
  <tr>
    <th>보내는 사람</th>
    <td><input name="send_name" value="<?=$mem_info->name?>" type="text" style="width:60px;" class="input_style" /></td>
  </tr>
  <tr>
    <th>전화번호</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td>
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
        </select>
        </td>
        <td>-</td>
        <td><input name="send_tphone2" value="<?=$mem_tphone[1]?>" type="text" class="input_style" style="width:60px;" /></td>
        <td>-</td>
        <td><input name="send_tphone3" value="<?=$mem_tphone[2]?>" type="text" style="width:60px;" class="input_style" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>휴대전화번호</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td>
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
        </select>
        </td>
        <td>-</td>
        <td><input name="send_hphone2" value="<?=$mem_hphone[1]?>" type="text" class="input_style" style="width:60px;" /></td>
        <td>-</td>
        <td><input name="send_hphone3" value="<?=$mem_hphone[2]?>" type="text" style="width:60px;" class="input_style" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>이메일</th>
    <td><input name="send_email" value="<?=$mem_info->email?>" type="text" class="input_style" style="width:95%;" /></td>
  </tr>
  <tr>
    <th>우편번호</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td><input name="send_post" value="<?=$mem_post[0]?>" type="text" style="width:60px;" class="input_style" /></td>
        
        <td><input type="button" value="주소찾기" class="btn_gray_small" style="width:50px;" onClick="zipSearch('send_')" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>주소</th>
    <td>
    <div><input name="send_address" value="<?=$mem_info->address?>" type="text" style="width:95%;" class="input_style" /></div>
    <div style="padding-top:2px;"><input name="send_address2" value="<?=$mem_info->address2?>" type="text" style="width:95%;" class="input_style" /></div>
    </td>
  </tr>
</table>
</div>



<div style="padding-top:20px; padding-left:10px; padding-right:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><b>수령 고객 정보</b></td>
    <td align="right"><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td></td>
        <td align="right" style="font-size:11px;">주문 고객정보와 동일</td>
    </tr></table></td>
  </tr>
</table>
</div>
<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
  <tr>
    <th>받는사람</th>
    <td><input name="rece_name" type="text" style="width:60px;" class="input_style" /></td>
  </tr>
  <tr>
    <th>일반전화</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td>
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
        </select>
        </td>
        <td>-</td>
        <td><input name="rece_tphone2" type="text" style="width:60px;" class="input_style" /></td>
        <td>-</td>
        <td><input name="rece_tphone3" type="text" style="width:60px;" class="input_style" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>휴대전화</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td>
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
        </select>
        </td>
        <td>-</td>
        <td><input name="rece_hphone2" type="text" style="width:60px;" class="input_style" /></td>
        <td>-</td>
        <td><input name="rece_hphone3" type="text" style="width:60px;" class="input_style" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>우편번호</th>
    <td><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td><input name="rece_post" type="text" style="width:60px;" class="input_style" /></td>
        
        <td><input type="button" value="주소찾기" class="btn_gray_small" style="width:50px;" onClick="zipSearch('rece_')" /></td>
    </tr></table></td>
  </tr>
  <tr>
    <th>주소</th>
    <td>
    <div><input name="rece_address" type="text" style="width:95%;" class="input_style" /></div>
    <div style="padding-top:2px;"><input name="rece_address2" type="text" style="width:95%;" class="input_style" /></div>
    </td>
  </tr>
  <tr>
    <th>주문메세지</th>
    <td><textarea name="demand" rows="2"></textarea></td>
  </tr>
</table>
</div>

<div style="padding-top:20px; padding-left:10px;"><b>결제정보</b></div>
<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
	<? if($oper_info->coupon_use == "Y"){ ?>
  <tr>
    <th>쿠폰사용</th>
    <td>
	  	<table border=0 cellpadding=0 cellspacing=0>
		  	<tr>
		    <td><input name="coupon_use" type="text" class="input_style" style="width:50px; text-align:right" readonly />원</td>
		    <td width="5"></td>
		    <td><input type="button" value="쿠폰조회" class="btn_gray_small" style="width:50px;" onClick="couponUse()" /></td>
		    </tr>
	    </table>
	   </td>
  </tr>
  <? } ?>

	<? if($oper_info->reserve_use == "Y"){ ?>
  <tr>
    <th>적립금 사용</th>
    <td>
    <div><table border="0" cellspacing="0" cellpadding="0"><tr>
    	<td>-</td>
        <td><input name="reserve_use" type="text" class="input_block" style="width:50px; text-align:right" onchange="reserveUse(this.form);" /></td>
        <td>원</td>
    </tr></table></div>
    <div>
    	<table border="0" cellspacing="0" cellpadding="0">
    		<tr>
	    		<td>(사용가능 적립금</td>
	        <td><input type="text" class="input_style" style="width:50px; text-align:right" value="<?=number_format($mem_info->reserve)?>" /></td>
	        <td>원)</td>
    		</tr>
    	</table>
    	<table>
    		<tr>
    			<td colspan="3"><font color=red>(적립금은 <?=number_format($oper_info->reserve_min)?>원부터 <?=number_format($oper_info->reserve_max)?>원까지 사용이 가능합니다)</font></td>
    		</tr>
    	</table>
    </div>
    </td>
  </tr>
  <? } ?>

  <tr>
    <th>총 결제금액</th>
    <td>상품(<b><?=number_format($prd_price)?>원</b>) <?=$discount_msg?> + 배송비(<b><?=number_format($deliver_price)?>원</b>) = <font class="prd_price"><?=number_format($total_price)?>원</font></td>
  </tr>
</table>
</div>


<div style="padding-top:20px; padding-left:10px;"><b>결제선택</b></div>
<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
  <tr>
    <th>결제방식</th>
    <td>
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
	    <div><table border="0" cellspacing="0" cellpadding="0"><tr>
	    	<td><input name="pay_method" type="radio" value="<?=$pay_method[$ii]?>" <?=$checked?> /></td><td><?=$pay_title?></td>
	    </tr></table></div>
			<?
				//}
			}
			?>
    </td>
  </tr>
  <? if(!strcmp($oper_info->tax_use, "Y")) { ?>
  <tr>
    <th>증빙서류</th>
    <td>
			<table width=100% border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>
					  <input type="radio" name="tax_type" value="N" checked onClick="qclick('');">발행안함
					  <input type="radio" name="tax_type" value="T" onClick="qclick('01');">세금계산서 신청
						<input type="radio" name="tax_type" value="C" onClick="qclick('02');">현금영수증 신청
					</td>
				</tr>
				<tr>
					<td>
			  		<table id="tax01" style="display:none" bgcolor="C8C8C8" width="100%" border="0" cellspacing="1" cellpadding="2">
			  			<tr>
			  				<td bgcolor="#F9F9F9">&nbsp; 사업자 번호</td><td colspan="3" bgcolor="#FFFFFF"><input type="text" name="com_num" value="<?=$mem_info->com_num?>" class="input_style"></td>
			  			</tr>
			  			<tr>
			  				<td width="20%" bgcolor="#F9F9F9">&nbsp; 상 호</td><td width="30%" bgcolor="#FFFFFF"><input type="text" name="com_name" value="<?=$mem_info->com_name?>" class="input_style"></td>
			  				<td width="20%" bgcolor="#F9F9F9">&nbsp; 대표자</td><td width="30%" bgcolor="#FFFFFF"><input type="text" name="com_owner" value="<?=$mem_info->com_owner?>" class="input_style"></td>
			  			</tr>
			  			<tr>
			  				<td bgcolor="#F9F9F9">&nbsp; 사업장 소재지</td><td colspan="3" bgcolor="#FFFFFF"><input type="text" name="com_address" value="<?=$mem_info->com_address?>" class="input_style"></td>
			  			</tr>
			  			<tr>
			  				<td bgcolor="#F9F9F9">&nbsp; 업 태</td><td bgcolor="#FFFFFF"><input type="text" name="com_kind" value="<?=$mem_info->com_kind?>" class="input_style"></td>
			  				<td bgcolor="#F9F9F9">&nbsp; 종 목</td><td bgcolor="#FFFFFF"><input type="text" name="com_class" value="<?=$mem_info->com_class?>" class="input_style"></td>
			  			</tr>
			  			<tr>
			  				<td bgcolor="#F9F9F9">&nbsp; 전화번호</td><td bgcolor="#FFFFFF"><input type="text" name="com_tel" value="<?=$mem_info->tphone?>" class="input_style"></td>
			  				<td bgcolor="#F9F9F9">&nbsp; 이메일</td><td bgcolor="#FFFFFF"><input type="text" name="com_email" value="<?=$mem_info->email?>" class="input_style"></td>
			  			</tr>
			  		</table>

			    	<table id="tax02" style="display:none;" bgcolor="C8C8C8" width="100%" border="0" cellspacing="1" cellpadding="2">
			      	<tr>
			        	<td width="20%" bgcolor="#F9F9F9">&nbsp; 발급사유</td>
			        	<td width="80%" bgcolor="#FFFFFF">
			        		<input type="radio" name="cash_type" value="C" onClick="qclick2('01');"> 사업자 지출증빙용
			        		<input type="radio" name="cash_type" value="P" onClick="qclick2('02');"> 개인소득 공제용
			        	</td>
			        </tr>
			        <tr>
			        	<td bgcolor="#F9F9F9">&nbsp; 신청정보</td>
			        	<td bgcolor="#FFFFFF">
			        		<div id="cash_info01"></div>
			        		<div id="cash_info02" style="padding:3px;"></div>
			        	</td>
			        </tr>
			        <tr>
			        	<td bgcolor="#F9F9F9">&nbsp; 신청자명</td>
			        	<td bgcolor="#FFFFFF">
			        		<input type="text" name="cash_name" value="" class="input_style">
			        	</td>
			        </tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
  </tr>
  <? } ?>
</table>
</div>

<div style="padding-left:10px; padding-top:10px"><ul class="gray_list">
<li>"결제방식"별 할인/적립 및 쿠폰은 결제 시 반영됩니다.</li>
<li>모바일샵 구매시 PC버전과 혜택 부분이 다를 수 있습니다.</li>
</ul></div>

<div class="btn">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="left">
	<tr>
		<td width="5"><img src="../img/sub/btn_gray_left.gif" /></td>
		<td><input type="submit" class="btn_grat_big" value="결제하기" /></td>
		<td width="5"><img src="../img/sub/btn_gray_right.gif" /></td>
	</tr>
</table>
</div>

</form>

<? include "../inc/footer.php" ?>
</body>
</html>