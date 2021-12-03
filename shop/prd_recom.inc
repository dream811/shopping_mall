<?
if(($catcode != "" && $cat_info->recom_use == "Y") || (!empty($brand) && !strcmp($brd_info[recom_use], "Y"))){
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="10"></td></tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td style="border:3px solid #eaeaea;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td height="37" style="border-bottom:1px solid #e5e5e5;"><img src="/images/shop/recom_tit.gif" /></td>
			  </tr>
			  <tr>
			    <td style="padding-top:8px;" align="center">

						<?

						if(!empty($brand)) $brand_sql = " and wp.brand = '$brand' ";

						$tmpcode = substr($catcode,0,2);
						$sql = "select wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.prdimg_M1, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.stock, wp.conprice,
										wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate
										from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
										inner join wiz_category wcat on wc.catcode = wcat.catcode
										left join wiz_mall as wm on wp.mallid = wm.id
										where wc.catcode like '$tmpcode%' and wp.recom = 'Y' and wp.showset != 'N' and wcat.catuse != 'N' and wp.status = 'Y' and (wm.status = 'Y' or ifnull(wm.status, 1) = 1) $brand_sql
										order by wp.prior desc, prdcode desc limit 5";
						$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
						$total = mysqli_num_rows($result);

						if($total > 0) {
						?>

			    	<table width="99%" border="0" cellpadding="0" cellspacing="0">
			        <tr valign="top">
			        	<?
								$no = 0;
								while($row = mysqli_fetch_object($result)){

									// 상품아이콘
									$sp_img = "";
									if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
									if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
									if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
									if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
									if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
									if($row->shortage == "Y" || $row->stock <= 0) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

									$prdicon_list = explode("/",$row->prdicon);
									for($ii=0; $ii<count($prdicon_list)-1; $ii++){
										$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
									}

									// 쿠폰아이콘
									$coupon_img = "";
									if(
									$row->coupon_use == "Y" &&
									$row->coupon_edate >= date('Y-m-d') &&
									($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
									){

										$coupon_img = "<font class=coupon>".$row->coupon_dis.$row->coupon_type."</font> <img src='/images/icon_coupon.gif' align='absmiddle'>";
									}

									// 정상가(판매가보다 높을경우 할인표시)
									$conprice = "";
									if($row->conprice > $row->sellprice){
										$conprice = "<s>".number_format($row->conprice)."원</s> → ";
									}

									$sellprice = number_format($row->sellprice)."원";

									if($no != 0 ) echo "<td width='1' background='/images/prdline_dot_bg.gif' valign='top'></td>";

									// 상품 이미지
									if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
									else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;
								?>
			          <td width="20%" align="center">
			          	<table width="140" border="0" cellpadding="0" cellspacing="0" class="pro_list">
			              <tr>
			                <td class="prd"><a href="prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>"><img src="<?=$row->prdimg_R?>" border="0" width="<?=$prd_width?>" height="<?=$prd_height?>"></a></td>
			              </tr>
			              <tr>
			                <td height="15"></td>
			              </tr>
			              <tr>
			                <td class="subject"><a href="prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>"><?=cut_str($row->prdname,25)?></a></td>
			              </tr>
			              <tr>
			                <td class="price"><?=$conprice?><?=$sellprice?></td>
			              </tr>
			              <tr>
			                <td class="coupon"><?=$sp_img?> <?=$coupon_img?></td>
			              </tr>
			              <tr>
			                <td height="20"></td>
			              </tr>
			          	</table>
			          </td>
			          <?
									$no++;
								}
								?>

			        </tr>
			      </table>
			      <?
						}else{
						?>
						<table width="99%" border="0" cellpadding="0" cellspacing="0">
			        <tr><td align="center" height="40">등록된 추천상품이 없습니다.</td></tr>
			      </table>
						<?
						}
						?>
					</td>
				</tr>
			</table>

		</td>
	</tr>
</table>
<?
}
?>