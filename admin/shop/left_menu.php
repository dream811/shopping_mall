<ul class="lnb">
    <? if(strpos($wiz_admin['permi'], "01-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_info") !== false){ echo 'active'; } ?>"><a href="shop_info.php">기본정보설정</a></li>
    <? } ?>
    
	<? if(strpos($wiz_admin['permi'], "01-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_oper") !== false){ echo 'active'; } ?>">
    	<a href="shop_oper.php">운영정보설정</a>
        <ul>
        	<li><a href="shop_oper.php#pay">결제정보</a></li>
        	<li><a href="shop_oper.php#del">배송정보</a></li>
        	<li><a href="shop_oper.php#res">적립금정보</a></li>
        </ul>
    </li>
	<? } ?>
    
    <? if(strpos($wiz_admin['permi'], "01-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_mailsms") !== false){ echo 'active'; } ?>"><a href="shop_mailsms.php">메일<? if(!strcmp($shop_info->sms_use, "Y")) { ?> / SMS<? } ?>설정</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_mailtest") !== false){ echo 'active'; } ?>"><a href="shop_mailtest.php">메일발송테스트</a></li>
    <? } ?>
    
    <? if((strpos($wiz_admin['permi'], "01-04") !== false || !strcmp($wiz_admin['designer'], "Y")) && !strcmp($shop_info->sms_use, "Y")) { ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_smsfill") !== false){ echo 'active'; } ?>"><a href="shop_smsfill.php">SMS관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "01-05") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_admin") !== false){ echo 'active'; } ?>"><a href="shop_admin.php">관리자설정</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "01-06") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_trade") !== false){ echo 'active'; } ?>"><a href="shop_trade.php">거래처관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "01-07") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_coupon") !== false){ echo 'active'; } ?>"><a href="shop_coupon.php">쿠폰관리</a></li>
    <? } ?>
</ul><!-- .lnb -->