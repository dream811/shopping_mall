<table width="100%" border="0" cellpadding="0" cellspacing="0">
<?
if($prd_width == "") $prd_width = "140";		// 상품이미지 너비
if($prd_height == "") $prd_height = "140";	// 상품이미지 높이

$prd_width = "width='$prd_width'";
$prd_height = "height='$prd_height'";

$no = 0;
$sql = "select distinct wp.prdcode,wp.prdname,wp.conprice,wp.sellprice,wp.strprice,wp.prdimg_R,wp.prdicon,wp.conprice,wp.coupon_use,wp.coupon_type,wp.coupon_dis,wp.coupon_amount,wp.coupon_limit,wp.coupon_sdate,wp.coupon_edate
		from wiz_product as wp inner join wiz_cprelation as wcp on wp.prdcode = wcp.prdcode
		inner join wiz_category as wc on wcp.catcode = wc.catcode
		inner join wiz_mall as wm on wp.mallid = wm.id
		where wp.$prd_type = 'Y' and wp.showset != 'N' and wc.catuse != 'N' and wp.status = 'Y' and wm.status = 'Y' order by wp.prior desc limit $prd_num";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
while($row = mysqli_fetch_object($result)){

	// 상품 이미지
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
	else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;
	$prdimg_R = "<a href=/shop/prd_view.php?prdcode=$row->prdcode><img src='$row->prdimg_R' border=0 $prd_width $prd_height></a>";
	$prdname = "<a href=/shop/prd_view.php?prdcode=$row->prdcode>".cut_str($row->prdname,$prd_len)."</a>";

	// 상품아이콘
	$sp_img = "";
	$prdicon_list = explode("/",$row->prdicon);
	for($ii=0; $ii<count($prdicon_list)-1; $ii++){
		$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
	}

	// 쿠폰아이콘
	$coupon_img = "";
	if(
	$row->coupon_use == "Y" &&
	$row->coupon_sdate <= date('Y-m-d') &&
	$row->coupon_edate >= date('Y-m-d') &&
	($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
	){
		$coupon_img = "<font class=coupon>".$row->coupon_dis.$row->coupon_type."</font> <img src='/images/icon_coupon.gif' align='absmiddle'>";
	}

	// 정가
	$conprice = "";
	if($row->conprice > $row->sellprice){
		$conprice = "<s>".number_format($row->conprice)."원</s> → ";
	}

	// 판매가
	$sellprice = $row->sellprice;
	if(!empty($row->strprice)) {
		$conprice = "";
		$sellprice = $row->strprice;
	} else {
		$sellprice = number_format($row->sellprice)."원";
	}

	if($no%$prd_row == 0) echo "<tr valign=top>";
?>
	<td align="center">
		<table width="140" border="0" cellpadding="0" cellspacing="0" class="pro_list">
			<tr><td class="prd"><?=$prdimg_R?></td></tr>
			<tr><td height="5"></td></tr>
			<tr><td align="center"><?=$prdname?></td></tr>
			<tr><td class="price"><?=$conprice?> <?=$sellprice?></td></tr>
			<tr><td class="coupon"><?=$sp_img?><?=$coupon_img?></td></tr>
			<tr><td height="10"></td></tr>
		</table>
	</td>
<?
	$no++;
}
?>
</table>