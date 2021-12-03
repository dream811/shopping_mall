<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&searchkey=$searchkey&s_birthday=$s_birthday&s_memorial=$s_memorial&s_age=$s_age";
$param .= "&s_address=$s_address&s_job=$s_job&s_marriage=$s_marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day&page=$page";
//------------------------------------------------------------------------------------------------------------------------------------

// 회원등록
if($mode == "insert"){

   $resno 		= $resno."-".$resno2;
   //$post 		= $post."-".$post2;
   $tphone 		= $tphone."-".$tphone2."-".$tphone3;
   $hphone 		= $hphone."-".$hphone2."-".$hphone3;
   $fax				= $fax."-".$fax2."-".$fax3;
   $birthday 	= $birthday."-".$birthday2."-".$birthday3;
   $memorial 	= $memorial."-".$memorial2."-".$memorial3;
   //$com_post	= $com_post."-".$com_post2;

   for($ii=0; $ii<count($consph); $ii++){ $tmpconsph .= $consph[$ii]."/"; }
   for($ii=0; $ii<count($conprd); $ii++){ $tmpconprd .= $conprd[$ii]."/"; }

	//$passwd = md5($passwd);

   $sql = "insert into wiz_member(id,passwd,name,resno,email,tphone,hphone,fax,post,address,address2,reemail,resms,birthday,bgubun,
   								marriage,memorial,scholarship,job,income,car,consph,conprd,level,recom,visit,visit_time,comment,com_num,com_name,com_owner,com_post,com_address,com_kind,com_class,wdate)
									values('$id', '$passwd', '$name', '$resno', '$email', '$tphone', '$hphone', '$fax', '$post', '$address', '$address2', '$reemail', '$resms',
									'$birthday', '$bgubun', '$marriage', '$memorial', '$scholarship', '$job', '$income', '$car', '$tmpconsph', '$tmpconprd',
									'$level', '$recom', '$visit', '$visit_time', '$comment','$com_num','$com_name','$com_owner','$com_post','$com_address','$com_kind','$com_class', now())";

   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

   complete("회원을 등록하였습니다.","member_list.php?$param");



// 회원정보 수정
}else if($mode == "update"){

   $resno = $resno."-".$resno2;
   //$post = $post."-".$post2;
   $tphone = $tphone."-".$tphone2."-".$tphone3;
   $hphone = $hphone."-".$hphone2."-".$hphone3;
   $fax				= $fax."-".$fax2."-".$fax3;

   $birthday2 = substr("0".$birthday2,-2);
   $birthday3 = substr("0".$birthday3,-2);
   $memorial2 = substr("0".$memorial2,-2);
   $memorial3 = substr("0".$memorial3,-2);

   $birthday = $birthday."-".$birthday2."-".$birthday3;
   $memorial = $memorial."-".$memorial2."-".$memorial3;


   for($ii=0; $ii<count($consph); $ii++){
      $tmpconsph .= $consph[$ii]."/";
   }
   for($ii=0; $ii<count($conprd); $ii++){
      $tmpconprd .= $conprd[$ii]."/";
   }

   //$com_post	= $com_post."-".$com_post2;
   
	if($passwd != "") {
		$passwd = md5($passwd);
		$passwd_sql = " passwd = '$passwd', ";
	}

   $sql = "update wiz_member set
                     $passwd_sql name = '$name', resno = '$resno', email = '$email', tphone = '$tphone', hphone = '$hphone', fax = '$fax', post = '$post', address = '$address', address2 = '$address2', reemail = '$reemail', resms = '$resms',
                     birthday = '$birthday', bgubun = '$bgubun', marriage = '$marriage', memorial = '$memorial', scholarship = '$scholarship', job = '$job', income = '$income', car = '$car', consph = '$tmpconsph', conprd = '$tmpconprd',
                     recom = '$recom', visit = '$visit', level = '$level', comment = '$comment', com_num = '$com_num', com_name = '$com_name', com_owner = '$com_owner', com_post = '$com_post', com_address = '$com_address', com_kind = '$com_kind', com_class = '$com_class' where id = '$id'";

   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

   complete("회원정보를 수정하였습니다.","member_info.php?mode=$mode&id=$id&$param");



// 회원 삭제
}else if($mode == "deluser"){

	$array_seluser = explode("|",$seluser);
	$i=0;
	while($array_seluser[$i]){

		$mem_id = $array_seluser[$i];

		// 회원테이블에서 삭제
	   $sql = "delete from wiz_member where id = '$mem_id'";
	   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	   // 찜리스트 삭제
	   $sql = "delete from wiz_wishlist where memid = '$mem_id'";
	   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	   // 적립금 삭제
	   $sql = "delete from wiz_reserve where memid = '$mem_id'";
	   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		// 주문내역 삭제(주문자 아이디를 [out] 으로 처리)
		$sql = "update wiz_order set send_id = '".$mem_id."[out]' where  send_id = '$mem_id'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));


		$i++;
	}

	complete("회원을 삭제하였습니다.","member_list.php?$param");



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



// 회원이메일 발송
}else if($mode == "mememail"){

	global $DOCUMENT_ROOT;

	// 관리자 정보 가져오기
	include "$DOCUMENT_ROOT/inc/shop_info.inc";
	$se_name = $shop_info->shop_name;
	$se_email = $shop_info->shop_email;
	$re_name = $shop_info->shop_name;
	$re_email = $shop_info->shop_email;

	$content = str_replace("\\","",$content);


	$no = 0;
	
	$today = date('n-d');
	$toyear = date('Y');

	$age_syear = substr($toyear-($age+9),-2)+1;
	$age_eyear = substr($toyear-$age,-2)+2;

	$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
	$join_edate = $next_year."-".$next_month."-".$next_day;

	$sql = "select id,passwd,name,hphone,email,visit,reemail,wdate from wiz_member where 1 ";
	if($level != "") 		$sql .= " and level = '$level'";
	if($prev_year != "") $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
	if($keyword != "") $sql .= " and $searchopt like '%$keyword%'";
	if($birthday == "Y") $sql .= " and birthday like '%$today'";
	if($memorial == "Y") $sql .= " and memorial like '%$today'";
	if($age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
	if($address != "")   $sql .= " and address like '%$address%'";
	if($job != "")       $sql .= " and job = '$job'";
	if($marriage != "")  $sql .= " and marriage = '$marriage'";
	if($remail == "RJ" || $remail == "")		$sql .= " and reemail != 'N'";
			
	$snum--;
	$amount = $enum - $snum;
	$sql = $sql." limit $snum, $amount";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_object($result)){

		$content = stripslashes($content);
		send_mail($se_name, $se_email, $row->name, $row->email, $subject, $content);
		echo "<font size=2><b>$row->name : </b> $row->email</font> <br>";

	$no++;

	}

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "<br><font color='red' size=2><b>발송을 완료하였습니다.</b></font>";
	echo "<a href=javascript:window.close()><font size=2 color=black><b>[ 닫기 ]</b></font></a>";

// 회원SMS 발송
}else if($mode == "memsms"){


	include_once "$DOCUMENT_ROOT/inc/oper_info.inc";

	$today = date('n-d');
	$toyear = date('Y');

	$age_syear = substr($toyear-($age+9),-2)+1;
	$age_eyear = substr($toyear-$age,-2)+2;

	$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
	$join_edate = $next_year."-".$next_month."-".$next_day;

	$sql = "select id,hphone from wiz_member where id != '' ";
	if($level != "") 		$sql .= " and level = '$level'";
	if($prev_year != "") $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
	if($searchopt != "") $sql .= " and $searchopt like '%$keyword%'";
	if($birthday == "Y") $sql .= " and birthday like '%$today'";
	if($memorial == "Y") $sql .= " and memorial like '%$today'";
	if($age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
	if($address != "")   $sql .= " and address like '%$address%'";
	if($job != "")       $sql .= " and job = '$job'";
	if($marriage != "")  $sql .= " and marriage = '$marriage'";
	if($resms == "RJ" || $resms == "")	$sql .= " and resms != 'N'";
	$sql .=" order by wdate desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	while($row = mysqli_fetch_object($result)){
		if($row->hphone != "") send_sms($se_num, $row->hphone, $sms_msg);
	}

	comalert("SMS 발송을 완료하였습니다.","");


//상담작성
}else if($mode == "consult"){

   $sql = "update wiz_consult set question = '$question', answer = '$answer', status = 'Y' where idx = '$idx'";

   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

   complete("상담작성을 완료하였습니다.","member_qna.php");



// 상담삭제
}else if($mode == "condelete"){

	$array_selconsult = explode("|",$selconsult);

	$i=0;
	while($array_selconsult[$i]){
		$idx = $array_selconsult[$i];
		$sql = "delete from wiz_consult where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$i++;
	}

	complete("선택한 상담을 삭제 하였습니다.","member_qna.php");




// 각 회원별 적립금 적용
}else if($mode == "reserve"){

	$memid = $_POST[memid];
	$reservemsg = $_POST[reservemsg];
	$reserve = $_POST[reserve_gubun].$_POST[reserve];

	$sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$memid', '$reservemsg', '$reserve', '$orderid', now())";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("적립금을 적용하였습니다.","member_reserve.php?id=$memid&name=$name");

// 각 회원별 적립금 삭제
}else if($mode == "delreserve"){

	$sql = "delete from wiz_reserve where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("해당 적립내역을 삭제하였습니다.","member_reserve.php?id=$memid&name=$name");

// 회원탈퇴 삭제
}else if($mode == "memoutdel"){

	$sql = "delete from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("탈퇴내역을 삭제하였습니다.","member_out.php");



}

?>