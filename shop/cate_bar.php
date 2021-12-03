<form action="/shop/prd_list.php" method="get">
<input type="hidden" name="catcode">
<div class="cate_history clearfix">
    <div class="container">
        <ul class="clearfix">
            <li><a href="/">홈 바로가기</a></li>
            <li>
                <select onchange="this.form.catcode.value=this.value; this.form.submit();">
					<option value="">전체보기</option>
					<?
					$sql	= "select catcode, catname, catimg, catimg_over from wiz_category where depthno=1 and catuse != 'N' order by priorno01 asc";
					$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
					if(!isset($catcode)) $catcode="";
					while($row = mysqli_fetch_object($result)){
					?>
					<option value="<?=$row->catcode?>"<?=(substr($catcode, 0, 2)==substr($row->catcode, 0, 2) ? ' selected' : '')?>><?=$row->catname?></option>
					<? } ?>
                </select><!-- 1차 메뉴 select -->
            </li>
            <li>
                <select onchange="this.form.catcode.value=this.value; this.form.submit();">
					<option value="">전체보기</option>
					<?
					if($catcode){
						$sql	= "select catcode, catname, catimg, catimg_over from wiz_category where catcode like '".substr($catcode, 0, 2)."%' and depthno=2 and catuse != 'N' order by priorno02 asc";
						$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
						while($row = mysqli_fetch_object($result)){
					?>
					<option value="<?=$row->catcode?>"<?=(substr($catcode, 0, 4)==substr($row->catcode, 0, 4) ? ' selected' : '')?>><?=$row->catname?></option>
					<? }} ?>
                </select><!-- 2차 메뉴 select -->
            </li>
			<li>
                <select onchange="this.form.catcode.value=this.value; this.form.submit();">
					<option value="">전체보기</option>
					<?
					if($catcode){
						$sql	= "select catcode, catname, catimg, catimg_over from wiz_category where catcode like '".substr($catcode, 0, 4)."%' and depthno=3 and catuse != 'N' order by priorno03 asc";
						$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
						while($row = mysqli_fetch_object($result)){
					?>
					<option value="<?=$row->catcode?>"<?=(substr($catcode, 0, 6)==substr($row->catcode, 0, 6) ? ' selected' : '')?>><?=$row->catname?></option>
					<? }} ?>
                </select><!-- 3차 메뉴 select -->
            </li>
        </ul>
    </div>
</div><!-- //cate_history -->
</form>