<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
if($save != "true"){
	
// 분석할 파라메터 가져오기
$sql = "select con_parameter from wiz_operinfo";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
?>
<html>
<head>
<title>:: 분석파라미터 설정 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/valueCheck.js"></script>
</head>
<body style="padding:10px;">
<table width="100%" border="0" cellpadding=6 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 분석파라미터 설정</td>
  </tr>
</table>
<table width="100%"cellpadding=2 cellspacing=1 class="t_style">
<form name="frm" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="save" value="true">
  <tr>
    <td class="t_name" align="center" height=35 width=100>분석파라미터</td>
    <td class="t_value">&nbsp; 
      <input type="text" name="parameter" value="<?=$row->con_parameter?>" size="55" class="input">
      <input type="submit" value="적용" />
      </td>
  </tr>
</form>
</table>
<br>
<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
        - 각 검색엔진 별로 분석해야할 파라미터 명이 다릅니다.<br>
        - ex) 네이버에서 "아디다스"로 검색한경우 상단 주소는 다음과 같습니다.<br>
        - http://search.naver.com/search.naver?where=nexearch&<font color='red'><b>query</b></font>=%BE%C6%B5%F0%B4%D9%BD%BA&frm=t1<br>
        - 이경우 분석해야할 파라메터는 <font color='red'><b>query</b></font>가 됩니다.<br>
        - 위의 분석파라메터에 각 파라메터를 컴마로 구분하여 저정하시면 됩니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->
</td>
</tr>
</table>
</body>
</html>
<?
}else{
	
	$sql = "update wiz_operinfo set con_parameter = '$parameter'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	complete("적용되었습니다.","$PHP_SELF");
	
}
?>