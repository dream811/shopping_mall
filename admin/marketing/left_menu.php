<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "07-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/connect_list") !== false){ echo 'active'; } ?>"><a href="connect_list.php">접속자분석</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "07-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/connect_refer") !== false){ echo 'active'; } ?>"><a href="connect_refer.php">접속경로분석</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/connect_osbrowser") !== false){ echo 'active'; } ?>"><a href="./connect_osbrowser.php">OS/브라우저</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "07-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/analy_paymethod") !== false){ echo 'active'; } ?>"><a href="analy_paymethod.php">매출통계분석</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "07-04") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/analy_prd") !== false){ echo 'active'; } ?>"><a href="analy_prd.php">상품통계분석</a></li>
    <? } ?>
    
    <?/* if(strpos($wiz_admin['permi'], "07-05") !== false || !strcmp($wiz_admin[designer], "Y")){ 
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/analy_member") !== false){ echo 'active'; } ?>"><a href="analy_member.php">회원통계 </a></li>
    <? } */?>
</ul>