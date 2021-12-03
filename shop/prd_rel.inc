<? if(!strcmp($oper_info->prdrel_use, "Y")) { ?>
<div class="prd_list_wrap">
<ul class="prd_list clearfix">
<?
$no = 0;
$rel_sql = "select wr.idx,wp.prdcode,wp.prdname,wp.prdimg_R,wp.sellprice,wp.strprice from wiz_prdrelation wr inner join wiz_product wp on wr.relcode = wp.prdcode inner join wiz_mall as wm on wp.mallid = wm.id where wr.prdcode = '$prdcode' and wp.showset != 'N' and wp.status = 'Y' and wm.status = 'Y'";
$rel_result = mysqli_query($connect, $rel_sql);
while($rel_row = mysqli_fetch_object($rel_result)){
	if($no%5 == 0) echo "";

	if(!empty($rel_row->strprice)) $rel_row->sellprice = $rel_row->strprice;
	else $rel_row->sellprice = number_format($rel_row->sellprice)."원";
?>
    <li>
    	<button type="button" class="wish_btn">관심상품 등록</button><!-- 클릭하면 관심상품 등록 얼럿과 함께 on 클래스 추가 / 등록된 상태에서 다시 클릭하면 관심상품 해제 얼럿과 함께 on 클래스 삭제 -->
		<a href="/shop/prd_view.php?prdcode=<?=$rel_row->prdcode?>">
			<div class="img_box">
				<img src="/data/prdimg/<?=$rel_row->prdimg_R?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
			</div>
			<dl>
				<dt><?=cut_str($rel_row->prdname,50)?></dt>
				<dd class="icon"><?=$coupon_img?><?=$sp_img?></dd>
				<dd>
					<span class="price">
						<?=$rel_row->sellprice?>
					</span>
					<span class="cost"><?=$rel_row->conprice?></span>
				</dd>
			</dl>
		</a>
    </li>
    
<?
	$no++;
}
?>
</ul>
</div>
<? } ?>