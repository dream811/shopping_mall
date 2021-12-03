<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";

// 기본정보설정
if ($mode == "shop_info") {

	$upfile_path = "../../data/config";

	// 사업자도장
	if ($com_seal['size'] > 0) {
		file_check($com_seal['name']);
		copy($com_seal['tmp_name'], $upfile_path . "/com_seal.gif");
		@chmod($upfile_path . "/com_seal.gif", 0606);
	}

	//mysqli_query($connect, "alter table wiz_shopinfo add naver_key varchar(100);");

	//$com_post = $com_post."-".$com_post2;
	$sql = "update wiz_shopinfo set naver_key='$naver_key', shop_name='$shop_name', shop_url='$shop_url', shop_email='$shop_email', shop_tel='$shop_tel', shop_hand='$shop_hand',
					com_num='$com_num', com_name='$com_name', com_owner='$com_owner', com_post='$com_post', com_address='$com_address', com_kind='$com_kind', com_class='$com_class', com_tel='$com_tel', com_fax='$com_fax', estimate_bigo='$estimate_bigo'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "update wiz_design set site_title='$site_title', site_intro='$site_intro', site_keyword='$site_keyword'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("기본정보 설정이 저장되었습니다.", "shop_info.php");




	// 운영정보설정
} else if ($mode == "oper_info") {


	for ($ii = 0; $ii < count($pay_method); $ii++) {
		$pay_tmp .= $pay_method[$ii] . "/";
	}

	$sql = "update wiz_operinfo set pay_method ='$pay_tmp', pay_id ='$pay_id', pay_key = '$pay_key', pay_agent ='$pay_agent', pay_escrow='$pay_escrow',  pay_test ='$pay_test',
						del_com='$del_com', del_prd='$del_prd', del_prd2='$del_prd2', del_method ='$del_method', del_fixprice ='$del_fixprice', del_staprice ='$del_staprice', del_staprice2 ='$del_staprice2', del_staprice3 ='$del_staprice3',
						del_extrapost1 ='$del_extrapost1', del_extrapost12 ='$del_extrapost12', del_extraprice1 ='$del_extraprice1',
						del_extrapost2 ='$del_extrapost2', del_extrapost22 ='$del_extrapost22', del_extraprice2 ='$del_extraprice2',
						del_extrapost3 ='$del_extrapost3', del_extrapost32 ='$del_extrapost32', del_extraprice3 ='$del_extraprice3',
						reserve_use ='$reserve_use', reserve_join ='$reserve_join', reserve_recom ='$reserve_recom', reserve_min ='$reserve_min', reserve_max ='$reserve_max', reserve_buy ='$reserve_buy', reserve_per ='$reserve_per',
						review_use ='$review_use', review_level ='$review_level', tax_use='$tax_use', tax_status='$tax_status', prdrel_use='$prdrel_use',
						mall_join='$mall_join',mall_prd='$mall_prd',mall_rate='$mall_rate',mall_showset='$mall_showset',mall_dis='$mall_dis',mall_reserve='$mall_reserve'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$code = "review";
	$sql = "update wiz_bbsinfo set usetype = '$review_usetype', wpermi = '$review_wpermi' where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$code = "qna";
	$sql = "update wiz_bbsinfo set usetype = '$qna_usetype', wpermi = '$qna_wpermi' where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("운영정보 설정이 저장되었습니다.", "shop_oper.php");

	// 거래처관리
} else if ($mode == "shop_trade") {


	if ($sub_mode == "insert") {

		//$com_post = $com_post."-".$com_post2;

		$sql = "insert into wiz_tradecom(idx,com_type,com_num,com_name,com_owner,com_post,com_address,com_kind,com_class,
	   											com_tel,com_fax,com_bank,com_account,com_homepage,charge_name,charge_email,charge_hand,charge_tel,descript)
													values(
	                        '$idx', '$com_type', '$com_num', '$com_name', '$com_owner', '$com_post', '$com_address', '$com_kind', '$com_class',
	                        '$com_tel', '$com_fax', '$com_bank', '$com_account', '$com_homepage',
	                        '$charge_name', '$charge_email', '$charge_hand', '$charge_tel', '$descript')";

		$result = mysqli_query($connect, $sql) or error("거래처 정보를 저장하는중 에러가 발생하였습니다.");

		complete("거래처 정보가 저장되었습니다.", "shop_trade.php");
	} else if ($sub_mode == "update") {


		//$com_post = $com_post."-".$com_post2;

		$sql = "update wiz_tradecom set
	                        com_type = '$com_type', com_num = '$com_num', com_name = '$com_name', com_owner = '$com_owner', com_post = '$com_post', com_address = '$com_address', com_kind = '$com_kind', com_class = '$com_class',
	                        com_tel = '$com_tel', com_fax = '$com_fax', com_bank = '$com_bank', com_account = '$com_account', com_homepage = '$com_homepage',
	                        charge_name = '$charge_name', charge_email = '$charge_email', charge_hand = '$charge_hand', charge_tel = '$charge_tel', descript = '$descript'
	                        where idx = '$idx'";

		$result = mysqli_query($connect, $sql) or error("거래처 정보를 수정하는중 에러가 발생하였습니다.");

		complete("거래처 정보가 저장되었습니다.", "shop_trade_input.php?sub_mode=update&idx=$idx");
	} else if ($sub_mode == "delete") {

		$sql = "delete from wiz_tradecom where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or error("거래처 삭제중 에러가 발생하였습니다.");

		complete("거래처가 삭제되었습니다.", "shop_trade.php");
	}


	// 쿠폰관리
} else if ($mode == "shop_coupon") {


	$couponimg_path = "../../images/coupon";
	if (!is_dir($couponimg_path)) mkdir($couponimg_path, 0707);	// 업로드 디렉토리 생성

	if ($idx) {
		$sql = "select * from wiz_coupon where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$coupon_info = mysqli_fetch_array($result);
		$tbl	= "wiz_coupon";
		$add_sql = " and idx='$idx'";
	} else if ($prdcode) {
		$sql = "select prdcode, concat('[상품쿠폰]', prdname) as coupon_name, coupon_sdate, coupon_edate, coupon_dis, coupon_type, coupon_amount, coupon_limit from wiz_product where prdcode = '$prdcode'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$coupon_info = mysqli_fetch_array($result);
		$tbl	= "wiz_product";
		$add_sql = " and prdcode='$prdcode'";
	}

	if ($sub_mode == "insert") {

		if ($coupon_img['size'] > 0) {
			file_check($coupon_img['name']);
			copy($coupon_img['tmp_name'], $couponimg_path . "/" . $coupon_img['name']);
			@chmod($couponimg_path . "/" . $coupon_img['name'], 0606);
		}

		$sql = "insert into wiz_coupon(idx, coupon_name, coupon_img, coupon_sdate, coupon_edate, coupon_amount, coupon_limit, coupon_dis, coupon_type, wdate)
												values('', '$coupon_name', '$coupon_img_name', '$coupon_sdate', '$coupon_edate', '$coupon_amount', '$coupon_limit', '$coupon_dis', '$coupon_type',now())";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("쿠폰이 생성되었습니다.", "shop_coupon.php");
	} else if ($sub_mode == "update") {
		if (!isset($coupon_img['size'])) $coupon_img['size'] = 0;
		if ($coupon_img['size'] > 0) {
			file_check($coupon_img['name']);
			@unlink($couponimg_path . "/" . $coupon_info['coupon_img']);
			copy($coupon_img['tmp_name'], $couponimg_path . "/" . $coupon_img['name']);
			chmod($couponimg_path . "/" . $coupon_img['name'], 0606);
			$coupon_img_sql = " coupon_img = '$coupon_img[name]', ";
		}
		if ($coupon_name) $coupon_name_sql = " coupon_name = '$coupon_name', ";
		if (!isset($coupon_img_sql)) $coupon_img_sql = "";
		if (!isset($coupon_limit)) $coupon_limit = "";

		$sql = "update $tbl set
		                $coupon_name_sql $coupon_img_sql coupon_sdate = '$coupon_sdate', coupon_edate = '$coupon_edate', coupon_amount = '$coupon_amount', coupon_limit = '$coupon_limit', coupon_dis = '$coupon_dis', coupon_type = '$coupon_type'
		                where 1" . $add_sql;
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("쿠폰이 수정되었습니다.", "shop_coupon_input.php?sub_mode=update&idx=$idx&prdcode=$prdcode");
	} else if ($sub_mode == "delete") {

		@unlink($couponimg_path . "/" . $coupon_info['coupon_img']);
		$sql = "delete from wiz_coupon where idx = '$idx'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("쿠폰이 삭제되었습니다.", "shop_coupon.php");
	}

	// 회원발급쿠폰 삭제
} else if (!strcmp($mode, "delmycoupon")) {

	$sql = "delete from wiz_mycoupon where idx = '$cidx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("해당 쿠폰을을 삭제하였습니다.", "shop_coupon_input.php?sub_mode=update&idx=$idx&prdcode=$prdcode");

	//상태 변경
} else if ($mode == "chmycoupon") {

	$sql = "select coupon_use from wiz_mycoupon where idx = '$cidx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	if ($row[coupon_use] == "N") $coupon_use = "Y";
	else $coupon_use = "N";

	$sql = "update wiz_mycoupon set coupon_use ='$coupon_use'  where idx = '$cidx' ";
	$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
	complete("쿠폰사용을 변경하였습니다..", "shop_coupon_input.php?sub_mode=update&idx=$idx&prdcode=$prdcode");

	// 쿠폰사용여부 설정
} else if ($mode == "coupon_use") {


	$sql = "update wiz_operinfo set coupon_use ='$coupon_use'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("쿠폰사용여부 설정이 저장되었습니다.", "shop_coupon.php");

	// 이메일 sms 설정
} else if ($mode == "mailsms") {

	if (!get_magic_quotes_gpc()) $content = addslashes($content);

	if ($sub_mode == "insert") {

		$content = str_replace("http://" . $HTTP_HOST, "{SHOP_URL}", $content);

		$sql = "insert into wiz_mailsms(code,subject,sms_cust,sms_oper,sms_msg,email_subj,email_cust,email_oper,email_msg)
							values('$code','$subject','$sms_cust','$sms_oper','$sms_msg','$email_subj','$email_cust','$email_oper','$content')";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("추가하였습니다.", "shop_mailsms_input.php?sub_mode=update&code=$code");
	} else if ($sub_mode == "update") {

		if (empty($sms_cust)) $sms_cust = "N";
		if (empty($sms_oper)) $sms_oper = "N";
		if (empty($email_cust)) $email_cust = "N";
		if (empty($email_oper)) $email_oper = "N";

		$content = str_replace("http://" . $HTTP_HOST, "{SHOP_URL}", $content);

		$sql = "update wiz_mailsms set subject = '$subject', sms_cust = '$sms_cust', sms_oper = '$sms_oper', sms_msg = '$sms_msg',
   	                     email_cust = '$email_cust', email_oper = '$email_oper', email_subj = '$email_subj', email_msg  = '$content' where code = '$code'";

		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("설정사항을 적용하였습니다.", "shop_mailsms_input.php?sub_mode=update&code=$code");
	} else if ($sub_mode == "delete") {

		$sql = "delete from wiz_mailsms where code = '$code'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("삭제 되었습니다.", "shop_mailsms.php");
	}



	// 관리자설정
} else if ($mode == "shop_admin") {

	if ($sub_mode == "insert") {

		$resno = $resno . "-" . $resno2;
		//$post = $post."-".$post2;
		$tphone = $tphone . "-" . $tphone2 . "-" . $tphone3;
		$hphone = $hphone . "-" . $hphone2 . "-" . $hphone3;

		for ($ii = 0; $ii < count($permi); $ii++) {
			$tmp_permi .= $permi[$ii] . "/";
		}

		$passwd = md5($passwd);

		$sql = "insert into wiz_admin(id, passwd, name, resno, email, tphone, hphone, post, address, address2, part, permi, last, wdate, descript)
                           values('$id', '$passwd', '$name', '$resno', '$email', '$tphone', '$hphone', '$post', '$address', '$address2', '$part', '$tmp_permi', '$last', now(), '$descript')";
		mysqli_query($connect, $sql) or error("이미 등록된 아이디 입니다.");

		complete("관리자가 추가되었습니다.", "shop_admin.php");
	} else if ($sub_mode == "update") {

		$resno = $resno . "-" . $resno2;
		//$post = $post."-".$post2;
		$tphone = $tphone . "-" . $tphone2 . "-" . $tphone3;
		$hphone = $hphone . "-" . $hphone2 . "-" . $hphone3;

		for ($ii = 0; $ii < count($permi); $ii++) {
			$tmp_permi .= $permi[$ii] . "/";
		}

		if ($passwd != "") {
			$passwd = md5($passwd);
			$passwd_sql = " passwd = '$passwd', ";
		}

		$sql = "update wiz_admin set
                     $passwd_sql name = '$name', resno = '$resno', email = '$email', tphone = '$tphone', hphone = '$hphone', post = '$post', address = '$address', address2 = '$address2', part='$part', permi='$tmp_permi', descript = '$descript' where id = '$id'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("관리자 정보가 수정되었습니다.", "shop_admin_input.php?sub_mode=update&id=$id");
	} else if ($sub_mode == "delete") {

		$sql = "select id from wiz_admin";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = mysqli_num_rows($result);

		if ($total <= 1) error("관리자계정이 하나밖에 없습니다. 삭제할 수 없습니다.");

		$sql = "delete from wiz_admin where id='$id'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("관리자 삭제되었습니다.", "shop_admin.php");
	} else if ($sub_mode == "logdel") {

		$sql = "delete from wiz_adminlog where admin_id='$admin_id'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		complete("로그가 삭제되었습니다.", "shop_admin_input.php?sub_mode=update&admin_id=$admin_id");
	}


	// 적립금 일괄적용
} else if ($mode == "setreserve") {

	$percent = $reserve_per / 100;

	$sql = "update wiz_product set reserve = sellprice * $percent";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "update wiz_operinfo set reserve_per ='$reserve_per'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("적립금 일괄적용 되었습니다.", "shop_oper.php");



	// sms충전
} else if ($mode == "smsfill") {

	$sms_id = "Any_" . $sms_id;
	$sql = "update wiz_operinfo set sms_type = '$sms_type', sms_id ='$sms_id', sms_pw ='$sms_pw'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("설정이 저장되었습니다.", "shop_smsfill.php");

	// 페이지 추가
} else if ($mode == "insert") {

	$sdate = $sdate_year . "-" . $sdate_month . "-" . $sdate_day;
	$edate = $edate_year . "-" . $edate_month . "-" . $sdate_day;

	$sql = "insert into wiz_content(idx,type,isuse,scroll,posi_x,posi_y,size_x,size_y,sdate,edate,linkurl,popup_type,title,content,wdate)
									values('','$type', '$isuse', '$scroll', '$posi_x', '$posi_y', '$size_x', '$size_y', '$sdate', '$edate', '$linkurl', '$popup_type', '$title', '$content',now())";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if ($type == "popup") complete("추가되었습니다.", "shop_popup.php");
	else complete("추가되었습니다.", "shop_content.php");

	// 페이지 수정
} else if ($mode == "update") {

	$sdate = $sdate_year . "-" . $sdate_month . "-" . $sdate_day;
	$edate = $edate_year . "-" . $edate_month . "-" . $edate_day;

	if (!empty($type)) $where_sql = " where type = '$type' and idx = '$idx'";
	else $where_sql = " where idx = '$idx'";

	$sql = "update wiz_content set isuse='$isuse', scroll='$scroll', posi_x='$posi_x', posi_y='$posi_y', size_x='$size_x', size_y='$size_y',
							sdate='$sdate', edate='$edate', linkurl='$linkurl', popup_type='$popup_type', title='$title', content='$content' $where_sql";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("수정되었습니다.", "");

	// 페이지 삭제
} else if ($mode == "delete") {

	$sql = "delete from wiz_content where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("삭제되었습니다.", "");
}
