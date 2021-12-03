<script>
function showPrdimg() {
	document.getElementById("prdimg_more").style.display = "block";
	document.getElementById("btn_more").style.display = "none";
}
</script>
<?php
// 상품 이미지
for($ii = 1; $ii <= 5; $ii++) {
	if($prd_info->{"prdimg_M".$ii} == "/images/noimg_M.gif") $prd_info->{"prdimg_M".$ii} = "";
}
?>
<div style="padding-top:10px; padding-bottom:10px; text-align:center;">
	<div><img src="<?=$prd_info->prdimg_M1?>" /></div>
	<div id="prdimg_more" style="display:none;">
		<?php
		// 상품 이미지
		for($ii = 2; $ii <= 5; $ii++) {
			if(trim($prd_info->{"prdimg_M".$ii}) != "") {
		?>
		<div><img src="<?=$prd_info->{"prdimg_M".$ii}?>" /></div>
		<?php
			}
		}
		?>
	</div>
</div>

<div id="btn_more" class="more_view"><a href="javascript:showPrdimg()">▼ 더보기</a></div>