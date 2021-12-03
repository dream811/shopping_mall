<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc";		      // 유틸 라이브러리
include "../inc/oper_info.inc";			// 운영정보
include "../inc/login_check.inc";		// 로그인 체크

// 회원정보 수정
if($mode == "my_info"){

	$e_post 		= $post;
	$e_tphone 		= $tphone."-".$tphone2."-".$tphone3;
	$e_hphone 		= $hphone."-".$hphone2."-".$hphone3;
	$e_birthday 	= $birthday."-".$birthday2."-".$birthday3;
	$e_memorial 	= $memorial."-".$memorial2."-".$memorial3;

	$e_fax				= $fax."-".$fax2."-".$fax3;
	$e_com_pt 	= $com_post;

	for($ii=0; $ii<count($consph); $ii++){ $tmpconsph .= $consph[$ii]."/"; }
	for($ii=0; $ii<count($conprd); $ii++){ $tmpconprd .= $conprd[$ii]."/"; }

	$sql = "update wiz_member set ";

	if($passwd != "") $sql .= "passwd = md5('$passwd'), ";
	if($post != "") $sql .= "post = '$e_post', ";
	if($address != "") $sql .= "address = '$address', ";
	if($address2 != "") $sql .= "address2 = '$address2', ";
	if($tphone != "") $sql .= "tphone = '$e_tphone', ";
	if($hphone != "") $sql .= "hphone = '$e_hphone', ";
	if($fax != "") $sql .= "fax = '$e_fax', ";
	if($resms != "") $sql .= "resms = '$resms', ";
	if($email != "") $sql .= "email = '$email', ";
	if($reemail != "") $sql .= "reemail = '$reemail', ";
	if($birthday != "") $sql .= "birthday = '$e_birthday', ";
	if($bgubun != "") $sql .= "bgubun = '$bgubun', ";
	if($marriage != "") $sql .= "marriage = '$marriage', ";
	if($memorial != "") $sql .= "memorial = '$e_memorial', ";
	if($scholarship != "") $sql .= "scholarship = '$scholarship', ";
	if($job != "") $sql .= "job = '$job', ";
	if($income != "") $sql .= "income = '$income', ";
	if($car != "") $sql .= "car = '$car', ";
	if($consph != "") $sql .= "consph = '$tmpconsph', ";
	if($conprd != "") $sql .= "conprd = '$tmpconprd', ";

	if($com_num != "") $sql .= "com_num = '$com_num', ";
	if($com_name != "") $sql .= "com_name = '$com_name', ";
	if($com_owner != "") $sql .= "com_owner = '$com_owner', ";
	if($com_post != "") $sql .= "com_post = '$e_com_post', ";
	if($com_address != "") $sql .= "com_address = '$com_address', ";
	if($com_kind != "") $sql .= "com_kind = '$com_kind', ";
	if($com_class != "") $sql .= "com_class = '$com_class', ";

	$sql .= " visit_time = now() where id = '".$wiz_session['id']."'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$prev = "http://".$_http_host.$prev;

	comalert("수정되었습니다.", "");


// 비밀번호 변경
}else if($mode == "my_passwd"){

	$sql = "select id from wiz_member where passwd = '$pre_passwd'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	if($total > 0){

		$sql = "update wiz_member set passwd = '$passwd' where id = '".$wiz_session['id']."' and passwd = '$pre_passwd'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		comalert("비밀번호가 변경되었습니다.");

	}else{

		error("이전비밀번호가 일치하지 않습니다.");

	}



// 회원탈퇴
}else if($mode == "my_out"){

	// 회원테이블에서 삭제
	$sql = "delete from wiz_member where id = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 찜리스트 삭제
	$sql = "delete from wiz_wishlist where memid = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 적립금 삭제
	$sql = "delete from wiz_reserve where memid = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 주문내역 삭제(주문자 아이디를 [out] 으로 처리)
	$sql = "update wiz_order set send_id = '".$wiz_session['id']."[out]' where  send_id = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 나의 질문 삭제
	$sql = "delete from wiz_consult where memid = '".$wiz_session['id']."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 관리자에게 이메일발송
	if($out_reason != "") $reason .= $out_reason." , ";
	if($out_reason2 != "") $reason .= $out_reason2." , ";
	if($out_reason3 != "") $reason .= $out_reason3." , ";
	if($out_reason4 != "") $reason .= $out_reason4." , ";
	if($out_reason5 != "") $reason .= $out_reason5." , ";
	if($out_reason6 != "") $reason .= $out_reason6." , ";
	if($out_reason7 != "") $reason .= $out_reason7." , ";
	if($out_reason8 != "") $reason .= $out_reason8." , ";

	// 탈퇴내용 작성
	$sql = "insert into wiz_bbs(idx,code,memid,name,subject,content,ip,wdate) values('','memout','".$wiz_session['id']."','".$wiz_session['name']."','$reason','$message','$REMOTE_ADDR',unix_timestamp('".date('Y-m-d H:i:s')."'))";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 회원탈퇴 메일/SMS 발송
	$re_info['id'] = $wiz_session['id'];
	$re_info['pw'] = $wiz_session['passwd'];
	$re_info['name'] = $wiz_session['name'];
	$re_info['email'] = $wiz_session['email'];
	$re_info['hphone'] = $wiz_session['hphone'];
	send_mailsms("mem_out", $re_info);

	if(!empty($wiz_session['id'])){
	  //session_unregister("wiz_session");
		setcookie("wiz_session['id']", "", time()-3600, "/");
		setcookie("wiz_session['name']", "", time()-3600, "/");
		setcookie("wiz_session['email']", "", time()-3600, "/");
		setcookie("wiz_session['level']", "", time()-3600, "/");
		setcookie("wiz_session['permi']", "", time()-3600, "/");
	}

	comalert("회원탈퇴가 완료되었습니다.", "/");


// 관심상품 추가
}else if($mode == "my_wish"){

	if(empty($wiz_session['id'])) {
		error("로그인 후 이용해주세요.");
		exit;
	}

	if(!empty($idx)) {
		$sql = "select * from wiz_basket_tmp where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		$prdcode = $row['prdcode'];

		$optcode = $row['optcode'];
		$opttitle = $row['opttitle'];
		$optcode2 = $row['optcode2'];
		$opttitle2 = $row['opttitle2'];
		$optcode3 = $row['optcode3'];
		$opttitle3 = $row['opttitle3'];
		$optcode4 = $row['optcode4'];
		$opttitle4 = $row['opttitle4'];
		$optcode5 = $row['optcode5'];
		$opttitle5 = $row['opttitle5'];
		$optcode6 = $row['optcode6'];
		$opttitle6 = $row['opttitle6'];
		$optcode7 = $row['optcode7'];
		$opttitle7 = $row['opttitle7'];
	}

	$sql = "select idx from wiz_wishlist where memid = '".$wiz_session['id']."' and prdcode = '$prdcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	if($total > 0 ){
		error("이미 등록한 관심상품 입니다.");
	}
	else{

		$sql = "insert into wiz_wishlist(idx,memid,prdcode,opttitle,optcode,opttitle2,optcode2,opttitle3,optcode3
						,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,amount,wdate)
						values('', '".$wiz_session['id']."', '$prdcode', '$opttitle','$optcode','$opttitle2','$optcode2'
						,'$opttitle3','$optcode3','$opttitle4','$optcode4','$opttitle5','$optcode5'
						,'$opttitle6','$optcode6','$opttitle7','$optcode7','$amount', now())";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		comalert("관심상품에 추가하였습니다.", $HTTP_REFERER);
	}

// 관심상품 삭제
}else if($mode == "my_wishdel"){

	if(!empty($selprd)) {

		$tmp_prd = explode("|", $selprd);

		for($ii = 0; $ii < count($tmp_prd); $ii++) {

			$idx = $tmp_prd[$ii];

			$sql = "delete from wiz_wishlist where memid = '".$wiz_session['id']."' and idx = '$idx'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		}

	} else {

		$sql = "delete from wiz_wishlist where memid = '".$wiz_session['id']."' and idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	}

	comalert("관심상품에서 삭제되었습니다.", $HTTP_REFERER);

// 1:1 qna
}else if($mode == "my_qna"){


	if($sub_mode == "insert"){

		$sql = "insert into wiz_consult(idx,memid,name,subject,question,answer,wdate,status) values('', '".$wiz_session['id']."', '".$wiz_session['name']."', '$subject', '$question', '$answer', now(), 'N')";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		Header("Location: my_qna.php");

	}else if($sub_mode == "modify"){

		$sql = "update wiz_consult set subject = '$subject', question = '$question' where memid = '".$wiz_session['id']."' and idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		comalert("수정 되었습니다.", "my_qnaview.php?idx=$idx");

	}else if($sub_mode == "delete"){

		$sql = "delete from wiz_consult where memid = '".$wiz_session['id']."' and idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		comalert("삭제 되었습니다.", "my_qna.php");

	}


// 관심상품등록/삭제
}else if($mode == "my_wish_ajax"){

	$sql	= "select idx from wiz_wishlist where memid = '".$wiz_session['id']."' and prdcode = '$prdcode'";
	$result	= mysqli_query($connect, $sql);
	$row	= mysqli_fetch_assoc($result);
	if($row['idx']){
		$sql	= "delete from wiz_wishlist where memid = '".$wiz_session['id']."' and idx = '".$row['idx']."'";
		mysqli_query($connect, $sql);
		$result	= 'delete';
	}
	else{
		$result	= 'insert';
	}
	echo $result;
}
?>