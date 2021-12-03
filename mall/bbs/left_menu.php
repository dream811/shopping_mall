<ul class="lnb">
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_oper") !== false){ echo 'active'; } ?>">
    	<a>게시물관리</a>
        <ul>
			<?
			$sql = "select code,title from wiz_bbsinfo where type != 'SCH' and (code like 'mall_%' or code = 'qna')";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($row = mysqli_fetch_object($result)){
					if($row->code == $code) $row->title = "<b>".$row->title."</b>";
			?>
        	<li><a href="list.php?code=<?=$row->code?>"><?=$row->title?></a></li>
			<? } ?>
        </ul>
    </li>

</ul><!-- .lnb -->