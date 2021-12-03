<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "09-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>   
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/sch_input") !== false){ echo 'active'; } ?>"><a href="sch_input.php?mode=update&code=schedule">일정설정</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "09-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/list") !== false){ echo 'active'; } ?>"><a href="list.php?code=schedule">일정보기</a></li>
    <? } ?>
</ul>