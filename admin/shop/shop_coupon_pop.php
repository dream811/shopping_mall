<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<html>
<?php
if (!isset($level)) $level = 0;
if (!isset($searchkey)) $searchkey = 0;
//if (!isset($ctype)) $ctype = 0;
?>

<head>
	<title>:: 쿠폰발급 ::</title>
	<link href="../style.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" type="text/javascript">
		<!--
		// 체크박스 전체선택
		function selectAll() {
			var i;
			for (i = 0; i < document.forms.length; i++) {
				if (document.forms[i].elements['id'] != null) {
					if (document.forms[i].select_checkbox) {
						document.forms[i].select_checkbox.checked = true;
					}
				}
			}
			return;
		}

		// 체크박스 선택해제
		function selectCancel() {
			var i;
			for (i = 0; i < document.forms.length; i++) {
				if (document.forms[i].select_checkbox) {
					if (document.forms[i].elements['id'] != null) {
						document.forms[i].select_checkbox.checked = false;
					}
				}
			}
			return;
		}

		// 체크박스선택 반전
		function selectReverse(form) {
			if (form.select_tmp.checked) {
				selectAll();
			} else {
				selectCancel();
			}
		}

		// 체크박스 선택리스트
		function selectValue() {
			var i;
			var seluser = "";
			for (i = 0; i < document.forms.length; i++) {
				if (document.forms[i].elements['id'] != null) {
					if (document.forms[i].select_checkbox) {
						if (document.forms[i].select_checkbox.checked)
							seluser = seluser + document.forms[i].elements['id'].value + "|";
					}
				}
			}
			return seluser;
		}

		//선택회원 쿠폰발급
		function couponUser() {

			seluser = selectValue();

			if (seluser == "") {
				alert("발급할 회원을 선택하세요.");
				return false;
			} else {
				if (confirm("선택한 회원에게 쿠폰을 발급하시겠습니까?")) {
					document.location = "<?= $PHP_SELF ?>?ctype=save&seluser=" + seluser + "&cidx=<?= $cidx ?>&prdcode=<?= $prdcode ?>";
				}
			}
		}

		function allcouponUser() {

			if (confirm("검색한 전체회원에게 쿠폰을 발급하시겠습니까?")) {
				document.location = "<?= $PHP_SELF ?>?ctype=save&seluser=all&cidx=<?= $cidx ?>&prdcode=<?= $prdcode ?>&level=<?= $level ?>&searchkey=<?= $searchkey ?>";
			}
		}
		-->
	</script>
</head>

<body>
	<table width="100%" cellpadding=6 cellspacing=0>
		<tr>
			<td>

				<table width="100%" border="0" cellspacing="0" cellpadding="2">
					<tr>
						<td class="tit_sub"><img src="../image/ics_tit.gif"> <?= $product ?>쿠폰발급</td>
					</tr>
				</table>

				<?
				$param = "&level=$level&searchkey=$searchkey";

				if ($level) $level_sql = "and level = '$level'";
				if ($searchkey) $searchkey_sql = "and(name like '%$searchkey%' or id like '%$searchkey%' or tphone like '%$searchkey%' or email like '%$searchkey%')";
				?>

				<? if ($ctype == "" || $ctype == "search") { ?>
					<form name="write_form" action="<?= $PHP_SELF ?>" method="post">
						<input type="hidden" name="ctype" value="search">
						<input type="hidden" name="cidx" value="<?= $cidx ?>">
						<input type="hidden" name="prdcode" value="<?= $prdcode ?>">
						<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
							<tr>
								<td width="15%" class="t_name">&nbsp; 등급</td>
								<td width="85%" class="t_value">
									<select name="level">
										<option value="">전체</option>
										<?= level_list(); ?>
									</select>
									<script language="javascript">
										<!--
										level = document.write_form.level;
										for (ii = 0; ii < level.length; ii++) {
											if (level.options[ii].value == "<?= $level ?>")
												level.options[ii].selected = true;
										}
										-->
									</script>
								</td>
							</tr>
							<tr>
								<td width="15%" class="t_name">&nbsp; 키워드</td>
								<td width="85%" class="t_value">
									<input type="text" name="searchkey" value="<?= $searchkey ?>" class="input">
									<input type="submit" value="검색" />
								</td>
							</tr>
						</table><br />
					</form>
					<?
					$sql = "select * from wiz_member where 1 $level_sql $searchkey_sql";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$total = mysqli_num_rows($result);

					$idx = 0;
					$rows = 10;
					$lists = 5;

					$ttime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
					$page_count = ceil($total / $rows);
					if (!isset($page) || !$page ||  $page > $page_count) $page = 1;
					$start = ($page - 1) * $rows;
					$no = $total - $start;
					?>
					<table width="100%" cellpadding=0 cellspacing=0>
						<tr>
							<td class="t_rd" colspan="20"></td>
						</tr>
						<form>
							<tr class="t_th">
								<td width="20px" style="padding:0; text-align:center;"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></td>
								<th>이름</th>
								<th>아이디</th>
								<th>휴대폰</th>
								<th>이메일</th>
							</tr>
						</form>
						<tr>
							<td class="t_rd" colspan="20"></td>
						</tr>
						<?
						$sql = "select * from wiz_member where 1 $level_sql $searchkey_sql limit $start, $rows";
						$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
						while ($row = mysqli_fetch_array($result)) {
						?>
							<form name="frm<?= $row['idx'] ?>">
								<input type="hidden" name="id" value="<?= $row['id'] ?>">
								<input type="hidden" name="name" value="<?= $row['name'] ?>">
								<input type="hidden" name="email" value="<?= $row['email'] ?>">
								<input type="hidden" name="hphone" value="<?= $row['hphone'] ?>">
								<input type="hidden" name="passwd" value="<?= $row['passwd'] ?>">
								<tr bgcolor=ffffff align=center>
									<td height="30" style="padding:0; text-align:center;"><input type="checkbox" name="select_checkbox"></td>
									<td><?= $row['name'] ?></td>
									<td><?= $row['id'] ?></td>
									<td><?= $row['tphone'] ?></td>
									<td><?= $row['email'] ?></td>
								</tr>
							</form>
							<tr>
								<td colspan="20" class="t_line"></td>
							</tr>
						<? } ?>
					</table>

					<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td><? print_pagelist($page, $lists, $page_count, "&id=$id"); ?></td>
						</tr>
					</table>

					<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0" class="t_style">
						<tr>
							<td class="t_value" align="center">
								<button type="button" onclick="allcouponUser()">검색회원발급</button>
								<button type="button" onclick="couponUser()">발급</button>
								<button type="button" onClick="self.close();">취소</button>
							</td>
						</tr>
					</table>

			</td>
		</tr>
	</table>
</body>

</html>
<? } ?>
<?
if ($ctype == "save") {

	if ($seluser == "all") {

		$sqlm = "select * from wiz_member where 1 $level_sql $searchkey_sql ";
		$resultm = mysqli_query($connect, $sqlm) or error(mysqli_error($connect));
		while ($rowm = mysqli_fetch_array($resultm)) {

			// 쿠폰정보
			if ($cidx) {
				$sql = "select * from wiz_coupon where idx = '$cidx'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$add_sql = " and eventidx='$cidx'";
			} else if ($prdcode) {
				$sql = "select prdcode, concat('[상품쿠폰]', prdname) as coupon_name, coupon_sdate, coupon_edate, coupon_dis, coupon_type, coupon_amount, coupon_limit from wiz_product where prdcode = '$prdcode'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$add_sql = " and prdcode='$prdcode'";
			}

			$total = mysqli_num_rows($result);
			$coupon_info = mysqli_fetch_object($result);
			if ($total <= 0) error("쿠폰정보가 없습니다.");

			if ($coupon_info->coupon_sdate <= date('Y-m-d') && $coupon_info->coupon_edate >= date('Y-m-d')) {

				// 쿠폰등록
				if ($coupon_info->coupon_limit == "N" || ($coupon_info->coupon_limit == "" && $coupon_info->coupon_amount > 0)) {

					$coupon_name = $coupon_info->coupon_name;
					$memid = $wiz_session['id'];
					$coupon_use = "N";
					$coupon_dis = $coupon_info->coupon_dis;
					$coupon_type = $coupon_info->coupon_type;
					$coupon_sdate = $coupon_info->coupon_sdate;
					$coupon_edate = $coupon_info->coupon_edate;

					$sql = "select idx from wiz_mycoupon where memid='" . $rowm['id'] . "' and coupon_sdate <= curdate() and coupon_edate >= curdate()" . $add_sql;
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$total = mysqli_num_rows($result);

					if ($coupon_info->coupon_amount <= 0 && $coupon_info->coupon_limit != "N") {
						error("쿠폰 수량이 모두 소진 되었습니다.");
					}

					if ($total == 0) {
						$sql = "insert into wiz_mycoupon(idx,memid,eventidx,prdcode,coupon_name,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_use,wdate)
							values('','" . $rowm['id'] . "','$cidx','$prdcode','$coupon_name','$coupon_dis','$coupon_type','$coupon_sdate','$coupon_edate','$coupon_use',now())";

						mysqli_query($connect, $sql) or error("할인쿠폰 다운시 에러가 발생하였습니다.\\n\\n관리자에게 문의하세요.");

						if ($coupon_info->coupon_limit != "N") {
							$sql = "update wiz_coupon set coupon_amount = coupon_amount - 1 where idx='$cidx'";
							mysqli_query($connect, $sql) or die(mysqli_error($connect));
						}
					}
					//error("정상적으로 발급되었습니다.");
				} else {
					error("쿠폰이 모두 소진되었습니다.");
				}
			} else {
				error("사용기간이 지난 쿠폰입니다.");
			}
		}
	} else {

		$array_seluser = explode("|", $seluser);
		$i = 0;
		while ($array_seluser[$i]) {

			// 쿠폰정보
			if ($cidx) {
				$sql = "select * from wiz_coupon where idx = '$cidx'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$add_sql = " and eventidx='$cidx'";
			} else if ($prdcode) {
				$sql = "select prdcode, concat('[상품쿠폰]', prdname) as coupon_name, coupon_sdate, coupon_edate, coupon_dis, coupon_type, coupon_amount, coupon_limit from wiz_product where prdcode = '$prdcode'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$add_sql = " and prdcode='$prdcode'";
			}

			$total = mysqli_num_rows($result);
			$coupon_info = mysqli_fetch_object($result);
			if ($total <= 0) error("쿠폰정보가 없습니다.");

			if ($coupon_info->coupon_sdate <= date('Y-m-d') && $coupon_info->coupon_edate >= date('Y-m-d')) {

				// 쿠폰등록
				if ($coupon_info->coupon_limit == "N" || ($coupon_info->coupon_limit == "" && $coupon_info->coupon_amount > 0)) {

					$coupon_name = $coupon_info->coupon_name;
					$memid = $wiz_session['id'];
					$coupon_use = "N";
					$coupon_dis = $coupon_info->coupon_dis;
					$coupon_type = $coupon_info->coupon_type;
					$coupon_sdate = $coupon_info->coupon_sdate;
					$coupon_edate = $coupon_info->coupon_edate;

					$sql = "select idx from wiz_mycoupon where memid='$array_seluser[$i]' and coupon_sdate <= curdate() and coupon_edate >= curdate()" . $add_sql;
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$total = mysqli_num_rows($result);

					if ($coupon_info->coupon_amount <= 0 && $coupon_info->coupon_limit != "N") {
						error("쿠폰 수량이 모두 소진 되었습니다.");
					}

					if ($total == 0) {
						$sql = "insert into wiz_mycoupon(idx,memid,eventidx,prdcode,coupon_name,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_use,wdate)
							values('','$array_seluser[$i]','$cidx','$prdcode','$coupon_name','$coupon_dis','$coupon_type','$coupon_sdate','$coupon_edate','$coupon_use',now())";
						mysqli_query($connect, $sql) or error("할인쿠폰 다운시 에러가 발생하였습니다.\\n\\n관리자에게 문의하세요.");

						if ($coupon_info->coupon_limit != "N") {
							$sql = "update wiz_coupon set coupon_amount = coupon_amount - 1 where idx='$cidx'";
							mysqli_query($connect, $sql) or die(mysqli_error($connect));
						}
					}
					//error("정상적으로 발급되었습니다.");
				} else {
					error("쿠폰이 모두 소진되었습니다.");
				}
			} else {
				error("사용기간이 지난 쿠폰입니다.");
			}
			$i++;
		}
	}

	echo "<script>
		opener.location.reload();
		window.close();
		</script>";
}
?>
</div>
</body>

</html>