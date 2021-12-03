<?
include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc";
include_once "$_SERVER[DOCUMENT_ROOT]/inc/util.inc";
include_once "$_SERVER[DOCUMENT_ROOT]/inc/shop_info.inc";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($wiz_admin['id']) && $wiz_admin['id'] != ""){
echo "<script>document.location='./main/main.php';</script>";
exit;
}
if (!isset($ssl)) $ssl="";
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>:: 쇼핑몰 관리자 ::</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){

   if(frm.admin_id.value == ""){
      alert("관리자 아이디를 입력하세요");
      frm.admin_id.focus();
      return false;
   }

   if(frm.admin_pw.value == ""){
      alert("관리자 비밀번호를 입력하세요");
      frm.admin_pw.focus();
      return false;
   }

	if(frm.secure_login != undefined) {
		if(!frm.secure_login.checked){
			frm.action = "/admin/admin_check.php";
		}
	}
}

function loginFocus(){

   var frm = document.frm;
   var admin_id = frm.admin_id.value;
   var admin_pw = frm.admin_pw.value;

   if(admin_id == ""){
      frm.admin_id.focus();
   }else{
      if(admin_pw == "") frm.admin_pw.focus();
   }
}
-->
</script>
<style>

</style>
</head>

<body onLoad="loginFocus();" class="AW-admin-loginbody">

<form name="frm" action="<?=$ssl?>/admin/admin_check.php" method="post" onSubmit="return inputCheck(this);">
<div class="AW-admin-login_wrap">
	<div class="tit">
    	<strong><span>ADMIN</span> LOGIN</strong>
        <p>
        	<b>웹 사이트 운영을 위한 관리자 모드입니다.</b>
            <small>아이디와 패스워드를 입력하신 후 로그인해 주시기 바랍니다.</small>
        </p>
    </div><!-- .tit -->
    <div class="form">
    	<dl>
        	<dt style="text-align:right; float:right; width:64px;">ID</dt>
            <dd><input type="text" name="admin_id" value="admin" placeholder="아이디를 입력하세요" /></dd>
        </dl>
    	<dl>
        	<dt>PASSWORD</dt>
            <dd><input type="password" name="admin_pw" value="1234" placeholder="비밀번호를 입력하세요" /></dd>
        </dl>
        <?=$hide_ssl_start?>
        <div class="secure"><label><input type="checkbox" name="secure_login" value="Y" checked> 보안접속</label></div>
		<?=$hide_ssl_end?>
        <div class="AW-btn-wrap"><button type="submit" class="on">로그인</button></div>
    </div><!-- .form -->
    <div class="help"><a href="http://anywiz.co.kr" target="_blank">로그인 문제가 발생할 경우 <i><img src="http://anywiz.co.kr/img/common/logo.png" /></i></a></div>
</div><!-- .AW-admin-login_wrap -->
</form>

</body>
</html>