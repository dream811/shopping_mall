<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "s_status=$s_status&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&date_type=$date_type&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//------------------------------------------------------------------------------------------------------------------------------------
?>

<script language="JavaScript" type="text/javascript">
<!--

// 주문상태 변경
function chgStatus(s_status){
   document.searchForm.s_status.value = s_status;
   document.searchForm.submit();
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
		if(document.forms[i].id != null){
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
			if(document.forms[i].id != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 기간설정
function setPeriod(pdate){

	var plist = pdate.split("-");

	prev_year = document.searchForm.prev_year;
	for(ii=0; ii<prev_year.length; ii++){
	   if(prev_year.options[ii].value == plist[0])
	      prev_year.options[ii].selected = true;
	}
	prev_month = document.searchForm.prev_month;
	for(ii=0; ii<prev_month.length; ii++){
	   if(prev_month.options[ii].value == plist[1])
	      prev_month.options[ii].selected = true;
	}
	prev_day = document.searchForm.prev_day;
	for(ii=0; ii<prev_day.length; ii++){
	   if(prev_day.options[ii].value == plist[2])
	      prev_day.options[ii].selected = true;
	}

	document.searchForm.submit();
}

// 정산정보 엑셀다운
function excelDown(){
	document.location = "account_excel.php?<?=$param?>";
}

// 선택항목 상태변경
function batchStatus(){

	var i;
	var selvalue = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null && document.forms[i].account != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked) {
					if(document.forms[i].account.value == "Y") {
						selvalue = selvalue + document.forms[i].idx.value + ":" + document.forms[i].acc_status.value;
						selvalue = selvalue + ":" + document.forms[i].mall_id.value + ":" + document.forms[i].mall_name.value;
						selvalue = selvalue + ":" + document.forms[i].mall_tel.value + ":" + document.forms[i].supprice.value;
						selvalue = selvalue + ":" + document.forms[i].prdprice.value + ":" + document.forms[i].resprice.value;
						selvalue = selvalue + ":" + document.forms[i].delprice.value + ":" + document.forms[i].couprice.value;
						selvalue = selvalue + ":" + document.forms[i].total_price.value + ":" + document.forms[i].acc_date.value;
						selvalue = selvalue + "|";
					}
				}
			}
		}
	}

	if(selvalue == ""){
		alert("변경할 항목을 선택하지 않았거나 정산대기 항목을 선택하였습니다.");
		return;
	}else{
		var url = "account_status.php?selvalue=" + selvalue;
		window.open(url,"batchStatus","height=170, width=250, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
	return;

}

// 변경여부 확인
function accCheck(frm) {

	var alert_msg = "";

	if(!frm.chg_status) {
		alert_msg = "정산요청을 하시겠습니까?";
	} else {
		alert_msg = "진행상태를 변경하시겠습니까?";
	}

	if(!confirm(alert_msg)) {
		return false;
	}
}
//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">정산목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">정산내역을 관리합니다.</td>
	    </tr>
	  </table>
	  <br>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="s_status" value="<?=$s_status?>">
      <tr>
        <td bgcolor="ffffff">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
        	<td width="15%" class="t_name">진행상태</td>
        	<td width="85%" class="t_value">
           <table>
           <tr><td>
           <input type="button" onClick="chgStatus('');" value=" 전 체 " <? if($s_status == "") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('AW');" value="정산대기" <? if($s_status == "AW") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('AA');" value="정산요청" <? if($s_status == "AA") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('AI');" value="정산중" <? if($s_status == "AI") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('AD');" value="정산보류" <? if($s_status == "AD") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           <input type="button" onClick="chgStatus('AC');" value="정산완료" <? if($s_status == "AC") echo "class=btn_sm"; else echo "class=btn_m"; ?>>
           </td></tr>
           </table>
          </td>
        </tr>
        <tr>
        	<td class="t_name">기간</td>
        	<td class="t_value">
           	 <select name="date_type" class="select2">
           	 	<option value="">::기간선택::</option>
           	 	<option value="app_date" <? if(!strcmp($date_type, "app_date")) echo "selected"; ?>>정산요청일</option>
           	 	<option value="com_date" <? if(!strcmp($date_type, "com_date")) echo "selected"; ?>>정산완료일</option>
           	 </select>

             <select name="prev_year" class="select2">
            <?
               if(empty($prev_year)) $prev_year = "2008";
               if(empty($prev_month)) $prev_month = "01";
               if(empty($prev_day)) $prev_day = "01";

               if(empty($next_year)) $next_year = date("Y");
               if(empty($next_month)) $next_month = date("m");
               if(empty($next_day)) $next_day = date("d");

               for($ii=2008; $ii <= date('Y') + 1; $ii++){
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
               for($ii=2008; $ii <= date('Y') + 1; $ii++){
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
            $yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d')-1,date('Y')));
            $to_day = date('Y-m-d');
            $week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d')-7,date('Y')));
            $month_day = date('Y-m-d', mktime(0,0,0,date('m')-1,date('d'),date('Y')));
            ?>
              <a href="javascript:setPeriod('<?=$to_day?>')" class="selec">오늘</a>
              <a href="javascript:setPeriod('<?=$yes_day?>')" class="selec">어제</a>
              <a href="javascript:setPeriod('<?=$week_day?>')" class="selec">1주일</a>
              <a href="javascript:setPeriod('<?=$month_day?>')" class="selec">1개월</a>
			    </td>
			  </tr>
        <tr>
        <td class="t_name">조건검색</td>
        <td class="t_value">
         	<select name="searchopt" class="select">
            <option value="wb.mall_name" <? if($searchopt == "wb.mall_name") echo "selected"; ?>>업체명
            <option value="wb.mallid" <? if($searchopt == "wb.mallid") echo "selected"; ?>>아이디
          	<option value="wb.mall_tel" <? if($searchopt == "wb.mall_tel") echo "selected"; ?>>연락처
          	<option value="acc_date" <? if($searchopt == "acc_date") echo "selected"; ?>>정산일
          </select>
          <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
          <input type="submit" value="검색" />
          <font style="font-size:11px; color:#A0A0A0">정산일 입력형식 예) <?=date('Y')?>년 <?=date('m')?>월 = <?=date('Ym')?></font>
        </td>
        </tr>
        </table>
      	</td>
      </tr>
    </form>
    </table>

    <br>
		<?php
		$search_sql = "";

		if(!strcmp($s_status, "AW")) $search_sql .= " AND ISNULL(wa.status) ";
		else if(!empty($s_status)) $search_sql .= " AND wa.status = '$s_status' ";

		if($searchopt && $searchkey) {
			if(!strcmp($searchopt, "acc_date")) $search_sql .= " AND (wa.acc_date like '%$searchkey%' or DATE_FORMAT(wo.send_date, '%Y%m') like '%$searchkey') ";
			else $search_sql .= " AND $searchopt like '%$searchkey%' ";
		}

		$prev_date = $prev_year."-".$prev_month."-".$prev_day;
		$next_date = $next_year."-".$next_month."-".$next_day;

		if($date_type) $search_sql .= " AND DATE_FORMAT($date_type, '%Y-%m-%d') >= '$prev_date' AND DATE_FORMAT($date_type, '%Y-%m-%d') <= '$next_date' ";

		$sql = "SELECT COUNT(idx) AS cnt FROM
						(
						SELECT wb.idx
						FROM wiz_basket AS wb LEFT JOIN wiz_order AS wo ON wb.orderid = wo.orderid
						LEFT JOIN wiz_account AS wa ON wb.mallid = wa.mall_id and DATE_FORMAT(wo.send_date, '%Y%m') = wa.acc_date
						LEFT JOIN
						(
						  SELECT SUM(del_price_mall) AS delprice, orderid, mallid FROM (
						  SELECT del_price_mall, orderid, mallid
						  FROM wiz_basket
						  WHERE ord_status = 'DC' OR ord_status = 'CC' and mallid = '".$wiz_mall['id']."'
						  GROUP BY mallid, orderid
						  ) AS wd GROUP BY mallid
						) AS wd  ON wb.mallid = wd.mallid
						WHERE (wb.ord_status = 'DC' or wb.ord_status = 'CC') and wb.mallid = '".$wiz_mall['id']."' $search_sql
						GROUP BY wb.mallid, DATE_FORMAT(wo.send_date, '%Y%m')
						ORDER BY wo.send_date DESC
						) AS total";
		//echo $sql."<br><br>";

		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);
		$total = $row['cnt'];

		$rows = 20;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;

		?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
        	<!--총 입점업체수 : <b><?=$all_total?></b> , 검색 입점업체수 : <b><?=$total?></b-->
	        <?php
	        if(!strcmp($oper_info->mall_dis, "M")) {
	        	$dis_msg = "- 쿠폰할인가";
	        	$mall_dis = "입점업체 부담";
	        } else {
	        	$mall_dis = "쇼핑몰 부담";
	        }
	        if(!strcmp($oper_info->mall_reserve, "M")) {
	        	$res_msg = "- 적립금";
	        	$mall_reserve = "입점업체 부담";
	        } else {
	        	$mall_reserve = "쇼핑몰 부담";
	        }
	        ?>
	        <b>합계 = 공급가 + 배송비 <?=$dis_msg?> <?=$res_msg?></b>
	        <font style="font-size:11px; color:#A0A0A0">(쿠폰할인가 : <?=$mall_dis?>,  적립금 : <?=$mall_reserve?>)</font>
        </td>
        <td align="right">
	       &nbsp; <font color="6DCFF6">■</font> 정산요청
	       &nbsp; <font color="BD8CBF">■</font> 정산완료
	       &nbsp; <font color="ED1C24">■</font> 정산보류 &nbsp;

		   <a class="AW-btn" onClick="excelDown();">엑셀파일 저장</a>
        </td>
      </tr>
	  <tr><td colspan="2" height="3"></td></tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="30" align="center"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
        <th align="center">번호</th>
        <th align="center">업체명</th>
        <th align="center">정산일</th>
        <th align="center">공급가</th>
        <th align="center">판매가</th>
        <th align="center">배송비</th>
        <th align="center">적립금</th>
        <th align="center">쿠폰금액</th>
        <th align="center">합계</th>
        <th width="130" align="center">진행상태</th>
        <th width="80" align="center">기능</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
      </form>
			<?php
			// AND wb.mallid != ''
			$sql = "SELECT wb.mallid, wb.mall_name, wb.mall_tel, wa.idx,
							IF(ISNULL(wa.supprice), SUM(wb.supprice * wb.amount), wa.supprice) as supprice,
							IF(ISNULL(wa.prdprice), SUM(wb.prdprice * wb.amount), wa.prdprice) as prdprice,
							IF(ISNULL(wa.resprice), SUM(wb.prdreserve * wb.amount), wa.resprice) as resprice,
							IF(ISNULL(wa.couprice), SUM(wb.coupon_use), wa.couprice) as couprice,
							IF(ISNULL(wa.delprice), wd.delprice, wa.delprice) as delprice, wa.total_price,
							IF(ISNULL(wa.acc_date), DATE_FORMAT(wo.send_date, '%Y%m'), wa.acc_date) as acc_date,
							wa.app_date, wa.com_date,
							IF(ISNULL(wa.status), 'AW', wa.status) as acc_status
							FROM wiz_basket AS wb LEFT JOIN wiz_order AS wo ON wb.orderid = wo.orderid
							LEFT JOIN wiz_account AS wa ON wb.mallid = wa.mall_id and DATE_FORMAT(wo.send_date, '%Y%m') = wa.acc_date
							LEFT JOIN
							(
							  SELECT SUM(del_price_mall) AS delprice, orderid, mallid FROM (
							  SELECT del_price_mall, orderid, mallid
							  FROM wiz_basket
							  WHERE ord_status = 'DC' OR ord_status = 'CC' and mallid = '".$wiz_mall['id']."'
							  GROUP BY mallid, orderid
							  ) AS wd GROUP BY mallid
							) AS wd  ON wb.mallid = wd.mallid
							WHERE (wb.ord_status = 'DC' or wb.ord_status = 'CC') and wb.mallid = '".$wiz_mall['id']."' $search_sql
							GROUP BY wb.mallid, DATE_FORMAT(wo.send_date, '%Y%m')
							ORDER BY wo.send_date DESC
							LIMIT $start, $rows";

			//echo $sql;

			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($row = mysqli_fetch_array($result)) {

				if(empty($row[mall_name])) {
					$row[mall_name] = $shop_info->com_name;
					$row[mall_tel] = $shop_info->com_tel;
				}

				$acc_y = substr($row[acc_date], 0, 4);
				$acc_m = substr($row[acc_date], 4, 2);

				if(empty($row['total_price'])) {

					$row['total_price'] = $row[supprice] + $row[delprice];

					if(!strcmp($oper_info->mall_dis, "M")) $row['total_price'] = 	$row['total_price'] - $row[couprice];
					if(!strcmp($oper_info->mall_reserve, "M")) $row['total_price'] = 	$row['total_price'] - $row[resprice];
				}

				if($row[acc_status] == "AA") $stacolor = "6DCFF6";
				else if($row[acc_status] == "AC") $stacolor = "BD8CBF";
				else if($row[acc_status] == "AD") $stacolor = "ED1C24";
				else $stacolor = "";

			?>
	     <form name="frm<?=$no?>" action="account_save.php" name="<?=$row[mallid]?><?=$row[acc_date]?>" method="get" onSubmit="return accCheck(this)">
        <input type="hidden" name="mode" value="chgstatus">
        <input type="hidden" name="page" value="<?=$page?>">

        <input type="hidden" name="idx" value="<?=$row['idx']?>">

        <input type="hidden" name="mall_id" value="<?=$row[mallid]?>">
        <input type="hidden" name="mall_name" value="<?=urlencode($row[mall_name])?>">
        <input type="hidden" name="mall_tel" value="<?=urlencode($row[mall_tel])?>">

        <input type="hidden" name="supprice" value="<?=$row[supprice]?>">
        <input type="hidden" name="prdprice" value="<?=$row[prdprice]?>">
        <input type="hidden" name="resprice" value="<?=$row[resprice]?>">
        <input type="hidden" name="delprice" value="<?=$row[delprice]?>">
        <input type="hidden" name="couprice" value="<?=$row[couprice]?>">
        <input type="hidden" name="total_price" value="<?=$row['total_price']?>">

        <input type="hidden" name="acc_date" value="<?=$row[acc_date]?>">
        <input type="hidden" name="acc_status" value="<?=$row[acc_status]?>">

        <input type="hidden" name="s_status" value="<?=$s_status?>">
        <input type="hidden" name="searchopt" value="<?=$searchopt?>">
        <input type="hidden" name="searchkey" value="<?=$searchkey?>">
        <input type="hidden" name="date_type" value="<?=$date_type?>">
        <input type="hidden" name="prev_year" value="<?=$prev_year?>">
        <input type="hidden" name="prev_month" value="<?=$prev_month?>">
        <input type="hidden" name="prev_day" value="<?=$prev_day?>">
        <input type="hidden" name="next_year" value="<?=$next_year?>">
        <input type="hidden" name="next_month" value="<?=$next_month?>">
        <input type="hidden" name="next_day" value="<?=$next_day?>">

        <tr>
          <td height="30" align="center"><input type="checkbox" name="select_checkbox"></td>
          <td align="center"><?=$no?></td>
          <td align="center"><?=$row[mall_name]?></td>
          <td align="center"><?=$acc_y?>년 <?=$acc_m?>월</td>
          <td align="center"><?=number_format($row[supprice])?>원</td>
          <td align="center"><?=number_format($row[prdprice])?>원</td>
          <td align="center"><?=number_format($row[delprice])?>원</td>
          <td align="center"><?=number_format($row[resprice])?>원</td>
          <td align="center"><?=number_format($row[couprice])?>원</td>
          <td align="center"><?=number_format($row['total_price'])?>원</td>
          <td align="center">
					<?php
						$account = "Y";
						if(!strcmp($row[acc_status], "AW")) {

							if($row[acc_date] < date('Ym')) echo "<input type='image' src='../image/btn_calculate_s.gif' alt='정산요청'>";
							else { echo "정산대기"; $account = "N"; }

						} else {

							switch($row[acc_status]) {
								case "AW" : $acc_status = "정산대기"; break;
								case "AA" : $acc_status = "정산요청"; break;
								case "AI" : $acc_status = "정산중"; break;
								case "AD" : $acc_status = "정산보류"; break;
								case "AC" : $acc_status = "정산완료"; break;
								default 	: $acc_status = ""; break;
							}

							$account = "N";

					?>
	          <table cellpadding="2">
	          	<tr>
	          		<td bgcolor="<?=$stacolor?>" align="center">
	          			<font style="color:<?=$stafont?>"><?=$acc_status?></font>
	          		</td>
							</tr>
						</table>
					<?php
						}
					?>
						<input type="hidden" name="account" value="<?=$account?>">
          </td>
          <td align="center"><a onClick="document.location='account_detail.php?mallid=<?=$row[mallid]?>&acc_date=<?=$row[acc_date]?>&<?=$param?>';" class="AW-btn-s del">상세보기</a></td>
        </tr>
      	<tr><td colspan="20" class="t_line"></td></tr>
      	</form>
   	<?
   		$no--;
			$rows--;
    }
  	if($total <= 0){
  	?>
  		<tr><td height=30 colspan=11 align=center>등록된 항목이 없습니다.</td></tr>
  		<tr><td colspan='20' class='t_line'></td></tr>
  	<?
  	}
    ?>
    </table>

    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
      <tr>
        <td width="33%"></td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
        <td width="33%" align="right"><a class="AW-btn" onClick="batchStatus();">상태 일괄변경</a></td>
      </tr>
    </table>


<div class="AW-manage-checkinfo">
    <div class="tit">체크사항</div>
	<div class="cont">
    - 정산요청 기간 전에는 진행상태가 "정산대기"로 표시되며 정산요청을 할 수 없습니다.<br>
	- 정산일은 "배송날짜" 기준입니다.
	</div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->




<? include "../footer.php"; ?>