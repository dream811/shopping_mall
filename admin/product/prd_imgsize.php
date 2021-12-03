<?
include "../../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../../inc/oper_info.inc";
if($save == ""){
?>
<html>
<head>
<title>상품이미지 사이즈</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.prdimg_R.value == ""){
		alert("대표 이미지 사이즈를 입력하세요");
		frm.prdimg_R.focus();
		return false;
	}
	if(frm.prdimg_S.value == ""){
		alert("축소 이미지 사이즈를 입력하세요");
		frm.prdimg_S.focus();
		return false;
	}
	if(frm.prdimg_M.value == ""){
		alert("제품상세 이미지 사이즈를 입력하세요");
		frm.prdimg_M.focus();
		return false;
	}
	if(frm.prdimg_L.value == ""){
		alert("확대 이미지 사이즈를 입력하세요");
		frm.prdimg_L.focus();
		return false;
	}
}
//-->
</script>
</head>

<body style="padding:10px;">

<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품이미지 사이즈</td>
  </tr>
</table>
<form name="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
<input type="hidden" name="save" value="true">
<table width="100%" align="center" border="0" cellpadding=3 cellspacing=1 class="t_style">
  <tr>
    <td width=50% height=25 class="t_name">&nbsp; 대표 이미지</td>
    <td width=50% class="t_value"><input type="text" name="prdimg_R" value="<?=$oper_info->prdimg_R?>" size="6" class="input"></td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 축소 이미지</td>
    <td class="t_value"><input type="text" name="prdimg_S" value="<?=$oper_info->prdimg_S?>" size="6" class="input"></td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 제품상세 이미지</td>
    <td class="t_value"><input type="text" name="prdimg_M" value="<?=$oper_info->prdimg_M?>" size="6" class="input"></td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 확대 이미지</td>
    <td class="t_value"><input type="text" name="prdimg_L" value="<?=$oper_info->prdimg_L?>" size="6" class="input"></td>
  </tr>
</table>



    <div class="AW-btn-wrap">
        <button type="submit" class="on">확인</button>
        <a onClick="self.close();">닫기</a>
    </div><!-- .AW-btn-wrap -->


</form>
<?
}else{

	$sql = "update wiz_operinfo set prdimg_R='$prdimg_R', prdimg_S='$prdimg_S', prdimg_M='$prdimg_M', prdimg_L='$prdimg_L'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("상품이미지 사이즈 설정이 저장되었습니다.","prd_imgsize.php");

}
?>