<?
$sub_tit="이용안내";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<?php
$page_type = "guide";
include "../../inc/page_info.inc"; 		// 페이지 정보

//page_info->content		= nl2br($page_info->content);
?>

<div class="page_cont">
	<h2><?=$sub_tit?></h2>
	<?=$page_info->content?>
</div>
<? include "../inc/footer.php" ?>
