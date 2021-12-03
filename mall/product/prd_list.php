<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code";
$param .= "&s_special=$s_special&s_display=$s_display&s_couponuse=$s_couponuse&s_searchopt=$s_searchopt&s_searchkey=$s_searchkey";
$param .= "&s_brand=$s_brand&s_shortage=$s_shortage&s_stock=$s_stock&s_status=$s_status";
//--------------------------------------------------------------------------------------------------

?>

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
		if(document.forms[i].prdcode != null){
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
			if(document.forms[i].prdcode != null){
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
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selected = selected + document.forms[i].prdcode.value + "|";
				}
			}
	}

	if(selected == ""){
		alert("삭제할 상품을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 상품을 정말 삭제하시겠습니까?")){
			document.location = "prd_save.php?mode=delete&page=<?=$page?>&<?=$param?>&selected=" + selected;
		}else{
			selectEmpty();
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

// 상품복사
function prdCopy(prdcode){
	if(confirm("동일한 상품을 하나더 자동등록합니다.")){
		document.location = "prd_save.php?mode=prdcopy&prdcode=" + prdcode;
	}
}

// 상품정보 엑셀다운
function excelDown(){
	var url = "prd_excel.php?<?=$param?>";
	window.open(url,"excelDown","height=250, width=560, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 상품정보 엑셀입력
function excelUp() {
	var url = "prd_excel_up.php";
	window.open(url,"excelUp","height=610, width=580, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
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

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selvalue = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].prdcode != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selvalue = selvalue + document.forms[i].prdcode.value + "|";
				}
			}
	}
	return selvalue;
}

//상품이동
function movePrd(){
	selvalue = selectValue();

	if(selvalue == ""){
		alert("이동할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_move.php?selvalue=" + selvalue;
		window.open(uri,"movePrd","width=350,height=150");
	}
}

// 상품복사
function copyPrd(){
	selvalue = selectValue();
	if(selvalue == ""){
		alert("복사할 상품을 선택하세요.");
		return false;
	}else{
		var uri = "prd_copy.php?selvalue=" + selvalue;
		window.open(uri,"copyPrd","width=350,height=150,resizable=yes");
	}
}

//-->
</script>
</head>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">상품목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">전체상품 목록 및 검색합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
      <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
      <input type="hidden" name="page" value="<?=$page?>">
        <tr>
          <td width="15%" class="t_name">상품분류</td>
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
          <td width="15%" class="t_name">검색조건</td>
          <td width="35%" class="t_value">
            <select name="s_searchopt" onChange="this.form.page.value=1;">
            <option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
            <option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
            <option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
            </select>
            <input type="text" size="25" name="s_searchkey" value="<?=$s_searchkey?>" class="input">
          </td>
          <td width="15%" class="t_name">쿠폰적용</td>
          <td width="35%" class="t_value">
          	<select name="s_couponuse" onChange="this.form.page.value=1;this.form.submit();">
            <option value="">:: 선택 ::
            <option value="Y" <? if($s_couponuse == "Y") echo "selected"; ?>>예
            <option value="N" <? if($s_couponuse == "N") echo "selected"; ?>>아니오
            </select>
          </td>
        </tr>
        <tr>
          <td class="t_name">재고여부</td>
          <td class="t_value">
            <select name="s_shortage" onChange="chgShortage(this.form)">
            <option value="">:: 재고여부 ::
            <option value="Y" <? if($s_shortage == "Y") echo "selected"; ?>>품절상품</option>
            <option value="N" <? if($s_shortage == "N") echo "selected"; ?>>무제한</option>
            <option value="S" <? if($s_shortage == "S") echo "selected"; ?>>수량</option>
            </select>
            <input type="text" size="5" name="s_stock" value="<?=$s_stock?>" class="input" <? if($s_shortage != "S") echo "disabled" ?>>개 이하
          </td>
          <td class="t_name">진열여부</td>
          <td class="t_value">
            <select name="s_display" onChange="this.form.page.value=1;this.form.submit();">
            <option value="">:: 선택 ::
            <option value="Y" <? if($s_display == "Y") echo "selected"; ?>>진열함
            <option value="N" <? if($s_display == "N") echo "selected"; ?>>진열안함
            </select>
          </td>
        </tr>
        <tr>
          <td class="t_name">그룹</td>
          <td class="t_value">
            <select name="s_special" onChange="this.form.page.value=1;this.form.submit();">
            <option value="">:: 그룹선택 ::
            <option value="new" <? if($s_special == "new") echo "selected"; ?>>신상품
            <option value="best" <? if($s_special == "best") echo "selected"; ?>>베스트상품
            <option value="popular" <? if($s_special == "popular") echo "selected"; ?>>인기상품
            <option value="recom" <? if($s_special == "recom") echo "selected"; ?>>추천상품
            <option value="sale" <? if($s_special == "sale") echo "selected"; ?>>세일상품
            </select>
			<button type="submit">검색</button>
          </td>
          <td class="t_name">브랜드</td>
          <td class="t_value">
          	<select name="s_brand" onChange="this.form.page.value=1;this.form.submit();">
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
        </tr>
        <tr>
        	<td class="t_name">승인여부</td>
        	<td class="t_value" colspan="3">
            <select name="s_status" onChange="this.form.page.value=1;this.form.submit();">
	            <option value="">:: 선택 ::
	            <option value="Y" <? if($s_status == "Y") echo "selected"; ?>>승인
	            <option value="N" <? if($s_status == "N") echo "selected"; ?>>미승인
            </select>
          </td>
        </tr>
      </table>

      <br>

      <?
			$sql = "select prdcode from wiz_product where mallid = '".$wiz_mall['id']."'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$all_total = mysqli_num_rows($result);

			if(!empty($dep_code)) $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code%' and ";
			if(!empty($s_special)) $special_sql = "wp.$s_special = 'Y' and ";
			if(!empty($s_display)) $display_sql = "wp.showset = '$s_display' and ";
			if(!empty($s_searchopt)) $search_sql = "wp.$s_searchopt like '%$s_searchkey%' and ";
			if(!empty($s_couponuse)) $coupon_sql = "wp.coupon_use = '$s_couponuse' and ";
			if(!empty($s_brand)) $brand_sql = "wp.brand = '$s_brand' and ";
			if(!empty($s_status)) $status_sql = "wp.status = '$s_status' and ";
			if(!empty($s_mallid)) $mallid_sql = " wp.mallid = '$s_mallid' and ";
			if(!empty($shortage)) {
				if(!strcmp($shortage, "N")) $shortage_sql = " (wp.shortage = '$s_shortage' or wp.shortage = '') and ";
				else $shortage_sql = " wp.shortage = '$s_shortage' and ";
			}
			if(!strcmp($s_shortage, "S")) $stock_sql = " wp.stock <= '$s_stock' and ";

			$sql = "select distinct wp.prdcode from wiz_product wp, wiz_cprelation wc
			              where $catcode_sql $special_sql $display_sql $search_sql $coupon_sql $brand_sql $shortage_sql $stock_sql $status_sql $mallid_sql wc.prdcode = wp.prdcode and wp.mallid = '".$wiz_mall['id']."' order by wp.prior desc, wp.prdcode desc";

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
          <td>총 상품수 : <b><?=$all_total?></b> , 검색 상품수 : <b><?=$total?></b></td>
          <td align="right">
				<a onclick="excelUp();" class="AW-btn">엑셀상품등록</a>
				<a onclick="excelDown();" class="AW-btn">엑셀파일저장</a>
				<a onclick="document.location='prd_input.php?<?=$param?>'" class="AW-btn">상품등록</a>
          </td>
        </tr>

		<tr>
        	<td colspan="2" height="5"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<form>
      	<tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="5%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
          <th width="10%">상품코드</td>
          <th width="5%"></td>
          <th width="30%">상품명</td>
          <th width="10%">상품가격</td>
          <th width="10%">재고</td>
          <th width="10%">진열순서</td>
          <th width="5%">승인여부</th>
          <th width="15%">기능</td>
        </tr>
        <tr><td class="t_rd" colspan="20"></td></tr>
      	</form>
				<?
				$sql = "select distinct wp.prdcode, wp.prdimg_R, wp.prdname, wp.sellprice, wp.prior, wp.stock, wp.mallid, wp.status from wiz_product wp, wiz_cprelation wc
				              where $catcode_sql $special_sql $display_sql $search_sql $coupon_sql $brand_sql $shortage_sql $stock_sql $status_sql $mallid_sql wc.prdcode = wp.prdcode and wp.mallid = '".$wiz_mall['id']."' order by wp.prior desc, wp.prdcode desc limit $start, $rows";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

				while(($row = mysqli_fetch_object($result)) && $rows){

					// 상품 이미지
					if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimage.gif";
					else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

					if(!strcmp($row->status, "Y")) $status = "승인";
					else $status = "<font color='red'>미승인</font>";

					$m_sql = "select com_name from wiz_mall where id = '$row->mallid'";
					$m_result = mysqli_query($connect, $m_sql) or error(mysqli_error($connect));
					$m_row = mysqli_fetch_object($m_result);

					if(!empty($m_row->com_name)) $com_name = "<b>[".$m_row->com_name."]</b><br>";
					else $com_name = "";

				?>
	     <form name="<?=$row->prdcode?>" action="product_save.php" onSubmit="return false;">
        <input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
        <tr>
          <td align="center" height="52"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><?=$row->prdcode?></a></td>
          <td><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><img src="<?=$row->prdimg_R?>" width="50" height="50" border="0"></a></td>
          <td><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>"><?=$row->prdname?></a></td>
          <td align="right"><?=number_format($row->sellprice)?>원</td>
          <td align="center"><?=$row->stock?></td>
          <td align="center">
            <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><a href="prd_save.php?mode=prior&posi=upup&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/upup_icon.gif" border="0" alt="10단계 위로"></a></td>
              <td width="4"></td>
              <td></td>
            </tr>
            <tr><td height="4"></td></tr>
            <tr>
              <td><a href="prd_save.php?mode=prior&posi=up&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/up_icon.gif" border="0" alt="1단계 위로"></a></td>
              <td width="4"></td>
              <td><a href="prd_save.php?mode=prior&posi=down&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/down_icon.gif" border="0" alt="1단계 아래로"></a></td>
            </tr>
            <tr><td height="4"></td></tr>
            <tr>
              <td></td>
              <td width="4"></td>
              <td><a href="prd_save.php?mode=prior&posi=downdown&prdcode=<?=$row->prdcode?>&prior=<?=$row->prior?>&page=<?=$page?>&<?=$param?>"><img src="../image/downdown_icon.gif" border="0" alt="10단계 아래로"></a> </td>
            </tr>
            </table>
          </td>
          <td align="center"><?=$status?></td>
          <td align="center">
				<a onclick="document.location='prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&page=<?=$page?>&<?=$param?>'" class="AW-btn-s modify">수정</a>
				<input type="button" onclick="selectEmpty();this.form.select_checkbox.checked=true;prdDelete('<?=$row->prdcode?>');" class="AW-btn-s del" value="삭제">
				<a onclick="prdCopy('<?=$row->prdcode?>');" class="AW-btn-s">복사</a>
          </td>
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

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr><td height="5"></td></tr>
        <tr>
          <td width="33%">
				<a onclick="prdDelete();" class="AW-btn">선택삭제</a>
				<a onclick="movePrd();" class="AW-btn">상품이동</a>
				<a onclick="copyPrd();" class="AW-btn">상품복사</a>
          </td>
          <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
          <td width="33%"></td>
        </tr>
      </table>

<? include "../footer.php"; ?>