<? include "../inc/header.php" ?>
<?php
$list = get_zipcode_list($address); $search_count = count($list);
?>
<script language="JavaScript">
<!--
function setAddr(zipcode, addr){
	opener.frm.<?=$kind?>post.value = zipcode;
	opener.frm.<?=$kind?>address.value = addr;
	if(opener.frm.<?=$kind?>address2 != null){
		opener.frm.<?=$kind?>address2.focus();
	}
	self.close();
}


function setAddress() {

	var address = document.getElementById("zipcode").value.split("|");

	if(address[0] != "") {
		setAddr(address[0], address[1], address[2]);
	} else {
		alert("주소를 입력하세요.");
	}

}
//-->
</script>
<body>
<div class="sub_title">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" height="30" style="padding-left:10px; font-weight:bold">우편번호 찾기</td>
    <td align="right" style="background:url(../img/sub/sub_tit_bar.gif) 55% 0 no-repeat; padding-right:10px;"><a href="javascript:self.close();">닫기</a></td>
  </tr>
</table>
</div>

<div style="padding:10px; font-size:12px;">
찾고자 하는 주소의 동(읍/면/리/가)명을 2글자 이상 입력하신 후 검색을 누르세요<br />
<font style="color:#999999;">예) 삼성동 검색시 '삼성', '삼성동'을 입력해주세요</font>
</div>

<form name="frm" method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="kind" value="<?=$kind?>">

<div style="padding:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input name="address" type="text" class="post_input" /></td>
    <td width="38"><input type="submit" class="btn_postsearch" value="검색" /></td>
  </tr>
</table>
</div>

</form>

<div style="padding-left:10px; padding-right:10px;" class="select_post">

  <!-- 우편번호 검색결과 -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
      <td colspan="2" height="2" bgcolor="#a9a9a9"></td>
    </tr>
    <tr>
      <td width="25%" height="35" align="center" bgcolor="#f9f9f9" style="color:#555555"><strong>우편번호</strong></td>
      <td width="75%" align="center" bgcolor="#f9f9f9" style="color:#555555"><strong>주소</strong></td>
    </tr>
    <tr><td colspan="2" height="1" bgcolor="#d7d7d7"></td></tr>

		<?
		if($address != ""){

			for ($i=0; $i<count($list); $i++) {
			$post1 		= $list[$i][zip1];
			//$post2 		= $list[$i][zip2];
			$set_addr = $list[$i][set_addr];
			$addr	= $list[$i][addr];
		?>
		<tr>
		<td align="center" style="color:#8f8f8f;" height="25"><?=$post1?></td>
		<td style="color:#8f8f8f;"><a href="" onClick="setAddr( '<? echo $post1 ?>' , '<? echo $set_addr ?>' )"><? echo $addr; ?></a></td>
		</tr>
		<tr><td colspan="2" height="1" bgcolor="#d7d7d7"></td></tr>
		<?
			}
			if($address != "" && $search_count <= 0){
		?>
		<tr>
		<td align="center" style="color:#8f8f8f;" height="25" colspan="2">찾으시는 주소가 없습니다. 다시 입력하세요.</td>
		</tr>
		<tr><td colspan="2" height="1" bgcolor="#d7d7d7"></td></tr>
		<?
			}
		} else {
		?>
		<tr>
		<td align="center" style="color:#8f8f8f;" height="25" colspan="2">주소를 입력하세요.</td>
		</tr>
		<tr><td colspan="2" height="1" bgcolor="#d7d7d7"></td></tr>
		<?php
		}
		?>
	</table>

</div>

</body>
</html>