<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "06-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_list") !== false){ echo 'active'; } ?>"><a href="member_list.php">회원목록</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "06-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_level") !== false){ echo 'active'; } ?>"><a href="member_level.php">등급관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "06-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_qna") !== false){ echo 'active'; } ?>"><a href="member_qna.php">1:1 상담관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "06-04") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_out") !== false){ echo 'active'; } ?>"><a href="member_out.php">탈퇴회원</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "06-05") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_email") !== false){ echo 'active'; } ?>"><a href="member_email.php">메일발송</a></li>
    <? } ?>
    
    <? if((strpos($wiz_admin['permi'], "06-06") !== false || !strcmp($wiz_admin['designer'], "Y")) && !strcmp($shop_info->sms_use, "Y")) { ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_sms") !== false){ echo 'active'; } ?>"><a href="member_sms.php">SMS발송</a></li>
    <? } ?>
    
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/member_analy") !== false){ echo 'active'; } ?>"><a href="member_analy.php">회원통계</a></li>
</ul>