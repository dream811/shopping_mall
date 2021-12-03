<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$list_param = "s_status=$s_status&searchopt=$searchopt&searchkey=$searchkey";
$list_param .= "&date_type=$date_type&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
$param = "mallid=$mallid&acc_date=$acc_date&s_prev_year=$s_prev_year&s_prev_month=$s_prev_month&s_prev_day=$s_prev_day&s_next_year=$s_next_year&s_next_month=$s_next_month&s_next_day=$s_next_day";
$param .= "&s_searchopt=$s_searchopt&s_searchkey=$s_searchkey";
//------------------------------------------------------------------------------------------------------------------------------------
?>

<script language="JavaScript" type="text/javascript">
<!--

// 기간설정
function setPeriod(pdate){

	var plist = pdate.split("-");

	s_prev_year = document.searchForm.s_prev_year;
	for(ii=0; ii<s_prev_year.length; ii++){
	   if(s_prev_year.options[ii].value == plist[0])
	      s_prev_year.options[ii].selected = true;
	}
	s_prev_month = document.searchForm.s_prev_month;
	for(ii=0; ii<s_prev_month.length; ii++){
	   if(s_prev_month.options[ii].value == plist[1])
	      s_prev_month.options[ii].selected = true;
	}
	s_prev_day = document.searchForm.s_prev_day;
	for(ii=0; ii<s_prev_day.length; ii++){
	   if(s_prev_day.options[ii].value == plist[2])
	      s_prev_day.options[ii].selected = true;
	}

	document.searchForm.submit();
}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">정산상세목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">정산상세내역을 관리합니다.</td>
	    </tr>
	  </table>
	  <br>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
    <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="mallid" value="<?=$mallid?>">
      <input type="hidden" name="acc_date" value="<?=$acc_date?>">
      <tr>
        <td bgcolor="ffffff">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
        	<td width="15%" class="t_name">주문일</td>
        	<td width="85%" class="t_value">
             <select name="s_prev_year" class="select2">
	            <?
	               if(empty($s_next_year)) $s_next_year = date("Y");
	               if(empty($s_next_month)) $s_next_month = date("m");
	               if(empty($s_next_day)) $s_next_day = date("d");

	               for($ii=2004; $ii <= date('Y') + 1; $ii++){
	                 if($ii == $s_prev_year) echo "<option value=$ii selected>$ii";
	                 else echo "<option value=$ii>$ii";
	               }
	            ?>
              </select>
              년
              <select name="s_prev_month" class="select2">
               <?
               for($ii=1; $ii <= 12; $ii++){
                 if($ii<10) $ii = "0".$ii;
                 if($ii == $s_prev_month) echo "<option value=$ii selected>$ii";
                 else echo "<option value=$ii>$ii";
               }
            	?>
              </select>
              월
              <select name="s_prev_day" class="select2">
               <?
               for($ii=1; $ii <= 31; $ii++){
                 if($ii<10) $ii = "0".$ii;
                 if($ii == $s_prev_day) echo "<option value=$ii selected>$ii";
                 else echo "<option value=$ii>$ii";
               }
            	?>
              </select>
              일 ~
              <select name="s_next_year" class="select2">
               <?
               for($ii=2004; $ii <= date('Y') + 1; $ii++){
                 if($ii == $s_next_year) echo "<option value=$ii selected>$ii";
                 else echo "<option value=$ii>$ii";
               }
            	?>
              </select>
              년
              <select name="s_next_month" class="select2">
               <?
               for($ii=1; $ii <= 12; $ii++){
                 if($ii<10) $ii = "0".$ii;
                 if($ii == $s_next_month) echo "<option value=$ii selected>$ii";
                 else echo "<option value=$ii>$ii";
               }
            	?>
              </select>
              월
              <select name="s_next_day" class="select2">
               <?
               for($ii=1; $ii <= 31; $ii++){
                 if($ii<10) $ii = "0".$ii;
                 if($ii == $s_next_day) echo "<option value=$ii selected>$ii";
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
					<select name="s_searchopt" class="select">
						<option value="wo.orderid" <? if($s_searchopt == "wo.orderid") echo "selected"; ?>>주문번호
						<option value="wo.send_name" <? if($s_searchopt == "wo.send_name") echo "selected"; ?>>주문자명
						<option value="wo.send_id" <? if($s_searchopt == "wo.send_id") echo "selected"; ?>>주문자ID
						<option value="wb.prdcode" <? if($s_searchopt == "wb.prdcode") echo "selected"; ?>>상품코드
						<option value="wb.prdname" <? if($s_searchopt == "wb.prdname") echo "selected"; ?>>상품명
					</select>
					<input type="text" name="s_searchkey" value="<?=$s_searchkey?>" class="input">
					<input type="submit" value="검색" />
        </td>
        </tr>
        </table>
      	</td>
      </tr>
    </form>
    </table>

    <br>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>
					<?php
					$acc_y = substr($acc_date, 0, 4);
					$acc_m = substr($acc_date, 4, 2);
					?>
	        <b><?=$acc_y?>년 <?=$acc_m?>월</b>
        </td>
        <td align="right"></td>
      </tr>
    </table>
		<?php
		$search_sql = "";

		if($s_searchopt && $s_searchkey) $search_sql .= " AND $s_searchopt like '%$s_searchkey%' ";

		$prev_date = $s_prev_year.$s_prev_month.$s_prev_day;
		$next_date = $s_next_year.$s_next_month.$s_next_day;

		if($s_prev_year) $search_sql .= " AND DATE_FORMAT(wo.order_date, '%Y%m%d') >= '$prev_date' AND DATE_FORMAT(wo.order_date, '%Y%m%d') <= '$next_date' ";

		$sql = "select count(wb.idx) as cnt
						from wiz_basket as wb left join wiz_order as wo on wb.orderid = wo.orderid
						left join wiz_account as wa on wb.mallid = wa.mall_id
						where wb.mallid = '$mallid'
						and (wa.acc_date = '$acc_date' or DATE_FORMAT(wo.send_date, '%Y%m') = '$acc_date')
						and (wb.ord_status = 'DC' or wb.ord_status = 'CC') $search_sql
						order by wb.orderid desc";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = $row['cnt'];

		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);
		$total = $row['cnt'];

		$rows = 50;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="100" align="center">주문번호</th>
        <th width="65" align="center">주문일</th>
        <th width="65" align="center">주문자명</th>
        <th width="65" align="center">상품코드</th>
        <th align="center">상품명</th>
        <th align="center">상품옵션</th>
        <th width="65" align="center">공급가</th>
        <th width="65" align="center">판매가</th>
        <th width="65" align="center">적립금</th>
        <th width="65" align="center">쿠폰금액</th>
        <th width="90" align="center">정산예정금액</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
      </form>
			<?php
			$sql = "select wb.*, DATE_FORMAT(wo.order_date, '%Y.%m.%d') as order_date, wo.send_id, wo.send_name
							from wiz_basket as wb left join wiz_order as wo on wb.orderid = wo.orderid
							left join wiz_account as wa on wb.mallid = wa.mall_id
							where wb.mallid = '$mallid'
							and (wa.acc_date = '$acc_date' or DATE_FORMAT(wo.send_date, '%Y%m') = '$acc_date')
							and (wb.ord_status = 'DC' or wb.ord_status = 'CC') $search_sql
							order by wb.orderid desc";
			//echo $sql;
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($row = mysqli_fetch_array($result)) {

				if(empty($row['total_price'])) {

					$row['total_price'] = $row[supprice];

					if(!strcmp($oper_info->mall_dis, "M")) $row['total_price'] = 	$row['total_price'] - $row[coupon_use];
					if(!strcmp($oper_info->mall_reserve, "M")) $row['total_price'] = 	$row['total_price'] - $row[prdreserve];
				}
			?>
        <tr>
          <td height="30" align="center"><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=$row['orderid']?></a></td>
          <td align="center"><?=$row[order_date]?></td>
          <td align="center">
          <?
          if($row[send_id] == "") echo "$row['send_name']<br>[비회원]";
          else echo "<a href='../member/member_info.php?mode=update&id=$row[send_id]'>$row['send_name']<br>[$row[send_id]]</a>";
          ?>
          </td>
          <td align="center"><a href="/shop/prd_view.php?prdcode=<?=$row[prdcode]?>" target="_blank"><?=$row[prdcode]?></a></td>
          <td align="center"><a href="/shop/prd_view.php?prdcode=<?=$row[prdcode]?>" target="_blank"><?=cut_str($row[prdname], 30)?></a></td>
          <td align="center">
<?php
	if($row[opttitle3] != '') echo "$row[opttitle3] : $row[optcode3] <br>";
	if($row[opttitle4] != '') echo "$row[opttitle4] : $row[optcode4] <br>";
	if($row[opttitle5] != '') echo "$row[opttitle5] : $row[optcode5] <br>";
	if($row[opttitle6] != '') echo "$row[opttitle6] : $row[optcode6] <br>";
	if($row[opttitle7] != '') echo "$row[opttitle7] : $row[optcode7] <br>";

	if($row[opttitle] != '') echo $row[opttitle];
	if($row[opttitle] != '' && $row[opttitle2] != '') echo "/";
	if($row[opttitle2] != '') echo $row[opttitle2];
	if($row[opttitle] != '' || $row[opttitle2] != '') echo " : ".$row[optcode]." <br>";
?>
          </td>
          <td align="center"><?=number_format($row[supprice])?>원</td>
          <td align="center"><?=number_format($row[prdprice])?>원</td>
          <td align="center"><?=number_format($row[prdreserve])?>원</td>
          <td align="center"><?=number_format($row[coupon_use])?>원</td>
          <td align="center"><?=number_format($row['total_price'])?>원</td>
        </tr>
      	<tr><td colspan="20" class="t_line"></td></tr>
   	<?
			$supprice += $row[supprice];
			$prdprice += $row[prdprice];
			$resprice += $row[prdreserve];
			$couprice += $row[coupon_use];
			$total_price += $row['total_price'];

			if(strcmp($row['orderid'], $tmp_orderid)) $delprice += $row[del_price_mall];

			$tmp_orderid = $row['orderid'];

   		$no--;
			$rows--;
    }

		if($delprice > 0) {
			$total_price += $delprice;
		?>
				<tr>
				  <td height="25" colspan="6" align="center">배송비</td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td align="center"><?=number_format($delprice)?></td>
				</tr>
				<tr><td colspan="20" class="t_line"></td></tr>
		<?php
		}

  	if($total <= 0){
  	?>
  		<tr><td height=30 colspan=11 align=center>등록된 항목이 없습니다.</td></tr>
  		<tr><td colspan='20' class='t_line'></td></tr>
  	<?
  	}
    ?>
      <tr>
        <td height="25" colspan="6" align="center" class="t_name">합계</td>
        <td align="center" class="t_name"><?=number_format($supprice)?></td>
        <td align="center" class="t_name"><?=number_format($prdprice)?></td>
        <td align="center" class="t_name"><?=number_format($resprice)?></td>
        <td align="center" class="t_name"><?=number_format($couprice)?></td>
        <td align="center" class="t_name"><?=number_format($total_price)?></td>
      </tr>
    </table>

    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
      <tr>
        <td width="33%"></td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
        <td width="33%" align="right"></td>
      </tr>
    </table>

<? include "../footer.php"; ?>