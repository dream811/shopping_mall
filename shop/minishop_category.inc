<?php
$sql = "select count(catcode) as cnt from wiz_category where catuse != 'N' and depthno = '1'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$total_category = $row['cnt'];

$sql = "select count(wp.prdcode) as cnt from wiz_product as wp inner join wiz_mall as wm on wp.mallid = wm.id where wp.status = 'Y' and wm.status = 'Y' and mallid = '$mallid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$total_product = $row['cnt'];
?>
<div class="prd_search clearfix">
<form action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="catcode" value="<?=$catcode?>">
<input type="hidden" name="grp" value="<?=$grp?>">
<input type="hidden" name="brand" value="<?=$brand?>">
<input type="hidden" name="mallid" value="<?=$mallid?>">
<input type="hidden" name="orderby" value="<?=$orderby?>">
	<span class="count">전체카테고리 <font color="#FF0033"><?=number_format($total_category)?></font>개 카테고리 / 총 <font color="#FF0033"><?=number_format($total_product)?></font>개 상품이 있습니다.</span>

	<div class="search_bar">
		<input name="prdname" value="<?=$prdname?>" placeholder="상품명을 입력하세요.">
		<button type="submit" class="submit_btn">검색</button>
	</div>
</form>
</div><!-- //prd_search -->
		
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="prd_category">
		<?php
		$cat_no = 0;
		$sql = "select catcode, catname, depthno from wiz_category where catuse != 'N' and depthno = '1' order by priorno01, priorno02, priorno03, priorno04 asc";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		while($row = mysqli_fetch_array($result)) {
			if($cat_no%6 == 0 && $cat_no > 0) echo "<tr>";

			// 현재카테고리에 몇개의 상품이 있는지
			$t_catcode = $row[catcode];
			if($row[depthno] == 1) $t_catcode = substr($row[catcode],0,2);
			if($row[depthno] == 2) $t_catcode = substr($row[catcode],0,4);

			$t_sql = "select count(wcp.idx) as prd_count from wiz_cprelation as wcp inner join wiz_product as wp on wcp.prdcode = wp.prdcode inner join wiz_mall as wm on wp.mallid = wm.id where wcp.catcode like '$t_catcode%' and wp.mallid = '$mallid' and wp.status = 'Y' and wm.status = 'Y'";
			$t_result = mysqli_query($connect, $t_sql) or error(mysqli_error($connect));
			$t_row = mysqli_fetch_object($t_result);
			$prd_count = $t_row->prd_count;
		?>
			<td height="46"><a href="<?=$PHP_SELF?>?mallid=<?=$mallid?>&catcode=<?=$row[catcode]?>"><?=$row[catname]?> (<strong><?=number_format($prd_count)?></strong>)</a></td>
		<?php
			$cat_no++;
		}
	  	if(($cat_no%6) > 0){ for($i = 0; $i < (6-($cat_no%6)); $i++){ echo "<td></td>"; } }
		?>
</table>
			
	