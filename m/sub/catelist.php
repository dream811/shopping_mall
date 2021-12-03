<?
$sub_tit="카테고리";
?>
<? include "../inc/header.php" ?>
<body>
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>
<? include "../inc/sub_title.php" ?>

<?php
$prdlist_url = "../sub/prdlist.php";
?>
<div class="cate_list"><ul>
	<li><a href="<?=$prdlist_url?>">전체상품 보기<div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<?php
	$sql = "select catcode, catname from wiz_category where catuse != 'N' and depthno = 1 order by priorno01 asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_array($result)) {
		$prd_list_url = $prdlist_url."?catcode=".$row[catcode];
	?>
	<li><a href="<?=$prd_list_url?>"><?=$row[catname]?><div><img src="../img/sub/catelist_allow.gif" /></div></a></li>
	<?php
	}
	?>
</ul></div>
<? include "../inc/footer.php" ?>
</body>
</html>