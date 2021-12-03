<?
include "../../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../../inc/oper_info.inc";

if($save == ""){

	if(empty($mode)) $mode = "insert";

	if(!strcmp($mode, "update")) {

		$del_info = explode("\n", $oper_info->del_trace);

		for($ii = 0; $ii <= count($del_info); $ii++) {

			$account_tmp = explode("^", $del_info[$ii]);

			if(!strcmp($account_tmp[0], $no)) {

				$del_name = $account_tmp[1];
				$del_url = $account_tmp[2];
				//$name = $account_tmp[3];

				$ii = count($del_info) + 1;

			}

		}

	}

?>
<html>
<head>
<title>택배사정보</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){
	if(frm.bank.value == ""){
		alert("은행명을 입력하세요");
		frm.bank.focus();
		return false;
	}
	if(frm.account.value == ""){
		alert("계좌번호를를 입력하세요");
		frm.account.focus();
		return false;
	}

}
//-->
</script>
</head>

<BODY onLoad="window.focus();"> 
<form name="frm" method="post" action="<?=$PHP_SELF?>" onSubmit="return inputCheck(this);">
<input type="hidden" name="save" value="true">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="no" value="<?=$no?>">

<table width="100%" cellpadding=10 cellspacing=0><tr><td>
	
<table width="100%" align=center border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 택배사정보</td>
  </tr>
</table>
<table width="100%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">

  <tr>
    <td width=30% height=25 class="t_name">&nbsp; 택배사</td>
    <td width=70% class="t_value"><input type="text" name="del_name" value="<?=$del_name?>" class="input"></td>
  </tr>
  <tr>
    <td height=25 class="t_name">&nbsp; 배송추적URL</td>
    <td class="t_value"><input type="text" name="del_url" size="70" value="<?=$del_url?>" class="input"></td>
  </tr>
  <!--
  <tr>
    <td height=25 class="t_name">&nbsp; 예금주</td>
    <td class="t_value"><input type="text" name="name" value="<?=$name?>" class="input"></td>
  </tr>
  -->
</table>

	<div class="AW-btn-wrap">
        <input type="button" value="확인" class="on" />
        <a onClick="self.close();">닫기</a>
    </div><!-- .AW-btn-wrap -->



</td></tr></table>

</form>

<?
}else{

	if(!strcmp($mode, "insert")) {

		$del_info = explode("\n",$oper_info->del_trace);
		$del_info_cnt = count($del_info) - 1;

		$del_tmp = explode("^", $del_info[$del_info_cnt]);

		$no = $del_tmp[0] + 1;

		$del_info_tmp = $oper_info->del_trace."\n".$no."^".$del_name."^".trim($del_url);

		$sql = "update wiz_operinfo set del_trace = '".$del_info_tmp."'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('저장되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "update")) {

		$del_info = explode("\n",$oper_info->del_trace);
		for($ii = 0; $ii < count($del_info); $ii++) {

			$del_tmp = explode("^", $del_info[$ii]);

			if(!empty($del_tmp[0])) {

				if(!strcmp($no, $del_tmp[0])) {
					$del_info_tmp .= "\n".$no."^".$del_name."^".trim($del_url);
				} else {
					$del_info_tmp .= "\n".$del_tmp[0]."^".$del_tmp[1]."^".$del_tmp[2];
				}

			}

		}

		$sql = "update wiz_operinfo set del_trace = '".$del_info_tmp."'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('수정되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";

	} else if(!strcmp($mode, "delete")) {

		$del_info = explode("\n",$oper_info->del_trace);
		for($ii = 0; $ii < count($del_info); $ii++) {

			$account_tmp = explode("^", $del_info[$ii]);

			if(!empty($account_tmp[0])) {
				if(!strcmp($no, $account_tmp[0])) {
					$delete = true;
				} else {
					if(!strcmp($delete, true)) $account_tmp[0] = $account_tmp[0] - 1;
					$del_info_tmp .= "\n".$account_tmp[0]."^".$account_tmp[1]."^".$account_tmp[2];
				}
			}

		}

		$sql = "update wiz_operinfo set del_trace = '".$del_info_tmp."'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('삭제되었습니다.');document.location='shop_oper.php';</script>";

	}

}
?>