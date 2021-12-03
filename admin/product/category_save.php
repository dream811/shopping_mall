<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

$subimg_path = "../../data/subimg";
$catimg_path = "../../data/catimg";

if($mode == "insert"){

	// 카테고리명에 따음표 들어가면 상품등록시 스크립트 에러
	$catname = trim($catname);
	$catname = str_replace("\"","”",$catname);
	$catname = str_replace("'","′",$catname);
	if($catname == "") error("분류명을 입력하세요.");

	// 카테고리넘버,깊이
	$parent_catcode = $catcode;

	$catnum1 = substr($catcode,0,2);
	$catnum2 = substr($catcode,2,2);
	$catnum3 = substr($catcode,4,2);
	$catnum4 = substr($catcode,6,2);

	if(empty($depthno)) $depthno = 0;

	if($depthno == 0){ $sposi = 1; $tmpcode = ""; }
	else if($depthno == 1){  $sposi = 3;  $tmpcode = $catnum1; }
	else if($depthno == 2){  $sposi = 5;  $tmpcode = $catnum1.$catnum2; }
	else if($depthno == 3){  $sposi = 7;  $tmpcode = $catnum1.$catnum2.$catnum3; }

	$sql = "select max(substring(catcode,$sposi,2)) as catnum from wiz_category where catcode like '$tmpcode%'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);
	$row->catnum = substr(++$row->catnum."0",0,2);

	if($depthno == 0){ $catcode = $row->catnum."000000";}
	else if($depthno == 1){  $catcode = $catnum1.$row->catnum."0000";}
	else if($depthno == 2){  $catcode = $catnum1.$catnum2.$row->catnum."00";}
	else if($depthno == 3){  $catcode = $catnum1.$catnum2.$catnum3.$row->catnum;}


	// 우선순위 설정
	$sql = "select * from wiz_category where catcode like '$tmpcode%' order by priorno01 desc, priorno02 desc, priorno03 desc, priorno04 desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);
	$priorno01 = $row->priorno01;
	$priorno02 = $row->priorno02;
	$priorno03 = $row->priorno03;
	$priorno04 = $row->priorno04;

	if($depthno == 0){ ++$priorno01; }
	else if($depthno == 1){  ++$priorno02;}
	else if($depthno == 2){  ++$priorno03;}
	else if($depthno == 3){  ++$priorno04;}

	$depthno++;


	// 카테고리 타이틀 저장
	if($subimg_type == "FIL"){
		if($subimg['size'] > 0){
			file_check($subimg['name']);
			$subimg_ext = strtolower(substr($subimg['name'],-3));
			$subimg_name = $catcode."_sub.".$subimg_ext;
			copy($subimg['tmp_name'], $subimg_path."/".$subimg_name);
			chmod($subimg_path."/".$subimg_name, 0606);
		}
	}else{
		$subimg_name = $subimg02;
	}

	// 메뉴이미지 저장
	if($catimg['size'] > 0){
		file_check($catimg['name']);
		$catimg_ext = strtolower(substr($catimg['name'],-3));
		$catimg_name = $catcode."_cat.".$catimg_ext;
		copy($catimg['tmp_name'], $catimg_path."/".$catimg_name);
		chmod($catimg_path."/".$catimg_name, 0606);
	}
	if($catimg_over['size'] > 0){
		file_check($catimg_over['name']);
		$catimg_over_ext = strtolower(substr($catimg_over['name'],-3));
		$catimg_over_name = $catcode."_cat_over.".$catimg_over_ext;
		copy($catimg_over['tmp_name'], $catimg_path."/".$catimg_over_name);
		chmod($catimg_path."/".$catimg_over_name, 0606);
	}
	if(!isset($catuse)) $catuse = "";
	if(!isset($catimg_name)) $catimg_name = "";
	if(!isset($catimg_over_name)) $catimg_over_name = "";
	if(!isset($prd_tema)) $prd_tema = "";
	if(!isset($recom_tema)) $recom_tema = "";
	if(!isset($recom_num)) $recom_num = "";
	//  카테고리 저장
	$sql = "insert into wiz_category(catcode,depthno,priorno01,priorno02,priorno03,priorno04,catname,catuse,catimg,catimg_over,subimg,subimg_type,
								prd_tema,prd_num,prd_width,prd_height,recom_use,recom_tema,recom_num,cms_rate)
								values('$catcode','$depthno','$priorno01','$priorno02','$priorno03','$priorno04',
								'$catname','$catuse','$catimg_name','$catimg_over_name','$subimg_name','$subimg_type','$prd_tema','$prd_num','$prd_width','$prd_height','$recom_use','$recom_tema','$recom_num','$cms_rate')";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$depthno--;

	complete("상품분류를 추가하였습니다.","prd_category.php?mode=$mode&catcode=$parent_catcode&depthno=$depthno");


}else if($mode == "update"){

	$catname = trim($catname);
	$catname = str_replace("\"","”",$catname);
	$catname = str_replace("'","′",$catname);
	if($catname == "") error("분류명을 입력하세요.");

	// 카테고리 타이틀 저장
	if($subimg_type == "FIL"){
		if($subimg['size'] > 0){
			file_check($subimg['name']);
			$subimg_ext = strtolower(substr($subimg['name'],-3));
			$subimg_name = $catcode."_sub.".$subimg_ext;
			copy($subimg['tmp_name'], $subimg_path."/".$subimg_name);
			chmod($subimg_path."/".$subimg_name, 0606);

			$subimg_sql = " subimg='$subimg_name', ";
		}
	}else if($subimg_type == "HTM"){
		$subimg_sql = " subimg='$subimg02', ";
	}else{
		$subimg_sql = " subimg='', ";
	}

	// 메뉴이미지 저장
	if($catimg['size'] > 0){
		file_check($catimg['name']);
		$catimg_ext = strtolower(substr($catimg['name'],-3));
		$catimg_name = $catcode."_cat.".$catimg_ext;
		@copy($catimg['tmp_name'], $catimg_path."/".$catimg_name);
		@chmod($catimg_path."/".$catimg_name, 0606);
		$catimg_sql = " catimg='$catimg_name', ";
	}
	if($catimg_over['size'] > 0){
		file_check($catimg_over['name']);
		$catimg_over_ext = strtolower(substr($catimg_over['name'],-3));
		$catimg_over_name = $catcode."_cat_over.".$catimg_over_ext;
		copy($catimg_over['tmp_name'], $catimg_path."/".$catimg_over_name);
		chmod($catimg_path."/".$catimg_over_name, 0606);
		$catimg_over_sql = " catimg_over='$catimg_over_name', ";
	}

	$sql = "update wiz_category set catname='$catname', catuse='$catuse', $catimg_sql $catimg_over_sql $subimg_sql  subimg_type='$subimg_type',
						prd_tema='$prd_tema', prd_num='$prd_num', prd_width='$prd_width', prd_height='$prd_height', recom_use='$recom_use', recom_tema='$recom_tema', recom_num='$recom_num', cms_rate='$cms_rate'
						where catcode = '$catcode'";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 분류숨김시 하위분류도 모두 숨김
	if($catuse == "N"){
		if($depthno == "1") $tmp_catcode = substr($catcode,0,2);
		else if($depthno == "2") $tmp_catcode = substr($catcode,0,4);
		else if($depthno == "3") $tmp_catcode = substr($catcode,0,6);
		if($tmp_catcode != ""){
			$sql = "update wiz_category set catuse='$catuse' where catcode like '$tmp_catcode%'";
			//echo $sql;
			mysqli_query($connect, $sql) or die(mysqli_error($connect));
		}
	}

	complete("분류정보를 수정하였습니다.","prd_category.php?mode=$mode&catcode=$catcode&depthno=$depthno");

}else if($mode == "delete"){



	if($depthno == 1){ $tmpcode = substr($catcode,0,2); }
   else if($depthno == 2){ $tmpcode = substr($catcode,0,4); }
   else if($depthno == 3){ $tmpcode = substr($catcode,0,6); }
   else if($depthno == 4){ $tmpcode = $catcode; }

   //$depthno = $depthno-1;
   // 하위분류가 존재하면 삭제하지 못함
   $sql = "select catcode from wiz_category where catcode != '$catcode' and catcode like '$tmpcode%'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   if($row = mysqli_fetch_object($result)){
      echo "<script>alert('하위분류가 존재합니다. 삭제하실 수 없습니다.');document.location='prd_category.php?mode=update&catcode=$catcode&depthno=$depthno';</script>";
      exit;
   }


   // 현재 또는 하위분류에 상품이 존재하면 삭제하지 못함
   $sql = "select wp.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode = '$catcode' and wc.prdcode = wp.prdcode";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   if($row = mysqli_fetch_object($result)){
      echo "<script>alert('현재분류에 상품이 존재합니다. 삭제하실 수 없습니다.');document.location='prd_category.php?mode=update&catcode=$catcode&depthno=$depthno';</script>";
      exit;
   }


   // 선택분류 삭제
   $sql = "delete from wiz_category where catcode = '$catcode'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

   complete("선택하신 분류를 삭제하였습니다.","prd_category.php?mode=$mode&catcode=$catcode&depthno=$depthno");


// 카테고리 우선순위
}else if($mode == "updateprior"){

   if($catcode != ""){

   $break = false;
   $sel_row = ""; $chg_row = ""; $tmp_row = "";
   $sql = "select * from wiz_category where depthno = '$depthno' order by priorno01, priorno02, priorno03 asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)){

		if($break == true) { $chg_row = $row; break;}

		if($row->catcode == $catcode){
			$sel_row = $row;
			if($posi == "up"){
				$chg_row = $tmp_row;
			}else if($posi == "down"){
				$break = true;
			}
		}

		$tmp_row = $row;
	}

	if($depthno == 1){
		$sel_catcode = substr($sel_row->catcode,0,2);
		$chg_catcode = substr($chg_row->catcode,0,2);

		$sel_sql = " priorno01='$chg_row->priorno01' ";
		$chg_sql = " priorno01='$sel_row->priorno01' ";

	}else if($depthno == 2){
		$sel_catcode = substr($sel_row->catcode,0,4);
		$chg_catcode = substr($chg_row->catcode,0,4);

		$sel_sql = " priorno02='$chg_row->priorno02' ";
		$chg_sql = " priorno02='$sel_row->priorno02' ";

	}else if($depthno == 3){
		$sel_catcode = $sel_row->catcode;
		$chg_catcode = $chg_row->catcode;

		$sel_sql = " priorno03='$chg_row->priorno03' ";
		$chg_sql = " priorno03='$sel_row->priorno03' ";
	}


	if($chg_row->catcode != ""){

		$sql = "update wiz_category set $sel_sql where catcode like '$sel_catcode%'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		$sql = "update wiz_category set $chg_sql where catcode like '$chg_catcode%'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	}

	}

   Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno");

}else if($mode == "delsubimg"){

	foreach (glob($subimg_path."/".$catcode."_sub.*") as $filename) {
		@unlink($filename);
	}
	$sql = "update wiz_category set subimg = '' where catcode = '$catcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno");

}else if($mode == "delcatimg"){

	foreach (glob($catimg_path."/".$catcode."_cat.*") as $filename) {
		@unlink($filename);
	}
	$sql = "update wiz_category set catimg = '' where catcode = '$catcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno");

}else if($mode == "delcatimg_over"){

	foreach (glob($catimg_path."/".$catcode."_cat_over.*") as $filename) {
		@unlink($filename);
	}
	$sql = "update wiz_category set catimg_over = '' where catcode = '$catcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno");

// 하위 카테고리 수수로율 수정
} else if(!strcmp($mode, "cms")) {

	if(!strcmp($depthno, "1")) $tmp_catcode = substr($catcode, 0, 2);
	else if(!strcmp($depthno, "2")) $tmp_catcode = substr($catcode, 0, 4);
	else if(!strcmp($depthno, "3")) $tmp_catcode = substr($catcode, 0, 6);

	$sql = "update wiz_category set cms_rate = '$cms' where catcode like '$tmp_catcode%'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

   Header("Location: prd_category.php?mode=update&catcode=$catcode&depthno=$depthno");
}
?>