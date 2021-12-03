<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if(!isset($dep_code)) $dep_code = "";
if(!isset($shortpage)) $shortpage = "";
if(!isset($dep2_code)) $dep2_code = "";
if(!isset($dep3_code)) $dep3_code = "";
if(!isset($s_special)) $s_special = "";
if(!isset($s_display)) $s_display = "";
if(!isset($s_searchkey)) $s_searchkey = "";
if(!isset($s_couponuse)) $s_couponuse = "";
if(!isset($s_searchopt)) $s_searchopt = "";
if(!isset($s_brand)) $s_brand = "";
if(!isset($s_shortage)) $s_shortage = "";
if(!isset($s_stock)) $s_stock = "";
if(!isset($s_status)) $s_status = "";
if(!isset($s_mallid)) $s_mallid = "";
if(!isset($page)) $page = "";
if(!isset($prdcode)) $prdcode = "";
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code";
$param .= "&s_special=$s_special&s_display=$s_display&s_couponuse=$s_couponuse&s_searchopt=$s_searchopt&s_searchkey=$s_searchkey";
$param .= "&s_brand=$s_brand&s_shortage=$s_shortage&s_stock=$s_stock&s_status=$s_status&s_mallid=$s_mallid&page=$page";
//--------------------------------------------------------------------------------------------------

if($shortpage == "Y") $listpage_url = "prd_shortage.php";
else $listpage_url = "prd_list.php";

$imgpath = $DOCUMENT_ROOT."/data/prdimg";

if(empty($mode)) $mode = "insert";

if($mode == "insert"){

	$catcode01 = $dep_code;
	$catcode02 = $dep_code.$dep2_code;
	$catcode03 = $dep_code.$dep2_code.$dep3_code;
  if(!isset($prd_row)) $prd_row = new stdClass();
	$prd_row->stock = "100";

	// 상품승인
	if(!strcmp($oper_info->mall_prd, "N")) $prd_row->status = "Y";
	else $prd_row->status = "N";

// 상품정보를 가져온다.
}else if($mode == "update"){

	$sql = "select wp.*, wc.idx, wc.catcode from wiz_product wp, wiz_cprelation wc where wp.prdcode = '$prdcode' and wp.prdcode = wc.prdcode";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$prd_row = mysqli_fetch_object($result);
	if(!get_magic_quotes_gpc()) $prd_row->content = stripslashes($prd_row->content);

	$relidx = $prd_row->idx;
	$catcode01 = substr($prd_row->catcode,0,2);
	$catcode02 = substr($prd_row->catcode,0,4);
	$catcode03 = substr($prd_row->catcode,0,6);

}

// 적립금 사용여부와 적용률을 가저온다.
$sql = "select reserve_use, reserve_buy from wiz_operinfo";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$reserve_use = $row->reserve_use;
$reserve_buy = $row->reserve_buy;

?>
<script language="JavaScript" type="text/javascript">
<!--
  var loding = false;
  var prd_class = new Array();
<?
   $no = 0;
   $sql = "select catcode, catname, depthno from wiz_category order by priorno01, priorno02, priorno03 asc";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   $total = mysqli_num_rows($result);
   while($row = mysqli_fetch_object($result)){

      $code01 = substr($row->catcode,0,2);
      $code02 = substr($row->catcode,0,4);
      $code03 = substr($row->catcode,0,6);

      if($row->depthno == 1){ $catcode = $code01; $parent = 0; }
      if($row->depthno == 2){ $catcode = $code02; $parent = $code01; }
      if($row->depthno == 3){ $catcode = $code03; $parent = $code02; }
?>

  prd_class[<?=$no?>] = new Array();
  prd_class[<?=$no?>][0] = "<?=$catcode?>";
  prd_class[<?=$no?>][1] = "<?=$row->catname?>";
  prd_class[<?=$no?>][2] = "<?=$parent?>";
  prd_class[<?=$no?>][3] = "<?=$row->depthno?>";

<?
   	$no++;
   }
?>
var tno = <?=$total?>;

function setClass01(){

  var arrayClass = eval("document.frm.class01");
  var arrayClass1 = eval("document.frm.class02");
  var arrayClass2 = eval("document.frm.class03");

  arrayClass.options[0] = new Option(":: 대분류 ::","");
  arrayClass1.options[0] = new Option(":: 중분류 ::","");
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='1'){
		 arrayClass.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }
}

function changeClass01(){

  var arrayClass = eval("document.frm.class01");
  var arrayClass1 = eval("document.frm.class02");
  var arrayClass2 = eval("document.frm.class03");

  var selidx = arrayClass.selectedIndex;
  var selvalue = arrayClass.options[selidx].value;

  arrayClass1.options.length=0;
  arrayClass2.options.length=0;
  arrayClass1.options[0] = new Option(":: 중분류 ::","");
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='2' && prd_class[no][2]==selvalue){
		 arrayClass1.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }

}

function changeClass02(){

  var arrayClass1 = eval("document.frm.class02");
  var arrayClass2 = eval("document.frm.class03");

  var selidx = arrayClass1.selectedIndex;
  var selvalue = arrayClass1.options[selidx].value;

  arrayClass2.options.length=0;
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='3' && prd_class[no][2]==selvalue){
		 arrayClass2.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }

}

function changeClass03(){
}

// 상품카테고리 설정
function setCategory(){

  var arrayClass01 = eval("document.frm.class01");
  var arrayClass02 = eval("document.frm.class02");
  var arrayClass03 = eval("document.frm.class03");

  for(no=1; no < arrayClass01.length; no++){
    if(arrayClass01.options[no].value == '<?=$catcode01?>'){
      arrayClass01.options[no].selected = true;
      changeClass01();
    }
  }

  for(no=1; no < arrayClass02.length; no++){
    if(arrayClass02.options[no].value == '<?=$catcode02?>'){
      arrayClass02.options[no].selected = true;
      changeClass02();
    }
  }

  for(no=1; no < arrayClass03.length; no++){
    if(arrayClass03.options[no].value == '<?=$catcode03?>')
      arrayClass03.options[no].selected = true;
  }

}

function inputCheck(frm){

   if(loding == false){
   	alert("상품정보를 가져오고 있습니다. 잠시후 재시도 하세요");
   	return false;
   }
	if(frm.prdname.value == ""){
		alert("상품명을 입력하세요");
		frm.prdname.focus();
		return false;
	}
	if(frm.sellprice.value == ""){
		alert("판매가를 입력하세요");
		frm.sellprice.focus();
		return false;
	}
	content.outputBodyHTML();

/*
	var optvalue = "";
	var length = frm.optcode_tmp.length;
	for(ii = 0; ii < length; ii++){ optvalue += frm.optcode_tmp.options[ii].value+"^^"; }
	frm.optcode.value = optvalue;
*/
}

//해당 이미지를 삭제한다.
function deleteImage(prdcode, prdimg, imgpath){
	if(imgpath == ""){
		alert("삭제할 이미지가 없습니다.");
		return;
	}else{
	if(confirm("이미지를 삭제하시겠습니까?"))
		document.location = "prd_save.php?mode=delete_image&prdcode="+prdcode+"&prdimg="+prdimg+"&imgpath="+imgpath;
	}
	return;
}

function appendOption(){

	var frm = document.frm;
	var length = frm.optcode_tmp.length;

	var optcode_01 = frm.optcode_01.value;
	var optcode_02 = frm.optcode_02.value;
	var optcode_03 = frm.optcode_03.value;

	if(optcode_01 == ""){
		alert("옵션을 입력하세요.");
		frm.optcode_01.focus();
		return;
	}
	if(optcode_02 == "") optcode_02 = "0";
	if(optcode_03 == "") optcode_03 = "0";

	if(!Check_Num(optcode_02)){
		alert("가격은 숫자만 가능합니다.");
		frm.optcode_02.focus();
		return;
	}
	if(!Check_Num(optcode_03)){
		alert("재고는 숫자만 가능합니다.");
		frm.optcode_03.focus();
		return;
	}

	var opttxt = optcode_01+" - "+optcode_02+"원 : "+optcode_03+"개";
	var optvalue = optcode_01+"^"+optcode_02+"^"+optcode_03;

	var option1 = new Option(opttxt, optvalue, true);
	frm.optcode_tmp.options[length] = option1;

	frm.optcode_01.value = "";
	frm.optcode_02.value = "";
	frm.optcode_03.value = "";

}

function deleteOption(){

	var frm = document.frm;
	var index = frm.optcode_tmp.selectedIndex;

	if(index >= 0){
		frm.optcode_tmp.options[index] = null;
	}
	frm.optcode_01.value = "";
	frm.optcode_02.value = "";
	frm.optcode_03.value = "";
}

function editOption(){


	var frm = document.frm;
	var length = frm.optcode_tmp.length;
	var idx = document.frm.optcode_tmp.selectedIndex;

	if(idx < 0) return;

	var optcode_01 = frm.optcode_01.value;
	var optcode_02 = frm.optcode_02.value;
	var optcode_03 = frm.optcode_03.value;

	if(optcode_01 == ""){
		alert("옵션을 입력하세요.");
		frm.optcode_01.focus();
		return;
	}
	if(optcode_02 == "") optcode_02 = "0";
	if(optcode_03 == "") optcode_03 = "0";

	if(!Check_Num(optcode_02)){
		alert("가격은 숫자만 가능합니다.");
		frm.optcode_02.focus();
		return;
	}
	if(!Check_Num(optcode_03)){
		alert("재고는 숫자만 가능합니다.");
		frm.optcode_03.focus();
		return;
	}

	var opttxt = optcode_01+" - "+optcode_02+"원 : "+optcode_03+"개";
	var optvalue = optcode_01+"^"+optcode_02+"^"+optcode_03;

	document.frm.optcode_tmp.options['idx'].text = opttxt;
	document.frm.optcode_tmp.options['idx'].value = optvalue;

}

function openOption(optno){

	var url = "option_list.php?optno=" + optno;
  window.open(url,"","height=400, width=400, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}


function chgOption(){

	var tmp_optcode = document.frm.optlist.value.split("^^");
	var tmp_opttext = document.frm.opttext.value.split("^^");
   var length = document.frm.optcode_tmp.length;
	for(ii=0; ii < (tmp_optcode.length-1); ii++){
		var option1 = new Option(tmp_opttext[ii], tmp_optcode[ii], true);
		document.frm.optcode_tmp.options[length] = option1;

		length++;
	}

	return false;
}

function setOption(){
	var idx = document.frm.optcode_tmp.selectedIndex;
	if(idx >= 0){
		var tmp_optcode = document.frm.optcode_tmp.options['idx'].value.split("^");
		frm.optcode_01.value = tmp_optcode[0];
		frm.optcode_02.value = tmp_optcode[1];
		frm.optcode_03.value = tmp_optcode[2];
	}

}


function optUp(){

	var frm = document.frm;
	var sel_idx = frm.optcode_tmp.selectedIndex;

	if(sel_idx > 0){

		chg_idx = sel_idx - 1;

		var sel_txt = frm.optcode_tmp.options[sel_idx].text;
		var sel_val = frm.optcode_tmp.options[sel_idx].value;
		var chg_txt = frm.optcode_tmp.options[chg_idx].text;
		var chg_val = frm.optcode_tmp.options[chg_idx].value;


		frm.optcode_tmp.options[chg_idx].text = sel_txt;
		frm.optcode_tmp.options[chg_idx].value = sel_val;
		frm.optcode_tmp.options[sel_idx].text = chg_txt;
		frm.optcode_tmp.options[sel_idx].value = chg_val;

		frm.optcode_tmp.options[chg_idx].selected = true;

	}



}

function optDown(){

	var frm = document.frm;
	var sel_idx = frm.optcode_tmp.selectedIndex;
	var opt_len = document.frm.optcode_tmp.length;

	if(-1 < sel_idx && sel_idx < (opt_len-1)){

		var chg_idx = sel_idx + 1;

		var sel_txt = frm.optcode_tmp.options[sel_idx].text;
		var sel_val = frm.optcode_tmp.options[sel_idx].value;
		var chg_txt = frm.optcode_tmp.options[chg_idx].text;
		var chg_val = frm.optcode_tmp.options[chg_idx].value;


		frm.optcode_tmp.options[chg_idx].text = sel_txt;
		frm.optcode_tmp.options[chg_idx].value = sel_val;
		frm.optcode_tmp.options[sel_idx].text = chg_txt;
		frm.optcode_tmp.options[sel_idx].value = chg_val;

		frm.optcode_tmp.options[chg_idx].selected = true;

	}

}

function prdlayCheck(){
	<?
	if(@file($imgpath."/".$prd_row->prdimg_S2)) echo "document.frm.prdlay_check2.checked = true; prdlay2.style.display='';";
	if(@file($imgpath."/".$prd_row->prdimg_S3)) echo "document.frm.prdlay_check3.checked = true; prdlay3.style.display='';";
	if(@file($imgpath."/".$prd_row->prdimg_S4)) echo "document.frm.prdlay_check4.checked = true; prdlay4.style.display='';";
	if(@file($imgpath."/".$prd_row->prdimg_S5)) echo "document.frm.prdlay_check5.checked = true; prdlay5.style.display='';";
	//if($prd_row->opttitle != "" || $prd_row->optcode != "") echo "document.frm.opt_use.checked = true; prdopt.style.display='';";
  if(!isset($prd_row->opt_use)) $prd_row->opt_use = "";
	if($prd_row->opt_use == "Y") echo "document.frm.opt_use.checked = true; prdopt.style.display='';";
	?>
}

// 상품가격에 따른 적립금 적용 퍼센트에따라 적립금 적용
function setReserve(frm){

   if(frm.reserve != null){
   	var sellprice = frm.sellprice.value;
   	if(!Check_Num(sellprice)){
			alert("판매가는 숫자이어야 합니다.");
			frm.sellprice.value = "";
			frm.sellprice.focus();
		}else{
	      var reserve = "" + sellprice*(<?=$reserve_buy?>/100)
	      reserve = reserve.split('.');
	      frm.reserve.value = reserve[0];
	   }
   }
}

function lodingComplete(){
	loding = true;
}

function prdCategory(){
  var url = "prd_catlist.php?prdcode=<?=$prdcode?>";
  window.open(url, "prdCategory", "height=330, width=600, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
}

function prdIcon(){
	var url = "prd_icon.php";
	window.open(url, "prdIcon", "height=300, width=500, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}

function setImgsize(){
	var url = "prd_imgsize.php";
   window.open(url, "setImgsize", "height=320, width=400, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=150, top=100");
}


function addopt(optid) {

	var frm = document.frm;
	var tbl = document.getElementById('opt');

	if(optid == 'opt1') {

		var row = tbl.insertRow(-1);

		var idx = tbl.rows.length - 2;

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(-1);
			switch (i){
				case 0: cell.innerHTML = "<input type=\"text\" class=\"input\" size=\"10\" name=\"tmp_optcode[]\" required>";break;
				default:
					cell.innerHTML = "<input type=\"text\" name=\"tmp_opt[sellprice][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[reserve][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[stock][]\" class=\"input\" size=\"7\">";
					break;
			}
		}

	} else if(optid == 'opt2') {

		for (i=0;i<tbl.rows.length;i++){

			cell = tbl.rows[i].insertCell(-1);
			switch (i){
				case 0: cell.innerHTML = "<input type=\"text\" class=\"input\" style=\"width:100%\" name=\"tmp_optcode2[]\" required>";break;
				default:
					cell.innerHTML = "<input type=\"text\" name=\"tmp_opt[sellprice][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[reserve][]\" class=\"input\" size=\"7\">";
					cell.innerHTML += ":<input type=\"text\" name=\"tmp_opt[stock][]\" class=\"input\" size=\"7\">";
					break;
			}
		}

	} else if(optid == 'opt3') {

		var tbl = document.getElementById('opt3');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode3_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_res[]\">";
		}

	} else if(optid == 'opt4') {

		var tbl = document.getElementById('opt4');
		var row = tbl.insertRow(-1);

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell(0);
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode4_opt[]\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_res[]\">";
		}

	}
}

function delopt(optid) {
	var tbl = document.getElementById('opt');

	if(optid == 'opt1') {

		if (tbl.rows.length > 2) opt.deleteRow(-1);

	} else if(optid == 'opt2') {

		if (tbl.rows[0].cells.length < 3) return;
		for (i=0;i<tbl.rows.length;i++){
			tbl.rows[i].deleteCell(-1);
		}

	} else if(optid == 'opt3') {
		var tbl = document.getElementById('opt3');
		if (tbl.rows.length > 1) opt3.deleteRow(-1);
	} else if(optid == 'opt4') {
		var tbl = document.getElementById('opt4');
		if (tbl.rows.length > 1) opt4.deleteRow(-1);
	}
}

// 상품별쿠폰 발급회원
function popMycoupon(prdcode){
	var url = "../shop/shop_mycoupon.php?prdcode=" + prdcode;
	window.open(url,"MyCouponList","height=400, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 입점업체 수수료 설정
function setCmsRate() {

	var frm = document.frm;

	if(frm.cms_type[0].checked) {

		var mall_opt = frm.mallid.options[frm.mallid.selectedIndex];

		var cms_type = mall_opt.getAttribute('cms_type');
		var cms_rate = mall_opt.getAttribute('cms_rate');

		if(cms_type == "M") {
			if(cms_rate != undefined) frm.cms_rate.value = cms_rate;
			else frm.cms_rate.value = 0;
		} else {

			var cat_opt1 = frm.class01.options[frm.class01.selectedIndex];
			var cat_opt2 = frm.class02.options[frm.class02.selectedIndex];
			var cat_opt3 = frm.class03.options[frm.class03.selectedIndex];

			if(frm.class03.selectedIndex > 0) var cat_rate = cat_opt3.getAttribute('cat_rate');
			else if(frm.class02.selectedIndex > 0) var cat_rate = cat_opt2.getAttribute('cat_rate');
			else if(frm.class01.selectedIndex > 0) var cat_rate = cat_opt1.getAttribute('cat_rate');

			if(cat_rate != undefined) frm.cms_rate.value = cat_rate;
			else frm.cms_rate.value = 0;

		}

	}

	setCmsType();
	setCms();

}

// 입점업체 수수료설정
function setCmsType() {

	var frm = document.frm;

	if("<?=$oper_info->mall_rate?>" != "Y") {
		if(frm.cms_type[1].checked || frm.cms_type2[1].checked) {

			alert("수수료 설정을 변경할 수 없습니다.");

			frm.cms_type[0].checked = true;
			frm.cms_type2[0].checked = true;

		}
	} else {

		if(frm.cms_type[0].checked && frm.cms_type2[1].checked) {
			alert("수수료 설정이 '카테고리 또는 입점업체 수수료'인 경우에 수수료기준으로 입력할 수 없습니다.");
			frm.cms_type2[0].checked = true;
			setCmsRate();
		}

	}

	if(frm.cms_type2[0].checked) {
		frm.cms_rate.className = "input_gray";
		frm.supprice.className = "input";

		frm.cms_rate.readOnly = true;
		frm.supprice.readOnly = false;
	} else {
		frm.cms_rate.className = "input";
		frm.supprice.className = "input_gray";

		frm.cms_rate.readOnly = false;
		frm.supprice.readOnly = true;
	}

}

// 수수료율, 공급가, 판매가 설정
function setCms(obj) {

	var frm = document.frm;

	var sellprice = frm.sellprice.value;
	var supprice = frm.supprice.value;
	var cms_rate = frm.cms_rate.value;

	if(obj == "" && frm.cms_type2[1].checked == true) {
		obj = "sup";
	}

	if(sellprice != "" && !Check_Num(sellprice)){
		alert("판매가는 숫자이어야 합니다.");
		frm.sellprice.value = "";
		frm.sellprice.focus();
	} else if(cms_rate > 100) {
		alert("수수료율은 100보다 작아야 합니다.");
		frm.cms_rate.value = "";
		frm.cms_rate.focus();
	} else{

		if(obj == "sup") {

			// 공급가 입력 시 수수료율 자동입력
			if(Number(sellprice) < Number(supprice)) {
				alert("공급가는 판매가보다 작아야합니다.");
				frm.cms_rate.value = "0";
				frm.supprice.value = "";
				frm.supprice.focus();
			} else {
				var cms_rate = (1 - (supprice/sellprice)) * 100;
				cms_rate = Math.round(cms_rate);
		    frm.cms_rate.value = cms_rate;
				/*
		    var sellprice = "" + supprice/((100 - cms_rate)/100);
		    sellprice = sellprice.split('.');
		    frm.sellprice.value = sellprice[0];

		    setReserve(frm);
		    */
		  }

		} else {

	    var supprice = "" + sellprice*(cms_rate/100);
	    supprice = supprice.split('.');
	    frm.supprice.value = sellprice - supprice[0];

	  }

  }

}

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">상품등록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">상품 상세정보를 설정합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기본정보</td>
			  </tr>
			</table>
      <form name="frm" action="prd_save.php?<?=$param?>" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="optlist" value="">
      <input type="hidden" name="opttext" value="">
      <input type="hidden" name="prdcode" value="<?=$prdcode?>">
      <input type="hidden" name="relidx" value="<?=$relidx?>">
      <input type="hidden" name="optcode" value="<?=$optcode?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
            	<tr>
            		<td class="t_name">공급업체</td>
                <td class="t_value">
                	<select name="mallid" onChange="setCmsRate()">
                		<option value="">::입점업체 선택::</option>
                		<?php
                		$sql = "select id, com_name, cms_type, cms_rate from wiz_mall where status = 'Y' order by com_name asc";
                		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
                    if(!isset($prd_row->mallid)) $prd_row->mallid = "";
                		while($row = mysqli_fetch_array($result)) {
                		?>
                		<option value="<?=$row['id']?>" cms_type="<?=$row['cms_type']?>" cms_rate="<?=$row['cms_rate']?>"  <? if(!strcmp($row['id'], $prd_row->mallid)) echo "selected" ?>><?=$row['com_name']?> (<?=$row['id']?>)</option>
                		<?php
                		}
                		?>
                	</select>
                </td>
                <td align="left" class="t_name">승인여부</td>
                <td class="t_value">
                	<input type="radio" name="status" value="Y" <? if(!strcmp($prd_row->status, "Y")) echo "checked" ?>> 승인
                	<input type="radio" name="status" value="N" <? if(!strcmp($prd_row->status, "N")) echo "checked" ?>> 미승인
                </td>
              </tr>
              <tr>
                <td class="t_name">상품분류</td>
                <td class="t_value" colspan="3">
                <select name="class01" onChange="changeClass01();setCmsRate();">
                </select>
                <select name="class02" onChange="changeClass02();setCmsRate();">
                </select>
                <select name="class03" onChange="changeClass03();setCmsRate();">
                </select>&nbsp;
                <? if($mode == "update"){ ?>
                	<a href="javascript:prdCategory()" class="AW-btn-s-black">분류추가</a>
                <? } ?>
                </td>
              </tr>
              <tr>
                <td class="t_name">상품그룹</td>
                <td class="t_value" colspan="3">
                  <?php
                  if(!isset($prd_row->new)) $prd_row->new = ""; 
                  if(!isset($prd_row->best)) $prd_row->best = ""; 
                  if(!isset($prd_row->popular)) $prd_row->popular = ""; 
                  if(!isset($prd_row->recom)) $prd_row->recom = ""; 
                  if(!isset($prd_row->sale)) $prd_row->sale = ""; 
                  ?>
                  <input type="checkbox" name="new" value="Y" <? if($prd_row->new == "Y") echo "checked"; ?>><img src="/images/icon_new.gif" border="0"> &nbsp;
                  <input type="checkbox" name="best" value="Y" <? if($prd_row->best == "Y") echo "checked"; ?>><img src="/images/icon_best.gif" border="0"> &nbsp;
                  <input type="checkbox" name="popular" value="Y" <? if($prd_row->popular == "Y") echo "checked"; ?>><img src="/images/icon_hit.gif" border="0"> &nbsp;
                  <input type="checkbox" name="recom" value="Y" <? if($prd_row->recom == "Y") echo "checked"; ?>><img src="/images/icon_rec.gif" border="0"> &nbsp;
                  <input type="checkbox" name="sale" value="Y" <? if($prd_row->sale == "Y") echo "checked"; ?>><img src="/images/icon_sale.gif" border="0"> &nbsp;
                </td>
              </tr>
              <tr>
                <td class="t_name">상품아이콘</td>
                <td class="t_value" colspan="3">
                	<table cellspacing=0 cellpadding=0><tr><td>
                	<table cellspacing=0 cellpadding=0>
                	<?
                  if(!isset($prd_row->prdicon)) $prd_row->prdicon = "";
                  if(!isset($prd_row->prdname)) $prd_row->prdname = "";
                  if(!isset($prd_row->prdcom)) $prd_row->prdcom = "";
                  if(!isset($prd_row->origin)) $prd_row->origin = "";
                  if(!isset($prd_row->showset)) $prd_row->showset = "";
                  if(!isset($prd_row->info_use)) $prd_row->info_use = "";
                  if(!isset($prd_row->coupon_use)) $prd_row->coupon_use = "";
                  if(!isset($prd_row->conprice)) $prd_row->conprice = "";
                  if(!isset($prd_row->sellprice)) $prd_row->sellprice = "";
                  if(!isset($prd_row->shortage)) $prd_row->shortage = "";
                  if(!isset($prd_row->cms_type)) $prd_row->cms_type = "";
                  if(!isset($prd_row->cms_type2)) $prd_row->cms_type2 = "";
                  if(!isset($prd_row->reserve)) $prd_row->reserve = "";
                  if(!isset($prd_row->strprice)) $prd_row->strprice = "";
                  if(!isset($prd_row->del_type)) $prd_row->del_type = "";
                  if(!isset($prd_row->del_price)) $prd_row->del_price = "";
                  if(!isset($prd_row->opttitle5)) $prd_row->opttitle5 = "";
                  if(!isset($prd_row->optcode5)) $prd_row->optcode5 = "";
                  if(!isset($prd_row->opttitle6)) $prd_row->opttitle6 = "";
                  if(!isset($prd_row->optcode6)) $prd_row->optcode6 = "";
                  if(!isset($prd_row->opttitle7)) $prd_row->opttitle7 = "";
                  if(!isset($prd_row->optcode7)) $prd_row->optcode7 = "";
                  if(!isset($prd_row->opttitle3)) $prd_row->opttitle3 = "";
                  if(!isset($prd_row->optcode3)) $prd_row->optcode3 = "";
                  if(!isset($prd_row->opttitle4)) $prd_row->opttitle4 = "";
                  if(!isset($prd_row->optcode4)) $prd_row->optcode4 = "";
                  if(!isset($prd_row->opttitle)) $prd_row->opttitle = "";
                  if(!isset($prd_row->optcode)) $prd_row->optcode = "";
                  if(!isset($prd_row->opttitle2)) $prd_row->opttitle2 = "";
                  if(!isset($prd_row->optcode2)) $prd_row->optcode2 = "";
                  if(!isset($prd_row->optvalue)) $prd_row->optvalue = "";
                  if(!isset($prd_row->stortexp)) $prd_row->stortexp = "";
                  if(!isset($prd_row->content)) $prd_row->content = "";

                	$prdicon= explode("/",$prd_row->prdicon);
                  for($ii=0; $ii<count($prdicon); $ii++){
                     $prdicon_list[$prdicon[$ii]] = true;
                  }

									$no = 0;
									if($handle = opendir('../../data/prdicon')){
										while(false !== ($file_name = readdir($handle))){
											if($file_name != "." && $file_name != ".."){
												if($no%7 == 0) echo "<tr>";
									?>
                  <td><input type="checkbox" name="prdicon[]" value="<?=$file_name?>" <? if(isset($prdicon_list["$file_name"]) && $prdicon_list["$file_name"]==true) echo "checked";?>></td>
                  <td><img src="/data/prdicon/<?=$file_name?>" border="0"></td>
                  <?
												$no++;
											}
										}
										closedir($handle);
									}
									?>
                  </table></td>
                  <td style="padding-left:15px;"><a href="javascript:prdIcon()" class="AW-btn-s-black">아이콘관리</a>
                  </td>
                  </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td class="t_name">상품명 <font color="red">*</font></td>
                <td class="t_value" colspan="3">
                <input type="text" name="prdname" value="<?=$prd_row->prdname?>" size="60" class="input">
                </td>
              </tr>
              <tr>
                <td width="15%" class="t_name">제조사</td>
                <td width="35%" class="t_value">
                	<input name="prdcom" type="text" value="<?=$prd_row->prdcom?>" class="input">
                	<select onChange="this.form.prdcom.value = this.value">
                	<option value="">::선택::</option>
                	<?
                	$sql = "select distinct prdcom from wiz_product where prdcom != '' order by prdcom asc";
                	$result = mysqli_query($connect, $sql);
                	while($row = mysqli_fetch_object($result)){
                	?>
                	<option value="<?=$row->prdcom?>"><?=$row->prdcom?></option>
                	<?
                	}
                	?>
                	<select>
                </td>
                <td width="15%" class="t_name">원산지</td>
                <td width="35%" class="t_value">
                	<input name="origin" type="text" value="<?=$prd_row->origin?>" class="input">
                	<select onChange="this.form.origin.value = this.value">
                	<option value="">::선택::</option>
                	<?
                	$sql = "select distinct origin from wiz_product where origin != '' order by origin asc";
                	$result = mysqli_query($connect, $sql);
                	while($row = mysqli_fetch_object($result)){
                	?>
                	<option value="<?=$row->origin?>"><?=$row->origin?></option>
                	<?
                	}
                	?>
                	<select>
                </td>
              </tr>
              <tr>
                <td class="t_name">브랜드</td>
                <td class="t_value">
                	<select name="brand" style="width:130px">
                	<option value="">::선택::</option>
                	<?
                	$sql = "select idx, brdname from wiz_brand where brduse != 'N' order by priorno asc";
                	$result = mysqli_query($connect, $sql);
                	while($row = mysqli_fetch_object($result)){
                	?>
                	<option value="<?=$row->idx?>" <? if(!strcmp($row->idx, $prd_row->brand)) echo "selected" ?>><?=$row->brdname?></option>
                	<?
                	}
                	?>
                	<select>
                </td>
                <td class="t_name">상품진열</td>
                <td class="t_value">
                <input type="radio" name="showset" value="Y" <? if($prd_row->showset == "Y" || empty($prd_row->showset)) echo "checked"; ?>>진열함&nbsp;
                <input type="radio" name="showset" value="N" <? if($prd_row->showset == "N") echo "checked"; ?>>진열안함
                </td>
              </tr>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품정보</td>
			    <td>
          	<input type="radio" name="info_use" onClick="if(this.checked==true) addinfo.style.display='none';" value="N" <? if($prd_row->info_use == "" || $prd_row->info_use == "N") echo "checked"; ?>>사용안함
          	<input type="radio" name="info_use" onClick="if(this.checked==true) addinfo.style.display='';" value="Y" <? if($prd_row->info_use == "Y") echo "checked"; ?>>사용함
          </td>
			  </tr>
			</table>
      <div id="addinfo" style=display:<? if($prd_row->info_use == "" || $prd_row->info_use == "N") echo "none"; else echo "show"; ?>>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">상품정보</td>
                <td width="85%" class="t_value">

                	<table border="0" cellspacing="5" cellpadding="0">
                		<tr>
                			<td></td>
                			<td>상품가격</td>
                			<td>1,000원 (예시)</td>
                		</tr>
                		<tr>
                			<td>1.</td>
                			<td><input name="info_name1" type="text" value="<?=$prd_row->info_name1?>" size="15" class="input"></td>
                			<td><input name="info_value1" type="text" value="<?=$prd_row->info_value1?>" size="20" class="input"></td>
                			<td width="60" align="right">4.</td>
                			<td><input name="info_name4" type="text" value="<?=$prd_row->info_name4?>" size="15" class="input"></td>
                			<td><input name="info_value4" type="text" value="<?=$prd_row->info_value4?>" size="20" class="input"></td>
                		</tr>
                		<tr>
                			<td>2.</td>
                			<td><input name="info_name2" type="text" value="<?=$prd_row->info_name2?>" size="15" class="input"></td>
                			<td><input name="info_value2" type="text" value="<?=$prd_row->info_value2?>" size="20" class="input"></td>
                			<td align="right">5.</td>
                			<td><input name="info_name5" type="text" value="<?=$prd_row->info_name5?>" size="15" class="input"></td>
                			<td><input name="info_value5" type="text" value="<?=$prd_row->info_value5?>" size="20" class="input"></td>
                		</tr>
                		<tr>
                			<td>3.</td>
                			<td><input name="info_name3" type="text" value="<?=$prd_row->info_name3?>" size="15" class="input"></td>
                			<td><input name="info_value3" type="text" value="<?=$prd_row->info_value3?>" size="20" class="input"></td>
                			<td align="right">6.</td>
                			<td><input name="info_name6" type="text" value="<?=$prd_row->info_name6?>" size="15" class="input"></td>
                			<td><input name="info_value6" type="text" value="<?=$prd_row->info_value6?>" size="20" class="input"></td>
                		</tr>
                	</table>

                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br>
      </div>



			<? if($oper_info->coupon_use == "Y"){ ?>

			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 할인쿠폰</td>
			    <td>
          	<input type="radio" name="coupon_use" onClick="if(this.checked==true) coupon.style.display='none';" value="N" <? if($prd_row->coupon_use == "" || $prd_row->coupon_use == "N") echo "checked"; ?>>사용안함
          	<input type="radio" name="coupon_use" onClick="if(this.checked==true) coupon.style.display='';" value="Y" <? if($prd_row->coupon_use == "Y") echo "checked"; ?>>사용함
          </td>
			  </tr>
			</table>
      <div id="coupon" style=display:<? if($prd_row->coupon_use == "" || $prd_row->coupon_use == "N") echo "none"; else echo "show"; ?>>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td class="t_name">쿠폰금액/할인율</td>
                <td colspan="3" class="t_value">
                	<input name="coupon_dis" type="text" value="<?=$prd_row->coupon_dis?>" class="input">
                	<input type="radio" name="coupon_type" value="%" <? if($prd_row->coupon_type == "" || $prd_row->coupon_type == "%") echo "checked"; ?>>% 퍼센트
                	<input type="radio" name="coupon_type" value="원" <? if($prd_row->coupon_type == "원") echo "checked"; ?>>원
                </td>
              </tr>
              <tr>
                <td width="15%" class="t_name">쿠폰수량</td>
                <td width="35%" class="t_value">
                	<input name="coupon_amount" type="text" value="<?=$prd_row->coupon_amount?>" class="input"  <? if($prd_row->coupon_limit == "N") echo "disabled"; ?>>
                	<input type="checkbox" name="coupon_limit" value="N" <? if($prd_row->coupon_limit == "N") echo "checked"; ?> onClick="if(this.checked==true) this.form.coupon_amount.disabled = true; else this.form.coupon_amount.disabled = false;">수량제한없음
                </td>
                <td width="15%" class="t_name">쿠폰종료일</td>
                <td width="35%" class="t_value">
                	<input name="coupon_sdate" size="12" type="text" value="<?=$prd_row->coupon_sdate?>" class="input" onClick="Calendar1('document.frm','coupon_sdate');"> ~
                	<input name="coupon_edate" size="12" type="text" value="<?=$prd_row->coupon_edate?>" class="input" onClick="Calendar1('document.frm','coupon_edate');">
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
        	<td align="right" height="30"><a onclick="popMycoupon('<?=$prdcode?>')" class="AW-btn">쿠폰발급회원</a></td>
        </tr>
      </table>
      <br>
      </div>

			<? } ?>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 가격및재고</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">판매가 <font color="red">*</font></td>
                <td width="35%" class="t_value"><input name="sellprice" type="text" value="<?=$prd_row->sellprice?>" class="input" onchange="setReserve(this.form);setCms();"></td>
                <td width="15%" class="t_name">정가</td>
                <td width="35%" class="t_value"><input name="conprice" type="text" value="<?=$prd_row->conprice?>" class="input"><br>* <s>5,000</s>로 표기됨, 0 입력시 표기안됨 </td>
              </tr>
              <tr>
                <td class="t_name">적립금<br><a href="../shop/shop_oper.php#res"><? if($reserve_use == "Y") echo "(판매가 ".$reserve_buy." %)"; ?></a></td>
                <td class="t_value"><input name="reserve" type="text" value="<?=$prd_row->reserve?>" class="input"></td>
                <td class="t_name">재고량</td>
                <td class="t_value">
                	<input type="radio" name="shortage" value="Y" <? if($prd_row->shortage == "Y") echo "checked"; ?>><img src="/images/icon_not.gif" border="0"> &nbsp;
                	<input type="radio" name="shortage" value="N" <? if($prd_row->shortage == "N" || empty($prd_row->shortage)) echo "checked"; ?>>무제한
                	<input type="radio" name="shortage" value="S" <? if($prd_row->shortage == "S") echo "checked"; ?>>수량
                	<input name="stock" type="text" size="5" value="<?=$prd_row->stock?>" class="input">개<br>
                	수량을 지정하면 재고가 없을시 판매중지
                </td>
              </tr>
              <tr>
              	<td class="t_name">수수료</td>
                <td class="t_value" colspan="3">

                	<table border="0" cellspacing="5" cellpadding="0">
                		<tr>
                			<td width="20%" class="t_name">수수료설정</td>
                			<td width="80%" class="t_value">
                				 <input type="radio" name="cms_type" value="C" onClick="setCmsType()" <? if(!strcmp($prd_row->cms_type, "C") || empty($prd_row->cms_type)) echo "checked" ?>> 카테고리 또는 입점업체 수수료
                				 <input type="radio" name="cms_type" value="P" onClick="setCmsType()" <? if(!strcmp($prd_row->cms_type, "P")) echo "checked" ?>> 상품별 수수료 설정
                			</td>
                		</tr>
                		<tr>
                			<td height="25" class="t_name">수수료입력</td>
                			<td class="t_value">
                				 <input type="radio" name="cms_type2" value="P" onClick="setCmsType()" <? if(!strcmp($prd_row->cms_type2, "P") || empty($prd_row->cms_type2)) echo "checked" ?>> 공급가기준
                				 <input type="radio" name="cms_type2" value="C" onClick="setCmsType()" <? if(!strcmp($prd_row->cms_type2, "C")) echo "checked" ?>> 수수료기준
                				 <br>
                				 수수료율 : <input type="text" name="cms_rate" class="input_gray" value="<?=$prd_row->cms_rate?>" size="3" readonly onKeyup="setCms()">%
                				 공급가 : <input type="text" name="supprice" class="input" value="<?=$prd_row->supprice?>" onKeyup="setCms('sup');">
                			</td>
                		</tr>
                		<tr>
                			<td colspan="2">
                				- 카테고리 또는 입점업체 수수료 : 입점업체 수수료가 우선시됩니다.. <br>
                				- 공급가기준 : 수수료율이 자동으로 입력됩니다. 판매가와 공급가를 입력해주세요. <br>
                				- 수수료기준 : 공급가가 자동으로 입력됩니다. 판매가와 수수료율을 입력해주세요.
                			</td>
                		</tr>
                	</table>

                </td>
              </tr>
              <tr>
              	<td class="t_name">가격대체문구</td>
                <td class="t_value" colspan="3">
                	<input name="strprice" type="text" value="<?=$prd_row->strprice?>" class="input">
                	가격대체문구를 입력하면 가격대신 입력한 문구가 보이며 구매가 불가능합니다.
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>


      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 배송비</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">배송비</td>
                <td width="85%" class="t_value">
                	<input type="radio" name="del_type" value="DA" <? if(!strcmp($prd_row->del_type, "DA") || empty($prd_row->del_type)) echo "checked" ?>> 기본 배송정책
                	<input type="radio" name="del_type" value="DB" <? if(!strcmp($prd_row->del_type, "DB")) echo "checked" ?>> 무료배송
                	<input type="radio" name="del_type" value="DC" <? if(!strcmp($prd_row->del_type, "DC")) echo "checked" ?>> 상품별 배송비
                	<input name="del_price" type="text" value="<?=$prd_row->del_price?>" class="input" size="10">원
                	<input type="radio" name="del_type" value="DD" <? if(!strcmp($prd_row->del_type, "DD")) echo "checked" ?>> 수신자부담(착불)
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품옵션</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>

            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">일반 옵션1</td>
                <td width="85%" class="t_value" colspan="3">
                옵션명 : <input type="text" name="opttitle5" value="<?=$prd_row->opttitle5?>" size="12" class="input">
                &nbsp; 옵션 : <input type="text" name="optcode5" value="<?=$prd_row->optcode5?>" size="40" class="input">
                <input type="button" onClick="openOption('opt5');" value="불러오기" class="AW-btn" /> 옵션은 컴마(,)로 구분
                </td>
              </tr>
              <tr>
                <td class="t_name">일반 옵션2</td>
                <td class="t_value" colspan="3">
                옵션명 : <input type="text" name="opttitle6" value="<?=$prd_row->opttitle6?>" size="12" class="input">
                &nbsp; 옵션 : <input type="text" name="optcode6" value="<?=$prd_row->optcode6?>" size="40" class="input">
                <input type="button" onClick="openOption('opt6');" value="불러오기" class="AW-btn" /> ex(95,100,105...)
                </td>
              </tr>
              <tr>
                <td class="t_name">일반 옵션3</td>
                <td class="t_value" colspan="3">
                옵션명 : <input type="text" name="opttitle7" value="<?=$prd_row->opttitle7?>" size="12" class="input">
                &nbsp; 옵션 : <input type="text" name="optcode7" value="<?=$prd_row->optcode7?>" size="40" class="input">
                <input type="button" onClick="openOption('opt7');" value="불러오기" class="AW-btn" />
                </td>
              </tr>
            </table>

            <table><tr><td></td></tr></table>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">가격추가 옵션1</td>
                <td width="85%" class="t_value" colspan="3">
	                옵션명 : <input type="text" name="opttitle3" value="<?=$prd_row->opttitle3?>" size="12" class="input">
                <input type="button" onClick="openOption('opt3');" value="불러오기" class="AW-btn" />
	                <a href="javascript:addopt('opt3')" class="AW-btn-s-black">항목추가</a>
	             	  <a href="javascript:delopt('opt3')" class="AW-btn-s-black">항목삭제</a>
	                <br>
	                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt3">
	                	<tr>
	                		<td></td>
	                	</tr>
										<?
										$optcode3_arr = explode("^^", $prd_row->optcode3);
										for($ii = 0; $ii < count($optcode3_arr) - 1; $ii++) {
											list($opt, $price, $reserve) = explode("^", $optcode3_arr[$ii]);
										?>
	                	<tr>
	                		<td>
												&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode3_opt[]" value="<?=$opt?>">
												추가가격 : <input type="text" class="input" name="optcode3_pri[]" value="<?=$price?>">
												추가적립금 : <input type="text" class="input" name="optcode3_res[]" value="<?=$reserve?>">
				              </td>
				            </tr>
										<?
										}
										?>
				          </table>
                </td>
              </tr>
              <tr>
                <td class="t_name">가격추가 옵션2</td>
                <td class="t_value" colspan="3">
	                옵션명 : <input type="text" name="opttitle4" value="<?=$prd_row->opttitle4?>" size="12" class="input">
                <input type="button" onClick="openOption('opt4');" value="불러오기" class="AW-btn" />
	                <a href="javascript:addopt('opt4')" class="AW-btn-s-black">항목추가</a>
	             	  <a href="javascript:delopt('opt4')" class="AW-btn-s-black">항목삭제</a>
	                <br>
	                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="opt4">
	                	<tr>
	                		<td></td>
	                	</tr>
										<?
										$optcode4_arr = explode("^^", $prd_row->optcode4);
										for($ii = 0; $ii < count($optcode4_arr) - 1; $ii++) {
											list($opt, $price, $reserve) = explode("^", $optcode4_arr[$ii]);
										?>
	                	<tr>
	                		<td>
												&nbsp; &nbsp;항목 : <input type="text" class="input" name="optcode4_opt[]" value="<?=$opt?>">
												추가가격 : <input type="text" class="input" name="optcode4_pri[]" value="<?=$price?>">
												추가적립금 : <input type="text" class="input" name="optcode4_res[]" value="<?=$reserve?>">
				              </td>
				            </tr>
										<?
										}
										?>
				          </table>
                </td>
              </tr>
            </table>

            <table><tr><td></td></tr></table>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">가격/재고 옵션</td>
                <td width="85%" class="t_value" colspan="3">
                하나 또는 두개의 옵션을 조합하여 가격/적립금추가,재고관리가 가능합니다.
                <input type="checkbox" name="opt_use" value="Y" onClick="if(this.checked==true) prdopt.style.display=''; else prdopt.style.display='none';"><font color="red">설정하기</font><br>
                <div id="prdopt" style="display:none">

	           	    <table border="0" cellpadding="0" cellspacing="0">
	             	    <tr height="30">
	             	    	<td>
	             	    		옵션 1 : <input type="text" name="opttitle" value="<?=$prd_row->opttitle?>" size="12" class="input">
	             	    		<a href="javascript:addopt('opt1')">[항목추가]</a>
	             	    		<a href="javascript:delopt('opt1')">[항목삭제]</a>
	             	    	</td>
	             	    	<td width="5"></td>
		             	    <td>
		             	    	옵션 2 : <input type="text" name="opttitle2" value="<?=$prd_row->opttitle2?>" size="12" class="input">
		             	    	<a href="javascript:addopt('opt2')">[항목추가]</a>
	             	    		<a href="javascript:delopt('opt2')">[항목삭제]</a>
		             	    </td>
	             	  	</tr>
	             	  </table>
	           	    <table border="0" cellpadding="0" cellspacing="0">
	             	    <tr height="30">
	             	    	<td>입력형식 : <input type="text" size="9" class="input" value="추가금액" readonly>:<input type="text" size="10" class="input" value="추가적립금" readonly>:<input type="text" size="8" class="input" value="재고" readonly>
	             	    	</td>
	             	  	</tr>
	             	  </table>
									<?
									$optcode_list = explode("^",$prd_row->optcode);
									$optcode2_list = explode("^",$prd_row->optcode2);

									$opt_list = explode("^^",$prd_row->optvalue);
									for($ii=0; $ii < count($opt_list)-1; $ii++){
										$optvalue[$ii] = explode("^",$opt_list[$ii]);
									}
                  if(!isset($optvalue)) {
                    $optvalue = array();
                    $optvalue[0][0] = "";
                    $optvalue[0][1] = "";
                    $optvalue[0][2] = "";
                  }
                  
									$no = 0;
									?>
	           	    <table id="opt" border="1" bordercolor="#cccccc" style="border-collapse:.">
										<tr align="center">
											<td></td>
											<td id="opt2_1"><input type="text" name="tmp_optcode2[]" class="input" value="<?=$optcode2_list[0]?>" style="width:100%"></td>
											<?
											for($ii = 1; $ii < count($optcode2_list) - 1; $ii++) {
											?>
											<td id="opt2_<? echo $ii + 1 ?>"><input type="text" name="tmp_optcode2[]" class="input" value="<?=$optcode2_list[$ii]?>" style="width:100%"></td>
											<?
											}
											?>
										</tr>
										<tr id="opt1">
											<td nowrap><input type="text" name="tmp_optcode[]" value="<?=$optcode_list[0]?>" size="10" class="input" ></td>
											<td>
												<input type="text" name="tmp_opt[sellprice][0]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][0]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][0]" value="<?=$optvalue[$no][2]?>" size="7" class="input">
											</td>
											<?
											for($ii = 1; $ii < count($optcode2_list) - 1; $ii++) {
												$no++;
											?>
											<td id="opt2_<? echo $ii + 1 ?>"><input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input"></td>
											<?
											}
											?>
										</tr>
										<?
										for($ii = 1; $ii < count($optcode_list) - 1; $ii++) {
											$no++;
										?>
										<tr id="opt1_<? echo $ii+1 ?>">
											<td nowrap><input type="text" name="tmp_optcode[]" value="<?=$optcode_list[$ii]?>" size="10" class="input" ></td>
											<td>
												<input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input">
											</td>
											<?
												for($jj = 1; $jj < count($optcode2_list) - 1; $jj++) {
													$no++;
											?>
											<td id="opt2_<? echo $jj + 1 ?>"><input type="text" name="tmp_opt[sellprice][]" value="<?=$optvalue[$no][0]?>" size="7" class="input">:<input type="text" name="tmp_opt[reserve][]" value="<?=$optvalue[$no][1]?>" size="7" class="input">:<input type="text" name="tmp_opt[stock][]" value="<?=$optvalue[$no][2]?>" size="7" class="input"></td>
											<?
												}
											?>
										</tr>
										<?
										}
										?>
									</table>
                 </div>

                </td>
              </tr>
            </table>

          </td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품사진</td>
			    <td>
            <input type="checkbox" name="prdlay_check2" onClick="if(this.checked==true) prdlay2.style.display=''; else prdlay2.style.display='none';"><font color="red">이미지추가2</font>
            <input type="checkbox" name="prdlay_check3" onClick="if(this.checked==true) prdlay3.style.display=''; else prdlay3.style.display='none';"><font color="red">이미지추가3</font>
            <input type="checkbox" name="prdlay_check4" onClick="if(this.checked==true) prdlay4.style.display=''; else prdlay4.style.display='none';"><font color="red">이미지추가4</font>
            <input type="checkbox" name="prdlay_check5" onClick="if(this.checked==true) prdlay5.style.display=''; else prdlay5.style.display='none';"><font color="red">이미지추가5</font> &nbsp; &nbsp;
            <a href="javascript:setImgsize();" class="AW-btn-s-black">이미지사이즈 설정</a>
          </td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="75%">
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="20%" class="t_name">원본 이미지</td>
                <td width="80%" class="t_value" colspan="3"><input type="file" name="realimg" class="input"> [GIF, JPG, PNG]<br>원본이미지를 등록하면 나머지 이미지가 자동생성 됩니다.</td>
              </tr>
              <tr>
                <td height="40" class="t_name">
                  상품목록 이미지 <font color="red">*</font><br>
                  &nbsp;&nbsp;⇒ 크기 : <?=$oper_info->prdimg_R?> x <?=$oper_info->prdimg_R?></td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_R" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_R) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_R?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_R?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_R?>';"><?=$prd_row->prdimg_R?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td height="40" class="t_name">
                  축소이미지 이미지1<br>
                  &nbsp;&nbsp;⇒ 크기 : <?=$oper_info->prdimg_S?> x <?=$oper_info->prdimg_S?></td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_S1" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_S1) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S1?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_S1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_S1?>';"><?=$prd_row->prdimg_S1?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td height="40" class="t_name">
                  제품상세 이미지1 <font color="red">*</font><br>
                  &nbsp;&nbsp;⇒ 크기 : <?=$oper_info->prdimg_M?> x <?=$oper_info->prdimg_M?></td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_M1" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_M1) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M1?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_M1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_M1?>';"><?=$prd_row->prdimg_M1?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td height="40" class="t_name">
                  확대 이미지1 <font color="red">*</font><br>
                  &nbsp;&nbsp;⇒ 크기 : <?=$oper_info->prdimg_L?> x <?=$oper_info->prdimg_L?></td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_L1" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_L1) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L1?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_L1?>" target="_blank" onMouseOver="document.prdimg1.src='../../data/prdimg/<?=$prd_row->prdimg_L1?>';"><?=$prd_row->prdimg_L1?></a>)
                <? } ?>

                </td>
              </tr>
            </table>
          </td>
          <td width="25%" height="100%">
            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td align="center" bgcolor="#ffffff">
                <?
                if(@file($imgpath."/".$prd_row->prdimg_R))
                	echo "<img src='../../data/prdimg/$prd_row->prdimg_R' name='prdimg1' width='100'>";
                else
                	echo "No<br>Image";
					 			?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <div id="prdlay2" style="display:none">
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="75%">
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="20%" class="t_name">원본 이미지</td>
                <td width="80%" class="t_value" colspan="3"><input type="file" name="realimg2" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">
                  축소 이미지2</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_S2" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_S2) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S2?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_S2?>" target="_blank" onMouseOver="document.prdimg2.src='../../data/prdimg/<?=$prd_row->prdimg_S2?>';"><?=$prd_row->prdimg_S2?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  상세 이미지2</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_M2" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_M2) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M2?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_M2?>" target="_blank" onMouseOver="document.prdimg2.src='../../data/prdimg/<?=$prd_row->prdimg_M2?>';"><?=$prd_row->prdimg_M2?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  확대 이미지2</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_L2" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_L2) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L2?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_L2?>" target="_blank" onMouseOver="document.prdimg2.src='../../data/prdimg/<?=$prd_row->prdimg_L2?>';"><?=$prd_row->prdimg_L2?></a>)
                <? } ?>

                </td>
              </tr>
            </table>
          </td>
          <td width="25%" height="100%">
            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td align="center" bgcolor="#ffffff">
                <?
                if(@file($imgpath."/".$prd_row->prdimg_M2))
                	echo "<img src='../../data/prdimg/$prd_row->prdimg_M2' name='prdimg2' width='100'>";
                else
                	echo "No<br>Image";
					 ?>
					 </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </div>
      <div id="prdlay3" style=display:none>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="75%">
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="20%" class="t_name">원본 이미지</td>
                <td width="80%" class="t_value" colspan="3"><input type="file" name="realimg3" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">
                  축소 이미지3</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_S3" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_S3) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S3?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_S3?>" target="_blank" onMouseOver="document.prdimg3.src='../../data/prdimg/<?=$prd_row->prdimg_S3?>';"><?=$prd_row->prdimg_S3?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  상세 이미지3</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_M3" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_M3) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M3?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_M3?>" target="_blank" onMouseOver="document.prdimg3.src='../../data/prdimg/<?=$prd_row->prdimg_M3?>';"><?=$prd_row->prdimg_M3?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  확대 이미지3</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_L3" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_L3) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L3?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_L3?>" target="_blank" onMouseOver="document.prdimg3.src='../../data/prdimg/<?=$prd_row->prdimg_L3?>';"><?=$prd_row->prdimg_L3?></a>)
                <? } ?>

                </td>
              </tr>
            </table>
          </td>
          <td width="25%" height="100%">
            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td align="center" bgcolor="#ffffff">
                <?
                if(@file($imgpath."/".$prd_row->prdimg_M3))
                	echo "<img src='../../data/prdimg/$prd_row->prdimg_M3' name='prdimg3' width='100'>";
                else
                	echo "No<br>Image";
					 ?>
					 </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </div>
      <div id="prdlay4" style=display:none>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="75%">
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="20%" class="t_name">원본 이미지</td>
                <td width="80%" class="t_value" colspan="3"><input type="file" name="realimg4" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">
                  축소 이미지4</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_S4" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_S4) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S4?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_S4?>" target="_blank" onMouseOver="document.prdimg4.src='../../data/prdimg/<?=$prd_row->prdimg_S4?>';"><?=$prd_row->prdimg_S4?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  상세 이미지4</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_M4" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_M4) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M4?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_M4?>" target="_blank" onMouseOver="document.prdimg4.src='../../data/prdimg/<?=$prd_row->prdimg_M4?>';"><?=$prd_row->prdimg_M4?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  확대 이미지4</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_L4" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_L4) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L4?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_L4?>" target="_blank" onMouseOver="document.prdimg4.src='../../data/prdimg/<?=$prd_row->prdimg_L4?>';"><?=$prd_row->prdimg_L4?></a>)
                <? } ?>

                </td>
              </tr>
            </table>
          </td>
          <td width="25%" height="100%">
            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td align="center" bgcolor="#ffffff">
                <?
                if(@file($imgpath."/".$prd_row->prdimg_M4))
                	echo "<img src='../../data/prdimg/$prd_row->prdimg_M4' name='prdimg4' width='100'>";
                else
                	echo "No<br>Image";
					 ?>
					 </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </div>
      <div id="prdlay5" style=display:none>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="75%">
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="20%" class="t_name">원본 이미지</td>
                <td width="80%" class="t_value" colspan="3"><input type="file" name="realimg5" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">
                  축소 이미지5</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_S5" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_S5) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_S5?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_S5?>" target="_blank" onMouseOver="document.prdimg5.src='../../data/prdimg/<?=$prd_row->prdimg_S5?>';"><?=$prd_row->prdimg_S5?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  상세 이미지5</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_M5" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_M5) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_M5?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_M5?>" target="_blank" onMouseOver="document.prdimg5.src='../../data/prdimg/<?=$prd_row->prdimg_M5?>';"><?=$prd_row->prdimg_M5?></a>)
                <? } ?>

                </td>
              </tr>
              <tr>
                <td class="t_name">
                  확대 이미지5</td>
                <td class="t_value" colspan="3">
                <input type="file" name="prdimg_L5" class="input">

                <? if( @file($imgpath."/".$prd_row->prdimg_L5) ){ ?>
                <input type="checkbox" name="delimg[]" value="<?=$prd_row->prdimg_L5?>">삭제 (<a href="/data/prdimg/<?=$prd_row->prdimg_L5?>" target="_blank" onMouseOver="document.prdimg5.src='../../data/prdimg/<?=$prd_row->prdimg_L5?>';"><?=$prd_row->prdimg_L5?></a>)
                <? } ?>

                </td>
              </tr>
            </table>
          </td>
          <td width="25%" height="100%">
            <table width="100%" height="100%" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td align="center" bgcolor="#ffffff">
                <?
                if(@file($imgpath."/".$prd_row->prdimg_M5))
                	echo "<img src='../../data/prdimg/$prd_row->prdimg_M5' name='prdimg5' width='100'>";
                else
                	echo "No<br>Image";
					 ?>
					 </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      </div>

      <? if(!strcmp($oper_info->prdrel_use, "Y")) { ?>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 관련상품</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="100%" class="t_value">
                <iframe width="100%" height="95" frameborder="0" src="prd_relation.php?mode=<?=$mode?>&prdcode=<?=$prdcode?>"></iframe>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    	<? } ?>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub" width="15%"><img src="../image/ics_tit.gif"> 상품설명</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
              <tr>
                <td width="15%" class="t_name">관리자주석</td>
                <td width="85%" class="t_value">
                <textarea name="stortexp" rows="3" cols="90" class="textarea" style="width:100%;" ><?=$prd_row->stortexp?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="3" align="center" class="t_name">상세설명</td>
              </tr>
              <tr>
                <td colspan="3" class="t_value">
                <?
                $edit_content = $prd_row->content;
                include "../webedit/WIZEditor.html";
                ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>



    <div class="AW-btn-wrap">
        <button type="submit" class="on">저장</button>
        <a onclick="document.location='<?=$listpage_url?>?<?=$param?>';">목록</a>
    </div><!-- .AW-btn-wrap -->

</form>

<script>setClass01();setCategory();setCmsRate();setCms();prdlayCheck();lodingComplete();</script>

<? include "../footer.php"; ?>