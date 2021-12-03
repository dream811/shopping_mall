<?
if(!empty($catcode)){

	$now_position = "<a href='/' class='home_ico'>Home</a>";
	$catcode1 = substr($catcode,0,2);
	$catcode2 = substr($catcode,0,4);
	$sql = "select * from wiz_category where catuse != 'N' and (catcode like '$catcode1%' and depthno = 1)
	                                             or (catcode like '$catcode2%' and depthno = 2)
	                                             or (catcode = '$catcode') order by priorno01 asc, priorno02 asc, priorno03 asc";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_object($result)){

		if($row->subimg != ""){ $subimg = $row->subimg; $subimg_type = $row->subimg_type; }

		if($row->catcode == $catcode){

			if($row->catuse == "N") error("사용하지 않는 분류입니다.");

	   	$catname = $row->catname;
		
	   	$cat_info = $row;
	   	$now_position = $now_position."<a href='/shop/prd_list.php?catcode=$row->catcode'>$row->catname</a>";

		}else{

			$now_position = $now_position."<a href='/shop/prd_list.php?catcode=$row->catcode'>$row->catname</a>";

		}
	}
	
	if(isset($subimg_type) && $subimg_type == "FIL"){

		$img_ext = substr($subimg,-3);

		if($img_ext == "swf"){
			$subimg = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\">";
			$subimg .= "<param name=\"movie\" value=\"/data/subimg/$subimg\">";
			$subimg .= "<param name=\"quality\" value=\"high\">";
			$subimg .= "<embed src=\"/data/subimg/$subimg\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>";
			$subimg .= "</object>";
		}else{
			$subimg = "<img src='/data/subimg/$subimg'>";
		}

	}

}else{
	$cat_info = new StdClass();
	$cat_info->prd_width= "";
	$cat_info->prd_height= "";
	$cat_info->prd_num= "";
	
	if(!isset($grp)) $grp="";
	if(!isset($catname)) $catname="";

	$page_type = $grp;
	include $_SERVER['DOCUMENT_ROOT']."/inc/page_info.inc";

	if($grp == "new") $catname = "<a href='/shop/prd_list.php?grp=new'>신상품</a>";
	else if($grp == "recom") $catname = "<a href='/shop/prd_list.php?grp=recom'>MD추천상품</a>";
	else if($grp == "sale") $catname = "<a href='/shop/prd_list.php?grp=sale'>세일상품</a>";
	else if($grp == "popular") $catname = "<a href='/shop/prd_list.php?grp=popular'>인기상품</a>";
	else if($grp == "best") $catname = "<a href='/shop/prd_list.php?grp=best'>베스트상품</a>";

	//$now_position .= "<a href='/' class='home_ico'>Home</a> $catname";
	$now_position = "<a href='/' class='home_ico'>Home</a> $catname";
}
?>