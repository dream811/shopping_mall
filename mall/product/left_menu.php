<ul class="lnb">
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_list") !== false){ echo 'active'; } ?>"><a href="prd_list.php">상품목록</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_input") !== false){ echo 'active'; } ?>"><a href="prd_input.php">상품등록</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_shortage") !== false){ echo 'active'; } ?>"><a href="prd_shortage.php">재고관리</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/prd_estimate") !== false){ echo 'active'; } ?>"><a href="prd_estimate.php">상품평관리</a></li>
</ul><!-- .lnb -->