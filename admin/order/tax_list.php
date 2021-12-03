<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "status=$status&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
$param .= "&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&tax_type=$tax_type";
//--------------------------------------------------------------------------------------------------

if($tax_type == "T") $tax_title = "세금계산서";
else if($tax_type == "C") $tax_title = "현금영수증";
?>
<script language="JavaScript" type="text/javascript">
<!--

// 주문상태 변경
function chgStatus(status){
   document.frm.status.value = status;
   document.frm.submit();
}

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
		if(document.forms[i].orderid != null){
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
		if(document.forms[i].orderid != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상품 삭제
function taxDelete(){

var i;
var selvalue = "";
for(i=0;i<document.forms.length;i++){
	if(document.forms[i].orderid != null){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].select_checkbox.checked)
				selvalue = selvalue + document.forms[i].orderid.value + "|";
			}
		}
}

if(selvalue == ""){
	alert("삭제할 항목을 선택하지 않았습니다.");
	return;
}else{
	if(confirm("선택한 항목을 정말 삭제하시겠습니까?")){
		document.location = "order_save.php?mode=tax_delete&selvalue=" + selvalue + "&tax_type=<?=$tax_type?>";
	}else{
		return;
	}
}
return;

}

// 선택주문 상태변경
function batchStatus(){

var i;
var selvalue = "";
for(i=0;i<document.forms.length;i++){
	if(document.forms[i].orderid != null){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].select_checkbox.checked)
				selvalue = selvalue + document.forms[i].orderid.value + "|";
			}
		}
}

if(selvalue == ""){
	alert("변경할 항목을 선택하지 않았습니다.");
	return;
}else{
	var url = "tax_status.php?selvalue=" + selvalue;
	window.open(url,"taxStatus","height=130, width=200, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}
return;

}

// 증빙서류 엑셀다운
function excelDown(){

	document.location = "tax_excel.php?<?=$param?>";

}

// 기간설정
function setPeriod(pdate){

var plist = pdate.split("-");

prev_year = document.frm.prev_year;
for(ii=0; ii<prev_year.length; ii++){
   if(prev_year.options[ii].value == plist[0])
      prev_year.options[ii].selected = true;
}
prev_month = document.frm.prev_month;
for(ii=0; ii<prev_month.length; ii++){
   if(prev_month.options[ii].value == plist[1])
      prev_month.options[ii].selected = true;
}
prev_day = document.frm.prev_day;
for(ii=0; ii<prev_day.length; ii++){
   if(prev_day.options[ii].value == plist[2])
      prev_day.options[ii].selected = true;
}

document.frm.submit();
}

var clickvalue='';
function viewTax( orderid ) {

	ccontent =eval("ccontent_"+orderid+".style");

	if(clickvalue != ccontent) {
		if(clickvalue!='') {
			clickvalue.display='none';
		}

		ccontent.display='table-row';
		clickvalue=ccontent;
	} else {
		ccontent.display='none';
		clickvalue='';
	}

}

// 증빙서류 출력
function printTax(orderid) {

	var url = "/shop/print_tax_sup.php?orderid=" + orderid;
	window.open(url, "taxPub", "height=750, width=670, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");

}
-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit"><?=$tax_title?>목록</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt"><?=$tax_title?> 목록 입니다.</td>
			  </tr>
			</table>

			<br>
			<form name="frm" action="<?=$PHP_SELF?>" method="get">
			<input type="hidden" name="page" value="">
			<input type="hidden" name="status" value="<?=$status?>">
			<input type="hidden" name="tax_type" value="<?=$tax_type?>">
			<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
			 <tr>
			   <td width="15%" class="t_name">&nbsp; 진행상태</td>
			   <td width="85%" class="t_value">

			     <table>
			     <tr><td>
			     <input type="button" onClick="chgStatus('');" value=" 전체 " <? if($status == "") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
			     <input type="button" onClick="chgStatus('Y');" value="발급완료" <? if($status == "Y") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
			     <input type="button" onClick="chgStatus('N');" value="발급대기" <? if($status == "N") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
			     </td></tr>
			     </table>

			   </td>
			 </tr>
			 <tr>
			   <td class="t_name">&nbsp; 기간</td>
			   <td class="t_value">

			     	<select name="prev_year" class="select2">
			    	<?
			       if(empty($next_year)) $next_year = date("Y");
			       if(empty($next_month)) $next_month = date("m");
			       if(empty($next_day)) $next_day = date("d");

			       for($ii=2004; $ii <= 2020; $ii++){
			         if($ii == $prev_year) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      년
			      <select name="prev_month" class="select2">
			        <?
			       for($ii=1; $ii <= 12; $ii++){
			         if($ii<10) $ii = "0".$ii;
			         if($ii == $prev_month) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      월
			      <select name="prev_day" class="select2">
			        <?
			       for($ii=1; $ii <= 31; $ii++){
			         if($ii<10) $ii = "0".$ii;
			         if($ii == $prev_day) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      일 ~
			      <select name="next_year" class="select2">
			        <?
			       for($ii=2004; $ii <= 2020; $ii++){
			         if($ii == $next_year) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      년
			      <select name="next_month" class="select2">
			        <?
			       for($ii=1; $ii <= 12; $ii++){
			         if($ii<10) $ii = "0".$ii;
			         if($ii == $next_month) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      월
			      <select name="next_day" class="select2">
			        <?
			       for($ii=1; $ii <= 31; $ii++){
			         if($ii<10) $ii = "0".$ii;
			         if($ii == $next_day) echo "<option value=$ii selected>$ii";
			         else echo "<option value=$ii>$ii";
			       }
			    	?>
			      </select>
			      일 &nbsp;
				    <?
				    $yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*1));
				    $to_day = date('Y-m-d');
				    $week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*7));
				    $month_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*30));
				    ?>
                    <a href="javascript:setPeriod('<?=$to_day?>')" class="selec">오늘</a>
                    <a href="javascript:setPeriod('<?=$yes_day?>')" class="selec">어제</a>
                    <a href="javascript:setPeriod('<?=$week_day?>')" class="selec">1주일</a>
                    <a href="javascript:setPeriod('<?=$month_day?>')" class="selec">1개월</a>
			   </td>
			 </tr>
			 <tr>
			   <td class="t_name">&nbsp; 조건검색</td>
			   <td class="t_value">
			       <select name="searchopt" class="select2">
			       <option value="orderid" <? if($searchopt == "orderid") echo "selected"; ?>>주문번호
			       <option value="com_name" <? if($searchopt == "com_name") echo "selected"; ?>>상호
			       <option value="com_owner" <? if($searchopt == "com_owner") echo "selected"; ?>>대표자
			       <option value="com_address" <? if($searchopt == "com_address") echo "selected"; ?>>사업장소재지
			       <option value="com_num" <? if($searchopt == "com_num") echo "selected"; ?>>사업자등록번호
			       <option value="com_kind" <? if($searchopt == "com_kind") echo "selected"; ?>>업태
			       <option value="com_class" <? if($searchopt == "com_class") echo "selected"; ?>>종목
			       <option value="com_tel" <? if($searchopt == "com_tel") echo "selected"; ?>>전화번호
			       <option value="com_email" <? if($searchopt == "com_email") echo "selected"; ?>>이메일
			       </select>
			       <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
                   <input type="submit" value="검색" class="AW-btn-search" />
			   </td>
			 </tr>
			</table>
			</form>

      <br>
      <?
      if($tax_type != "") $tax_sql = " and wt.tax_type='$tax_type' ";

    	$sql = "select wt.orderid from wiz_tax as wt,wiz_order as wo where wt.orderid = wo.orderid and tax_date != '' $tax_sql";
    	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $all_total = mysqli_num_rows($result);

      if($prev_year){
         $prev_period = $prev_year."-".$prev_month."-".$prev_day;
         $next_period = $next_year."-".$next_month."-".$next_day." 23:59:59";
         $period_sql = " and tax_date >= '$prev_period' and tax_date <= '$next_period'";
      }
      if($status == "") $status_sql = "and tax_pub != ''";
      else $status_sql = "and tax_pub = '$status'";

      if($searchopt && $searchkey) $searchopt_sql = " and $searchopt like '%$searchkey%'";

      $sql = "select wt.*, wo.status 
				from wiz_tax as wt,wiz_order as wo
				where 
					wt.orderid = wo.orderid and
					tax_date != '' $tax_sql $status_sql $period_sql $searchopt_sql order by tax_date desc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);

      $rows = 20;
      $lists = 5;
     	$page_count = ceil($total/$rows);
     	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
     	$start = ($page-1)*$rows;
     	$no = $total-$start;
     	if($start>1) mysql_data_seek($result,$start);
      ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 <?=$tax_title?>수 : <b><?=$all_total?></b> &nbsp; 검색 <?=$tax_title?>수 : <b><?=$total?></b></td>
          <td align="right">
	       	&nbsp; <font color="6DCFF6">■</font> 발급완료
	       	&nbsp; <font color="ED1C24">■</font> 발급대기
            <a onClick="excelDown();" class="AW-btn">엑셀파일저장</a>
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
          <th width="3%"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
          <th width="10%">주문번호</th>
          <th>품 명</th>
          <th width="8%">발급일</th>
          <th width="8%">승인일</th>
          <th width="8%">공급가액</th>
          <th width="8%">세액</th>
		  <th width="10%">주문상태</th>
          <th width="15%">처리상태</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan="20"></td></tr>
      	</form>
				<?
				while(($row = mysqli_fetch_object($result)) && $rows){

					if($row->tax_pub == "Y") $stacolor = "6DCFF6";
					else if($row->tax_pub == "N") $stacolor = "ED1C24";
					else $stacolor = "";

					$prd_name = "";

					$prd_info = explode("^^", $row->prd_info);
					$no = 0;
					for($ii = 0; $ii < count($prd_info); $ii++) {

						if(!empty($prd_info[$ii])) {
							$tmp_prd = explode("^", $prd_info[$ii]);
							if($ii < 1) $prd_name = cut_str($tmp_prd[0], 25);
							$no++;
						}
					}

					if($no > 1) {
						$prd_name .= " 외 ".($no-1)."건";
					}

				?>
	     	<form action="order_save.php" name="<?=$row->orderid?>" method="get">
        <input type="hidden" name="mode" value="tax_status">
        <input type="hidden" name="page" value="<?=$page?>">
        <input type="hidden" name="orderid" value="<?=$row->orderid?>">
        <input type="hidden" name="tmp_tax_pub" value="<?=$row->tax_pub?>">

        <input type="hidden" name="status" value="<?=$status?>">
        <input type="hidden" name="prev_year" value="<?=$prev_year?>">
        <input type="hidden" name="prev_month" value="<?=$prev_month?>">
        <input type="hidden" name="prev_day" value="<?=$prev_day?>">
        <input type="hidden" name="next_year" value="<?=$next_year?>">
        <input type="hidden" name="next_month" value="<?=$next_month?>">
        <input type="hidden" name="next_day" value="<?=$next_day?>">
        <input type="hidden" name="searchopt" value="<?=$searchopt?>">
        <input type="hidden" name="searchkey" value="<?=$searchkey?>">

        <input type="hidden" name="tax_type" value="<?=$tax_type?>">

        <tr class="t_tr">
          <td align="center" height="27"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><a href="order_info.php?orderid=<?=$row->orderid?>&page=<?=$page?>&<?=$param?>"><?=$row->orderid?></a></td>
          <td align="center"> <?= $prd_name ?> </td>
          <td align="center"><?=$row->tax_date?></td>
          <td align="center"><?=$row->wdate?></td>
          <td align="center"><?=number_format($row->supp_price)?>원</td>
          <td align="center"><?=number_format($row->tax_price)?>원</td>
		  <td align="center"><?=order_status($row->status)?></td>
          <td align="center">
	          <table cellpadding="2">
	          	<tr>
	          		<td bgcolor=<?=$stacolor?>>
			          <select name="tax_pub" class="state">
						<? if($row->wdate == null) {	//발급대기,발급완료시 상태변경 불가능 ?>
						  <option value="" <? if($row->wdate == null) echo "selected"; ?>>발급대기</option>
						<? } ?>
			            <option value="N" <? if($row->tax_pub == "N" && $row->wdate != null) echo "selected"; ?>>취소</option>
			            <option value="Y" <? if($row->tax_pub == "Y") echo "selected"; ?>>발급완료</option>
			          </select> 
	          		</td>
	          		<td><input type="submit" class="AW-btn-s modify" value="적용" /></td>
	          	</tr>
	          </table>
	        </td>
	        <td align="center">
            	<a onClick="viewTax('<?=$row->orderid?>')" class="AW-btn-s">보기</a>
	        	<!--img src="../image/btn_print_s.gif" style="cursor:pointer" align="absmiddle" onClick="printTax('<?=$row->orderid?>')"-->
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
       	<tr bgcolor="#FFFFFF" id="ccontent_<?=$row->orderid?>" style="display:none">
          <td height="30" colspan="10" style="padding:3px">
          	<? if($row->tax_type == "T") { ?>
			  		<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
			  			<tr>
			  				<td width="15%" height="25" class="t_name">사업자 번호</td>
                            <td width="35%" class="t_value"><?=$row->com_num?></td>
			  				<td width="15%" class="t_name">상 호</td>
                            <td class="t_value"><?=$row->com_name?></td>
			  			</tr>
			  			<tr>
			  				<td height="25" class="t_name">대표자</td>
                            <td class="t_value"><?=$row->com_owner?></td>
			  				<td class="t_name">사업장 소재지</td>
                            <td class="t_value"><?=$row->com_address?></td>
			  			</tr>
			  			<tr>
			  				<td height="25" class="t_name">업 태</td>
                            <td class="t_value"><?=$row->com_kind?></td>
			  				<td class="t_name">종 목</td>
                            <td class="t_value"><?=$row->com_class?></td>
			  			</tr>
			  			<tr>
			  				<td height="25" class="t_name">전화번호</td>
                            <td class="t_value"><?=$row->com_tel?></td>
			  				<td class="t_name">이메일</td>
                            <td class="t_value"><?=$row->com_email?></td>
			  			</tr>
			  		</table>
			  		<? } else if($row->tax_type == "C") { ?>
			  		<table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
			  			<tr>
			  				<td width="15%" height="25" class="t_name">발급사유</td>
                            <td width="35%" class="t_value"><?=get_cash_type_name($row->cash_type)?></td>
			  				<td width="15%" class="t_name">신청정보 </td>
                            <td class="t_value"><?=get_cash_type2_name($row->cash_type2)?></td>
			  			</tr>
			  			<tr>
			  				<td height="25" class="t_name">신청자명</td>
                            <td class="t_value"><?=$row->cash_name?></td>
			  				<td class="t_name">신청정보 내용</td>
                            <td class="t_value"><?=$row->cash_info?></td>
			  			</tr>
			  		</table>
			  		<? } ?>
          </td>
        </tr>
        </form>
        <?
        		$no--;
            $rows--;
         }
       	if($total <= 0){
       	?>
       		<tr><td height=30 colspan=9 align=center>등록된 항목이 없습니다.</td></tr>
       		<tr><td colspan="20" class="t_line"></td></tr>
       	<?
       	}
        ?>
      </table>

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="5"></td></tr>
			 <tr>
			   <td width="33%"><a onClick="taxDelete();" class="AW-btn">선택삭제</a>
<!-- 			     <img src="../image/btn_statuschg.gif" style="cursor:pointer" onClick="batchStatus();"> -->
			   </td>
			   <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
			   <td width="33%"></td>
			 </tr>
			</table>
		<?
			if($tax_type=="C"){
		?>

<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
    	<b>유의사항</b><br />
        - LG유플러스 전용 입니다.(타 PG사 불가능) 무통장입금에 한해 현금영수증을 자동 발급하는 메뉴 입니다.<br />
        - 관리자 -> 상점관리 -> 사업자정보에 사업자번호, 상호, 전화번호는 반드시 입력해주세요<br />
        - 국세청 현금영수증 사이트(<a href='http://www.taxsave.go.kr'>http://www.taxsave.go.kr</a>)에 등록된 정보가 아니면 발급이 되지 않습니다.<br />
        - 결제완료된 주문건에 한해 처리상태를 발급완료로 두신후 적용을 누르시면 자동으로 현금영수증이 발급됩니다.(미발급선택시 취소)<br />
        - 발급된 현금영수증은 주문배송조회에서 30분경과후 출력및 확인이 가능하며<br />
        - 또는 국세청 현금영수증 사이트에서 출력 및 확인이 가능합니다.<br /><br />
        
        - 발급시 신분정보확인오류가 출력되는 경우 유효한 핸드폰번호 및 사업자등록번호가 아니니 구매자에게 다시 확인 하시면 됩니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->
		<?
			}
		?>
		<?
			if($tax_type=="T"){
		?>


<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
        <b>유의사항</b><br />
        - 세금계산서는 단순 발급 확인용의 기능만 제공이 되며 자동으로 연동 되는 부분이 아닙니다.<br />
        - 실제 발급은 세금계산서 발급을 원하는 고객의 이메일등으로 보내셔야 합니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->
		<?
			}
		?>
<? include "../footer.php"; ?>