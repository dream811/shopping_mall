<div class="all_area">
	<ul class="all_menu">
		<?
		if(!isset($arrCat)) $arrCat = array();
		$sql	= "select catcode, catname, catimg, catimg_over from wiz_category where depthno=1 and catuse != 'N' order by priorno01 asc";
		$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
		while($row = mysqli_fetch_object($result)){

			if($row->catimg == ""){
				$cat_url ="<a href='/shop/prd_list.php?catcode=".$row->catcode."'>".$row->catname."</a>";
			}else{
				if($row->catimg_over == "") $row->catimg_over = $row->catimg;
				if(!isset($no)) $no=0;
				$cat_url = "<a href='/shop/prd_list.php?catcode=".$row->catcode."' onMouseOver=\"displayLay('".($no-1)."');MM_swapImage('c_".$no."','','/data/catimg/".$row->catimg_over."',1)\" onMouseOut=\"MM_swapImgRestore();disableLay('".($no-1)."')\"><a href='/shop/prd_list.php?catcode=".$row->catcode."'><img src='/data/catimg/".$row->catimg."' name='c_".$no."' border=0 id='c_".$no."'></a>";
			}

			unset($arrCat);
			$c_sql	= "select catcode, catname, catimg, catimg_over from wiz_category where catcode like '".substr($row->catcode, 0, 2)."%' and depthno=2 and catuse != 'N' order by priorno02 asc";
			$c_result	= mysqli_query($connect, $c_sql) or error(mysqli_error($connect));
			while($c_row = mysqli_fetch_object($c_result)) $arrCat[] = $c_row;
		?>
		<li>
			<?=$cat_url?>
			<? if(isset($arrCat) && sizeof($arrCat)){ ?>
			<div class="depth_menu clearfix">
				<span class="depth_ttl"><?=$row->catname?></span>
				<ul class="dep">
					<?
					for($i=0; $i<sizeof($arrCat); $i++){
						$c_row = $arrCat[$i];
						if($c_row->catimg == ""){
							$cat_url ="<a href='/shop/prd_list.php?catcode=".$c_row->catcode."'>".$c_row->catname."</a>";
						}else{
							if($c_row->catimg_over == "") $c_row->catimg_over = $c_row->catimg;
							$cat_url = "<a href='/shop/prd_list.php?catcode=".$c_row->catcode."' onMouseOver=\"displayLay('".($no-1)."');MM_swapImage('c_".$no."','','/data/catimg/".$c_row->catimg_over."',1)\" onMouseOut=\"MM_swapImgRestore();disableLay('".($no-1)."')\"><a href='/shop/prd_list.php?catcode=".$c_row->catcode."'><img src='/data/catimg/".$c_row->catimg."' name='c_".$no."' border=0 id='c_".$no."'></a>";
						}

						if($i>0 && $i%9==0) echo '</ul><ul class="dep">';
					?>
					<li><a href="/shop/prd_list.php?catcode=<?=$c_row->catcode?>"><?=$c_row->catname?></a></li><!-- 메뉴명 최대 15글자제한 -->
					<? } ?>
				</ul>
			</div><!-- //depth_menu -->
			<? } ?>
		</li>
		<? } ?>
	</ul>
</div><!-- //all_area -->