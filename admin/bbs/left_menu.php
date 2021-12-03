<ul class="lnb">
	<? if(strpos($wiz_admin['permi'], "08-01") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/bbs_pro_list") !== false){ echo 'active'; } ?>"><a href="bbs_pro_list.php">게시판관리</a></li>
    <? } ?>
    
    <? if(strpos($wiz_admin['permi'], "08-02") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/bbs_list") !== false){ echo 'active'; } ?>">
    	<a>게시물관리</a>
        <ul>
			<?
            $sql = "select code,title from wiz_bbsinfo where type != 'SCH'";
            $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
            if(!isset($code)) $code = "";    
            while($row = mysqli_fetch_object($result)){
                if($row->code == $code) $row->title = "<b>".$row->title."</b>";
            ?>
            <li><a href="bbs_list.php?code=<?=$row->code?>"><?=$row->title?></a></li>
            <? } ?>
        </ul>
    </li>
    <? } ?>
</ul>