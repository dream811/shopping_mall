<?php
	include "../../inc/common.inc";
	include "../../inc/util.inc";
	include "../../inc/oper_info.inc";
	include "../../inc/shop_info.inc";

	$filename = "정산정보[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	if(!strcmp($s_status, "AW")) $search_sql .= " AND ISNULL(wa.status) ";
	else if(!empty($s_status)) $search_sql .= " AND wa.status = '$s_status' ";

	if($searchopt && $searchkey) {
		if(!strcmp($searchopt, "acc_date")) $search_sql .= " AND (wa.acc_date like '%$searchkey%' or DATE_FORMAT(wo.send_date, '%Y%m') like '%$searchkey') ";
		else $search_sql .= " AND $searchopt like '%$searchkey%' ";
	}

	$prev_date = $prev_year."-".$prev_month."-".$prev_day;
	$next_date = $next_year."-".$next_month."-".$next_day;

	if($date_type) $search_sql .= " AND DATE_FORMAT($date_type, '%Y-%m-%d') >= '$prev_date' AND DATE_FORMAT($date_type, '%Y-%m-%d') <= '$next_date' ";

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
					  WHERE ord_status = 'DC' OR ord_status = 'CC'
					  GROUP BY mallid, orderid
					  ) AS wd GROUP BY mallid
					) AS wd  ON wb.mallid = wd.mallid
					WHERE (wb.ord_status = 'DC' or wb.ord_status = 'CC') $search_sql
					GROUP BY wb.mallid, DATE_FORMAT(wo.send_date, '%Y%m')
					ORDER BY wo.send_date DESC";
	//echo $sql."<br><br>";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$excel_title = "";
	$excel_title .= "정산기준일(YYYYMM)	";
	$excel_title .= "업체ID	";
	$excel_title .= "업체명	";
	$excel_title .= "업체연락처	";
	$excel_title .= "공급가	";
	$excel_title .= "판매가	";
	$excel_title .= "적립금	";
	$excel_title .= "배송비	";
	$excel_title .= "쿠폰할인가	";
	$excel_title .= "합계	";
	$excel_title .= "정산신청일	";
	$excel_title .= "정산완료일	";
	$excel_title .= "진행상태	";

	echo $excel_title."\n";

	while($row = mysqli_fetch_array($result)){

		if(empty($row[mall_name])) {
			$row[mall_name] = $shop_info->com_name;
			$row[mall_tel] = $shop_info->com_tel;
		}

		if(empty($row['total_price'])) {

			$row['total_price'] = $row[supprice] + $row[delprice];

			if(!strcmp($oper_info->mall_dis, "M")) $row['total_price'] = 	$row['total_price'] - $row[couprice];
			if(!strcmp($oper_info->mall_reserve, "M")) $row['total_price'] = 	$row['total_price'] - $row[resprice];
		}

		switch($row[acc_status]) {
			case "AW" : $acc_status = "정산대기"; break;
			case "AA" : $acc_status = "정산요청"; break;
			case "AI" : $acc_status = "정산중"; break;
			case "AD" : $acc_status = "정산보류"; break;
			case "AC" : $acc_status = "정산완료"; break;
			default 	: $acc_status = ""; break;
		}

		$excel_data = "";
		$excel_data .= $row[acc_date]."	";
		$excel_data .= $row[mallid]."	";
		$excel_data .= $row[mall_name]."	";
		$excel_data .= $row[mall_tel]."	";
		$excel_data .= $row[supprice]."	";
		$excel_data .= $row[prdprice]."	";
		$excel_data .= $row[resprice]."	";
		$excel_data .= $row[delprice]."	";
		$excel_data .= $row[couprice]."	";
		$excel_data .= $row['total_price']."	";
		$excel_data .= $row[app_date]."	";
		$excel_data .= $row[com_date]."	";
		$excel_data .= $acc_status."	";

		echo $excel_data."\n";

	}

?>