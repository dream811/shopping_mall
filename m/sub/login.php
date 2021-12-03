<?
$sub_tit="로그인";

if($prev == "" && strpos($_SERVER['HTTP_REFERER'],"/member/")===false) $prev = $_SERVER['HTTP_REFERER'];
else $prev = "http://".$_SERVER['HTTP_HOST'].$prev;
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.id.value == ""){
		alert("아이디를 입력하세요");
		frm.id.focus();
		return false;
	}
	if(frm.passwd.value == ""){
		alert("비밀번호를 입력하세요");
		frm.passwd.focus();
		return false;
	}

	if(frm.secure_login != undefined) {
		if(!frm.secure_login.checked){
			frm.action = "/member/login_check.php";
		}
	}
}

function orderCheck(frm){
	if(frm.send_name.value == ""){
		alert("주문자명을 입력하세요");
		frm.send_name.focus();
		return false;
	}
	if(frm.orderid.value == ""){
		alert("주문번호를 입력하세요");
		frm.orderid.focus();
		return false;
	}
}
-->
</script>


<div class="login_ttl">
    <h2>로그인</h2>
    <span>위즈몰에 오신 것을 환영합니다.</span>
</div>

<form name="frm" action="<?=$ssl?>/member/login_check.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="prev" value="<?=$prev?>">
<div class="login_form">
    <div class="input">
        <label for="id">아이디</label>
        <input name="id" type="text" id="id" value="" placeholder="아이디를 입력해주세요." onFocus="this.value=''" tabindex="1" />
    </div>
    <div class="input">
        <label for="pw">비밀번호</label>
        <input name="passwd" type="password" id="pw" value="" placeholder="비밀번호를 입력해주세요." onFocus="this.value=''" tabindex="2" />
    </div>
    <div class="option">
        <input type="checkbox" value="save" id="id_save" /> <label for="id_save">아이디 저장</label>
        <?=$hide_ssl_start?>
        <input type="checkbox" name="secure_login" value="Y" id="secure" checked> <label for="secure">보안접속</label>
        <?=$hide_ssl_end?>
    </div><!-- .option -->
    <div class="button"><button type="submit">로그인</button></div>
</div><!-- .login_form -->
</form>


<? if($order == "true"){	// 비회원 주문 ?>
<div class="guest_btn">
	<button type="submit" onclick="goURL('order_form.php?order_guest=true')">비회원 구매</button>
</div><!-- .guest_btn -->
<? } ?>


<? if($orderlist == "true"){	// 비회원 구매조회 ?>
<div class="order_guest">
    <div class="tit">비회원 구매조회</div>
    <div class="form">
    <form action="orderlist.php" method="get" onSubmit="return orderCheck(this);">
    <input type="hidden" name="order_guest" value="true">
    	<input type="text" name="send_name" value="주문자" onFocus="this.value=''" />
        <input type="text" name="orderid" value="주문번호" onFocus="this.value=''" />
        <button type="submit">조회</button>
    </form>
    </div><!-- .form -->
</div><!-- .order_guest -->
<? } ?>

<div class="login_button">
	<a href="/m/sub/join_agree.php">위즈몰 회원가입</a>
</div><!-- .lobin_button -->

<div class="gry_bar"></div>
<? include "../inc/footer.php" ?>
