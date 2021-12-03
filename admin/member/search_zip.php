<?
include "../../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 	      // 라이브러리 함수

$list = get_zipcode_list($address); $search_count = count($list);
?>
<html>
<head>
<title>우편번호 검색</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function setAddr(zipcode, addr){
	opener.frm.<?=$kind?>post.value = zipcode;
	opener.frm.<?=$kind?>address.value = addr;
	if(opener.frm.<?=$kind?>address2 != null)
		opener.frm.<?=$kind?>address2.focus();
	self.close();
}

//-->
</script>
</head>

<body onLoad="document.frm.address.focus();">

<table width="98%" align="center"><tr><td>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 우편번호 검색</td>
  </tr>
</table>
<table width="100%" cellpadding=2 cellspacing=1 class="t_style">
<form name="frm" method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="kind" value="<?=$kind?>">
  <tr>
    <td width="35%" class="t_name">지역명</td>
    <td class="t_value">
      <input type="text" name="address" class="input" size="20">
      <input type="image" src="../image/btn_search.gif" align="absmiddle">
    </td>
  </tr>
</form>
</table>
<br>
<?
if( $address != ""){
?>
<table border=0 cellpadding=2 cellspacing=0 width=100% bgcolor=#ffffff align=center>
	<?

	for ($i=0; $i<count($list); $i++) {

		$post1 		= $list[$i][zip1];
		//$post2 		= $list[$i][zip2];
		$set_addr = $list[$i][set_addr];
		$addr	= $list[$i][addr];

		if($i%2 == 0) $bgcolor="#ffffff";
		else $bgcolor = "#ECFFFB";
	?>
	<tr>
	  <td width=70 height=20><font color=#2088CD><?=$post1?></font></td>
	  <td><a href="" onClick="setAddr( '<? echo $post1 ?>' , '<? echo $set_addr ?>' )"><? echo $addr; ?></a></td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor=#f0f0f0></td></tr>
	<?
	}
	?>
	<?
	if(!empty($address)&& $search_count <= 0){
	?>
	<tr>
	  <td colspan="2" align="center">- 찾으시는 주소가 없습니다. 다시 입력하세요.</td>
	</tr>
	<?
	}
	?>

</table>
<?
}
?>
</td></tr></table>
</body>
</html>