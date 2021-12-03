<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "10-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/pollinfo_input") !== false){ echo 'active'; } ?>"><a href="pollinfo_input.php?mode=update&code=poll">설문설정</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "09-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/poll_list") !== false){ echo 'active'; } ?>"><a href="poll_list.php?code=poll">설문목록</a></li>
    <? } ?>
</ul>