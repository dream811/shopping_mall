<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "prdcode=$prdcode&dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code";
$param .= "&special=$special&showset=$showset&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&brand=$brand&shortage=$shortage&stock=$stock";
//--------------------------------------------------------------------------------------------------

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/admin/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--

//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty(); 
	}
}

//체크박스 전체선택
function selectAll(){
	
	var i; 	
	
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].name != "searchForm" && document.forms[i].name != "checkForm"){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].name != "searchForm" && document.forms[i].name != "checkForm"){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function addReation(){

	var i;
	var selected = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].name != "searchForm" && document.forms[i].name != "checkForm"){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selected = selected + document.forms[i].prdcode.value + "|";
				}
			}
	}

	if(selected == ""){
		alert("상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("관련상품으로 등록하시겠습니까?")){
			document.location = "prd_save.php?mode=reladd&page=<?=$page?>&<?=$param?>&selected=" + selected;
		}else{
			return;
		}
	}
	return;
	
}

// 카테고리 변경
function catChange(form, idx){
   if(idx == "1"){
      form.dep2_code.options[0].selected = true;
      form.dep3_code.options[0].selected = true;
   }else if(idx == "2"){
      form.dep3_code.options[0].selected = true;
   }
   	form.page.value = 1;
   	form.submit();
}

// 재고여부 
function chgShortage(frm) {
	
	frm.page.value = 1;
	
	if(frm.shortage.value == "S") {
		frm.stock.disabled = false;
		frm.stock.focus();
	} else {
		frm.stock.disabled = true;
		frm.submit();
	}
	
}

//-->
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="6">
<tr>
<td>
	
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 관련상품등록</td>
  </tr>
</table>

<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
  <tr>
    <td width="15%" class="t_name">&nbsp; 상품분류</td>
    <td width="85%" colspan="3" class="t_value">
	    <select name="dep_code" onChange="catChange(this.form,'1');" class="select">
	    <option value=''>:: 대분류 ::
	     <?
	     $sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1 order by priorno01 asc";
	     $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	     while($row = mysqli_fetch_object($result)){
	        if($row->catcode == $dep_code)
	           echo "<option value='$row->catcode' selected>$row->catname";
	        else
	           echo "<option value='$row->catcode'>$row->catname";
	     }
	     ?>
	    </select>
    	<select name="dep2_code" onChange="catChange(this.form,'2');" class="select">
    	<option value=''>:: 중분류 ::
  		<?
  		if($dep_code != ''){
     		$sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%' order by priorno02 asc";
     		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
     		while($row = mysqli_fetch_object($result)){
        	if($row->catcode == $dep2_code)
           echo "<option value='$row->catcode' selected>$row->catname";
        	else
           echo "<option value='$row->catcode'>$row->catname";
     		}
  		}
  		?>
    	</select>
    	<select name="dep3_code" onChange="catChange(this.form,'3');" class="select">
    	<option value=''>:: 소분류 ::
		  <?
		  if($dep_code != '' && $dep2_code != ''){
		     $sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%' order by  priorno03 asc";
		     $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		     while($row = mysqli_fetch_object($result)){
		        if($row->catcode == $dep3_code)
		           echo "<option value='$row->catcode' selected>$row->catname";
		        else
		           echo "<option value='$row->catcode'>$row->catname";
		     }
		  }
		  ?>
    </select>
    </td>
  </tr>
  <tr>
    <td width="15%" class="t_name">&nbsp; 검색조건</td>
    <td class="t_value" colspan="3">
      <select name="searchopt" onChange="this.form.page.value=1;">
      <option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
      <option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
      <option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
      </select>
      <input type="text" size="18" name="searchkey" value="<?=$searchkey?>" class="input">
      <input type="submit" value="검색" class="AW-btn-search" />
    </td>
  </tr>
  <tr>
    <td class="t_name">&nbsp; 재고여부</td>
    <td class="t_value" colspan="3">
      <select name="shortage" onChange="chgShortage(this.form)">
      <option value="">:: 재고여부 ::
      <option value="Y" <? if($shortage == "Y") echo "selected"; ?>>품절상품</option>
      <option value="N" <? if($shortage == "N") echo "selected"; ?>>무제한</option>
      <option value="S" <? if($shortage == "S") echo "selected"; ?>>수량</option>
      </select>
      <input type="text" size="5" name="stock" value="<?=$stock?>" class="input" <? if($shortage != "S") echo "disabled" ?>>개 이하
    </td>
  </tr>
  <tr>
    <td width="15%" class="t_name">&nbsp; 브랜드</td>
    <td class="t_value">
    	<select name="brand" onChange="this.form.page.value=1;this.form.submit();">
    	<option value="">:: 브랜드선택 ::
    	<?
    	$sql = "select idx, brdname from wiz_brand where brduse != 'N' order by priorno asc";
    	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    	while($row = mysqli_fetch_array($result)) {
    	?>
    	<option value="<?=$row['idx']?>" <? if($brand == $row['idx']) echo "selected"; ?>><?=$row[brdname]?></option>
    	<?
    	}
    	?>
    </td>
    <td width="15%" class="t_name">&nbsp; 쿠폰적용</td>
    <td class="t_value">
    	<select name="coupon_use" onChange="this.form.page.value=1;this.form.submit();">
      <option value="">:: 선택 ::
      <option value="Y" <? if($coupon_use == "Y") echo "selected"; ?>>예
      <option value="N" <? if($coupon_use == "N") echo "selected"; ?>>아니오
      </select>
    </td>
  </tr>
  <tr>
    <td width="15%" class="t_name">&nbsp; 그룹</td>
    <td width="35%" class="t_value">
      <select name="special" onChange="this.form.page.value=1;this.form.submit();">
      <option value="">:: 그룹선택 ::
      <option value="new" <? if($special == "new") echo "selected"; ?>>신상품
      <option value="best" <? if($special == "best") echo "selected"; ?>>베스트상품
      <option value="popular" <? if($special == "popular") echo "selected"; ?>>인기상품
      <option value="recom" <? if($special == "recom") echo "selected"; ?>>추천상품
      <option value="sale" <? if($special == "sale") echo "selected"; ?>>세일상품
      <option value="stock" <? if($special == "stock") echo "selected"; ?>>품절상품
      </select>
    </td>
    <td width="15%" class="t_name">&nbsp; 진열여부</td>
    <td width="35%" class="t_value">
    <select name="showset" onChange="this.form.page.value=1;this.form.submit();">
    <option value="">:: 선택 ::
    <option value="Y" <? if($showset == "Y") echo "selected"; ?>>진열함
    <option value="N" <? if($showset == "N") echo "selected"; ?>>진열안함
    </select>
    </td>
  </tr>
</form>
</table>


<br>
<?
$sql = "select prdcode from wiz_product where prdcode!='$prdcode'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$all_total = mysqli_num_rows($result);

if(!empty($dep_code)) $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code%' and ";
if(!empty($special)) $special_sql = "wp.$special = 'Y' and ";
if(!empty($showset)) $showset_sql = "wp.showset = '$showset' and ";
if(!empty($searchopt)) $search_sql = "wp.$searchopt like '%$searchkey%' and ";
if(!empty($coupon_use)) $coupon_sql = "wp.coupon_use = '$coupon_use' and ";
if(!empty($brand)) $brand_sql = "wp.brand = '$brand' and ";
if(!empty($shortage)) {
	if(!strcmp($shortage, "N")) $shortage_sql = " (wp.shortage = '$shortage' or wp.shortage = '') and ";
	else $shortage_sql = " wp.shortage = '$shortage' and ";
}
if(!strcmp($shortage, "S")) $stock_sql = " wp.stock <= '$stock' and ";

$sql = "select distinct wp.prdcode from wiz_product wp, wiz_cprelation wc
		where $catcode_sql $special_sql $showset_sql $search_sql $coupon_sql $brand_sql $shortage_sql $stock_sql wp.prdcode!='$prdcode' and wc.prdcode = wp.prdcode
		order by wp.prior desc, wp.prdcode desc";

$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

$rows = 16;
$lists = 5;
$page_count = ceil($total/$rows);
if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="25">총 상품수 : <b><?=$all_total?></b> , 검색 상품수 : <b><?=$total?></b></td>
    <td align="right"><a onClick="addReation();" class="AW-btn">관련상품등록</a></td>
  </tr>
  <tr><td colspan="2" height="10"></td></tr>
</table> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<form name="checkForm">
	<tr><td class="t_rd" colspan="20"></td></tr>
  <tr class="t_th">
    <th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
    <th width="12%">상품코드</th>
    <th width="5%"></th>
    <th>상품명</th>
    <th>상품가격</th>
  </tr>
  <tr><td class="t_rd" colspan="20"></td></tr>
	</form>
	<?
	$sql = "select distinct wp.prdcode, wp.prdimg_R, wp.prdname, wp.sellprice, wp.prior, wp.stock from wiz_product wp, wiz_cprelation wc 
			where $catcode_sql $special_sql $showset_sql $search_sql $coupon_sql $brand_sql $shortage_sql $stock_sql wp.prdcode!='$prdcode' and wc.prdcode = wp.prdcode
			order by wp.prior desc, wp.prdcode desc limit $start, $rows";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	while(($row = mysqli_fetch_object($result)) && $rows){
	if($row->prdimg_R == "") $row->prdimg_R = "/images/noimage.gif";
	else $row->prdimg_R = "/data/prdimg/$row->prdimg_R";
	
	?>
 	<form name="<?=$row->prdcode?>" action="product_save.php">
  <input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
  <tr> 
    <td align="center" height="32"><input type="checkbox" name="select_checkbox"></td>
    <td align="center"><?=$row->prdcode?></td>
    <td><img src="<?=$row->prdimg_R?>" width="30" height="30" border="0"></td>
    <td><?=$row->prdname?></td>
    <td align="right"><?=number_format($row->sellprice)?>원</td>
  </tr>
  <tr><td colspan="20" class="t_line"></td></tr>
  </form>
	<?
		$no--;
		$rows--;
	}
	                     
	if($total <= 0){
	?>
	<tr><td height='30' colspan=7 align=center>등록된 상품이 없습니다.</td></tr>
	<tr><td colspan="20" class="t_line"></td></tr>
	<?
	}
	?>
</table>

<br>
<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
  </tr>
</table>

<br><br>
</td>
</tr>
</table>
</body>
</html>