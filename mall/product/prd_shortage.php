<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "dep_code=$dep_code&dep2_code=$dep2_code&dep3_code=$dep3_code";
$param .= "&searchopt=$searchopt&searchkey=$searchkey&stock_opt=$stock_opt";
//--------------------------------------------------------------------------------------------------

?>

<script language="JavaScript" type="text/javascript">
<!--
// 가격변동 옵션 수정
function editOption(prdcode){

	var url = "option_edit.php?prdcode=" + prdcode;
	window.open(url,"editOption","height=400, width=400, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no");

}

// 상품정보 엑셀다운
function excelDown(){
	document.location = "prd_excel_stock.php?<?=$param?>";
}

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">재고관리</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">재고를 관리합니다.</td>
			  </tr>
			</table>

			<br>
			<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
			<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
			<input type="hidden" name="page" value="<?=$page?>">
			<tr>
			<td width="15%" class="t_name">상품분류</td>
			<td width="85%" class="t_value">
			<select name="dep_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 대분류 ::
			<?
			$sql = "select substring(catcode,1,2) as catcode, catname from wiz_category where depthno = 1";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($row = mysqli_fetch_object($result)){
			 if($row->catcode == $dep_code)
			    echo "<option value='$row->catcode' selected>$row->catname";
			 else
			    echo "<option value='$row->catcode'>$row->catname";
			}
			?>
			</select>
			<select name="dep2_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 중분류 ::
			<?
			if($dep_code != ''){
			 $sql = "select substring(catcode,3,2) as catcode, catname from wiz_category where depthno = 2 and catcode like '$dep_code%'";
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
			<select name="dep3_code" onChange="this.form.submit();" class="select">
			<option value=''>:: 소분류 ::
			<?
			if($dep_code != '' && $dep2_code != ''){
			 $sql = "select substring(catcode,5,2) as catcode, catname from wiz_category where depthno = 3 and catcode like '$dep_code$dep2_code%'";
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
			<td class="t_name">재고상태</td>
			<td class="t_value">
			 <input type="radio" name="stock_opt" value="all" onclick="this.form.submit();" <? if($stock_opt=="all" || $stock_opt=="") echo "checked"; ?>>전체
			 <input type="radio" name="stock_opt" value="shortage" onclick="this.form.submit();" <? if($stock_opt=="shortage") echo "checked"; ?>>품절
			 <input type="radio" name="stock_opt" value="lack" onclick="this.form.submit();" <? if($stock_opt=="lack") echo "checked"; ?>>부족
			 <input type="radio" name="stock_opt" value="reserve" onclick="this.form.submit();" <? if($stock_opt=="reserve") echo "checked"; ?>>여유
			 <input type="radio" name="stock_opt" value="showset" onclick="this.form.submit();" <? if($stock_opt=="showset") echo "checked"; ?>>진열안함
			</td>
			</tr>
			<tr>
			<td class="t_name">검색조건</td>
			<td class="t_value">
			 <select name="searchopt" onChange="this.form.page.value=1;">
			 <option value="">:: 선택 ::
			 <option value="prdcode" <? if($searchopt == "prdcode") echo "selected"; ?>>상품코드
			 <option value="prdname" <? if($searchopt == "prdname") echo "selected"; ?>>상품명
			 <option value="prdcom" <? if($searchopt == "prdcom") echo "selected"; ?>>제조사
			 </select>
			 <input type="text" size="18" name="searchkey" value="<?=$searchkey?>" class="input">
			<input type="submit" value="검색" />
			</td>
			</tr>
			</form>
			</table>

      <br>
      <?
			$sql = "select prdcode from wiz_product where mallid = '".$wiz_mall['id']."'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$all_total = mysqli_num_rows($result);

			if(!empty($dep_code)) $catcode_sql = "wc.catcode like '$dep_code$dep2_code$dep3_code%' and ";
			if($stock_opt == "shortage") $stock_sql = " (wp.optcode like '%^0^%' or stock <= 0) and ";
			if($stock_opt == "lack") $stock_sql = " (wp.optcode like '%^1^%' or stock < savestock) and ";
			if($stock_opt == "reserve") $stock_sql = " (wp.optcode not like '%^0^%' and stock > savestock)  and ";
			if($stock_opt == "showset") $stock_sql = "wp.showset = 'N' and ";

			if(!empty($searchopt)) $search_sql = "wp.$searchopt like '%$searchkey%' and ";

			$sql = "select distinct wp.prdcode from wiz_product wp, wiz_cprelation wc
			              where $catcode_sql $stock_sql $search_sql wc.prdcode = wp.prdcode and wp.mallid = '".$wiz_mall['id']."' order by wp.prior desc, wp.prdcode desc";
			//echo $sql;
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 20;
			$lists = 5;

			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

      ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 상품수 : <b><?=$all_total?></b> , 검색 상품수 : <b><?=$total?></b></td>
          <td align="right"><a class="AW-btn" onClick="excelDown();">엑셀파일저장</a></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="8%">상품코드</th>
          <th width="8%"></th>
          <th>상품명</th>
          <th width="10%">판매가</th>
          <th width="10%">여유</th>
          <th width="10%">재고</th>
          <th width="10%">안전재고</th>
          <th width="10%">기능</th>
        </tr>
				<tr><td class="t_rd" colspan="20"></td></tr>
      <?
			$sql = "select distinct wp.prdcode, wp.optcode, wp.optcode2, wp.optvalue, wp.prdimg_R, wp.prdname, wp.sellprice, wp.prior, wp.stock, wp.savestock, wp.opt_use from wiz_product wp, wiz_cprelation wc
		              where $catcode_sql $stock_sql $search_sql wc.prdcode = wp.prdcode and wp.mallid = '".$wiz_mall['id']."' order by wp.prior desc, wp.prdcode desc limit $start, $rows";
			//echo $sql;
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while(($row = mysqli_fetch_object($result)) && $rows){

				if($row->prdimg_R == "") $row->prdimg_R = "/images/noimage.gif";
				else $row->prdimg_R = "/data/prdimg/$row->prdimg_R";

				$reserve = $row->stock - $row->savestock;

				// 옵션별 재고부족상품
				if(!strcmp($row->opt_use, "Y")) {
					$short_list = "<table align=center cellpadding=2 cellspacing=1 width=95% border=0 bgcolor=#EBEBEB>";
					$opt1_arr = explode("^", $row->optcode);
					$opt2_arr = explode("^", $row->optcode2);
					$opt_tmp = explode("^^", $row->optvalue);

					$no = 0;
					for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
						for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if($stock <= 1) $short_list .= "<tr><td width=70% bgcolor=#ffffff><font color='red'>".$opt1_arr[$ii]."/".$opt2_arr[$jj]."</td><td width=30% bgcolor=#ffffff> ".number_format($stock)."개</font></td></tr>";
							else $short_list .= "<tr><td width=70% bgcolor=#ffffff><font color='blue'>".$opt1_arr[$ii]."/".$opt2_arr[$jj]."</td><td width=30% bgcolor=#ffffff> ".number_format($stock)."개</font></td></tr>";

							$no++;
						}
					}

					$short_list .= "</table>";
				} else {
					$short_list = "";
				}
			?>
		  <form action="prd_save.php?<?=$param?>" method="post">
		  <input type="hidden" name="tmp">
		  <input type="hidden" name="mode" value="stock">
		  <input type="hidden" name="page" value="<?=$page?>">
		  <input type="hidden" name="prdcode" value="<?=$row->prdcode?>">
	     <tr>
          <td height="52" align="center"><?=$row->prdcode?></td>
          <td><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&shortpage=Y&<?=$param?>&page=<?=$page?>"><img src="<?=$row->prdimg_R?>" width="50" height="50" border="0"></a></td>
          <td style="padding:10 0 10 0"><a href="prd_input.php?mode=update&prdcode=<?=$row->prdcode?>&shortpage=Y&<?=$param?>&page=<?=$page?>"><?=$row->prdname?></a></span><table><tr><td></td></tr></table><?=$short_list?></td>
          <td align="center"><?=number_format($row->sellprice)?>원</td>
          <td align="center"><?=$reserve?></td>
          <td align="center"><input type="text" size="6" name="stock" value="<?=$row->stock?>" <? if($row->stock > 0) echo "class='inputB'"; else echo "class='inputR'"; ?>></td>
          <td align="center"><input type="text" size="6" name="savestock" value="<?=$row->savestock?>" class="input"></td>
          <td align="center">
			<input type="submit" value="수정" class="AW-btn-s modify" />
			<a onClick="editOption('<?=$row->prdcode?>');" class="AW-btn-s">옵션</a>
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

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
      		<td width="33%"></td>
      	</tr>
     	</table>


<? include "../footer.php"; ?>