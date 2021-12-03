<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/oper_info.inc";
include "../../inc/mall_check.inc";

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "s_status=$s_status&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
$param .= "&searchopt=$searchopt&searchkey=$searchkey";
if($reason != "") $param .= "&reason=$reason";
if($tax_type != "") $param .= "&tax_type=$tax_type";
//--------------------------------------------------------------------------------------------------

function changeStatus($orderid, $status, $delsno="", $deldate=""){

	global $DOCUMENT_ROOT, $HTTP_HOST, $connect, $oper_info, $order_info;

	// 운송장 번호가 있는 경우 update
	if(!empty($delsno)) {
		$sql = "update wiz_order set deliver_num='$delsno', deliver_date='$deldate' where orderid='$orderid'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$sql = "update wiz_basket set deliver_num='$delsno', deliver_date='$deldate' where orderid = '$orderid'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));
	}

	$sql = "select * from wiz_order where orderid = '$orderid'";
	$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

	$re_info[name] = $order_info->send_name;
	$re_info[email] = $order_info->send_email;
	$re_info[hphone] = $order_info->send_hphone;

	$del_com = $oper_info->del_com;
	//if($order_info->status != $status ){
	if($order_info->status){

		// 배송완료 → 다른 진행상태로 변경 시 배송완료수 -1
		if(!strcmp($order_info->status, "DC") && strcmp($status, "DC")) {

			$sql = "select wb.prdcode, wp.comcnt
							from wiz_basket as wb INNER JOIN wiz_product as wp on wb.prdcode = wp.prdcode
							where wb.orderid = '$order_info->orderid'";
			$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

			while($row = mysqli_fetch_object($result)){

				if($row->comcnt > 0) {
					$sql = "update wiz_product set comcnt = comcnt - 1 where prdcode = '$row->prdcode'";
					mysqli_query($connect, $sql) or die(mysqli_error($connect));
				}

			}

		}

		// 주문취소, 환불완료 → 다른 진행상태로 변경 시 주문취소수 -1
		if((!strcmp($order_info->status, "OC") && strcmp($status, "OC")) || (!strcmp($order_info->status, "RC") && strcmp($status, "RC"))){

			$sql = "select wb.prdcode, wp.cancelcnt
							from wiz_basket as wb INNER JOIN wiz_product as wp on wb.prdcode = wp.prdcode
							where wb.orderid = '$order_info->orderid'";
			$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

			while($row = mysqli_fetch_object($result)){

				if($row->cancelcnt > 0) {
					$sql = "update wiz_product set cancelcnt = cancelcnt - 1 where prdcode = '$row->prdcode'";
					mysqli_query($connect, $sql) or die(mysqli_error($connect));
				}

			}

		}

	   // 입금확인시
		if($status == "OY"){

			// 이전의 상태와 변경상태가 다른 경우에만
			if(strcmp($status, $order_info->status)) {

				// 재고처리(결제완료[OY]인 경우에만 재고 감소 -> 주문완료 시 재고가 감소되므로 따로 재고처리 하지 않음)
				// Exe_stock();

				// 적립금사용 적용
				if($order_info->reserve_use > 0){

				$sql = "select idx from wiz_reserve where memid = '$order_info->send_id' and orderid = '$orderid' and reserve < 0";
				$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
				$total = mysqli_num_rows($result);

					// 이미 적립금이 적용榮쩝?체크
					if($total <= 0){
					    $reserve_msg = "상품구입시 사용";
					    $sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$order_info->send_id', '$reserve_msg', -$order_info->reserve_use, '$orderid', now())";
					    mysqli_query($connect, $sql) or error(mysqli_error($connect));
					}

				}

				$oper_time = ", pay_date = now()";

				include "$DOCUMENT_ROOT/shop/order_mail.inc";
				send_mailsms("order_pay", $re_info, $ordmail);

			}

		// 배송완료
		}else if(!strcmp($status, "DC")) {

			//적립금적용
			if($order_info->reserve_price > 0){

				$sql = "select idx from wiz_reserve where memid = '$order_info->send_id' and orderid = '$orderid' and reserve > 0";
				$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
				$total = mysqli_num_rows($result);

				// 이미 적립금이 적용榮쩝?체크
				if($total <= 0){
				    $reserve_msg = "상품구입시 적립됨";
				    $sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$order_info->send_id', '$reserve_msg', $order_info->reserve_price, '$orderid', now())";
				    mysqli_query($connect, $sql) or error(mysqli_error($connect));
				}

			}

			// 마케팅분석 > 상품통계분석 > 배송완료 증가
			$sql = "select wb.prdcode, wp.comcnt
							from wiz_basket as wb INNER JOIN wiz_product as wp on wb.prdcode = wp.prdcode
							where wb.orderid = '$order_info->orderid'";
			$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

			while($row = mysqli_fetch_object($result)){

				if(strcmp($order_info->status, $status)) {
					$sql = "update wiz_product set comcnt = comcnt + 1 where prdcode = '$row->prdcode'";
					mysqli_query($connect, $sql) or die(mysqli_error($connect));
				}
			}

			$oper_time = ", send_date = now()";

		// 주문취소시, 환불완료시
		}else if($status == "OC" || $status == "RC"){

			//적립금적용(해당주문에 대한 적립내역 삭제)
			$sql = "delete from wiz_reserve where memid='$order_info->send_id' and orderid='$order_info->orderid'";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));

			// 주문취소 시 주문접수일 경우를 제외하고 재고 증가 -> 주문접수인 경우에도 재고 증가
			//if(strcmp($order_info->status, "OR")) {
				// 주문취소, 주문완료 수량적용
				$sql = "select wb.prdcode, wb.amount, wb.optcode, wb.status, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue as p_optvalue, wp.opt_use, wp.shortage
								from wiz_basket as wb INNER JOIN wiz_product as wp on wb.prdcode = wp.prdcode
								where orderid = '$order_info->orderid'";
				$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
				while($row = mysqli_fetch_object($result)){
					// 옵션별 재고관리 없는 제품이라면 전체재고 증가
					if(strcmp($row->opt_use, "Y")){

						$sql = "update wiz_product set cancelcnt = cancelcnt + 1, stock = stock + $row->amount where prdcode = '$row->prdcode'";
						mysqli_query($connect, $sql) or error(mysqli_error($connect));

					// 옵션별 재고관리 상품
					}else{

						$opt_list_app = "";

						$opt1_arr = explode("^", $row->p_optcode);
						$opt2_arr = explode("^", $row->p_optcode2);
						$opt_tmp = explode("^^", $row->p_optvalue);

						list($optcode1, $optcode2) = explode("/", $row->optcode);

						if(strcmp($row->stuats, "CC")) {

							$opt1_cnt = count($opt1_arr) - 1;
							$opt2_cnt = count($opt2_arr) - 1;

							if($opt1_cnt < 1) $opt1_cnt = 1;
							if($opt2_cnt < 1) $opt2_cnt = 1;

							$no = 0;
							for($ii = 0; $ii < $opt1_cnt; $ii++) {
								for($jj = 0; $jj < $opt2_cnt; $jj++) {
									list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

									if(!empty($tmp_optvalue[$row->prdcode][$no])) $stock = $tmp_optvalue[$row->prdcode][$no];

									if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
										$stock = $stock + $row->amount;
									}

									$opt_list_app .= $price."^".$reserve."^".$stock."^^";

									$tmp_optvalue[$row->prdcode][$no] = $stock;
									$no++;
								}
							}
							/*
							$opt_list = explode("^^",$row->p_optcode);
							for($ii=0; $ii < count($opt_list)-1; $ii++){
								$opt_list2 = explode("^",$opt_list[$ii]);
								if($opt_list2[0] == $row->optcode){
									$opt_list2[2] = $opt_list2[2] + $row->amount;
									$opt_list_app .= $opt_list2[0]."^".$opt_list2[1]."^".$opt_list2[2]."^^";
								}else{
									$opt_list_app .= $opt_list[$ii]."^^";
								}
							}
							*/

							$optvalue_sql = ", optvalue = '$opt_list_app'";

						}

						$sql = "update wiz_product set cancelcnt = cancelcnt + 1  $optvalue_sql where prdcode = '$row->prdcode'";

						mysqli_query($connect, $sql) or error(mysqli_error($connect));

					}

				}
			//}

			// 쿠폰
			$coupon_list = explode("|", $order_info->coupon_idx);
			if(is_array($coupon_list)) {
				foreach($coupon_list as $c_idx => $cidx) {
					$sql = "update wiz_mycoupon set coupon_use = 'N' where idx = '".$cidx."'";
					mysqli_query($connect, $sql) or die(mysqli_error($connect));
				}
			}

			$oper_time = ", cancel_date = now()";
			include "$DOCUMENT_ROOT/shop/order_mail.inc";
			send_mailsms("order_cancel", $re_info, $ordmail);

		// 배송처리 시
		} else if(!strcmp($status, "DI")) {

			include "$DOCUMENT_ROOT/shop/order_mail.inc";
			send_mailsms("order_deliver", $re_info, $ordmail);

		}

		$sql = "update wiz_order set status = '$status' $oper_time where orderid = '$orderid'";
		mysqli_query($connect, $sql);

		$sql = "update wiz_basket set ord_status = '$status' where orderid = '$orderid'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

   }

   // 배송처리, 배송완료인 경우 배송정보 전송
   if(!strcmp($status, "DI") || !strcmp($status, "DC")) {

			// 배송정보 전송
			escrow_delivery($order_info, $oper_info, $order_info->deliver_num, $order_info->deliver_date);

		}

}

// 주문상태 변경
if($mode == "chgstatus"){

	//$sql = "update wiz_basket set ord_status = '$chg_status', deliver_num='$deliver_num', deliver_date='$deliver_date' where orderid = '$orderid' and mallid = '".$wiz_mall['id']."'";
	$sql = "update wiz_basket set ord_status = '$chg_status' where orderid = '$orderid' and mallid = '".$wiz_mall['id']."'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "select ord_status from wiz_basket where orderid = '$orderid' group by ord_status";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$no = 0;
	while($row = mysqli_fetch_array($result)) {
		$status = $row[ord_status];
		$no++;
	}

	if($no == 1) {
		changeStatus($orderid, $status);
	}

	complete("주문정보가 수정되었습니다.","order_list.php?page=$page&$param");

// 주문정보 수정
}else if($mode == "update"){

	$send_post = $send_post."-".$send_post2;
	$rece_post = $rece_post."-".$rece_post2;

	if(!empty($chg_status)) {
		changeStatus($orderid, $chg_status, $deliver_num, $deliver_date);
		$chg_status_sql = " status = '$chg_status', ";
	}

	$sql = "update wiz_order set $chg_status_sql send_name = '$send_name', send_tphone = '$send_tphone', send_hphone = '$send_hphone', send_email = '$send_email',
                        send_post = '$send_post', send_address = '$send_address', rece_name =' $rece_name', rece_tphone = '$rece_tphone',
                        rece_hphone = '$rece_hphone', rece_post = '$rece_post', rece_address = '$rece_address', demand = '$demand', message = '$message', cancelmsg='$cancelmsg', descript = '$descript',
                        deliver_num = '$deliver_num', deliver_date = '$deliver_date', tax_type = '$tax_type', id_info='$id_info', bill_yn='$bill_yn', authno='$authno' where orderid = '$orderid'";

  $result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

  $sql = "select orderid from wiz_tax where orderid = '$orderid'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $row = mysqli_fetch_array($result);

	include_once "../../inc/shop_info.inc";

	$shop_name 		= $shop_info->com_name;
	$shop_owner 		= $shop_info->com_owner;
	$shop_num			= $shop_info->com_num;
	$shop_address	= $shop_info->com_address;
	$shop_kind 		= $shop_info->com_kind;
	$shop_class		= $shop_info->com_class;
	$shop_tel			= $shop_info->com_tel;
	$shop_email		= $shop_info->shop_email;

  if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y")) $tax_pub_sql = ", wdate = now(), shop_name='$shop_name', shop_owner='$shop_owner', shop_num='$shop_num', shop_address='$shop_address', shop_kind='$shop_kind', shop_class='$shop_class', shop_tel='$shop_tel', shop_email='$shop_email' ";

  if(!empty($row['orderid'])) {
	  $sql = "update wiz_tax set com_num='$com_num', com_name='$com_name', com_owner='$com_owner', com_address='$com_address', com_kind='$com_kind', com_class='$com_class', com_tel='$com_tel', com_email='$com_email'
	  				, tax_pub='$tax_pub',tax_type = '$tax_type',cash_type='$cash_type',cash_type2='$cash_type2',cash_info='$cash_info',cash_name='$cash_name' $tax_pub_sql where orderid = '$orderid'";
	} else {

		include_once "../../inc/shop_info.inc";

		$shop_name 		= $shop_info->com_name;
		$shop_owner 	= $shop_info->com_owner;
		$shop_num			= $shop_info->com_num;
		$shop_address	= $shop_info->com_address;
		$shop_kind 		= $shop_info->com_kind;
		$shop_class		= $shop_info->com_class;
		$shop_tel			= $shop_info->com_tel;
		$shop_email		= $shop_info->shop_email;

		$supp_price = intval($total_price/1.1);
		$tax_price = $total_price - $supp_price;

		$sql = "INSERT INTO wiz_tax(orderid,com_num,com_name,com_owner,com_address,com_kind,com_class,com_tel,com_email,shop_num,shop_name,shop_owner,shop_address,shop_kind,shop_class,shop_tel,shop_email,prd_info,supp_price,tax_price,tax_pub,tax_date,tax_type,cash_type,cash_type2,cash_info,cash_name)
						VALUES ('".$orderid."','".$com_num."','".$com_name."','".$com_owner."','".$com_address."','".$com_kind."','".$com_class."','".$com_tel."','".$com_email."','".$shop_num."','".$shop_name."','".$shop_owner."','".$shop_address."','".$shop_kind."','".$shop_class."','".$shop_tel."','".$shop_email."','".$prd_info."','".$supp_price."','".$tax_price."','".$tax_pub."',now(),'".$tax_type."','".$cash_type."','".$cash_type2."','".$cash_info."','".$cash_name."')";

	}

  mysqli_query($connect, $sql) or die(mysqli_error($connect));

  complete("주문정보가 수정되었습니다.","order_info.php?orderid=$orderid&page=$page&$param");


// 주문삭제
}else if($mode == "delete"){

	$i=0;
	$array_selorder = explode("|",$selorder);
	while($array_selorder[$i]){
		$orderid = $array_selorder[$i];
		$sql = "delete from wiz_order where orderid = '$orderid'";
		$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

		$sql = "delete from wiz_basket where orderid = '$orderid'";
		$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));

		$sql = "delete from wiz_tax where orderid = '$orderid'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$i++;
	}

	complete("주문을 삭제하였습니다.","order_list.php?page=$page&$param");


// 주문상태 일괄변경
}else if($mode == "batchStatus"){

	$i=0;
	$array_selorder = explode("|",$selorder);
	while($array_selorder[$i]){
		list($orderid, $old_status) = explode(":",$array_selorder[$i]);

		if(strcmp($old_status, "OC") && strcmp($old_status, "RC") && strcmp($old_status, "OR")) {

			$sql = "update wiz_basket set ord_status = '$chg_status', deliver_num='$deliveryno[$i]', deliver_date='$deliver_date[$i]' where orderid = '$orderid' and mallid = '".$wiz_mall['id']."'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$sql = "select ord_status from wiz_basket where orderid = '$orderid' group by ord_status";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$no = 0;
			while($row = mysqli_fetch_array($result)) {
				$status = $row[ord_status];
				$no++;
			}

			if($no == 1) {
				changeStatus($orderid, $status);
			}
		}

		$i++;
	}

	echo "<script>alert('주문상태를 변경하였습니다.');opener.document.location.reload();self.close();</script>";

// 상품 취소
}else if($mode == "cancel"){

	if(!strcmp($orderstatus, "OR")) {

		$sql = "select wb.*, wo.reserve_use, wo.reserve_price, wo.deliver_price, wo.prd_price, wo.prd_price, wm.level, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue as p_optvalue
						from wiz_basket as wb INNER JOIN wiz_order as wo ON wb.orderid = wo.orderid
						INNER JOIN wiz_member AS wm ON wo.send_id = wm.id
						INNER JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
						where wb.idx = '$idx'";
		$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		$reserve_price = $row[reserve_price] - ($row[prdreserve] * $row[amount]);
		$prd_price 		 = $row[prd_price] - ($row[prdprice] * $row[amount]);

		$discount_price = level_discount($row[level],$prd_price);			// 회원할인 [$discount_msg 메세지 생성]
		$deliver_price = deliver_price($prd_price, $oper_info);				// 배송비
		$total_price = $prd_price + $deliver_price - $discount_price; // 전체결제금액

		// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
		$sql = "update wiz_order set reserve_price = '$reserve_price', deliver_price = '$deliver_price',
						discount_price = '$discount_price', prd_price = '$prd_price', total_price = '$total_price'
						where orderid = '$row['orderid']'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));

		// basket 업데이트
		$sql = "update wiz_basket set status = 'CC', admin = '".$wiz_mall['id']."', bank = '$bank', account = '$account',
						acc_name = '$acc_name', reason = '$reason', memo = '$memo', repay = '$repay', ca_date = now(), cc_date = now()
						where idx = '$idx'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));

   	complete("상품이 취소되었습니다.","order_info.php?orderid=$row['orderid']&page=$page&$param");

	} else {

		// basket 업데이트
		$sql = "update wiz_basket set status = 'CA', admin = '".$wiz_mall['id']."', bank = '$bank', account = '$account',
						acc_name = '$acc_name', reason = '$reason', memo = '$memo', repay = '$repay', ca_date = now()
						where idx = '$idx'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));

   	complete("상품이 취소요청이 되었습니다. 상품취소목록에서 확인하실 수 있습니다.","order_info.php?orderid=$orderid&page=$page&$param");

	}

// 개별취소 목록
} else if(!strcmp($mode, "cancel_status")){

	if(!strcmp($chg_status, "CC")) {

			$sql = "select wb.*, wo.reserve_use, wo.reserve_price, wo.deliver_price, wo.prd_price, wo.prd_price, wo.send_id, wo.status as o_status, wm.level, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue as p_optvalue, wp.opt_use, wp.shortage
							from wiz_basket as wb INNER JOIN wiz_order as wo ON wb.orderid = wo.orderid
							INNER JOIN wiz_member AS wm ON wo.send_id = wm.id
							INNER JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
							where wb.idx = '$idx'";
			$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
			$row = mysqli_fetch_array($result);

		if(!strcmp($row['status'], "CC")) {
			error("이미 취소처리된 상품입니다.");
		} else {

			$reserve_price = $row[reserve_price] - ($row[prdreserve] * $row[amount]);
			$prd_price 		 = $row[prd_price] - ($row[prdprice] * $row[amount]);

			$discount_price = level_discount($row[level],$prd_price);			// 회원할인 [$discount_msg 메세지 생성]
			$deliver_price = deliver_price($prd_price, $oper_info);				// 배송비
			$total_price = $prd_price + $deliver_price - $discount_price; // 전체결제금액

			// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
			$sql = "update wiz_order set reserve_price = '$reserve_price', deliver_price = '$deliver_price',
							discount_price = '$discount_price', prd_price = '$prd_price', total_price = '$total_price'
							where orderid = '$row['orderid']'";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));

			// 상품 재고
			// 주문접수일 경우를 제외하고 재고증가
			if(strcmp($row[o_status], "OR")) {
				// 옵션별 재고관리 없는 제품이라면 전체 재고 증가
				if(strcmp($row[opt_use], "Y")){

					if(!strcmp($row[shortage], "S")) {
						$sql = "update wiz_product set cancelcnt = cancelcnt + 1, stock = stock + $row[amount] where prdcode = '$row[prdcode]'";
						$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					} else {
						$sql = "update wiz_product set cancelcnt = cancelcnt + 1 where prdcode = '$row[prdcode]'";
						$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					}

				// 옵션별 재고관리 상품
				}else{

					$opt_list_app = "";

					$opt1_arr = explode("^", $row[p_optcode]);
					$opt2_arr = explode("^", $row[p_optcode2]);
					$opt_tmp = explode("^^", $row[p_optvalue]);

					list($optcode1, $optcode2) = explode("/", $row[optcode]);

					$opt1_cnt = count($opt1_arr) - 1;
					$opt2_cnt = count($opt2_arr) - 1;

					if($opt1_cnt < 1) $opt1_cnt = 1;
					if($opt2_cnt < 1) $opt2_cnt = 1;

					$no = 0;
					for($ii = 0; $ii < $opt1_cnt; $ii++) {
						for($jj = 0; $jj < $opt2_cnt; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if(!empty($tmp_optvalue[$row[prdcode]][$no])) $stock = $tmp_optvalue[$row[prdcode]][$no];

							if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
								$stock = $stock + $row[amount];
							}

							$opt_list_app .= $price."^".$reserve."^".$stock."^^";

							$tmp_optvalue[$row[prdcode]][$no] = $stock;
							$no++;
						}
					}

					$sql = "update wiz_product set cancelcnt = cancelcnt + 1, optvalue = '$opt_list_app' where prdcode = '$row[prdcode]'";
					mysqli_query($connect, $sql) or error(mysqli_error($connect));

				}
			}
		}

		// 적립금으로 환불 시 적립금 적립
		if(!strcmp($row[repay], "R")) {
			$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate)
							values('', '$row[send_id]', '상품환불 적립금','".($row[prdprice] * $row[amount])."','$row['orderid']',now())";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));
		}

		$cc_date_sql = ", cc_date = now() ";

	}

	$sql = "update wiz_basket set status = '$chg_status' $cc_date_sql where idx = '$idx'";
	mysqli_query($connect, $sql) or error(mysqli_error($connect));

	// 세금계산서 금액 수정
	$supp_price = intval($total_price/1.1);
	$tax_price = $total_price - $supp_price;

	$prd_info = "";

	$b_sql = "select prdname, prdprice, amount from wiz_basket where orderid = '$row['orderid']' and status != 'CC' order by idx asc";
	$b_result = mysqli_query($connect, $b_sql,$connect) or error(mysqli_error($connect));
	while($b_row = mysqli_fetch_array($b_result)) {
		$prd_info .= $b_row[prdname]."^".$b_row[prdprice]."^".$b_row[amount]."^^";
	}

	$sql = "update wiz_tax set supp_price='$supp_price', tax_price='$tax_price', prd_info='$prd_info' where orderid = '$row['orderid']'";
	mysqli_query($connect, $sql) or error(mysqli_error($connect));


 	complete("적용되었습니다.","cancel_list.php?page=$page&$param");

// 개별취소 삭제
} else if(!strcmp($mode, "delete_basket")) {

	$idx_list = explode("|", $selbasket);
	for($ii = 0; $ii < count($idx_list); $ii++) {
		$idx = $idx_list[$ii];

		$sql = "delete from wiz_basket where idx = '$idx'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));
	}

  complete("삭제되었습니다.","cancel_list.php?page=$page&$param");

// 취소상태 일괄변경
}else if($mode == "batchStatusBasket"){

	$i=0;
	$array_selbasket = explode("|",$selbasket);

	while($array_selbasket[$i]){
		$idx = $array_selbasket[$i];

		$sql = "select wb.*, wo.reserve_use, wo.reserve_price, wo.deliver_price, wo.prd_price, wo.prd_price, wo.send_id, wm.level, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue as p_optvalue
						from wiz_basket as wb INNER JOIN wiz_order as wo ON wb.orderid = wo.orderid
						INNER JOIN wiz_member AS wm ON wo.send_id = wm.id
						INNER JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
						where wb.idx = '$idx'";
		$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		if(!strcmp($row['status'], "CC")) {
		} else {
			if(!strcmp($chg_status, "CC")) {
				$reserve_price = $row[reserve_price] - ($row[prdreserve] * $row[amount]);
				$prd_price 		 = $row[prd_price] - ($row[prdprice] * $row[amount]);

				$discount_price = level_discount($row[level],$prd_price);			// 회원할인 [$discount_msg 메세지 생성]
				$deliver_price = deliver_price($prd_price, $oper_info);				// 배송비
				$total_price = $prd_price + $deliver_price - $discount_price; // 전체결제금액

				// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
				$sql = "update wiz_order set reserve_price = '$reserve_price', deliver_price = '$deliver_price',
								discount_price = '$discount_price', prd_price = '$prd_price', total_price = '$total_price'
								where orderid = '$row['orderid']'";
				mysqli_query($connect, $sql) or error(mysqli_error($connect));

				// 상품 재고
				// 옵션별 재고관리 없는 제품이라면 전체재고 증가
				if($row[optcode] == ""){

					$sql = "update wiz_product set cancelcnt = cancelcnt + 1 , comcnt = comcnt - 1, stock = stock + $row[amount] where prdcode = '$row[prdcode]'";
					mysqli_query($connect, $sql) or error(mysqli_error($connect));

				// 옵션별 재고관리 상품
				}else{

					$opt_list_app = "";

					$opt1_arr = explode("^", $row[p_optcode]);
					$opt2_arr = explode("^", $row[p_optcode2]);
					$opt_tmp = explode("^^", $row[p_optvalue]);

					list($optcode1, $optcode2) = explode("/", $row[optcode]);

					$opt1_cnt = count($opt1_arr) - 1;
					$opt2_cnt = count($opt2_arr) - 1;

					if($opt1_cnt < 1) $opt1_cnt = 1;
					if($opt2_cnt < 1) $opt2_cnt = 1;

					$no = 0;
					for($ii = 0; $ii < $opt1_cnt; $ii++) {
						for($jj = 0; $jj < $opt2_cnt; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if(!empty($tmp_optvalue[$row[prdcode]][$no])) $stock = $tmp_optvalue[$row[prdcode]][$no];

							if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
								$stock = $stock + $row[amount];
							}

							$opt_list_app .= $price."^".$reserve."^".$stock."^^";

							$tmp_optvalue[$row[prdcode]][$no] = $stock;
							$no++;
						}
					}
					$sql = "update wiz_product set cancelcnt = cancelcnt + 1 , comcnt = comcnt - 1, optvalue = '$opt_list_app' where prdcode = '$row[prdcode]'";
					mysqli_query($connect, $sql) or error(mysqli_error($connect));

				}

				$cc_date_sql = ", cc_date = now() ";
			}

			// 적립금으로 환불 시 적립금 적립
			if(!strcmp($row[repay], "R")) {
				$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate)
								values('', '$row[send_id]', '상품환불 적립금','".($row[prdprice] * $row[amount])."','$row['orderid']',now())";
				mysqli_query($connect, $sql) or error(mysqli_error($connect));
			}
			$sql = "update wiz_basket set status = '$chg_status' $cc_date_sql where idx = '$idx'";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));

			// 세금계산서 금액 수정
			$supp_price = intval($total_price/1.1);
			$tax_price = $total_price - $supp_price;

			$prd_info = "";

			$b_sql = "select prdname, prdprice, amount from wiz_basket where orderid = '$row['orderid']' and status != 'CC' order by idx asc";
			$b_result = mysqli_query($connect, $b_sql) or error(mysqli_error($connect));
			while($b_row = mysqli_fetch_array($b_result)) {
				$prd_info .= $b_row[prdname]."^".$b_row[prdprice]."^".$b_row[amount]."^^";
			}

			$sql = "update wiz_tax set supp_price='$supp_price', tax_price='$tax_price', prd_info='$prd_info' where orderid = '$row['orderid']'";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));

			$sql = "update wiz_basket set status = '$chg_status' $cc_date_sql where idx = '$idx'";
			mysqli_query($connect, $sql) or error(mysqli_error($connect));

		}

		$i++;
	}

	echo "<script>alert('상태를 변경하였습니다.\\n\\n취소완료된 건은 상태가 변경되지 않습니다.');opener.document.location.reload();self.close();</script>";

// 세금계산서 목록 > 승인
} else if(!strcmp($mode, "tax_status")) {

	include_once "../../inc/shop_info.inc";

	$shop_name 		= $shop_info->com_name;
	$shop_owner 	= $shop_info->com_owner;
	$shop_num			= $shop_info->com_num;
	$shop_address	= $shop_info->com_address;
	$shop_kind 		= $shop_info->com_kind;
	$shop_class		= $shop_info->com_class;
	$shop_tel			= $shop_info->com_tel;
	$shop_email		= $shop_info->shop_email;

  if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y")) $tax_pub_sql = ", wdate = now(), shop_name='$shop_name', shop_owner='$shop_owner', shop_num='$shop_num', shop_address='$shop_address', shop_kind='$shop_kind', shop_class='$shop_class', shop_tel='$shop_tel', shop_email='$shop_email' ";

	$sql = "update wiz_tax set tax_pub = '$tax_pub' $tax_pub_sql where orderid = '$orderid'";
	mysqli_query($connect, $sql) or error(mysqli_error($connect));

 	complete("적용되었습니다.","tax_list.php?page=$page&$param");

// 세금계산서 삭제
} else if(!strcmp($mode, "tax_delete")) {

	$orderid_list = explode("|", $selvalue);
	for($ii = 0; $ii < count($orderid_list); $ii++) {
		$orderid = $orderid_list[$ii];
		$sql = "delete from wiz_tax where orderid = '$orderid'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));

		$sql = "update wiz_order set tax_type = 'N' where orderid = '$orderid'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));
	}

  complete("삭제되었습니다.","tax_list.php?page=$page&$param");

// 세금계산서 목록 > 상태일괄변경
} else if(!strcmp($mode, "batchStatusTax")) {

	include_once "../../inc/shop_info.inc";

	$shop_name 		= $shop_info->com_name;
	$shop_owner 	= $shop_info->com_owner;
	$shop_num			= $shop_info->com_num;
	$shop_address	= $shop_info->com_address;
	$shop_kind 		= $shop_info->com_kind;
	$shop_class		= $shop_info->com_class;
	$shop_tel			= $shop_info->com_tel;
	$shop_email		= $shop_info->shop_email;

  if(!strcmp($tax_pub, "Y") && strcmp($tmp_tax_pub, "Y")) $tax_pub_sql = ", wdate = now(), shop_name='$shop_name', shop_owner='$shop_owner', shop_num='$shop_num', shop_address='$shop_address', shop_kind='$shop_kind', shop_class='$shop_class', shop_tel='$shop_tel', shop_email='$shop_email' ";

	$orderid_list = explode("|", $selvalue);
	for($ii = 0; $ii < count($orderid_list); $ii++) {

		$orderid = $orderid_list[$ii];

		$sql = "update wiz_tax set tax_pub = '$tax_pub' $tax_pub_sql where orderid = '$orderid'";
		mysqli_query($connect, $sql) or error(mysqli_error($connect));

	}

  echo "<script>alert('변경되었습니다.');opener.document.location.reload();self.close();</script>";

// 장바구니 주문상태, 운송장번호, 발송일자 업데이트
} else if(!strcmp($mode, "basket_update")) {

	// 주문취소인 경우에 상품재고 및 주문정보 변경
	if(strcmp($tmp_ord_status, $ord_status) && (!strcmp($ord_status, "OC") || !strcmp($ord_status, "RC"))) {

		$sql = "select wb.*, wo.reserve_use, wo.reserve_price, wo.deliver_price, wo.prd_price, wo.prd_price, wm.level,
						wp.opt_use, wp.shortage, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue as p_optvalue,
						wmall.com_name, wmall.com_tel, wmall.del_method, wmall.del_fixprice, wmall.del_staprice, wmall.del_staprice2, wmall.del_staprice3, wmall.del_prd, wmall.del_prd2
						from wiz_basket as wb INNER JOIN wiz_order as wo ON wb.orderid = wo.orderid
						INNER JOIN wiz_member AS wm ON wo.send_id = wm.id
						INNER JOIN wiz_product AS wp ON wb.prdcode = wp.prdcode
						INNER JOIN wiz_mall AS wmall ON wb.mallid = wmall.id
						where wb.orderid = '$orderid' and wb.mallid = '$mallid'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while($row = mysqli_fetch_array($result)) {

			$reserve_price += ($row[prdreserve] * $row[amount]);
			$prd_price 		 += ($row[prdprice] * $row[amount]);

			$ord_deliver_price = $row[deliver_price];
			$ord_reserve_price = $row[reserve_price];
			$ord_prd_price 		 = $row[prd_price];
			$ord_level				 = $row[level];

			if(!empty($row[del_method])) {

				$tmp_oper_info->mallid 				= $row[mallid];

				$tmp_oper_info->del_method 		= $row[del_method];
				$tmp_oper_info->del_fixprice 	= $row[del_fixprice];
				$tmp_oper_info->del_staprice 	= $row[del_staprice];
				$tmp_oper_info->del_staprice2 = $row[del_staprice2];
				$tmp_oper_info->del_staprice3 = $row[del_staprice3];

				$tmp_oper_info->del_prd	 = $row[del_prd];
				$tmp_oper_info->del_prd2 = $row[del_prd2];

			} else {
				$tmp_oper_info = $oper_info;
			}

			// 상품 재고
			// 주문접수일 경우를 제외하고 재고증가
			if(strcmp($row[ord_status], "OR")) {
				// 옵션별 재고관리 없는 제품이라면 전체 재고 증가
				if(strcmp($row[opt_use], "Y")){

					if(!strcmp($row[shortage], "S")) {
						$sql = "update wiz_product set cancelcnt = cancelcnt + 1, stock = stock + $row[amount] where prdcode = '$row[prdcode]'";
						mysqli_query($connect, $sql) or die(mysqli_error($connect));
					} else {
						$sql = "update wiz_product set cancelcnt = cancelcnt + 1 where prdcode = '$row[prdcode]'";
						mysqli_query($connect, $sql) or die(mysqli_error($connect));
					}

				// 옵션별 재고관리 상품
				}else{

					$opt_list_app = "";

					$opt1_arr = explode("^", $row[p_optcode]);
					$opt2_arr = explode("^", $row[p_optcode2]);
					$opt_tmp = explode("^^", $row[p_optvalue]);

					list($optcode1, $optcode2) = explode("/", $row[optcode]);

					$opt1_cnt = count($opt1_arr) - 1;
					$opt2_cnt = count($opt2_arr) - 1;

					if($opt1_cnt < 1) $opt1_cnt = 1;
					if($opt2_cnt < 1) $opt2_cnt = 1;

					$no = 0;
					for($ii = 0; $ii < $opt1_cnt; $ii++) {
						for($jj = 0; $jj < $opt2_cnt; $jj++) {
							list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

							if(!empty($tmp_optvalue[$row[prdcode]][$no])) $stock = $tmp_optvalue[$row[prdcode]][$no];

							if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
								$stock = $stock + $row[amount];
							}

							$opt_list_app .= $price."^".$reserve."^".$stock."^^";

							$tmp_optvalue[$row[prdcode]][$no] = $stock;
							$no++;
						}
					}

					$sql = "update wiz_product set cancelcnt = cancelcnt + 1, optvalue = '$opt_list_app' where prdcode = '$row->prdcode'";
					mysqli_query($connect, $sql) or error(mysqli_error($connect));

				}
			}

		}

		$reserve_price = $ord_reserve_price - $reserve_price;
		$prd_price = $ord_prd_price - $prd_price;

		$discount_price = level_discount($ord_level,$prd_price);			// 회원할인 [$discount_msg 메세지 생성]

		// 배송비
		$deliver_price_mall = deliver_price($prd_price, $tmp_oper_info, $mallid);
		$deliver_price = $ord_deliver_price - $deliver_price_mall;

		$total_price = $prd_price + $deliver_price - $discount_price; // 전체결제금액

		if($reserve_price < 0) $reserve_price = 0;
		if($deliver_price < 0) $deliver_price = 0;
		if($discount_price < 0) $discount_price = 0;
		if($prd_price < 0) $prd_price = 0;
		if($total_price < 0) $total_price = 0;

		// 주문 정보에서 해당 금액, 적립금, 배송비, 회원할인비 가감
		$sql = "update wiz_order set reserve_price = '$reserve_price', deliver_price = '$deliver_price',
						discount_price = '$discount_price', prd_price = '$prd_price', total_price = '$total_price'
						where orderid = '$orderid'";

		mysqli_query($connect, $sql) or error(mysqli_error($connect));

		$status_sql = " , status = 'CC', ca_date = now(), cc_date = now()";

	}

	// basket 업데이트
	$sql = "update wiz_basket set
					ord_status = '$ord_status', deliver_num='$deliver_num', deliver_date='$deliver_date',
					admin = '".$wiz_mall['id']."', bank = '$bank', account = '$account',acc_name = '$acc_name',
					reason = '$reason', memo = '$memo', repay = '$repay' $status_sql
					where orderid = '$orderid' and mallid = '$mallid'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "select ord_status from wiz_basket where orderid = '$orderid' and status != 'CC' group by ord_status";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$no = 0;
	while($row = mysqli_fetch_array($result)) {
		$status = $row[ord_status];
		$no++;
	}

	if($no < 1) {

		$sql = "select ord_status from wiz_basket where orderid = '$orderid' and status = 'CC' group by ord_status";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$no = 0;
		while($row = mysqli_fetch_array($result)) {
			$status = $row[ord_status];
			$no++;
		}

	}

	if($no == 1) {
		changeStatus($orderid, $status);
	}

  complete("주문정보가 수정되었습니다.","order_info.php?orderid=$orderid&page=$page&$param");

}else if($mode == "sendsms"){

	include_once "$DOCUMENT_ROOT/inc/oper_info.inc";

	$user_list = explode(",",$seluser);

  for($ii=0; $ii < count($user_list); $ii++){
	   if($user_list[$ii] != "") send_sms($se_tel, $user_list[$ii], $content, $se_name);
	}

	comalert("발송되었습니다.","");

// 개별회원 이메일 발송
}else if($mode == "sendemail"){

	include "$DOCUMENT_ROOT/inc/shop_info.inc";
   $se_name = $shop_info->shop_name;
   $se_email = $shop_info->shop_email;

   $content = str_replace("\\","",$content);

   $user_list = explode(",",$seluser);
   for($ii=0; $ii < count($user_list); $ii++){

      list($re_name, $re_email) = explode(":", $user_list[$ii]);
      $content = stripslashes($content);
      if($re_name != "") send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content);

   }

	complete("이메일 발송을 완료하였습니다.","send_email.php?seluser=$seluser");

}
?>