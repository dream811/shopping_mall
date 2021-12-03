<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 	      // 라이브러리 함수

$list = get_zipcode_list($address); $search_count = count($list);
?>

<html>
<head>
<title>우편번호 검색</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="/css/style.css">
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

//-->
</script>
</head>

<body onLoad="document.frm.address.focus();">
<table width="410" border="0" cellpadding="0" cellspacing="0" style="border:6px solid #979797;" height="310">
  <tr>
    <td width="351" height="74" style="padding-left:37px;"><img src="/images/member/zip_01.gif"></td>
    <td width="60"><a href="javascript:window.close();"><img src="/images/member/id_check_close.gif" width="21" height="21" border="0"></a></td>
  </tr>
  <tr><td colspan="2" height="1" bgcolor="#d2d2d2"></td></tr>
  <tr>
    <td colspan="2" align="center" valign="top">

        <table width="360" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="60" style="padding-left:10px;"><img src="/images/member/post_txt.gif"></td>
          </tr>
          <tr>
            <td bgcolor="#f5f5f5" height="50" align="center">

                <!-- 우편번호 검색 -->
                <table width="280" border="0" cellpadding="0" cellspacing="0">
                <form name="frm" method="post" action="<?=$PHP_SELF?>">
								<input type="hidden" name="kind" value="<?=$kind?>">
                  <tr>
                    <td><img src="/images/member/zip_02.gif"></td>
                    <td align="center"><input type="text" name="address" class="search_input" style="width:188px;"></td>
                    <td><input type="image" src="/images/member/but_idcheck.gif"></td>
                  </tr>
                </form>
                </table>

            </td>
          </tr>
          <tr>
            <td style="padding:16px 0 48px 0;" height="203" valign="top">

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
								}
								?>

            	</table>
            </td>
          </tr>
        </table>

    </td>
  </tr>
</table>