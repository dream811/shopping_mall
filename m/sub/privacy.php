<?
$sub_tit="개인정보취급방침";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<?php
$page_type = "join";
include "../../inc/page_info.inc"; 		// 페이지 정보

$page_info->content		= nl2br($page_info->content);
$page_info->content2	= nl2br($page_info->content2);
?>

<div class="page_cont">
	<h2><?=$sub_tit?></h2>
	<?=$page_info->content2?>
</div>
<? include "../inc/footer.php" ?>
