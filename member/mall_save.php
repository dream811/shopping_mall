<?

include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc";			// 운영정보
include "../inc/util.inc";		      // 유틸라이브러리

// 스팸글 차단
$pos = strpos($HTTP_REFERER, $_http_host);
if($pos === false) error("잘못된 경로 입니다.");

// 자동등록방지 코드 검사
if(empty($_POST[tmp_vcode]) || empty($_POST[vcode])) {
	error("자동등록방지 코드가 존재하지 않습니다.");
} else if(strcmp($_POST[tmp_vcode], md5($_POST[vcode]))) {
	error("자동등록방지 코드가 일치하지 않습니다.");
}

if($_POST['id'] == "") error("필요한 정보가 전달되지 않았습니다.");
if($_POST[passwd] == "") error("필요한 정보가 전달되지 않았습니다.");
if($_POST[com_name] == "") error("필요한 정보가 전달되지 않았습니다.");

$com_tel 	= $com_tel."-".$com_tel2."-".$com_tel3;
$com_hp 	= $com_hp."-".$com_hp2."-".$com_hp3;
$com_fax 	= $com_fax."-".$com_fax2."-".$com_fax3;

//$com_post = $com_post."-".$com_post2;

$cms_type = "C";								// 수수료

// 승인상태
if(!strcmp($oper_info->mall_join, "N")) $status = "Y";
else $status = "N";

// 입력정보 저장
$sql = "insert into wiz_mall (id,passwd,com_name,com_owner,com_num,com_kind,com_class,com_tel,com_hp,com_fax,acc_name
										,acc_bank,acc_num,manager,email,homepage,post,address,address2,comment,cms_type,
										cms_rate,status,wdate,adate,last)
										values('$id','$passwd','$com_name','$com_owner','$com_num','$com_kind','$com_class','$com_tel',
										'$com_hp','$com_fax','$acc_name','$acc_bank','$acc_num','$manager','$email','$homepage',
										'$post','$address','$address2','$comment','$cms_type','$cms_rate','$status',
										now(),'$adate','$last')";

mysqli_query($connect, $sql) or die(mysqli_error($connect));

// 회원가입 축하 메일/SMS 발송
$re_info['id'] = $id;
$re_info[pw] = $passwd;
$re_info[name] = $name;
$re_info[email] = $email;
$re_info[hphone] = $com_hp;
send_mailsms("mall_apply", $re_info);

Header("Location: http://".$_http_host."/member/mall_ok.php");

?>

