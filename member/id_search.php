<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc";		      // util 라이브러리
include "../inc/oper_info.inc";			// 운영정보
include "../inc/shop_info.inc"; 		// 상점 정보

$id = trim($id);
$name = trim($name);
$email = trim($email);
if($mode == "id"){

	$sql = "select id,passwd,name,email,hphone from wiz_member where name = '$name' and email = '$email'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if($row = mysqli_fetch_object($result)){

		$re_info['id'] = $row->id;
		$re_info[pw] = $row->passwd;
		$re_info[name] = $row->name;
		$re_info[email] = $row->email;
		$re_info[hphone] = $row->hphone;
		send_mailsms("mem_id", $re_info);

		comalert("회원님의 아이디를 이메일($email)로 보내드렸습니다.", "http://".$_http_host.$PHP_SELF);

	}else{
		error("회원정보가 일치하지 않습니다.");
	}


}else if($mode == "passwd"){

	$sql = "select id,passwd,name,email,hphone from wiz_member where id = '$id' and name = '$name' and email = '$email'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if($row = mysqli_fetch_object($result)){

		$passwd = substr(md5(time()),0,6);
		$sql = "update wiz_member set passwd = '".md5($passwd)."' where id = '$id'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$re_info['id'] = $row->id;
		$re_info[pw] = $passwd;
		$re_info[name] = $row->name;
		$re_info[email] = $row->email;
		$re_info[hphone] = $row->hphone;
		send_mailsms("mem_pw", $re_info);

		comalert("회원님의 비밀번호를 이메일($email)로 보내드렸습니다.", "http://".$_http_host.$PHP_SELF);

	}else{
		error("회원정보가 일치하지 않습니다.");
	}

}
$now_position = "<a href=/>Home</a> &gt; <strong>아이디 / 비밀번호 찾기</strong>";
$page_type = "login";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
?>
<script language="JavaScript">
<!--
function idSearch(frm){
	if(frm.name.value == ""){
		alert("이름을 입력하세요");
		frm.name.focus();
		return false;
	}
	if(frm.email.value == ""){
		alert("이메일을 입력하세요");
		frm.email.focus();
		return false;
	}
}
function pwSearch(frm){
	if(frm.id.value == ""){
		alert("아이디를 입력하세요");
		frm.id.focus();
		return false;
	}
	if(frm.name.value == ""){
		alert("이름을 입력하세요");
		frm.name.focus();
		return false;
	}
	if(frm.email.value == ""){
		alert("이메일을 입력하세요");
		frm.email.focus();
		return false;
	}
}
-->
</script>

<div class="AW_ttl clearfix">
	<h2>아이디 &#183; 비밀번호 찾기</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>아이디 &#183; 비밀번호 찾기</span>
	</div>
</div>
<form name="frm" action="<?=$ssl?><?=$PHP_SELF?>" method="post" onSubmit="return idSearch(this);">
															<input type="hidden" name="mode" value="id">
<div class="AW_id_search_wrap"><div class="inner">
	<div class="tit">
    	아이디 찾기
        <small>회원님의 이름과 가입시 작성하신 이메일 주소를 입력해주세요.</small>
    </div><!-- .tit -->
    <div class="form">
    	<input name="name" type="text" placeholder="이름" />
    	<input name="email" type="text" / placeholder="이메일">
        <button type="submit">아이디 찾기</button>
    </div><!-- .form -->
</div></div><!-- .AW_id_search_wrap -->
</form>




<form name="frm" action="<?=$ssl?><?=$PHP_SELF?>" method="post" onSubmit="return pwSearch(this);">
                                <input type="hidden" name="mode" value="passwd">
<div class="AW_id_search_wrap pw"><div class="inner">
	<div class="tit">
    	비밀번호 찾기
        <small>회원님의 아이디와 이름, 가입시 작성하신 이메일 주소를 입력해주세요.</small>
    </div><!-- .tit -->
    <div class="form">
    	<input name="id" type="text" placeholder="아이디" />
    	<input name="name" type="text" placeholder="이름" />
    	<input name="email" type="text" placeholder="이메일" />
        <button type="submit">비밀번호 찾기</button>
    </div><!-- .form -->
</div></div><!-- .AW_id_search_wrap -->
</form>



<?
include "../inc/footer.inc"; 		// 하단디자인
?>