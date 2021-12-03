<?

include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc"; 		// 운영 정보
include "../inc/util.inc";		      // 유틸lib

if($rece_name == "") error("받으시는 분 이름이 빠졌습니다.");
if($rece_post == "" || $rece_post2 == "") error("받으시는 분 우편번호가 빠졌습니다.");
if($rece_address == "" || $rece_address2 == "") error("받으시는 분 주소가 빠졌습니다.");
if($rece_tphone == "" || $rece_tphone2 == "" || $rece_tphone3 == "") error("받으시는 분 전화번호가 빠졌습니다.");

$send_id = $wiz_session['id'];
$reserve_use = $_POST["reserve_use"];

$send_post = $send_post."-".$send_post2;
$send_address = $send_address." ".$send_address2;
$send_tphone = $send_tphone."-".$send_tphone2."-".$send_tphone3;
$send_hphone = $send_hphone."-".$send_hphone2."-".$send_hphone3;

$rece_post = $rece_post."-".$rece_post2;
$rece_address = $rece_address." ".$rece_address2;
$rece_tphone = $rece_tphone."-".$rece_tphone2."-".$rece_tphone3;
$rece_hphone = $rece_hphone."-".$rece_hphone2."-".$rece_hphone3;

// 주문번호
$orderid = date("ymdHis").rand(100,999);

// 각 입점업체별 상품 수 구하기
$sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket_tmp where uniq_id='".$_COOKIE["uniq_id"]."' group by mallid";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
while($row = mysqli_fetch_array($result)) {

	$mall_list[$row['mallid']]['cnt'] = $row['cnt'];
	$mall_list[$row['mallid']]['total'] = $row['total'];

}

// 주문가격 정보(상품가격, 배송비, 적립금, 전체결제금액)
// 주문정보 입력폼에서 전달시 변조될 위험있음

$sql = "SELECT wb.*, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2,
				wm.del_extrapost1, wm.del_extrapost12, wm.del_extrapost2, wm.del_extrapost22, wm.del_extrapost3, wm.del_extrapost32,
				wm.del_extraprice1, wm.del_extraprice2, wm.del_extraprice3, wm.com_name, wm.com_tel
				FROM wiz_basket_tmp as wb inner join wiz_mall as wm on wb.mallid = wm.id
				WHERE uniq_id='".$_COOKIE["uniq_id"]."'";
$bkresult = mysqli_query($connect, $sql) or die(mysqli_error($connect));
while($bkinfo = mysqli_fetch_array($bkresult)){
	$prd_price += ($bkinfo['prdprice'] * $bkinfo['amount']);
	$reserve_price += ($bkinfo['prdreserve'] * $bkinfo['amount']);

	$prd_price_mall[$bkinfo['mallid']] += ($bkinfo['prdprice'] * $bkinfo['amount']);

	$mall_no[$bkinfo['mallid']]++;

	$mall_info[$bkinfo['mallid']]['name'] = $bkinfo['com_name'];
	$mall_info[$bkinfo['mallid']]['tel'] = $bkinfo['com_tel'];

	if(!strcmp($mall_no[$bkinfo['mallid']], $mall_list[$bkinfo['mallid']]['cnt'])) {

		if(!empty($bkinfo['del_method'])) {

			$tmp_oper_info->mallid 				= $bkinfo['mallid'];

			$tmp_oper_info->del_method 		= $bkinfo['del_method'];
			$tmp_oper_info->del_fixprice 	= $bkinfo['del_fixprice'];
			$tmp_oper_info->del_staprice 	= $bkinfo['del_staprice'];
			$tmp_oper_info->del_staprice2 = $bkinfo['del_staprice2'];
			$tmp_oper_info->del_staprice3 = $bkinfo['del_staprice3'];

			$tmp_oper_info->del_prd	 = $bkinfo['del_prd'];
			$tmp_oper_info->del_prd2 = $bkinfo['del_prd2'];

			$tmp_oper_info->del_extrapost1 = $bkinfo['del_extrapost1'];
			$tmp_oper_info->del_extrapost12 = $bkinfo['del_extrapost12'];
			$tmp_oper_info->del_extrapost2 = $bkinfo['del_extrapost2'];
			$tmp_oper_info->del_extrapost22 = $bkinfo['del_extrapost22'];
			$tmp_oper_info->del_extrapost3 = $bkinfo['del_extrapost3'];
			$tmp_oper_info->del_extrapost32 = $bkinfo['del_extrapost32'];

			$tmp_oper_info->del_extraprice1 = $bkinfo['del_extraprice1'];
			$tmp_oper_info->del_extraprice2 = $bkinfo['del_extraprice2'];
			$tmp_oper_info->del_extraprice3 = $bkinfo['del_extraprice3'];

		} else {
			$tmp_oper_info = $oper_info;
		}

		// 배송비'
		$deliver_price_mall[$bkinfo['mallid']] = deliver_price($prd_price_mall[$bkinfo['mallid']], $tmp_oper_info, $bkinfo['mallid']);

		// 배송할증료 적용(고정값,구매가격별에서만 적용)
		if($bkinfo['del_method'] == "DC" || $bkinfo['del_method'] == "DD"){
			$tmp_post = str_replace("-","",$rece_post);
			if($tmp_oper_info->del_extrapost1 <= $tmp_post && $tmp_post <= $tmp_oper_info->del_extrapost12) $deliver_price_mall[$bkinfo['mallid']] = $deliver_price_mall[$bkinfo['mallid']] + $tmp_oper_info->del_extraprice1;
			if($tmp_oper_info->del_extrapost2 <= $tmp_post && $tmp_post <= $tmp_oper_info->del_extrapost22) $deliver_price_mall[$bkinfo['mallid']] = $deliver_price_mall[$bkinfo['mallid']] + $tmp_oper_info->del_extraprice2;
			if($tmp_oper_info->del_extrapost3 <= $tmp_post && $tmp_post <= $tmp_oper_info->del_extrapost32) $deliver_price_mall[$bkinfo['mallid']] = $deliver_price_mall[$bkinfo['mallid']] + $tmp_oper_info->del_extraprice3;
		}

		$deliver_price += $deliver_price_mall[$bkinfo['mallid']];

	}

}

// 배송비
//$deliver_price = deliver_price($prd_price, $oper_info);

// 배송방법
$deliver_method = $oper_info->del_method;

// 회원할인 [$discount_msg 메세지 생성]
$discount_price = level_discount($wiz_session['level'],$prd_price);


// 배송할증료 적용(고정값,구매가격별에서만 적용)
if($deliver_method == "DC" || $deliver_method == "DD"){
	$tmp_post = str_replace("-","",$rece_post);
	if($oper_info->del_extrapost1 <= $tmp_post && $tmp_post <= $oper_info->del_extrapost12) $deliver_price = $deliver_price + $oper_info->del_extraprice1;
	if($oper_info->del_extrapost2 <= $tmp_post && $tmp_post <= $oper_info->del_extrapost22) $deliver_price = $deliver_price + $oper_info->del_extraprice2;
	if($oper_info->del_extrapost3 <= $tmp_post && $tmp_post <= $oper_info->del_extrapost32) $deliver_price = $deliver_price + $oper_info->del_extraprice3;
}

$total_price = $prd_price + $deliver_price - $discount_price;



// 적립금사용시 결제액 감소, 적림금감소
if($oper_info->reserve_use == "Y" && $reserve_use > 0 && $wiz_session['id'] != ""){

	// 회원적립금 가져오기
	$sql = "SELECT SUM(reserve) AS reserve FROM wiz_reserve WHERE memid = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$mem_info = mysqli_fetch_object($result);
	if($mem_info->reserve == "") $mem_info->reserve = 0;

	// 적립금 사용금액이 실제 적립금보다 많다면
	if($reserve_use > $mem_info->reserve){
		error("실제적립금 보다 사용액이 많습니다.");
	}else{
		$total_price = $total_price - $reserve_use;
	}

}

// 쿠폰사용
if($coupon_use != "" && $coupon_use > 0){

	$total_price = $total_price - $coupon_use;

	//$coupon_tmp = mallid^prdcode^coupon_use^coupon_idx|
	$coupon_list = explode("|", $coupon_tmp);
	for($ii = 0; $ii < count($coupon_list); $ii++) {

		if(!empty($coupon_list[$ii])) {

			list($c_mallid, $c_prdcode, $c_use, $c_idx) = explode("^", $coupon_list[$ii]);

			$coupon_mall[$c_mallid][$c_prdcode]['coupon_use'] = $c_use;
			$coupon_mall[$c_mallid][$c_prdcode]['idx'] = $c_idx;

		}

	}

}

// 주문정보 저장
$sql = "INSERT INTO wiz_order(
				orderid,send_id,send_name,send_tphone,send_hphone,send_email,send_post,send_address,demand,message,cancelmsg,
				rece_name,rece_tphone,rece_hphone,rece_post,rece_address,pay_method,account_name,account,coupon_use,coupon_idx,reserve_use,
				reserve_price,deliver_method,deliver_price,deliver_num,discount_price,prd_price,total_price,status,order_date,
				pay_date,send_date,cancel_date,descript,tax_type
				)VALUES(
				'".$orderid."','".$send_id."', '".$send_name."', '".$send_tphone."', '".$send_hphone."', '".$send_email."', '".$send_post."', '".$send_address."', '".$demand."', '".$message."', '$cancelmsg ',
				'".$rece_name."', '".$rece_tphone."', '".$rece_hphone."', '".$rece_post."', '".$rece_address."',
				'".$pay_method."', '".$account_name."', '".$account."', '".$coupon_use."','".$coupon_idx."',
				'".$reserve_use."', '".$reserve_price."', '".$deliver_method."', '".$deliver_price."', '".$deliver_num."', '".$discount_price."','".$prd_price."', '".$total_price."',
				'".$status."', now(), '".$paydate."', '".$sendddate."', '".$canceldate."', '".$descript."','".$tax_type."')";

mysqli_query($connect, $sql) or die(mysqli_error($connect));

$prd_info = "";

// 주문상품 저장
$sql = "SELECT wb.*, wp.del_type, wp.del_price FROM wiz_basket_tmp as wb inner join wiz_product as wp on wb.prdcode = wp.prdcode WHERE wb.uniq_id='".$_COOKIE["uniq_id"]."'";
$bkiresult = mysqli_query($connect, $sql) or die(mysqli_error($connect));
while($bkirow = mysqli_fetch_array($bkiresult)){
	$sql = "INSERT INTO wiz_basket(idx,orderid,prdcode,prdname,prdimg,prdprice,prdreserve,supprice,mallid,mall_name,mall_tel,
				opttitle,optcode,opttitle2,optcode2,opttitle3,optcode3,
				opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,
				opttitle7,optcode7,amount,wdate,status,del_type,del_price
				)VALUES(
				'','".$orderid."','".$bkirow['prdcode']."','".$bkirow['prdname']."','".$bkirow['prdimg']."','".$bkirow['prdprice']."','".$bkirow['prdreserve']."','".$bkirow['supprice']."',
				'".$bkirow['mallid']."','".$mall_info[$bkirow['mallid']]['name']."','".$mall_info[$bkirow['mallid']]['tel']."',
        '".$bkirow['opttitle']."','".$bkirow['optcode']."','".$bkirow['opttitle2']."','".$bkirow['optcode2']."','".$bkirow['opttitle3']."','".$bkirow['optcode3']."',
        '".$bkirow['opttitle4']."','".$bkirow['optcode4']."','".$bkirow['opttitle5']."','".$bkirow['optcode5']."','".$bkirow['opttitle6']."','".$bkirow['optcode6']."',
        '".$bkirow['opttitle7']."','".$bkirow['optcode7']."','".$bkirow['amount']."',now(),'','".$bkirow['del_type']."','".$bkirow['del_price']."')";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$prd_info .= $bkirow['prdname']."^".$bkirow['prdprice']."^".$bkirow['amount']."^^";
}

// 세금계산서 저장
if(!strcmp($oper_info->tax_use, "Y") && $tax_type != "N") {

	//$supp_price = intval($total_price/1.1);
	$supp_price = ($total_price/1.1);
	$tax_price = $total_price - $supp_price;

	// 신청정보
	if(is_array($cash_info_arr)) $cash_info = implode("-", $cash_info_arr);

	$sql = "INSERT INTO wiz_tax(orderid,com_num,com_name,com_owner,com_address,com_kind,com_class,com_tel,com_email,prd_info,supp_price,tax_price,tax_pub,tax_type,cash_type,cash_type2,cash_info,cash_name)
					VALUES ('".$orderid."','".$com_num."','".$com_name."','".$com_owner."','".$com_address."','".$com_kind."','".$com_class."','".$com_tel."','".$com_email."','".$prd_info."','".$supp_price."','".$tax_price."','N','".$tax_type."','".$cash_type."','".$cash_type2."','".$cash_info."','".$cash_name."')";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

}

if(mobile_check() == true) {
 $go_url = "http://".$_http_host.$HTTP_POST."/".$mobile_path."/sub/order_pay.php?orderid=".$orderid."&pay_method=".$pay_method;
} else {
 $go_url = "http://".$_http_host."/shop/order_pay.php?orderid=".$orderid."&pay_method=".$pay_method;
}

header("Location: $go_url");
?>