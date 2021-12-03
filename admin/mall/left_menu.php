<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "11-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/mall_list") !== false){ echo 'active'; } ?>"><a href="mall_list.php">입점업체목록 </a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "11-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/mall_out") !== false){ echo 'active'; } ?>"><a href="mall_out.php">탈퇴업체목록 </a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "11-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/링크파account_list일명") !== false){ echo 'active'; } ?>">
        <a href="account_list.php">정산목록</a>
        <ul>
            <li class="<?php if($s_status == "AW"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AW">정산대기</a></li>
            <li class="<?php if($s_status == "AA"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AA">정산요청</a></li>
            <li class="<?php if($s_status == "AI"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AI">정산중</a></li>
            <li class="<?php if($s_status == "AD"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AD">정산보류</a></li>
            <li class="<?php if($s_status == "AC"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AC">정산완료</a></li>
        </ul>
    </li>
    <? } ?>
</ul>