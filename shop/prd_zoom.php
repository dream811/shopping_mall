<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악

// 상품정보 가져오기
$sql = "select * from wiz_product where prdcode='$prdcode'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
$prd_info = mysqli_fetch_object($result);
if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");

if(!empty($prd_info->strprice)) $sellprice = $prd_info->strprice;
else $sellprice = number_format($prd_info->sellprice)."원";

// 인기,신상,추천...
if($prd_info->popular == "Y") $sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
if($prd_info->recom == "Y") $sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
if($prd_info->new == "Y") $sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
if($prd_info->sale == "Y"){ $sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;"; }
if($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

// 상품상세 이미지 보기 증가
$sql = "update wiz_product set deimgcnt = deimgcnt + 1 where prdcode = '$prdcode'";
mysqli_query($connect, $sql) or die(mysqli_error($connect));

// 상품 이미지
for($ii = 1; $ii <= 5; $ii++) {
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$prd_info->{"prdimg_L".$ii})) $prd_info->{"prdimg_L".$ii} = "/images/noimg_L.gif";
	else $prd_info->{"prdimg_L".$ii} = "/data/prdimg/".$prd_info->{"prdimg_L".$ii};
}
?>
<html>
<head>
<title>제품 확대이미지 보기</title>
<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="border:6px solid #979797;" >
	<tr>
		<td valign=top width="520" align="left" style="padding:10px"><img src="<?=$prd_info->prdimg_L1?>" width="500" height="500" name="prdimg"></td>
		<td valign=top width="280">
			<table border=0 width=100% height="100%" cellpadding=0 cellspacing=0 style="padding:10px">
				<tr>
					<td valign=top>
						
						<table border=0 cellpadding=0 cellspacing=0 width=100% align=center>
							<tr><td height=50><font color=#000000><b><?=$prd_info->prdname?></b></font></td></tr>
							<tr><td height=2 bgcolor=#cccccc></td></tr>
							<tr><td valign=top>
							<tr>
								<td bgcolor=#f5f5f5 style="padding:5 0 5 0">
									<table border=0 cellpadding=0 cellspacing=0 width=90% align=center>
										<tr>
											<td height=25 width=80>판매가격</td>
											<td><span class="price_b"><?=$sellprice?></td>
										</tr>
										<? if($oper_info->reserve_use == "Y" && empty($prd_info->strprice)){ ?>
										<tr>
											<td height=25>적립금</td>
											<td><b><?=number_format($prd_info->reserve)?>원</b></td>
										</tr>
										<? } ?>
									</table>
								</td>
							</tr>
							<tr>
								<td style="padding:5 0 5 0">

									<table border=0 cellpadding=0 cellspacing=0 width=90% align=center>
										<? if($prd_info->prdcom != ""){ ?>
										<tr>
											<td height=25 width=80>제조사</td>
											<td><?=$prd_info->prdcom?></td>
										</tr>
										<? } ?>
										<tr>
											<td height=25 width=80>제품상태</td>
											<td><?=$sp_img?></td>
										</tr>
									</table>
									
								</td>
							</tr>
							<tr><td height=1 bgcolor=#dadada></td></tr>
						</table>

					</td>
				</tr>
				<tr>
					<td valign="bottom">
						<table border=0 cellpadding=3 cellspacing=0>
					  <? $imgpath = $_SERVER[DOCUMENT_ROOT]."/data/prdimg"; ?>
					  <tr>
		        <? if(@file($imgpath."/".$prd_info->prdimg_S1)){ ?><td><table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1 solid #cdcdcd"><tr><td align=center><img src="/data/prdimg/<?=$prd_info->prdimg_S1?>" onMouseOver="document.prdimg.src='<?=$prd_info->prdimg_L1?>'"></td></tr></table></td><?}?>
					  <? if(@file($imgpath."/".$prd_info->prdimg_S2)){ ?><td><table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1 solid #cdcdcd"><tr><td align=center><img src="/data/prdimg/<?=$prd_info->prdimg_S2?>" onMouseOver="document.prdimg.src='<?=$prd_info->prdimg_L2?>'"></td></tr></table></td><?}?>
						<? if(@file($imgpath."/".$prd_info->prdimg_S3)){ ?><td><table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1 solid #cdcdcd"><tr><td align=center><img src="/data/prdimg/<?=$prd_info->prdimg_S3?>" onMouseOver="document.prdimg.src='<?=$prd_info->prdimg_L3?>'"></td></tr></table></td><?}?>
					  </tr>
					  </table>
					  <table border=0 cellpadding=3 cellspacing=0>
						<tr>
						<? if(@file($imgpath."/".$prd_info->prdimg_S4)){ ?><td><table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1 solid #cdcdcd"><tr><td align=center><img src="/data/prdimg/<?=$prd_info->prdimg_S4?>" onMouseOver="document.prdimg.src='<?=$prd_info->prdimg_L4?>'"></td></tr></table></td><?}?>
						<? if(@file($imgpath."/".$prd_info->prdimg_S5)){ ?><td><table width=50 height=50 cellpadding=0 cellspacing=0 style="border: 1 solid #cdcdcd"><tr><td align=center><img src="/data/prdimg/<?=$prd_info->prdimg_S5?>" onMouseOver="document.prdimg.src='<?=$prd_info->prdimg_L5?>'"></td></tr></table></td><?}?>
					  </tr>
						</table>
						<br>
						<table align=center border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td><img src="/images/shop/btn_close.gif" border=0 onClick="self.close();" style="cursor:hand"></td>
							</tr>
						</table>
						<br>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
</table>

</body>
</html>