<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   	// 유틸 라이브러리

if (!isset($catcode)) $catcode = "";
if (!isset($brand)) $brand = "";
if (!isset($grp)) $grp = "";
if (!isset($orderby)) $orderby = "";
if (!isset($page)) $page = "";
if (!isset($mallid)) $mallid = "";
if (!isset($subimg_type)) $subimg_type = "";
if (!isset($sp_img)) $sp_img = "";

$param = "catcode=$catcode&grp=$grp&orderby=$orderby&brand=$brand&page=$page";

$prd_list_url = "prd_list.php";

if ($mallid != "") {
	$param .= "&mallid=" . $mallid;
	$prd_list_url = "minishop.php";
}

// 상품정보 가져오기 (이동하지 말것)
$sql = "select * from wiz_product wp, wiz_cprelation wc where wp.prdcode='$prdcode' and wc.prdcode = wp.prdcode";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
$prd_info = mysqli_fetch_object($result);
if ($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");
if ($catcode == "") $catcode = $prd_info->catcode;

if ($mallid == "") $mallid = $prd_info->mallid;

include "../inc/cat_info.inc"; 		// 카테고리정보
include "../inc/oper_info.inc";		// 운영정보
include "../inc/header.inc"; 			// 상단디자인

// 상품아이콘
if ($prd_info->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
if ($prd_info->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
if ($prd_info->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
if ($prd_info->best == "Y") 			$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
if ($prd_info->sale == "Y")			$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";

if (!empty($prd_info->strprice)) $sellprice = $prd_info->strprice;
else $sellprice = "<strong>" . number_format($prd_info->sellprice) . "</strong>원";

if ($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

$prdicon_list = explode("/", $prd_info->prdicon);
for ($ii = 0; $ii < count($prdicon_list) - 1; $ii++) {
	$sp_img .= "<img src='/data/prdicon/" . $prdicon_list[$ii] . "'> ";
}

// 상품조회수 증가
$sql = "update wiz_product set viewcnt = viewcnt + 1 where prdcode='$prdcode'";
mysqli_query($connect, $sql) or die(mysqli_error($connect));

// 관심상품 여부 확인
$sql = "select idx from wiz_wishlist where prdcode='$prdcode' and memid='" . $wiz_session['id'] . "'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

echo $wish_idx = isset($row['idx']) ? $row['idx'] : "";

// 다음이전 상품
$catcode01 = str_replace("00", "", substr($catcode, 0, 2));
$catcode02 = str_replace("00", "", substr($catcode, 2, 2));
$catcode03 = str_replace("00", "", substr($catcode, 4, 2));
$tmp_catcode = $catcode01 . $catcode02 . $catcode03;
$sql = "select wp.prdcode from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode inner join wiz_mall as wm on wp.mallid = wm.id where wc.catcode like '$tmp_catcode%' and wp.showset != 'N' and wp.status = 'Y' and wm.status = 'Y' and wp.prior > '$prd_info->prior' order by wp.prior asc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if ($row = mysqli_fetch_object($result)) $prev_prdcode = "prd_view.php?prdcode=$row->prdcode&catcode=$catcode&brand=$brand";
else $prev_prdcode = "#";

$sql = "select wc.prdcode from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode inner join wiz_mall as wm on wp.mallid = wm.id where wc.catcode like '$tmp_catcode%' and wp.showset != 'N' and wp.status = 'Y' and wm.status = 'Y' and wp.prior < '$prd_info->prior' order by wp.prior desc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if ($row = mysqli_fetch_object($result)) $next_prdcode = "prd_view.php?prdcode=$row->prdcode&catcode=$catcode&brand=$brand";
else $next_prdcode = "#";


// 오늘본 상품목록에 추가
$view_exist = false;
$view_idx = 0;
for ($ii = 0; $ii < 100; $ii++) {
	if (isset($_SESSION["view_list"][$ii]) && $_SESSION["view_list"][$ii]['prdcode']) $view_idx++;
}
for ($ii = 0; $ii < $view_idx; $ii++) {
	if ($_SESSION["view_list"][$ii]['prdcode'] == $prdcode) {
		$view_exist = true;
		break;
	}
}
if (!$view_exist) {
	$_SESSION["view_list"][$view_idx]['prdcode'] = $prdcode;
	$_SESSION["view_list"][$view_idx]['prdimg'] = $prd_info->prdimg_R;
}

// 상품 이미지
for ($ii = 1; $ii <= 5; $ii++) {
	if (!@file($_SERVER['DOCUMENT_ROOT'] . "/data/prdimg/" . $prd_info->{"prdimg_M" . $ii})) $prd_info->{"prdimg_M" . $ii} = "/images/noimg_M.gif";
	else $prd_info->{"prdimg_M" . $ii} = "/data/prdimg/" . $prd_info->{"prdimg_M" . $ii};
}
?>
<script language="javascript">
	// 상품이미지 팝업
	function prdZoom() {

		var url = "prd_zoom.php?prdcode=<?= $prdcode ?>";
		window.open(url, "prdZoom", "width=800,height=540,scrollbars=yes");

	}

	// 가격설정
	function setSellprice() {

		var tmp_sellprice = document.prdForm.tmp_sellprice.value;
		var opt_price1 = document.prdForm.opt_price1.value;
		var opt_price2 = document.prdForm.opt_price2.value;
		var opt_price3 = document.prdForm.opt_price3.value;

		var tmp_reserve = document.prdForm.tmp_reserve.value;
		var opt_reserve1 = document.prdForm.opt_reserve1.value;
		var opt_reserve2 = document.prdForm.opt_reserve2.value;
		var opt_reserve3 = document.prdForm.opt_reserve3.value;

		if (tmp_sellprice == "") tmp_sellprice = 0;
		if (opt_price1 == "") opt_price1 = 0;
		if (opt_price2 == "") opt_price2 = 0;
		if (opt_price3 == "") opt_price3 = 0;

		if (tmp_reserve == "") tmp_reserve = 0;
		if (opt_reserve1 == "") opt_reserve1 = 0;
		if (opt_reserve2 == "") opt_reserve2 = 0;
		if (opt_reserve3 == "") opt_reserve3 = 0;

		var sellprice = String(eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3));
		var reserve = String(eval(tmp_reserve) + eval(opt_reserve1) + eval(opt_reserve2) + eval(opt_reserve3));

		document.getElementById("sellprice").innerHTML = "<strong>" + won_Comma(sellprice) + "</strong>원";
		document.getElementById("reserve").innerHTML = "" + won_Comma(reserve) + "원";

		<?php
		if (
			$prd_info->coupon_use == "Y" &&
			$prd_info->coupon_sdate <= date('Y-m-d') &&
			$prd_info->coupon_edate >= date('Y-m-d') &&
			($prd_info->coupon_limit == "N" || ($prd_info->coupon_limit == "" && $prd_info->coupon_amount > 0))
		) {
		?>

			sellprice = eval(tmp_sellprice) + eval(opt_price1) + eval(opt_price2) + eval(opt_price3);

			var coupon_dis = document.prdForm.coupon_dis.value;
			var coupon_type = document.prdForm.coupon_type.value;

			var coupon_price;

			if (coupon_type == "%") {
				coupon_dis = coupon_dis / 100;
				coupon_price = sellprice - (sellprice * coupon_dis);
			} else {
				coupon_price = sellprice - coupon_dis;
			}

			coupon_price = String(coupon_price);

			//document.getElementById("coupon").innerHTML = "<font class='coupon'>"+ won_Comma(coupon_price) +"원 &nbsp;<?= number_format($prd_info->coupon_dis) ?><?= $prd_info->coupon_type ?></font>";

		<?php
		}
		?>

		document.prdForm.tmp_sellprice.value = tmp_sellprice;

	}

	// 수량 증가
	function incAmount() {

		var amount = document.prdForm.amount.value;
		document.prdForm.amount.value = ++amount;
		checkAmount();

	}

	// 수량 감소
	function decAmount() {

		var amount = document.prdForm.amount.value;
		if (amount > 1)
			document.prdForm.amount.value = --amount;
		checkAmount();

	}

	// 수량체크
	function checkAmount() {

		var amount = document.prdForm.amount.value;
		if (!Check_Num(amount) || amount < 1) {

			document.prdForm.amount.value = "1";

		} else {
			<? if ($prd_info->opt_use == "Y" && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))) { ?>
				if (document.prdForm.amount != null) {
					var selvalue = document.prdForm.optcode.value;
					var optlist = selvalue.split("^");
					if (amount > eval(optlist[3])) {
						alert("재고량이 부족합니다.");
						document.prdForm.amount.value = "1";
						return false;
					} else {
						return true;
					}
				}
			<? } else if (!strcmp($prd_info->shortage, "S")) { ?>
				if (document.prdForm.amount != null) {
					if (amount > <?= $prd_info->stock ?>) {
						alert("재고량이 부족합니다.");
						document.prdForm.amount.value = "1";
						return false;
					} else {
						return true;
					}
				}
			<? } else { ?>

				return true;

			<? } ?>
		}

	}


	// 가격변동,품절옵션 체크
	function checkOpt01() {

		if (document.prdForm.optcode != null) {

			var optval = document.prdForm.optcode.value;
			var optlist = optval.split("^");

			if (optval == "") {

				document.prdForm.opt_price1.value = "";
				document.prdForm.opt_reserve1.value = "";
				setSellprice();

			} else {

				//optlist[0] : 옵션명 optlist[1] : 가격 optlist[2] : 적립금 optlist[3] 재고

				if (optlist[3] == "0" || optlist[3] == "") {
					alert('품절된 상품입니다.');
					document.prdForm.optcode[0].selected = true;
					document.prdForm.opt_price1.value = "";
					document.prdForm.opt_reserve1.value = "";
					setSellprice();

					return false;

					// 옵션별 가격 적용
				} else {
					document.prdForm.opt_price1.value = optlist[1];
					document.prdForm.opt_reserve1.value = optlist[2];
					setSellprice();
				}

			}

		}

		return checkAmount();

	}

	// 가격변동 체크
	function checkOpt03() {

		if (document.prdForm.optcode3 != null) {

			if (document.prdForm.optcode3.value == "") {

				document.prdForm.opt_price2.value = "";
				document.prdForm.opt_reserve2.value = "";
				setSellprice();

			} else {

				var optval = document.prdForm.optcode3.value;
				var optlist = optval.split("^");

				document.prdForm.opt_price2.value = optlist[1];
				document.prdForm.opt_reserve2.value = optlist[2];
				setSellprice();

			}
		}

	}

	// 가격변동 체크
	function checkOpt04() {

		if (document.prdForm.optcode4 != null) {

			if (document.prdForm.optcode4.value == "") {

				document.prdForm.opt_price3.value = "";
				document.prdForm.opt_reserve3.value = "";
				setSellprice();

			} else {

				var optval = document.prdForm.optcode4.value;
				var optlist = optval.split("^");

				document.prdForm.opt_price3.value = optlist[1];
				document.prdForm.opt_reserve3.value = optlist[2];
				setSellprice();

			}
		}

	}


	// 옵션체크
	function checkOption() {

		if (document.prdForm.optcode5 != null) {
			if (document.prdForm.optcode5.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode5.focus();
				return false;
			}
		}
		if (document.prdForm.optcode6 != null) {
			if (document.prdForm.optcode6.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode6.focus();
				return false;
			}
		}
		if (document.prdForm.optcode7 != null) {
			if (document.prdForm.optcode7.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode7.focus();
				return false;
			}
		}

		if (document.prdForm.optcode3 != null) {
			if (document.prdForm.optcode3.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode3.focus();
				return false;
			}
		}
		if (document.prdForm.optcode4 != null) {
			if (document.prdForm.optcode4.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode4.focus();
				return false;
			}
		}

		if (document.prdForm.optcode != null) {
			if (document.prdForm.optcode.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode.focus();
				return false;
			}
		}
		if (document.prdForm.optcode2 != null) {
			if (document.prdForm.optcode2.value == "") {
				alert("옵션을 선택하세요");
				document.prdForm.optcode2.focus();
				return false;
			}
		}
		return true;
	}


	// 장바구니에 담기
	function saveBasket(direct) {
		<?
		if ($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) echo "alert('죄송합니다. 품절상품 입니다.');";
		else echo "if(checkOption() && checkOpt01()){ document.prdForm.direct.value = direct; document.prdForm.submit(); }";
		?>
	}

	// 관심상품 등록
	function saveWish() {
		<? if (!empty($wiz_session['id'])) { ?>
			if ($(".wish_btn").hasClass('on')) {
				var frm = document.prdForm;
				frm.action = "/member/my_save.php";
				frm.mode.value = "my_wishdel";
				frm.submit();
			} else {
				if (checkOption()) {
					var frm = document.prdForm;
					frm.action = "/member/my_save.php";
					frm.mode.value = "my_wish";
					frm.submit();
				}
			}
		<? } else { ?>
			alert("로그인 후 이용해주세요.");
		<? } ?>
	}
	//-->
</script>

<!--제품 상세보기 시작-->
<div class="prd_view_wrap clearfix">

	<div class="left_area">
		<div class="img_box">
			<img src="<?= $prd_info->prdimg_M1 ?>" name="reper_img" width="500" height="500">
		</div>
		<div class="thumb_box">
			<? for ($i = 1; $i <= 5; $i++) {
				if (@file($_SERVER["DOCUMENT_ROOT"] . "/data/prdimg/" . $prd_info->{"prdimg_S" . $i})) {
			?>
					<span class="thumb"><img src="/data/prdimg/<?= $prd_info->{"prdimg_S" . $i} ?>" alt="썸네일 이미지" onclick="document.reper_img.src='/data/prdimg/<?= $prd_info->{"prdimg_L" . $i} ?>'" /></span>
			<?	}
			} ?>
			<!-- 이미지 S사이즈 70*70이 기본값이 되게 변경해주세요. 현재 50*50 -->
		</div>
		<script>
			$(document).ready(function() {
				$('.thumb_box .thumb').click(function() {
					$(this).addClass('on').siblings().removeClass('on');
				});
				$('.thumb_box .thumb:first-child').trigger('click');
			});
		</script>
	</div><!-- //left_area -->
	<div class="right_area">
		<!-- 제품정보 -->
		<form name="prdForm" action="prd_save.php" method="post">
			<input type="hidden" name="mode" value="insert">
			<input type="hidden" name="direct" value="">
			<input type="hidden" name="prdcode" value="<?= $prdcode ?>">
			<input type="hidden" name="idx" value="<?= $wish_idx ?>">
			<? if (!strcmp($prd_info->opt_use, "Y") && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))) { ?>
				<input type="hidden" name="opttitle" value="<?= $prd_info->opttitle ?>">
				<input type="hidden" name="opttitle2" value="<?= $prd_info->opttitle2 ?>">
			<? } ?>
			<input type="hidden" name="opttitle3" value="<?= $prd_info->opttitle3 ?>">
			<input type="hidden" name="opttitle4" value="<?= $prd_info->opttitle4 ?>">
			<input type="hidden" name="opttitle5" value="<?= $prd_info->opttitle5 ?>">
			<input type="hidden" name="opttitle6" value="<?= $prd_info->opttitle6 ?>">
			<input type="hidden" name="opttitle7" value="<?= $prd_info->opttitle7 ?>">

			<input type="hidden" name="tmp_sellprice" value="<?= $prd_info->sellprice ?>">
			<input type="hidden" name="opt_price1" value="">
			<input type="hidden" name="opt_price2" value="">
			<input type="hidden" name="opt_price3" value="">

			<input type="hidden" name="tmp_reserve" value="<?= $prd_info->reserve ?>">
			<input type="hidden" name="opt_reserve1" value="">
			<input type="hidden" name="opt_reserve2" value="">
			<input type="hidden" name="opt_reserve3" value="">

			<div class="prd_ttl">
				<h3 class="prd_name"><?= $prd_info->prdname ?></h3>
				<div class="price_box clearfix">
					<span class="sell_price"><?= $sellprice ?></span>
					<? if ($prd_info->conprice > $prd_info->sellprice) { ?>
						<span class="con_price"><strong><?= number_format($prd_info->conprice) ?></strong>원</span>
					<? } ?>
					<div class="prd_sns clearfix">
						<span class="sns twit" onclick="snsTwitter('<?= $prd_info->prdname ?>','http://<?= $HTTP_HOST ?><?= $REQUEST_URI ?>');">트위터 공유하기</span>
						<span class="sns face" onclick="snsFacebook('<?= $prd_info->prdname ?>','http://<?= $HTTP_HOST ?><?= $REQUEST_URI ?>');">페이스북 공유하기</span>
						<!--<span class="sns insta" onclick="#">인스타그램 공유하기</span>
					<span class="sns kakao" onclick="#">카카오톡 공유하기</span>-->
					</div><!-- //prd_sns -->
					<table width="100%">
						<?php
						if (!empty($wiz_session['id']) && empty($prd_info->strprice)) {

							$level_info = level_info();
							$level = $level_info[$wiz_session['level']]['name'];

							$sql = "select * from wiz_level where idx = '$wiz_session[level]'";
							$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
							$row = mysqli_fetch_object($result);

							if ($row->discount > 0) {
								if ($row->distype == "W") {
									$row->distype = "원";
									$member_price = $row->discount;
								} else {
									$row->distype = "%";
									$member_dis = $row->discount / 100;
									$member_price = floor($prd_info->sellprice * $member_dis);
								}
						?>
								<tr align="left">
									<td width="25%" class="p_tit">등급할인액</td>
									<td class="p_info"><?= number_format($member_price) ?>원 &nbsp;<?= number_format($row->discount) ?><?= $row->distype ?> [<?= $level ?>]</td>
								</tr>
						<?
							}
						}
						?>

						<?
						if (
							$prd_info->coupon_use == "Y" &&
							$prd_info->coupon_sdate <= date('Y-m-d') &&
							$prd_info->coupon_edate >= date('Y-m-d') &&
							($prd_info->coupon_limit == "N" || ($prd_info->coupon_limit == "" && $prd_info->coupon_amount > 0))
							&& empty($prd_info->strprice)
						) {
							if ($prd_info->coupon_type == "%") {
								$coupon_dis = $prd_info->coupon_dis / 100;
								$coupon_price = $prd_info->sellprice * $coupon_dis;
							} else {
								$coupon_price = $prd_info->coupon_dis;
							}
						?>

							<input type="hidden" name="coupon_dis" value="<?= $prd_info->coupon_dis ?>">
							<input type="hidden" name="coupon_type" value="<?= $prd_info->coupon_type ?>">
							<tr align="left">
								<td class="p_tit"><b>쿠폰할인액</b></td>
								<td class="p_info">
									<table height=25 border=0 cellpadding=0 cellspacing=0>
										<tr>
											<td id="coupon">
												<font class="coupon"><?= number_format($coupon_price) ?>원 &nbsp;<?= number_format($prd_info->coupon_dis) ?><?= $prd_info->coupon_type ?></font>
											</td>
											<td width="5"></td>
											<td><a href="coupon_down.php?prdcode=<?= $prdcode ?>" class="coupon_down">[쿠폰다운로드]</a></td>
										</tr>
									</table>
								</td>
							</tr>
						<? } ?>
						<? if ($oper_info->reserve_use == "Y" && empty($prd_info->strprice)) { ?>
							<tr align="left">
								<td class="p_tit"><b>적립금</b></td>
								<td class="p_info" id="reserve"><?= number_format($prd_info->reserve) ?>원</td>
							</tr>
						<? } ?>
					</table>
				</div>
			</div><!-- //prd_ttl -->
			<div class="prd_info">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<? if ($sp_img != "") { ?>
						<tr align="left">
							<td width="25%" class="p_tit">제품상태</td>
							<td class="p_info"><?= $sp_img ?></td>
						</tr>
					<? } ?>

					<? if ($prd_info->prdcom != "") { ?>
						<tr align="left">
							<td width="25%" class="p_tit">제조사</td>
							<td class="p_info"><?= $prd_info->prdcom ?></td>
						</tr>
					<? } ?>

					<? if ($prd_info->origin != "") { ?>
						<tr align="left">
							<td width="25%" class="p_tit">원산지</td>
							<td class="p_info"><?= $prd_info->origin ?></td>
						</tr>
					<? } ?>

					<?php
					if (!strcmp($prd_info->info_use, "Y")) {
						if (!empty($prd_info->info_name1)) {
					?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name1 ?></td>
								<td class="p_info"><?= $prd_info->info_value1 ?></td>
							</tr>
						<?php
						}
						if (!empty($prd_info->info_name2)) {
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name2 ?></td>
								<td class="p_info"><?= $prd_info->info_value2 ?></td>
							</tr>
						<?php
						}
						if (!empty($prd_info->info_name3)) {
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name3 ?></td>
								<td class="p_info"><?= $prd_info->info_value3 ?></td>
							</tr>
						<?php
						}
						if (!empty($prd_info->info_name4)) {
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name4 ?></td>
								<td class="p_info"><?= $prd_info->info_value4 ?></td>
							</tr>
						<?php
						}
						if (!empty($prd_info->info_name5)) {
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name5 ?></td>
								<td class="p_info"><?= $prd_info->info_value5 ?></td>
							</tr>
						<?php
						}
						if (!empty($prd_info->info_name6)) {
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->info_name6 ?></td>
								<td class="p_info"><?= $prd_info->info_value6 ?></td>
							</tr>
					<?php
						}
					}
					?>
					<tr align="left">
						<td width="25%" class="p_tit"><strong>판매자정보</strong></td>
						<td class="p_info">
							<?php
							$m_sql = "select * from wiz_mall where id = '$prd_info->mallid'";
							$m_result = mysqli_query($connect, $m_sql) or die(mysqli_error($connect));
							$m_row = mysqli_fetch_array($m_result);

							$m_url = "/shop/minishop.php?mallid=" . $m_row['id'];
							?>
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><a href="<?= $m_url ?>"><?= $m_row['com_name'] ?></a></td>
									<td align="right"><a href="#qna" class="bar">문의하기</a><a href="<?= $m_url ?>">판매자 상품 더보기</a></td>
								</tr>
							</table>
					</tr>
				</table>
			</div><!-- //prd_info -->
			<div class="prd_opt">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<? if (empty($prd_info->strprice)) { ?>
						<tr align="left">
							<td width="25%" class="p_tit">수 량</td>
							<td class="p_info">
								<div class="count_box clearfix">
									<a href="javascript:decAmount();" class="down">-</a>
									<input type=text name=amount value=1 size=2 onChange="checkAmount();" onKeyUp="checkAmount()" class="input">
									<a href="javascript:incAmount();" class="up">+</a>
								</div><!-- //count_box -->
							</td>
						</tr>

						<? if ($prd_info->opttitle5 != "") { ?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle5 ?></td>
								<td class="p_info">
									<select name="optcode5">
										<option value=""> 선택하세요 </option>
										<?
										$opt_list = explode(",", $prd_info->optcode5);
										for ($ii = 0; $ii < count($opt_list); $ii++) {
											echo "<option value='" . $opt_list[$ii] . "'>" . $opt_list[$ii] . "\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>

						<? if ($prd_info->opttitle6 != "") { ?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle6 ?></td>
								<td class="p_info">
									<select name="optcode6">
										<option value=""> 선택하세요 </option>
										<?
										$opt_list = explode(",", $prd_info->optcode6);
										for ($ii = 0; $ii < count($opt_list); $ii++) {
											echo "<option value='" . $opt_list[$ii] . "'>" . $opt_list[$ii] . "\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>

						<? if ($prd_info->opttitle7 != "") { ?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle7 ?></td>
								<td class="p_info">
									<select name="optcode7">
										<option value=""> 선택하세요 </option>
										<?
										$opt_list = explode(",", $prd_info->optcode7);
										for ($ii = 0; $ii < count($opt_list); $ii++) {
											echo "<option value='" . $opt_list[$ii] . "'>" . $opt_list[$ii] . "\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>

						<? if ($prd_info->opttitle3 != "") { ?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle3 ?></td>
								<td class="p_info">
									<select name="optcode3" onChange="checkOpt03()">
										<option value=""> 선택하세요 </option>
										<?
										$opt_list = explode("^^", $prd_info->optcode3);
										for ($ii = 0; $ii < count($opt_list) - 1; $ii++) {
											list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

											if ($price > 0) $price_tmp = " : " . number_format($price) . "원 추가";
											else $price_tmp = "";

											echo "<option value='" . $opt . "^" . $price . "^" . $reserve . "'>" . $opt . $price_tmp . "\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>

						<? if ($prd_info->opttitle4 != "") { ?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle4 ?></td>
								<td class="p_info">
									<select name="optcode4" onChange="checkOpt04()">
										<option value=""> 선택하세요 </option>
										<?
										$opt_list = explode("^^", $prd_info->optcode4);
										for ($ii = 0; $ii < count($opt_list) - 1; $ii++) {
											list($opt, $price, $reserve) = explode("^", $opt_list[$ii]);

											if ($price > 0) $price_tmp = " : " . number_format($price) . "원 추가";
											else $price_tmp = "";

											echo "<option value='" . $opt . "^" . $price . "^" . $reserve . "'>" . $opt . $price_tmp . "\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>

						<?
						if ($prd_info->opt_use == "Y" && (!empty($prd_info->opttitle) || !empty($prd_info->opttitle2))) {
							if (!empty($prd_info->opttitle) && !empty($prd_info->opttitle2)) $prd_info->opttitle2 = "/" . $prd_info->opttitle2;
						?>
							<tr align="left">
								<td width="25%" class="p_tit"><?= $prd_info->opttitle ?><?= $prd_info->opttitle2 ?></td>
								<td class="p_info">
									<?php

									$opt1_arr = explode("^", $prd_info->optcode);
									$opt2_arr = explode("^", $prd_info->optcode2);
									$opt_tmp = explode("^^", $prd_info->optvalue);

									if (count($opt1_arr) - 1 < 1) $opt1_cnt = 1;
									else $opt1_cnt = count($opt1_arr) - 1;

									if (count($opt2_arr) - 1 < 1) $opt2_cnt = 1;
									else $opt2_cnt = count($opt2_arr) - 1;

									$no = 0;
									for ($ii = 0; $ii < $opt1_cnt; $ii++) {
										for ($jj = 0; $jj < $opt2_cnt; $jj++) {
											list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

											$optcode[$no]['optcode'] = $opt1_arr[$ii];
											if (!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode[$no]['optcode'] .= "/";
											$optcode[$no]['optcode'] .= $opt2_arr[$jj];
											$optcode[$no]['price'] = $price;
											$optcode[$no]['reserve'] = $reserve;
											$optcode[$no]['stock'] = $stock;
											$no++;
										}
									}
									?>
									<select name="optcode" onChange="checkOpt01();">
										<option value=""> 선택하세요 </option>
										<?
										for ($ii = 0; $ii < count($optcode); $ii++) {

											$opt_sub_value = $optcode[$ii]['optcode'] . "^" . $optcode[$ii]['price'] . "^" . $optcode[$ii]['reserve'] . "^" . $optcode[$ii]['stock'];

											if ($optcode[$ii]['stock'] <= 0) $optcode[$ii]['stock'] = " [품절]";
											else $optcode[$ii]['stock'] = "";

											if ($optcode[$ii]['price'] > 0) $optcode[$ii]['price'] = " : " . number_format($optcode[$ii]['price']) . "원 추가  ";
											else $optcode[$ii]['price'] = "";

											$opt_sub_txt = $optcode[$ii]['optcode'] . $optcode[$ii]['price'] . $optcode[$ii]['stock'];

											echo "<option value='$opt_sub_value'>$opt_sub_txt\n";
										}
										?>
									</select>
								</td>
							</tr>
						<? } ?>
					<? } ?>
				</table>
			</div><!-- //prd_opt -->
			<div class="prd_price clearfix">
				<span class="p_tit">총 상품금액</span>
				<span class="total_price" id="sellprice"><?= $sellprice ?></span>
			</div><!-- //prd_price -->
			<div class="prd_order_btn clearfix">
				<? if (empty($prd_info->strprice)) { ?>
					<a href="javascript:saveWish();" class="wish_btn<?= ($wish_idx ? ' on' : '') ?>">관심상품</a>
					<a href="javascript:saveBasket('buy');" class="order_btn">구매하기</a>
					<a href="javascript:saveBasket('basket');" class="cart_btn">장바구니</a>
				<? } ?>
			</div><!-- //prd_order_btn -->
		</form>
	</div><!-- //right_area -->

	<div class="prd_detail_area">
		<a name="info"></a>
		<ul class="detail_btn clearfix">
			<li><a href="#info" class="on">상세정보</a></li>
			<li><a href="#del">구매가이드</a></li>
			<li><a href="#qna">상품 Q&#38;A</a></li>
			<li><a href="#review">상품 후기</a></li>
		</ul>
		<div class="prd_cont">
			<?= $prd_info->content ?>
		</div><!-- //prd_cont -->
		<a name="del"></a>
		<ul class="detail_btn clearfix">
			<li><a href="#info">상세정보</a></li>
			<li><a href="#del" class="on">구매가이드</a></li>
			<li><a href="#qna">상품 Q&#38;A</a></li>
			<li><a href="#review">상품 후기</a></li>
		</ul>
		<div class="prd_cont">
			<? $page_type = "prdview";
			include "../inc/page_info.inc"; ?>
			<?= $page_info->content ?>
			<!--     구매가이드         -->
			<?php
			if (!empty($prd_info->mallid)) {
				$sql = "select guide as content from wiz_mall where id = '$prd_info->mallid'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$page_info = mysqli_fetch_object($result);
			} else {
				$page_type = "prdview";
				include "../inc/page_info.inc";
			}
			?>

			<?= $page_info->content ?>
		</div>
		<? if ($oper_info->qna_usetype == "Y") { ?>
			<!--     상품QNA         -->
			<a name="qna"></a>
			<ul class="detail_btn clearfix">
				<li><a href="#info">상세정보</a></li>
				<li><a href="#del">구매가이드</a></li>
				<li><a href="#qna" class="on">상품 Q&#38;A</a></li>
				<li><a href="#review">상품 후기</a></li>
			</ul>
			<div class="prd_cont">
				<? include "prd_qna.inc"; ?>
			</div>
		<? } ?>
		<? if ($oper_info->review_usetype == "Y") { ?>
			<!--     상품리뷰         -->
			<a name="review"></a>
			<ul class="detail_btn clearfix">
				<li><a href="#info">상세정보</a></li>
				<li><a href="#del">구매가이드</a></li>
				<li><a href="#qna">상품 Q&#38;A</a></li>
				<li><a href="#review" class="on">상품 후기</a></li>
			</ul>
			<div class="prd_cont">
				<? include "prd_review.inc"; ?>
			</div>
		<? } ?>
	</div><!-- //prd_detail_area -->
	<? if ($oper_info->prdrel_use == "Y") { ?>
		<!--     관련상품      -->
		<h3 class="prd_rel_ttl">관련상품보기</h3>
		<div class="prd_cont prd_rel_area">
			<? include "prd_rel.inc"; ?>
		</div>
	<? } ?>

	<a href="<?= $prd_list_url ?>?<?= $param ?>" class="go_list">리스트</a>
</div><!-- //prd_view_wrap -->

<?
include "../inc/footer.inc"; 		// 하단디자인
?>