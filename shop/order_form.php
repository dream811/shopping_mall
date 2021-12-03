<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악

// 로그인 하지 않은경우 로그인 페이지로 이동
if(empty($wiz_session['id']) && empty($order_guest)){
	echo "<script>document.location='/member/login.php?prev=$PHP_SELF&order=true';</script>";
	exit;
}
$now_position = "<a href=/>Home</a> &gt; 주문하기 &gt; 주문정보 입력";
$page_type = "orderform";

include "../inc/util.inc"; 		   	// 유틸 라이브러리
include "../inc/shop_info.inc"; 	// 상점 정보
include "../inc/oper_info.inc"; 	// 운영 정보
include "../inc/mem_info.inc"; 		// 회원 정보
include "../inc/page_info.inc"; 	// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인

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
<script language="JavaScript" src="/js/util_lib.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="JavaScript">
<!--
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

		//alert(document.frm.del_list.length);

		for(i=0 ; i<document.frm.del_list.length; i++){
			document.frm.del_list[i].checked = false;
		}


		//frm.del_list[].checked = false;
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

	if(!selidx) {
		alert("주문할 상품을 선택하세요.");
		return false;
	}

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

// 주문자 우편번호
function zipSearch() {
	kind = 'send_';
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
			eval('document.frm.'+kind+'post').value = data.zonecode;
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

					eval('document.frm.'+kind+'address').value = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)

					eval('document.frm.'+kind+'address').value = data.jibunAddress;

                }

			//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
			//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
			//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
			//document.getElementById('addr').value = addr;

			if(eval('document.frm.'+kind+'address2') != null)
				eval('document.frm.'+kind+'address2').focus();
		}
	}).open();
}

// 수령자 우편번호
function zipSearch2() {
	kind = 'rece_';
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
			eval('document.frm.'+kind+'post').value = data.zonecode;
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

					eval('document.frm.'+kind+'address').value = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)

					eval('document.frm.'+kind+'address').value = data.jibunAddress;

                }

			//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
			//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
			//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
			//document.getElementById('addr').value = addr;

			if(eval('document.frm.'+kind+'address2') != null)
				eval('document.frm.'+kind+'address2').focus();
		}
	}).open();
}

function delSearch(){

	//document.frm.send_address.focus();
	var url = "/member/del_search.php?kind=rece_";
	window.open(url, "delSearch", "width=427, height=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");

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

	var url = "./coupon_list.php";
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

	var type1 = "<input type=\"radio\" name=\"cash_type2\" value=\"CARDNUM\" onclick=\"qclick3('01')\" id=\"cash1\"><label for=\"cash1\">현금영수증 카드번호</label>";
	var type2 = "<input type=\"radio\" name=\"cash_type2\" value=\"COMNUM\" onclick=\"qclick3('02')\" id=\"cash2\"><label for=\"cash2\">사업자 등록번호</label>";
	var type3 = "<input type=\"radio\" name=\"cash_type2\" value=\"HPHONE\" onclick=\"qclick3('03')\" id=\"cash3\"><label for=\"cash3\">휴대전화번호</label>";
	var type4 = "<input type=\"radio\" name=\"cash_type2\" value=\"RESNO\" onclick=\"qclick3('04')\" id=\"cash4\"><label for=\"cash4\">주민등록번호</label>";

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

	var cash_info01 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input\">";
	var cash_info02 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"3\" maxlength=\"3\" class=\"input\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"2\" maxlength=\"2\" class=\"input\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input\">";
	var cash_info03 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"3\" maxlength=\"3\" class=\"input\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input\"> - <input type=\"text\" name=\"cash_info_arr[]\" size=\"4\" maxlength=\"4\" class=\"input\">";
	var cash_info04 = "<input type=\"text\" name=\"cash_info_arr[]\" size=\"6\" maxlength=\"6\" class=\"input\"> - <input type=\"password\" name=\"cash_info_arr[]\" size=\"7\" maxlength=\"7\" class=\"input\">";

	var cash_info = eval("cash_info"+idnum);
	document.getElementById("cash_info02").innerHTML = cash_info;

}
//-->
</script>

<body onUnload="cuponClose();">
<?
$tab2="on";
include "prd_basket_step.php";
?>

<div style="margin:15px 0 0;"><? include "basket_listing.inc"; ?></div>
<!--
  190416_ 상단 include basket_list.inc → basket_order.inc 로 교체해도 되면 교체해주시고 아니면
  order_form.php 로 넘어오면 체크박스 삭제 / 수량 값만 노출 / 기능 칸 숨김처리 해주세요.
-->

<form name="frm" action="<?=$ssl?>/shop/order_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="total_price" value="<?=$total_price?>">
<input type="hidden" name="coupon_idx" value="">
<input type="hidden" name="basket_exist" value="<?=$basket_exist?>">
<input type="hidden" name="selidx" value="<?=$selidx?>">

<h2 class="order_ttl">주문자 정보</h2>
<table border=0 cellpadding=0 cellspacing=0 width=100% class="AW_order_table">
	<tr>
		<td width="16%" class="tit">주문하시는 분</td>
		<td width="84%" class="val"><input type=text name="send_name" value="<?=$mem_info->name?>" size=25 class="input"></td>
	</tr>
	<tr>
		<td class="tit">전화번호</td>
		<td class="val">
			<input type=text name="send_tphone" value="<?=$mem_tphone[0]?>" size=3 class="input"> -
			<input type=text name="send_tphone2" value="<?=$mem_tphone[1]?>" size=4 class="input"> -
			<input type=text name="send_tphone3" value="<?=$mem_tphone[2]?>" size=4 class="input">
		</td>
	</tr>
	<tr>
		<td class="tit">휴대전화번호</td>
		<td class="val">
			<input type=text name="send_hphone" value="<?=$mem_hphone[0]?>" size=3 class="input"> -
			<input type=text name="send_hphone2" value="<?=$mem_hphone[1]?>" size=4 class="input"> -
			<input type=text name="send_hphone3" value="<?=$mem_hphone[2]?>" size=4 class="input">
		</td>
	</tr>
	<tr>
		<td class="tit">이메일</td>
		<td class="val"><input type=text name="send_email" value="<?=$mem_info->email?>" size=30 class="input"></td>
	</tr>
	<tr>
		<td class="tit">주 소</td>
		<td class="val">
			<input type=text name="send_post" value="<?=$mem_post[0]?>" size=7 class="input">
			<a href="javascript:zipSearch();" class="post_btn">우편번호 찾기</a><br />
			<input type=text name="send_address" value="<?=$mem_info->address?>" size=70 class="input"><br />
			<input type=text name="send_address2" value="<?=$mem_info->address2?>" size=70 class="input">
		</td>
	</tr>
</table>


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="chk_table">
	<tr>
		<td align="left"><h2 class="order_ttl">배송지 정보</h2></td>
		<td align="right">
			<script src="//code.jquery.com/jquery-1.10.2.js"></script>
			<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
			<script>
				function del_Add() {
					window.open("del_add.php", "del_add", "height=400, width=600, left=600,top=450,menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
				}

				function showInfo(idx) {
					var form_data = {
						is_ajax: 1
					};
					$.ajax({
						type: "POST",
						url: "ajax.php?idx=" + idx,
						data: form_data,
						dataType: 'json',
						success: function(result) {
							if (result) {
								var tphone = result.tphone.split('-');
								var hphone = result.hphone.split('-');
								var address = result.address.split('|');
								var posts = result.post.split('-');

								$("#name").val(result.name);
								$("#t1").val(tphone[0]);
								$("#t2").val(tphone[1]);
								$("#t3").val(tphone[2]);

								$("#h1").val(hphone[0]);
								$("#h2").val(hphone[1]);
								$("#h3").val(hphone[2]);
								$("#post1").val(posts[0]);
								$("#post2").val(posts[1]);

								$("#address1").val(address[0]);
								$("#address2").val(address[1]);
							} else {

							}
						}
					});
					return false;
				}
			</script>
			<!--
		<?
			$sql = "select * from wiz_dellist where id='".$wiz_session['id']."'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($row = mysqli_fetch_object($result)){
		?>
				<input type="radio" name="del_list" value="<?=$row->idx?>" onClick="showInfo('<?=$row->idx?>')"><?=$row->delname?>
		<?
			}
		?>

		<a href="javascript:del_Add()">[신규배송지 추가]</a>
		-->
			<input type="checkbox" name="same_check" onClick="sameCheck(this.form);" id="same_chk" /><label for="same_chk">주문자 정보와 동일합니다.</label>
		</td>
	</tr>
</table>
<div id="info">
	<table id="" border=0 cellpadding=0 cellspacing=0 width=100% class="AW_order_table">
		<tr>
			<td width="20%" class="tit">받으시는분</td>
			<td width="80%" class="val"><input type=text id="name" name="rece_name" size=25 class="input"></td>
		</tr>
		<tr>
			<td class="tit">전화번호</td>
			<td class="val">
			  <input type=text id="t1" name="rece_tphone" size=3 class="input"> -
			  <input type=text id="t2" name="rece_tphone2" size=4 class="input"> -
			  <input type=text id="t3" name="rece_tphone3" size=4 class="input"></td>
		</tr>
		<tr>
			<td class="tit">휴대전화번호</td>
			<td class="val">
			  <input type=text id="h1" name="rece_hphone" size=3 class="input"> -
			  <input type=text id="h2" name="rece_hphone2" size=4 class="input"> -
			  <input type=text id="h3" name="rece_hphone3" size=4 class="input"></td>
		</tr>
		<tr>
			<td class="tit">주소</td>
			<td class="val">
			  <input type=text id="post1" name="rece_post" size=7 class="input">
			  <a href="javascript:zipSearch2();" class="post_btn">우편번호 찾기</a><br />
			  <input type=text id="address1" name="rece_address" size=70 class="input"><br />
			  <input type=text id="address2" name="rece_address2" size=70 class="input">
			</td>
		</tr>
		<tr>
			<td class="tit">요청사항<br><br></td>
			<td class="val"><textarea name="demand" cols="80" rows="4" class="input"></textarea></td>
		</tr>
	</table>

</div>


<table border=0 cellpadding=6 cellspacing=0 width=100% class="AW_order_table">
	<? if($oper_info->coupon_use == "Y"){ ?>
	<tr>
		<td class="tit">쿠폰사용</td>
		<td class="val">
			<input type="text" name="coupon_use" style="text-align:right" size="15" class="input" readonly>&nbsp;원
			<a href="javascript:couponUse();" class="post_btn">쿠폰조회 및 적용</a>
		</td>
	</tr>
	<? } ?>

	<? if($oper_info->reserve_use == "Y"){ ?>
	<tr>
		<td class="tit">적립금사용</td>
		<td class="val">
			<input type="text" name="reserve_use" style="text-align:right" size="15" class="input" onchange="reserveUse(this.form);">&nbsp;원 (보유적립금 :
			<?=number_format($mem_info->reserve)?>원)
			<p class="reserve_ttl">
				&#8251; 적립금은 <?=number_format($oper_info->reserve_min)?>원부터 <?=number_format($oper_info->reserve_max)?>원까지 사용이 가능합니다.
			</p>
		</td>
	</tr>
	<? } ?>
	<tr>
		<td width="20%" class="tit" style="height:40px;">결제방법</td>
		<td width="80%" class="val">
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
				echo "<input type='radio' name='pay_method' value='$pay_method[$ii]' id='$pay_method[$ii]' $checked><label for='$pay_method[$ii]'>$pay_title</label>";
			//}

		}
		?>
		</td>
	</tr>

	<? if(!strcmp($oper_info->tax_use, "Y")) { ?>
	<tr>
		<td class="tit">세금계산서</td>
		<td class="val">
			<input type="radio" name="tax_type" value="N" checked onClick="qclick('');" id="tax_N"><label for="tax_N">발행안함</label>
			<input type="radio" name="tax_type" value="T" onClick="qclick('01');" id="tax_T"><label for="tax_T">세금계산서 신청</label>
			<input type="radio" name="tax_type" value="C" onClick="qclick('02');" id="tax_C"><label for="tax_C">현금영수증 신청</label>

			<table id="tax01" class="tax_table" style="display:none">
				<tr>
					<td bgcolor="#F9F9F9">&nbsp; 사업자 번호</td>
					<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="com_num" value="<?=$mem_info->com_num?>" class="input" size="20"></td>
				</tr>
				<tr>
					<td width="20%" bgcolor="#F9F9F9">&nbsp; 상 호</td>
					<td width="30%" bgcolor="#FFFFFF"><input type="text" name="com_name" value="<?=$mem_info->com_name?>" class="input"></td>
					<td width="20%" bgcolor="#F9F9F9">&nbsp; 대표자</td>
					<td width="30%" bgcolor="#FFFFFF"><input type="text" name="com_owner" value="<?=$mem_info->com_owner?>" class="input"></td>
				</tr>
				<tr>
					<td bgcolor="#F9F9F9">&nbsp; 사업장 소재지</td>
					<td colspan="3" bgcolor="#FFFFFF"><input type="text" name="com_address" value="<?=$mem_info->com_address?>" class="input" size="50"></td>
				</tr>
				<tr>
					<td bgcolor="#F9F9F9">&nbsp; 업 태</td>
					<td bgcolor="#FFFFFF"><input type="text" name="com_kind" value="<?=$mem_info->com_kind?>" class="input"></td>
					<td bgcolor="#F9F9F9">&nbsp; 종 목</td>
					<td bgcolor="#FFFFFF"><input type="text" name="com_class" value="<?=$mem_info->com_class?>" class="input"></td>
				</tr>
				<tr>
					<td bgcolor="#F9F9F9">&nbsp; 전화번호</td>
					<td bgcolor="#FFFFFF"><input type="text" name="com_tel" value="<?=$mem_info->tphone?>" class="input"></td>
					<td bgcolor="#F9F9F9">&nbsp; 이메일</td>
					<td bgcolor="#FFFFFF"><input type="text" name="com_email" value="<?=$mem_info->email?>" class="input"></td>
				</tr>
			</table>

			<table id="tax02" class="tax_table" style="display:none;">
				<tr>
					<td width="20%" bgcolor="#F9F9F9">&nbsp; 발급사유</td>
					<td width="80%" bgcolor="#FFFFFF">
						<input type="radio" name="cash_type" value="C" onClick="qclick2('01');" id="cash_C"><label for="cash_C">사업자 지출증빙용</label>
						<input type="radio" name="cash_type" value="P" onClick="qclick2('02');" id="cash_P"><label for="cash_P">개인소득 공제용</label>
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
						<input type="text" name="cash_name" value="" class="input">
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<? } ?>
</table>
<div class="AW_btn_area">
	<button type="submit" class="submit_btn">결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">주문취소</button>
</div>

</form>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>