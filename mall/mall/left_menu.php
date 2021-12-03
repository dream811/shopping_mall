<ul class="lnb">
    <li class="<?php if($s_status == ""){ echo 'active'; } ?>"><a href="account_list.php">정산목록</a></li>
    <li class="<?php if($s_status == "AW"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AW">정산대기</a></li>
    <li class="<?php if($s_status == "AA"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AA">정산요청</a></li>
    <li class="<?php if($s_status == "AI"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AI">정산중</a></li>
    <li class="<?php if($s_status == "AD"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AD">정산보류</a></li>
    <li class="<?php if($s_status == "AC"){ echo 'active'; } ?>"><a href="account_list.php?s_status=AC">정산완료</a></li>
</ul><!-- .lnb -->