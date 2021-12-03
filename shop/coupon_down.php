<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

if($wiz_session['id'] == "") error("로그인후 이용하세요.");

// 상품쿠폰
if($prdcode != ""){

	// 상품정보
	$sql = "select * from wiz_product where prdcode='$prdcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	$prd_info = mysqli_fetch_object($result);
	if($total <= 0) error("상품정보가 없습니다.");

	$today = date('Y-m-d');

	if($prd_info->coupon_sdate <= $today && $prd_info->coupon_edate >= $today) {

		// 쿠폰등록
		if($prd_info->coupon_limit == "N" || ($prd_info->coupon_limit == "" && $prd_info->coupon_amount > 0)){

			$coupon_name = '[상품쿠폰]'.$prd_info->prdname;
			$memid = $wiz_session['id'];
			$coupon_use = "N";
			$coupon_dis = $prd_info->coupon_dis;
			$coupon_type = $prd_info->coupon_type;
			$coupon_sdate = $prd_info->coupon_sdate;
			$coupon_edate = $prd_info->coupon_edate;

			$sql = "select idx from wiz_mycoupon where memid='$memid' and prdcode='$prdcode' and coupon_use != 'Y' and coupon_sdate <= '$today' and coupon_edate >= '$today'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);
			if($total > 0) error("이미 발급된 쿠폰입니다.");

			$sql = "insert into wiz_mycoupon(idx,memid,prdcode,coupon_name,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_use,wdate)
								values('','$memid','$prdcode','$coupon_name','$coupon_dis','$coupon_type','$coupon_sdate','$coupon_edate','$coupon_use',now())";
			mysqli_query($connect, $sql) or error("할인쿠폰 다운시 에러가 발생하였습니다.\\n\\n관리자에게 문의하세요.");
		}

		if($prd_info->coupon_limit != "N"){
			$sql = "update wiz_product set coupon_amount = coupon_amount - 1 where prdcode='$prdcode'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));
		}
		comalert("정상적으로 발급되었습니다.");

	} else {
		alert("사용기간이 지난 쿠폰입니다.");
	}

// 이벤트 쿠폰
}else{

	// 쿠폰정보
	$sql = "select * from wiz_coupon where idx='$eventidx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	$coupon_info = mysqli_fetch_object($result);
	if($total <= 0) error("쿠폰정보가 없습니다.");

	if($coupon_info->coupon_sdate <= date('Y-m-d') && $coupon_info->coupon_edate >= date('Y-m-d')) {

		// 쿠폰등록
		if($coupon_info->coupon_limit == "N" || ($coupon_info->coupon_limit == "" && $coupon_info->coupon_amount > 0)){

			$coupon_name = $coupon_info->coupon_name;
			$memid = $wiz_session['id'];
			$coupon_use = "N";
			$coupon_dis = $coupon_info->coupon_dis;
			$coupon_type = $coupon_info->coupon_type;
			$coupon_sdate = $coupon_info->coupon_sdate;
			$coupon_edate = $coupon_info->coupon_edate;

			$sql = "select idx from wiz_mycoupon where memid='$memid' and eventidx='$eventidx' and coupon_use != 'Y' and coupon_sdate <= curdate() and coupon_edate >= curdate()";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			if($total > 0) error("이미 발급된 쿠폰입니다.");

			$sql = "delete from wiz_mycoupon where memid = '$memid' and eventidx='$eventidx' and coupon_use != 'Y'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$sql = "insert into wiz_mycoupon(idx,memid,eventidx,coupon_name,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_use,wdate)
								values('','$memid','$eventidx','$coupon_name','$coupon_dis','$coupon_type','$coupon_sdate','$coupon_edate','$coupon_use',now())";
			mysqli_query($connect, $sql) or error("할인쿠폰 다운시 에러가 발생하였습니다.\\n\\n관리자에게 문의하세요.");
		}

		if($coupon_info->coupon_limit != "N"){
			$sql = "update wiz_coupon set coupon_amount = coupon_amount - 1 where idx='$eventidx'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));
		}

		comalert("정상적으로 발급되었습니다.");

	} else {
		alert("사용기간이 지난 쿠폰입니다.");
	}
}
?>