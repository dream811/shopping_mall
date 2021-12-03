<?

include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc";			// 운영정보
include "../inc/util.inc";		      // 유틸라이브러리

$type = "join";
$sql = "select * from wiz_page where type='$type'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$page_info = mysqli_fetch_object($result);

// 입력정보 사용여부
$info_tmp = explode("/",$page_info->addinfo);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_use[$info_tmp[$ii]] = true;
}

// 입력정보 필수여부
$info_tmp = explode("/",$page_info->addinfo2);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_ess[$info_tmp[$ii]] = true;
}

// 스팸글 차단
$pos = strpos($HTTP_REFERER, $_http_host);
if($pos === false) error("잘못된 경로 입니다.");

if($info_use[spam] == true){
  // 자동등록방지 코드 검사
  if(empty($_POST[tmp_vcode]) || empty($_POST[vcode])) {
  	error("자동등록방지 코드가 존재하지 않습니다.");
  } else if(strcmp($_POST[tmp_vcode], md5($_POST[vcode]))) {
  	error("자동등록방지 코드가 일치하지 않습니다.");
  }
}

$resno 		= $resno."-".$resno2;
//$post 		= $post."-".$post2;
$tphone 		= $tphone."-".$tphone2."-".$tphone3;
$hphone 		= $hphone."-".$hphone2."-".$hphone3;
$fax		 		= $fax."-".$fax2."-".$fax3;
$birthday 	= $birthday."-".$birthday2."-".$birthday3;
$memorial 	= $memorial."-".$memorial2."-".$memorial3;
//$com_post 	= $com_post."-".$com_post2;

for($ii=0; $ii<count($consph); $ii++){ $tmpconsph .= $consph[$ii]."/"; }
for($ii=0; $ii<count($conprd); $ii++){ $tmpconprd .= $conprd[$ii]."/"; }

if($_POST['id'] == "") error("필요한 정보가 전달되지 않았습니다.");
if($_POST[passwd] == "") error("필요한 정보가 전달되지 않았습니다.");
if($_POST[name] == "") error("필요한 정보가 전달되지 않았습니다.");



// 주민번호 중복체크
if($resno != "-"){
$sql = "select id from wiz_member where resno = '$resno' and resno != ''";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
if($total > 0) error("이미등록된 주민번호 입니다.\\n\\n회원가입하신적이 없다면 문의하시기 바랍니다.");
}

// 가입레벨(가장낮은 레벨)
$sql = "select idx from wiz_level order by level desc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$level = $row->idx;

$passwd = md5($passwd);

// 입력정보 저장
$sql = "insert into wiz_member(id,passwd,name,resno,email,tphone,hphone,fax,post,address,address2,reemail,resms,birthday,bgubun,marriage,memorial,
									scholarship,job,income,car,consph,conprd,level,recom,visit,visit_time,comment,com_num,com_name,com_owner,com_post,com_address,com_kind,com_class,wdate)
									values('$id', '$passwd', '$name', '$resno', '$email', '$tphone', '$hphone', '$fax', '$post', '$address', '$address2', '$reemail', '$resms',
									'$birthday', '$bgubun', '$marriage', '$memorial', '$scholarship', '$job', '$income', '$car', '$tmpconsph', '$tmpconprd',
									'$level', '$recom', '$visit', '$visit_time', '$comment','$com_num','$com_name','$com_owner','$com_post','$com_address','$com_kind','$com_class', now())";

mysqli_query($connect, $sql) or die(mysqli_error($connect));



// 적립금 처리
if($oper_info->reserve_use == "Y"){

   // 회원가입 적립테이블에 저장
   if($oper_info->reserve_join > 0){

      $reserve_msg = "회원가입 적립금";

      $sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$id', '$reserve_msg', '$oper_info->reserve_join', '$orderid', now())";
      mysqli_query($connect, $sql) or die(mysqli_error($connect));

   }

   // 추천인 적립금 저장
   if($oper_info->reserve_recom > 0 && !empty($recom) && $id != $recom){

      $reserve_msg = "[$id] 가입시 추천인 적립금";

      $sql = "insert into wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) values('', '$recom', '$reserve_msg', '$oper_info->reserve_recom', '$orderid', now())";
      mysqli_query($connect, $sql) or die(mysqli_error($connect));

   }

}

// 회원가입 축하 메일/SMS 발송
$re_info['id'] = $id;
$re_info[pw] = $passwd;
$re_info[name] = $name;
$re_info[email] = $email;
$re_info[hphone] = $hphone;
send_mailsms("mem_apply", $re_info);

Header("Location: http://".$_http_host."/member/join_ok.php");

?>

