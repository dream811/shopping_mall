<?
include "../../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../../inc/oper_info.inc";

if (!isset($save) || $save == "") {

	if (empty($mode)) $mode = "insert";

	if (!strcmp($mode, "update")) {

		$pay_account = explode("\n", $oper_info->pay_account);

		for ($ii = 0; $ii <= count($pay_account); $ii++) {

			$account_tmp = explode("^", $pay_account[$ii]);

			if (!strcmp($account_tmp[0], $no)) {

				$bank = $account_tmp[1];
				$account = $account_tmp[2];
				$name = $account_tmp[3];

				$ii = count($pay_account) + 1;
			}
		}
	}

?>
	<html>

	<head>
		<title>은행계좌번호</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link href="../style.css" rel="stylesheet" type="text/css">
		<script language="JavaScript">
			function inputCheck(frm) {
				if (frm.bank.value == "") {
					alert("은행명을 입력하세요");
					frm.bank.focus();
					return false;
				}
				if (frm.account.value == "") {
					alert("계좌번호를를 입력하세요");
					frm.account.focus();
					return false;
				}
				if (frm.name.value == "") {
					alert("예금주를 입력하세요");
					frm.name.focus();
					return false;
				}

				frm.action = "/admin/shop/pay_account.php";
			}
			//
		</script>
	</head>

	<BODY onLoad="window.focus();">
		<form name="frm" method="post" action="<?= $PHP_SELF ?>" onSubmit="return inputCheck(this);">
			<input type="hidden" name="save" value="true">
			<input type="hidden" name="mode" value="<?= $mode ?>">
			<input type="hidden" name="no" value="<?= $no ?>">

			<table width="100%" cellpadding=10 cellspacing=0>
				<tr>
					<td>

						<table width="100%" align=center border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td class="tit_sub"><img src="../image/ics_tit.gif"> 은행계좌번호</td>
							</tr>
						</table>


						<table width="100%" align=center border="0" cellpadding=3 cellspacing=1 class="t_style">

							<tr>
								<td width=30% height=25 class="t_name">&nbsp; 은행명</td>
								<td width=70% class="t_value"><input type="text" name="bank" value="<?= $bank ?>" class="input"></td>
							</tr>
							<tr>
								<td height=25 class="t_name">&nbsp; 계좌번호</td>
								<td class="t_value"><input type="text" name="account" value="<?= $account ?>" class="input"></td>
							</tr>
							<tr>
								<td height=25 class="t_name">&nbsp; 예금주</td>
								<td class="t_value"><input type="text" name="name" value="<?= $name ?>" class="input"></td>
							</tr>
						</table>



						<div class="AW-btn-wrap">
							<input type="submit" value="확인" class="on" />
							<a onClick="self.close();">닫기</a>
						</div><!-- .AW-btn-wrap -->

					</td>
				</tr>
			</table>
		</form>


	<?
} else {

	if (!strcmp($mode, "insert")) {

		$pay_account = explode("\n", $oper_info->pay_account);
		$pay_account_cnt = count($pay_account) - 1;

		$account_tmp = explode("^", $pay_account[$pay_account_cnt]);

		$no = $account_tmp[0] + 1;

		$pay_account_tmp = $oper_info->pay_account . "\n" . $no . "^" . $bank . "^" . $account . "^" . $name;

		$sql = "update wiz_operinfo set pay_account = '" . $pay_account_tmp . "'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('저장되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";
	} else if (!strcmp($mode, "update")) {

		$pay_account = explode("\n", $oper_info->pay_account);
		for ($ii = 0; $ii < count($pay_account); $ii++) {

			$account_tmp = explode("^", $pay_account[$ii]);

			if (!empty($account_tmp[0])) {

				if (!strcmp($no, $account_tmp[0])) {
					$pay_account_tmp .= "\n" . $no . "^" . $bank . "^" . $account . "^" . $name;
				} else {
					$pay_account_tmp .= "\n" . $account_tmp[0] . "^" . $account_tmp[1] . "^" . $account_tmp[2] . "^" . $account_tmp[3];
				}
			}
		}

		$sql = "update wiz_operinfo set pay_account = '" . $pay_account_tmp . "'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('수정되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";
	} else if (!strcmp($mode, "delete")) {

		$pay_account = explode("\n", $oper_info->pay_account);
		for ($ii = 0; $ii < count($pay_account); $ii++) {

			$account_tmp = explode("^", $pay_account[$ii]);

			if (!empty($account_tmp[0])) {
				if (!strcmp($no, $account_tmp[0])) {
					$delete = true;
				} else {
					if (!strcmp($delete, true)) $account_tmp[0] = $account_tmp[0] - 1;
					$pay_account_tmp .= "\n" . $account_tmp[0] . "^" . $account_tmp[1] . "^" . $account_tmp[2] . "^" . $account_tmp[3];
				}
			}
		}

		$sql = "update wiz_operinfo set pay_account = '" . $pay_account_tmp . "'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

		echo "<script>alert('삭제되었습니다.');document.location='shop_oper.php';</script>";
	}
}
	?>