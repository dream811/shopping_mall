<?
include "../../inc/common.inc"; 			// DB컨넥션, 접속자 파악

$zipfile = file("$DOCUMENT_ROOT/wiz_zipcode.db");
$search_count = 0;

if($address) {
	while($zipcode = each($zipfile)) {
		if(strstr(substr($zipcode[1], 9, 512), $address)) {
			$list[$search_count][zip1] = substr($zipcode[1],0,3);
			$list[$search_count][zip2] = substr($zipcode[1],4,3);
			$addr = explode(" ", substr($zipcode[1],8));

			if($addr[sizeof($addr)-1]) {
				$list[$search_count][addr] = str_replace($addr[sizeof($addr)-1], "", substr($zipcode[1], 8));
				$list[$search_count][bunji] = trim($addr[sizeof($addr)-1]);
			} else {
				$list[$search_count][addr] = substr($zipcode[1], 8);
			}

			$list[$search_count][encode_addr] = urlencode($list[$search_count][addr]);
			$search_count++;
		}
	}
}
?>
<html>
<head>
<title>우편번호 검색</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function setAddr(zipcode1, zipcode2 , addr){
	opener.frm.<?=$kind?>post.value = zipcode1;
	opener.frm.<?=$kind?>post2.value = zipcode2;
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
		$post2 		= $list[$i][zip2];
		$set_addr = $list[$i][addr];
		$addr	= $list[$i][addr]." ".$list[$i][bunji];

		if($i%2 == 0) $bgcolor="#ffffff";
		else $bgcolor = "#ECFFFB";
	?>
	<tr>
	  <td width=70 height=20><font color=#2088CD><?=$post1?>-<?=$post2?></font></td>
	  <td><a href="" onClick="setAddr( '<? echo $post1 ?>' , '<? echo $post2 ?>' , '<? echo $set_addr ?>' )"><? echo $addr; ?></a></td>
	</tr>
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