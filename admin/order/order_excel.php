<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";

if($exceldown != "ok"){
?>
<html>
<head>
<title>:: 주문정보 다운로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function selBasic(frm){

	frm.orderid.checked = true;
	frm.orderprd.checked = true;
	frm.total_price.checked = true;
	frm.pay_method.checked = true;
	frm.order_date.checked = true;
	frm.account.checked = true;
	frm.deliver_num.checked = false;
	frm.account_name.checked = false;
	frm.ostatus.checked = true;

	frm.send_name.checked = true;
	frm.send_email.checked = false;
	frm.send_tphone.checked = true;
	frm.send_hphone.checked = false;
	frm.send_post.checked = false;
	frm.send_address.checked = false;

	frm.rece_name.checked = true;
	frm.rece_tphone.checked = true;
	frm.rece_hphone.checked = false;
	frm.rece_post.checked = true;
	frm.rece_address.checked = true;
	frm.demand.checked = false;

}

function selAll(frm){

	frm.orderid.checked = true;
	frm.orderprd.checked = true;
	frm.total_price.checked = true;
	frm.pay_method.checked = true;
	frm.order_date.checked = true;
	frm.account.checked = true;
	frm.deliver_num.checked = true;
	frm.account_name.checked = true;
	frm.ostatus.checked = true;
	frm.descript.checked = true;

	frm.send_name.checked = true;
	frm.send_email.checked = true;
	frm.send_tphone.checked = true;
	frm.send_hphone.checked = true;
	frm.send_post.checked = true;
	frm.send_address.checked = true;

	frm.rece_name.checked = true;
	frm.rece_tphone.checked = true;
	frm.rece_hphone.checked = true;
	frm.rece_post.checked = true;
	frm.rece_address.checked = true;
	frm.demand.checked = true;
	frm.cancelmsg.checked = true;

}
//-->
</script>
</head>

<body style="padding:10px;">
<form name="frm" action="" method="post">
<input type="hidden" name="exceldown" value="ok">
<input type="hidden" name="status" value="<?=$status?>">
<input type="hidden" name="prev_year" value="<?=$prev_year?>">
<input type="hidden" name="prev_month" value="<?=$prev_month?>">
<input type="hidden" name="prev_day" value="<?=$prev_day?>">
<input type="hidden" name="next_year" value="<?=$next_year?>">
<input type="hidden" name="next_month" value="<?=$next_month?>">
<input type="hidden" name="next_day" value="<?=$next_day?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<table width="100%" border="0" cellpadding="3" cellspacing="6" class="t_style" align="center">
  <tr>
    <td bgcolor="ffffff">
    <table><tr></td></tr></table>
     <table width="100%" cellspacing="2" cellpadding="0" border="0">
     <tr>
      <td width="100"><font color="2369C9"><b>선택구분</b></font></td>
      <td><input type="radio" name="sel_gubun" onClick="selBasic(this.form);" checked><font color="red"><b>기본선택</b></font></td>
		  <td><input type="radio" name="sel_gubun" onClick="selAll(this.form);"><font color="red"><b>전체선택</b></font></td>
		  <td></td>
		  <td></td>
		</tr>
		<tr><td height="3"></td></tr>
    <tr>
      <td><font color="2369C9"><b>주문정보</b></font></td>
      <td><input type="checkbox" name="orderid" value="Y" checked>주문번호</td>
		  <td><input type="checkbox" name="orderprd" value="Y" checked>주문상품</td>
		  <td><input type="checkbox" name="total_price" value="Y" checked>총결제금액</td>
		  <td><input type="checkbox" name="pay_method" value="Y" checked>결제방법</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="order_date" value="Y" checked>주문일자</td>
		  <td><input type="checkbox" name="account" value="Y" checked>결제계좌</td>
		  <td><input type="checkbox" name="deliver_num" value="Y">운송장번호</td>
		  <td><input type="checkbox" name="account_name" value="Y" checked>입금인</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="ostatus" value="Y" checked>처리상태</td>
		  <td><input type="checkbox" name="descript" value="Y">관리자메모</td>
		  <td></td>
		  <td></td>
	   </tr>
	   <tr><td height="3"></td></tr>
		<tr>
		  <td><font color="2369C9"><b>주문자정보</b></font></td>
			<td><input type="checkbox" name="send_name" value="Y" checked>주문자명</td>
			<td><input type="checkbox" name="send_email" value="Y">주문자 이메일</td>
			<td><input type="checkbox" name="send_tphone" value="Y" checked>주문자 전화번호</td>
			<td><input type="checkbox" name="send_hphone" value="Y">주문자 휴대폰</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="send_post" value="Y">주문자 우편번호</td>
		  <td><input type="checkbox" name="send_address" value="Y">주문자 주소</td>
		  <td></td>
		  <td></td>
	   </tr>
	   <tr><td height="6"></td></tr>
		<tr>
		  <td><font color="2369C9"><b>수취인정보</b></font></td>
			<td><input type="checkbox" name="rece_name" value="Y" checked>수취인명</td>
			<td><input type="checkbox" name="rece_tphone" value="Y" checked>수취인 전화번호</td>
			<td><input type="checkbox" name="rece_hphone" value="Y">수취인 휴대폰</td>
			<td><input type="checkbox" name="rece_post" value="Y" checked>수취인 우편번호</td>
    </tr>
    <tr>
      <td></td>
      <td><input type="checkbox" name="rece_address" value="Y" checked>수취인주소</td>
		  <td><input type="checkbox" name="demand" value="Y">수취인요청사항</td>
		  <td><input type="checkbox" name="cancelmsg" value="Y">주문취소사유</td>
      <td></td>
      <td></td>
      </tr>
    </table>
   </td>
 </tr>
</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="self.close();">닫기</a>
</div><!-- .AW-btn-wrap -->

</form>
</body>
</html>
<?
}else{

	$filename = "주문정보[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	if($prev_year){
	   $prev_period = $prev_year."-".$prev_month."-".$prev_day;
	   $next_period = $next_year."-".$next_month."-".$next_day." 23:59:59";
	   $period_sql = " and wo.order_date >= '$prev_period' and wo.order_date <= '$next_period'";
	}
	if($s_status == "") $status_sql = "and wo.status != ''";
	else if($s_status == "MI") $status_sql = "and wo.status = ''";
	else $status_sql = "and wo.status = '$s_status'";

	if($searchopt && $searchkey) $searchopt_sql = " and wo.$searchopt like '%$searchkey%'";

	$sql = "select wo.*, wb.amount, wb.opttitle, wb.optcode, wb.opttitle2, wb.optcode2, wb.opttitle3, wb.optcode3, wb.opttitle4, wb.optcode4, wb.opttitle5, wb.optcode5, wb.opttitle6, wb.optcode6, wb.opttitle7, wb.optcode7, wb.prdname from wiz_order wo, wiz_basket wb where wo.orderid = wb.orderid $status_sql $period_sql $searchopt_sql order by orderid desc";
	//echo $sql;
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	if($orderid == "Y") $excel_title .= "주문번호	";
	if($orderprd == "Y") $excel_title .= "주문상품	";
	if($total_price == "Y") $excel_title .= "총결제금액	";
	if($pay_method == "Y") $excel_title .= "결제방법	";
	if($order_date == "Y") $excel_title .= "주문일자	";
	if($account == "Y") $excel_title .= "결제계좌	";
	if($deliver_num == "Y") $excel_title .= "운송장번호	";
	if($account_name == "Y") $excel_title .= "입금인	";

	if($send_name == "Y") $excel_title .= "주문자명	";
	if($send_email == "Y") $excel_title .= "주문자 이메일	";
	if($send_tphone == "Y") $excel_title .= "주문자 전화번호	";
	if($send_hphone == "Y") $excel_title .= "주문자 휴대폰	";
	if($send_post == "Y") $excel_title .= "주문자 우편번호	";
	if($send_address == "Y") $excel_title .= "주문자 주소	";

	if($rece_name == "Y") $excel_title .= "수취인명	";
	if($rece_tphone == "Y") $excel_title .= "수취인 전화번호	";
	if($rece_hphone == "Y") $excel_title .= "수취인 휴대폰	";
	if($rece_post == "Y") $excel_title .= "수취인 우편번호	";
	if($rece_address == "Y") $excel_title .= "수취인주소	";

	if($demand == "Y") $excel_title .= "요청사항	";
	if($cancelmsg == "Y") $excel_title .= "주문취소사유	";
	if($descript == "Y") $excel_title .= "관리자메모	";
	if($ostatus == "Y") $excel_title .= "처리상태	";

	echo $excel_title."\n";

	$prdname = "";
	$orderid_tmp = "";
	while($row = mysqli_fetch_object($result)){

		if($row_tmp->prdname != "") {
			$prd_opt = "";
			if($row_tmp->optcode != "") $prd_opt .= $row_tmp->opttitle.":".$row_tmp->optcode;
			if($row_tmp->optcode2 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle2.":".$row_tmp->optcode2; }
			if($row_tmp->optcode3 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle3.":".$row_tmp->optcode3; }
			if($row_tmp->optcode4 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle4.":".$row_tmp->optcode4; }
			if($row_tmp->optcode5 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle5.":".$row_tmp->optcode5; }
			if($row_tmp->optcode6 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle6.":".$row_tmp->optcode6; }
			if($row_tmp->optcode7 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle7.":".$row_tmp->optcode7; }

			if($prd_opt != "") $prd_opt = "[".$prd_opt."]";

			$prdname .= $row_tmp->prdname.$prd_opt."[".$row_tmp->amount."개]:::";
		}

		if($orderid_tmp != $row->orderid){

			if($orderid_tmp != ""){

				$row_tmp->account = str_replace("\r\n", " ", trim($row_tmp->account));
				$row_tmp->deliver_num = str_replace("\r\n", " ", trim($row_tmp->deliver_num));
				$row_tmp->account_name = str_replace("\r\n", " ", trim($row_tmp->account_name));

				$row_tmp->demand = str_replace("\r\n", " ", $row_tmp->demand);
				$row_tmp->cancelmsg = str_replace("\r\n", " ", $row_tmp->cancelmsg);
				$row_tmp->descript = str_replace("\r\n", " ", $row_tmp->descript);

				$excel_data = "";
				if($orderid == "Y") $excel_data .= $row_tmp->orderid."	";
				if($orderprd == "Y") $excel_data .= $prdname."	";
				if($total_price == "Y") $excel_data .= number_format($row_tmp->total_price)."원"."	";
				if($pay_method == "Y") $excel_data .= pay_method($row_tmp->pay_method)."	";
				if($order_date == "Y") $excel_data .= $row_tmp->order_date."	";
				if($account == "Y") $excel_data .= $row_tmp->account."	";
				if($deliver_num == "Y") $excel_data .= $row_tmp->deliver_num."	";
				if($account_name == "Y") $excel_data .= $row_tmp->account_name."	";

				if($send_name == "Y") $excel_data .= $row_tmp->send_name."	";
				if($send_email == "Y") $excel_data .= $row_tmp->send_email."	";
				if($send_tphone == "Y") $excel_data .= $row_tmp->send_tphone."	";
				if($send_hphone == "Y") $excel_data .= $row_tmp->send_hphone."	";
				if($send_post == "Y") $excel_data .= $row_tmp->send_post."	";
				if($send_address == "Y") $excel_data .= $row_tmp->send_address."	";

				if($rece_name == "Y") $excel_data .= $row_tmp->rece_name."	";
				if($rece_tphone == "Y") $excel_data .= $row_tmp->rece_tphone."	";
				if($rece_hphone == "Y") $excel_data .= $row_tmp->rece_hphone."	";
				if($rece_post == "Y") $excel_data .= $row_tmp->rece_post."	";
				if($rece_address == "Y") $excel_data .= $row_tmp->rece_address."	";

				if($demand == "Y") $excel_data .= $row_tmp->demand."	";
				if($cancelmsg == "Y") $excel_data .= $row_tmp->cancelmsg."	";
				if($descript == "Y") $excel_data .= $row_tmp->descript."	";
				if($ostatus == "Y") $excel_data .= order_status($row_tmp->status)."	";

				echo $excel_data."\n";
				$prdname = "";

			}

		}

		$row_tmp = $row;
		$orderid_tmp = $row->orderid;

	}

	$prd_opt = "";
	if($row_tmp->optcode != "") $prd_opt .= $row_tmp->opttitle.":".$row_tmp->optcode;
	if($row_tmp->optcode2 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle2.":".$row_tmp->optcode2; }
	if($row_tmp->optcode3 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle3.":".$row_tmp->optcode3; }
	if($row_tmp->optcode4 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle4.":".$row_tmp->optcode4; }
	if($row_tmp->optcode5 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle5.":".$row_tmp->optcode5; }
	if($row_tmp->optcode6 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle6.":".$row_tmp->optcode6; }
	if($row_tmp->optcode7 != "") { if($prd_opt != "") $prd_opt .= "/"; $prd_opt .= $row_tmp->opttitle7.":".$row_tmp->optcode7; }

	if($prd_opt != "") $prd_opt = "[".$prd_opt."]";

	$prdname .= $row_tmp->prdname.$prd_opt."[".$row_tmp->amount."개]:::";

	$row_tmp->account = str_replace("\r\n", " ", trim($row_tmp->account));
	$row_tmp->deliver_num = str_replace("\r\n", " ", trim($row_tmp->deliver_num));
	$row_tmp->account_name = str_replace("\r\n", " ", trim($row_tmp->account_name));

	$row_tmp->demand = str_replace("\r\n", " ", $row_tmp->demand);
	$row_tmp->cancelmsg = str_replace("\r\n", " ", $row_tmp->cancelmsg);
	$row_tmp->descript = str_replace("\r\n", " ", $row_tmp->descript);

	$excel_data = "";
	if($orderid == "Y") $excel_data .= $row_tmp->orderid."	";
	if($orderprd == "Y") $excel_data .= $prdname."	";
	if($total_price == "Y") $excel_data .= number_format($row_tmp->total_price)."원"."	";
	if($pay_method == "Y") $excel_data .= pay_method($row_tmp->pay_method)."	";
	if($order_date == "Y") $excel_data .= $row_tmp->order_date."	";
	if($account == "Y") $excel_data .= $row_tmp->account."	";
	if($deliver_num == "Y") $excel_data .= $row_tmp->deliver_num."	";
	if($account_name == "Y") $excel_data .= $row_tmp->account_name."	";

	if($send_name == "Y") $excel_data .= $row_tmp->send_name."	";
	if($send_email == "Y") $excel_data .= $row_tmp->send_email."	";
	if($send_tphone == "Y") $excel_data .= $row_tmp->send_tphone."	";
	if($send_hphone == "Y") $excel_data .= $row_tmp->send_hphone."	";
	if($send_post == "Y") $excel_data .= $row_tmp->send_post."	";
	if($send_address == "Y") $excel_data .= $row_tmp->send_address."	";

	if($rece_name == "Y") $excel_data .= $row_tmp->rece_name."	";
	if($rece_tphone == "Y") $excel_data .= $row_tmp->rece_tphone."	";
	if($rece_hphone == "Y") $excel_data .= $row_tmp->rece_hphone."	";
	if($rece_post == "Y") $excel_data .= $row_tmp->rece_post."	";
	if($rece_address == "Y") $excel_data .= $row_tmp->rece_address."	";

	if($demand == "Y") $excel_data .= $row_tmp->demand."	";
	if($cancelmsg == "Y") $excel_data .= $row_tmp->cancelmsg."	";
	if($descript == "Y") $excel_data .= $row_tmp->descript."	";
	if($ostatus == "Y") $excel_data .= order_status($row_tmp->status)."	";

	echo $excel_data."\n";

}
?>