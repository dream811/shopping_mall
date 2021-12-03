<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/shop_info.inc"; 		// 상점 정보

$now_position = "<a href=/>Home</a> &gt; <strong>로그인</strong>";
$page_type = "login";
include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인
$HTTP_PORT = $_SERVER['SERVER_PORT'] ? ':'.$_SERVER['SERVER_PORT'] : '';
$HTTP_REFERER = $_SERVER['HTTP_REFERER'];
if(!isset($prev)) $prev = "";
if(!isset($ssl)) $ssl = "";
if($prev == "" && strpos($HTTP_REFERER,"/member/")===false) $prev = $HTTP_REFERER;
else $prev = "http://".$HTTP_HOST;
?>
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

<div class="AW_ttl clearfix">
	<h2>로그인</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>로그인</span>
	</div>
</div>
<form name="frm" action="<?=$ssl?>/member/login_check.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="prev" value="<?=$prev?>">
<div class="AW_login_wrap"><div class="inner">

	<div class="tit">로그인</div>
    
    <div class="form">
    	<input name="id" value="test" type="text" placeholder="아이디" />
    	<input name="passwd" value="1234"  type="password" placeholder="비밀번호" />
        <button type="submit">로그인</button>
    </div><!-- .form -->
    
    <div class="secure">
    	<label><input type="checkbox" /> 아이디 저장</label>
		<?=$hide_ssl_start?>
        <label><input type="checkbox" name="secure_login" value="Y" checked> 보안접속</label>
        <?=$hide_ssl_end?>
    </div><!-- .secure -->
    
    <div class="btn_link">
    	<a href="/member/join.php">회원가입</a>
    	<a href="/member/id_search.php">아이디/비밀번호 찾기</a>
    </div><!-- .btn_link -->
    
</div></div><!-- .AW_login_wrap -->
</form>




<? if($order == "true"){  //비회원 주문?>
<div class="non_member">
	<a href="/shop/order_form.php?order_guest=true">비회원으로 구매하기</a>
</div><!-- .non_member -->
<? } ?>




<? if($orderlist == "true"){  // 주문배송조회 ?>
<form action="/shop/order_list.php" method="get" onSubmit="return orderCheck(this);">
<input type="hidden" name="order_guest" value="true">
<div class="order_check_wrap"><div class="inner">
	
    <div class="tit">
    	비회원으로 조회
        <small>비회원으로 구매하신 분은 받으신 주문번호를 입력하여 주시기 바랍니다.</small>
    </div><!-- .tit -->
    
    <div class="form">
    	<input name="send_name" type="text" placeholder="주문자명" />
        <input name="orderid" type="text" placeholder="주문번호" />
        <button type="submit">비회원 주문조회</button>
    </div><!-- .form -->
    
</div></div><!-- .order_check_wrap -->
</form>
<? } ?>


<?
include "../inc/footer.inc"; 		// 하단디자인
?>