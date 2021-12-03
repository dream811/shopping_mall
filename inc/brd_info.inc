<?
if(!empty($brand)){

	$now_position = "<a href='/'>Home</a>";
	$sql = "select * from wiz_brand where idx = '$brand'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_array($result)){

		if($row[subimg] != ""){ $subimg = $row[subimg]; $subimg_type = $row[subimg_type]; }

		if(!strcmp($row['idx'],$brand)){

			if($row[brduse] == "N") error("사용하지 않는 브랜드입니다.");

	   	$catname = $row[brdname];
	   	$brd_info = $row;

	   	$now_position = $now_position." &gt; <a href='/shop/prd_list.php?brand=".$row['idx']."'>$row[brdname]</a>";

		}else{

			$now_position = $now_position." &gt; <a href='/shop/prd_list.php?brand=".$row['idx']."'>$row[brdname]</a>";

		}

		$cat_info->prd_width = $row[prd_width];
		$cat_info->prd_height = $row[prd_height];
		$cat_info->prd_num = $row[prd_num];

	}

	if($subimg_type == "FIL"){

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

}
?>