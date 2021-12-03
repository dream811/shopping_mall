<? include_once "../../inc/oper_info.inc"; ?>
<? $tmp_menu = explode("_", basename($PHP_SELF)); ?>
<ul class="lnb">
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/order_list") !== false){ echo 'active'; } ?>"><a href="order_list.php">전체주문목록</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/cancel_list") !== false){ echo 'active'; } ?>"><a href="cancel_list.php">주문취소목록</a></li>
</ul><!-- .lnb -->