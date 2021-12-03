<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "03-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/popup_list") !== false){ echo 'active'; } ?>"><a href="popup_list.php">팝업관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/banner_list") !== false){ echo 'active'; } ?>"><a href="banner_list.php">배너관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_company") !== false){ echo 'active'; } ?>"><a href="page_company.php">회사소개</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-04") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_join") !== false){ echo 'active'; } ?>"><a href="page_join.php">회원가입</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-05") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_prdview") !== false){ echo 'active'; } ?>"><a href="page_prdview.php">상품상세</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-06") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_basket") !== false){ echo 'active'; } ?>"><a href="page_basket.php">장바구니</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-07") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_center") !== false){ echo 'active'; } ?>"><a href="page_center.php">고객센터</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-08") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_guide") !== false){ echo 'active'; } ?>"><a href="page_guide.php">이용안내</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "03-09") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_privacy") !== false){ echo 'active'; } ?>"><a href="page_privacy.php">개인정보보호정책</a></li>
    <? } ?>
    
    <? //if(strpos($wiz_admin['permi'], "03-10") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <!--<li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_other") !== false){ echo 'active'; } ?>"><a href="page_other.php">기타페이지 </a></li>-->
    <? //} ?>
    
    <? //if(strpos($wiz_admin['permi'], "03-11") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <!--<li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_content") !== false){ echo 'active'; } ?>"><a href="page_content.php">추가페이지 </a></li>-->
    <? //} ?>
    
    <? if(strpos($wiz_admin['permi'], "03-12") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_mallinfo") !== false){ echo 'active'; } ?>"><a href="page_mallinfo.php">입점안내</a></li>
    <? } ?>
    <? if(strpos($wiz_admin['permi'], "03-13") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/page_malljoin") !== false){ echo 'active'; } ?>"><a href="page_malljoin.php">입점신청</a></li>
    <? } ?>
</ul>