<?
$sub_tit="쿠폰내역";
?>
<? include "../inc/header.php" ?>
<body>
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>
<? include "../inc/sub_title.php" ?>


<div style="padding-top:10px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="join_input_table">
  <tr>
    <th style="width:20%">기간</th>
    <th>쿠폰명</th>
    <th style="width:10%">할인액</th>
    <th style="width:15%">사용여부</th>
  </tr>
	<?php
	$memid = $wiz_session['id'];

	$no = 0;
	$sql = "select * from wiz_mycoupon where memid = '$memid' and coupon_sdate <= curdate() and coupon_edate >= curdate() order by idx desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)) {
		if($row->coupon_use == "Y") $row->coupon_use = "사용함";
		else if($row->coupon_edate < date("Y-m-d")) $row->coupon_use = "기간만료";
		else $row->coupon_use = "미사용";
	?>
  <tr>
    <td><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
    <td><?=$row->coupon_name?></td>
    <td style="text-align:right"><?=$row->coupon_dis?><?=$row->coupon_type?></td>
    <td style="text-align:center"><?=$row->coupon_use?></td>
  </tr>
  <?php
		$no++;
	}

	if($no <= 0) {
	?>
	<tr>
		<td colspan="4"><div style="padding:20px 10px; text-align:center;"><b>등록된 쿠폰이 없습니다.</b></div></td>
	</tr>
	<?php
	}
	?>
</table>
</div>

<!--div class="btn">
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="left"><tr><td width="5"><img src="../img/sub/btn_gray_left.gif" /></td><td><input type="button" class="btn_grat_big" value="쿠폰번호 등록하기" onClick="location.href='payment.php'" /></td><td width="5"><img src="../img/sub/btn_gray_right.gif" /></td></tr></table>
</div-->

<div class="graybox" style="margin-top:10px; margin-left:10px; margin-right:10px;">
※ 주문서 작성시 해등 쿠폰번호를 입력하시면 됩니다.<br /><br />

<b>ⓘ 유의사항</b><br />
1. 쿠폰별 사용가능금액과 기간이 정해져 있습니다.<br />
2. 주문서당 1개의 쿠폰만 사용 가능합니다.<br />
3. 반품/환불/취소된 경우 재사용 할 수 없습니다.<br />
4. 각 품목 및 카테고리별 제한이 있을 수 있습니다.<br />
5. 할인/적립쿠폰은 적립금 할인 등을 제외한 실제 결제 금액에 적용됩니다.<br />
6. 해당 상품에 대한 쿠폰은 해당 상품만 구매시 적용이 가능합니다.<br />
7. 배송비 무료 쿠폰은 해당 배송에만 적용됩니다.<br />
(기본 배송비 무료는 당일,해외, 기타 배송에는 미적용 )<br />
</div>

<? include "../inc/footer.php" ?>
</body>
</html>