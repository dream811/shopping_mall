<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "04-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_list") !== false){ echo 'active'; } ?>"><a href="prd_list.php">상품목록</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_input") !== false){ echo 'active'; } ?>"><a href="prd_input.php">상품등록</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_category") !== false){ echo 'active'; } ?>"><a href="prd_category.php?mode=insert">상품분류관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-07") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_brand") !== false){ echo 'active'; } ?>"><a href="prd_brand.php">브랜드관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-04") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_option") !== false){ echo 'active'; } ?>"><a href="prd_option.php">옵션항목 관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-05") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_shortage") !== false){ echo 'active'; } ?>"><a href="prd_shortage.php">재고관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "04-06") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_estimate") !== false){ echo 'active'; } ?>"><a href="prd_estimate.php">상품평관리</a></li>
	<? } ?>
</ul>