<? include_once "../../inc/oper_info.inc"; ?>
<? $tmp_menu = explode("_", basename($PHP_SELF)); ?>
<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "05-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/order_list") !== false){ echo 'active'; } ?>"><a href="order_list.php">전체주문목록</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/cancel_list") !== false){ echo 'active'; } ?>"><a href="cancel_list.php">주문취소목록</a></li>
    <? if(!strcmp($oper_info->tax_use, "Y")) { ?>
    <li class="<?php if($tax_type == "T"){ echo 'active'; } ?>"><a href="tax_list.php?tax_type=T">세금계산서</a></li>
    <li class="<?php if($tax_type == "C"){ echo 'active'; } ?>"><a href="tax_list.php?tax_type=C">현금영수증</a></li>
    <? } ?>
</ul>
<? } ?>