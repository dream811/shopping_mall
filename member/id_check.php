<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
?>
<html>
<head>
<title>아이디 중복 체크</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/css/style.css">
<script language="JavaScript" src="/js/lib.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function inputCheck(){

	if(frm.id.value.length < 4 || frm.id.value.length > 12){
		alert("아이디는 4 ~ 12자리만 가능합니다.");
		frm.id.focus();
		return false;
	}else{
		if(!Check_Char(frm.id.value)){
			alert("아이디는 특수문자를 사용할수 없습니다.");
			frm.id.focus();
			return false;
		}
	}

}

// 아이디 입력폼으로 전송
function setId(){
	opener.frm.id.value = '<?=$id?>';
	opener.frm.passwd.focus();
	self.close();
}
//-->
</script>
</head>

<body onLoad="document.frm.id.focus();">
<?
$sql = "select id from wiz_member where id='$id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

$sql = "select id from wiz_admin where id = '$id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total2 = mysqli_num_rows($result);

$sql = "select designer_id from wiz_shopinfo where designer_id  = '$id' or anywiz_id = '".md5($id)."'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total3 = mysqli_num_rows($result);

$sql = "select id from wiz_mall where id = '$id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total4 = mysqli_num_rows($result);

if($id != ""){
	if($total > 0){
		$message = "<font color=#00BCBC><b>\"$id\"</b></font> 는 이미사용중인 아이디 입니다.";
	} else if($total2 + $total3 + $total4 > 0) {
		$message = "<font color=#00BCBC><b>\"".$id."\"</b></font> 는 사용할 수 없는 아이디 입니다.";
	} else{
		$message = "<font color=#00BCBC><b>\"$id\"</b></font> 는 사용가능한 아이디 입니다. <a href='javascript:setId();'><img src='/images/member/but_use.gif' align='absmiddle' border='0'></a>";
	}
}
?>
<style>
form{margin:0;}
.idchk_wrap{position:relative; width:100%; height:100%; border:6px solid #979797; box-sizing:border-box; padding:15px;}
.idchk_wrap .ttl{font-size:22px; font-weight:600; letter-spacing:-0.095rem; color:#333; margin:0; line-height:1.6;}
.idchk_wrap .ttl small{display:block; font-size:14px; font-weight:normal; color:#666; letter-spacing:-0.045rem;}
.idchk_wrap .close{position:absolute; right:10px; top:10px;}
.idchk_wrap .idsearch{background:#f5f5f5; padding:15px 0; text-align:center; letter-spacing:-0.045rem; margin:10px 0 0;}
.idchk_wrap .idsearch, .idchk_wrap .idsearch *{vertical-align:middle;}
.idchk_wrap .idsearch span{font-size:13px;}
.idchk_wrap .idsearch .search_input{width:60%; height:30px; line-height:28px; padding:0 0 0 10px; font-size:14px; background:#fff; border:1px solid #ccc; box-sizing:border-box;}
.idchk_wrap .idsearch input[type="submit"]{width:15%; height:30px; border:1px solid #777; border-radius:2px; background:#888; font-size:14px; color:#fff;}

.idchk_wrap .msg{padding:10px 0; line-height:1.6; font-size:14px; letter-spacing:-0.025rem; text-align:center; border-width:2px 0 1px; border-style:solid; border-color:#aaa #ddd #ddd; margin:10px 0 0;}
.idchk_wrap .msg b{letter-spacing:0;}
</style>

<div class="idchk_wrap">
	<div class="ttl">
		아이디 중복확인
		<small>띄어쓰기 없이 영문(숫자포함) 6~20자까지 사용 가능함.</small>
	</div>
	<a href="javascript:window.close();" class="close"><img src="/images/member/id_check_close.gif" border="0"></a>
	
	<div class="idsearch">
	<form name="frm" action="<?=$PHP_SELF?>" method="post" onSubmit="return inputCheck(this);">
		<span>아이디</span>
		<input type="text" name="id" class="search_input" style="width:188px;">
		<input type="submit" value="검색" />
    </form>
	</div><!-- .idsearch -->

	<div class="msg"><?=$message?></div>

</div><!-- .idchk_wrap -->
</body>
</html>