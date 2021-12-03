<?
if(isset($cat_info->depthno) && $cat_info->depthno == 1) $tmp_catcode = substr($catcode,0,2);
else if(isset($cat_info->depthno) && $cat_info->depthno == 2) $tmp_catcode = substr($catcode,0,4);
else if(isset($cat_info->depthno) && $cat_info->depthno == 3) $tmp_catcode = substr($catcode,0,4);

$depthno = isset($cat_info->depthno) ? $cat_info->depthno : "";
if($depthno < 4) $depthno++;

/*
2단계까지만 보여줄경우
$tmp_catcode = isset($catcode) ? substr($catcode,0,2) : "";
if($cat_info->depthno < 2) $cat_info->depthno++;
*/
if(!isset($tmp_catcode)) $tmp_catcode = "";

$sql = "select catcode, catname, depthno from wiz_category where catuse != 'N' and catcode like '$tmp_catcode%' and depthno = $depthno order by priorno02, priorno03 asc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

if($total > 0){
?>

	<table width="100%" class="prd_category">
		<?
		if(!isset($catcode)) $catcode = "";
		$no = 0;
		while($row = mysqli_fetch_object($result)){
			// 현재카테고리에 몇개의 상품이 있는지
			$t_catcode = $row->catcode;
			if($row->depthno == 2) $t_catcode = substr($row->catcode,0,4);

			$t_sql = "select count(idx) as prd_count from wiz_cprelation where catcode like '$t_catcode%'";
			$t_result = mysqli_query($connect, $t_sql) or error(mysqli_error($connect));
			$t_row = mysqli_fetch_object($t_result);
			$prd_count = $t_row->prd_count;

			if(!strcmp($row->catcode, $catcode)) $total = $prd_count;

			if($catcode == $row->catcode) $row->catname = "<strong>".$row->catname." (".$prd_count.")</strong>";
			else $row->catname = $row->catname."";
			if($no%6 == 0) echo "<tr>";

			echo "<td height=\"46\" align=\"left\"><a href='/shop/prd_list.php?catcode=".$row->catcode."'>".$row->catname."</a></td>";

			$no++;

		}
		if(($total%6) > 0){ for($i = 0; $i < (6-($total%6)); $i++){ echo "<td></td>"; } }
		?>
	</table><!-- 첫 줄 첫 칸은 해당 카테고리 전체보기 메뉴 추가(1차,2차 상관없이) / 선택된 td에 on클래스 추가  -->

<?
}
?>