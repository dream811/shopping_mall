<?
$sub_tit="마이페이지";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<?php

$memid = $wiz_session['id'];
if($memid != "") {

	// 관심상품
	$sql = "select count(*) as cnt from wiz_wishlist where memid = '$memid'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	$wish_cnt = $row['cnt'];

	// 주문내역
	$sql = "select count(*) as cnt from wiz_order where send_id = '$memid' and status != ''";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	$order_cnt = $row['cnt'];

	// 쿠폰내역
	$sql = "select count(*) as cnt from wiz_mycoupon where memid = '$memid' and coupon_use != 'Y' and coupon_sdate <= curdate() and coupon_edate >= curdate()";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	$coupon_cnt = $row['cnt'];

}
?>
<div class="cate_list"><ul>
	<li><a href="../sub/myinfo.php">회원정보수정<div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<li><a href="../sub/wishlist.php">관심상품 <small>(<?=number_format($wish_cnt)?>)</small><div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<li><a href="../sub/orderlist.php">주문내역 <small>(<?=number_format($order_cnt)?>)</small><div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<li><a href="../sub/coupon.php">쿠폰내역 <small>(<?=number_format($coupon_cnt)?>)</small><div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<li><a href="../sub/agreement.php">이용약관<div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
</ul></div>
<? include "../inc/footer.php" ?>
