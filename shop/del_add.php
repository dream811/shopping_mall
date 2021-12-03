<link href="/css/style.css" rel="stylesheet" type="text/css">
<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이페이지 &gt; <strong>배송지관리</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/mem_info.inc"; 		// 회원 정보

if(empty($mode)) $mode = "insert";
else if($mode == "update"){

	$sql = "select * from wiz_dellist where idx='$idx' and id='$wiz_session['id']'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);

	if($row->id != $wiz_session['id']){
		error("잘못된 접근 입니다.");
		exit;
	}
}

	$lsql = "select count(*) from wiz_dellist where id='$wiz_session['id']'";
	$lresult = mysqli_query($connect, $lsql) or error(mysqli_error($connect));
	$lrow = mysql_fetch_row($lresult);

	if($lrow[0] > 4){
		error("배송지 추가는 5개까지 가능합니다.");
		exit;
	}
?>
<script language="JavaScript">
<!--
function zipSearch(){

	document.frm.send_address.focus();
	var url = "/member/zip_search.php?kind=send_";
	window.open(url, "zipSearch", "width=427, height=400, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");

}

//-->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">
		<form name="frm" action="/member/del_save.php" method="post">
			<input type="hidden" name="mode" value="viewinsert">
			<input type="hidden" name="idx" value="<?=$idx?>">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			   <tr>
				 <td bgcolor="#a9a9a9" height="2"></td>
			   </tr>
			</table>
			<table width="100%" border="0" cellpadding="5" cellspacing="0">
				<tr><td colspan="2" height="1" bgcolor="#d7d7d7"></td></tr>
				<tr>
					<td width="20%">주소</td>
					<td>
						<?
							$post = explode("-", $row->post);
							$address = explode("|", $row->address);
						?>
						<input type=text name="send_post" value="<?=$post[0]?>" size=7 class="input"> -
						<input type=text name="send_post2" value="<?=$post[1]?>" size=7 class="input">
						<a href="javascript:zipSearch();"><img src="/images/shop/but_find_zip.gif" border=0 align=absmiddle></a>
						<br />
						<input type=text name="send_address" value="<?=$address[0]?>" size=70 class="input"><br />
						<input type=text name="send_address2" value="<?=$address[1]?>" size=70 class="input">
					</td>
				</tr>
			    <tr>
				    <td colspan="3" bgcolor="#d7d7d7" height="1"></td>
			    </tr>
				<tr>
					<td width="">수취인명</td>
					<td>
						<input type="hidden" name="id" value="<?=$wiz_session['id']?>">
						<input type="text" name="name" size="10" value="<?=$row->name?>" class="input">
					</td>
				</tr>
			    <tr>
				    <td colspan="3" bgcolor="#d7d7d7" height="1"></td>
			    </tr>
				<tr>
					<td width="">배송지이름</td>
					<td>
						<input type="text" name="delname" size="20" value="<?=$row->delname?>" class="input">
					</td>
				</tr>
			    <tr>
				    <td colspan="3" bgcolor="#d7d7d7" height="1"></td>
			    </tr>
				<tr>
					<td width="">전화번호</td>
					<td>
						<?
							$tphone = explode("-", $row->tphone);
							$hphone = explode("-", $row->hphone);
						?>
						<input type="text" name="tphone1" size="3" value="<?=$tphone[0]?>" class="input">-
						<input type="text" name="tphone2" size="4" value="<?=$tphone[1]?>" class="input">-
						<input type="text" name="tphone3" size="4" value="<?=$tphone[2]?>" class="input">
					</td>
				</tr>
			    <tr>
				    <td colspan="3" bgcolor="#d7d7d7" height="1"></td>
			    </tr>
				<tr>
					<td width="">핸드폰</td>
					<td>
						<input type="text" name="hphone1" size="3" value="<?=$hphone[0]?>" class="input">-
						<input type="text" name="hphone2" size="4" value="<?=$hphone[1]?>" class="input">-
						<input type="text" name="hphone3" size="4" value="<?=$hphone[2]?>" class="input">
					</td>
				</tr>
			    <tr>
				    <td colspan="3" bgcolor="#d7d7d7" height="1"></td>
			    </tr>
			</table>
			<br />
			<div style="text-align:center;">
				<input type="image" src="/images/member/bt_join_ok.gif">
				<img style="cursor:hand" src="/images/member/bt_cancel.gif" onclick="history.go(-1)">
			</div>
		</form>



    </td>
  </tr>
</table>
