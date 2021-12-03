<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
$upfile_path = "../../data/bbs/$code";		// 업로드 파일위치

$upfile_idx = date('ymdhis').rand(1,9);					// 업로드파일명
$category_path = "../../data/category/$code";		// 카테고리 업로드 파일위치

// 업로드 디렉토리 생성
if(isset($code) && !is_dir($upfile_path)) mkdir($upfile_path, 0707);

// 업로드 디렉토리 생성
if(isset($code) && !is_dir($category_path)) mkdir($category_path, 0707);
if(!isset($titleimg_sql)) $titleimg_sql = "";
if(!isset($header)) $header = "";
if(!isset($footer)) $footer = "";
if(!isset($category)) $category = "";
if(!isset($pageurl)) $pageurl = "";
if(!isset($privacy)) $privacy = "";
if(!isset($remail)) $remail = "";
if(!isset($imgview)) $imgview = "";
if(!isset($abuse)) $abuse = "";

if($mode == "insert"){

	if($titleimg['size'] > 0){

		file_check($titleimg['name']);

		$ext = substr($titleimg['name'],-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg['tmp_name'], $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
	}

	if(empty($type)) $type = "BBS";
	if(empty($skin)) $type = "bbsBasic";
	if(isset($titleimg_sql)) $titleimg_sql = "";
	if(isset($header)) $header = "";
	if(isset($footer)) $footer = "";
	if(isset($category)) $category = "";
	if(isset($pageurl)) $pageurl = "";
	if(isset($privacy)) $privacy = "";
	if(isset($remail)) $remail = "";
	if(isset($imgview)) $imgview = "";

	$sql = "insert into wiz_bbsinfo (code,type,title,titleimg,header,footer,category,bbsadmin,lpermi,
					rpermi,wpermi,apermi,cpermi,datetype_list,datetype_view,skin,permsg,perurl,pageurl,editor,
					usetype,privacy,upfile,movie,comment,remail,imgview,recom,abuse,abtxt,simgsize,mimgsize,
					rows,lists,newc,hotc,line,subject_len,img_align,btn_view,spam_check,view_list)
					values('$code','$type','$title','$titleimg_name','$header','$footer','$category','$bbsadmin',
					'$lpermi','$rpermi','$wpermi','$apermi','$cpermi','$datetype_list','$datetype_view','$skin',
					'$permsg','$perurl','$pageurl','$editor','$usetype','$privacy','$upfile','$movie','$comment',
					'$remail','$imgview','$recom','$abuse','$abtxt','$simgsize','$mimgsize','$rows','$lists',
					'$newc','$hotc','$line','$subject_len','$img_align','$btn_view','$spam_check','$view_list')";
	$result = mysqli_query($connect, $sql);

	if(mysqli_errno($connect) > 0) echo "<script>alert('이미생성된 게시판입니다.');history.go(-1);</script>";
	else {

		if(empty($catname)) $catname = "전체";

		//전체 카테고리 추가
	  	$sql = "insert into wiz_bbscat (idx,gubun,code,catname,catimg,catimg_over,caticon,prior)
	  				values('','A','$code','$catname','$catimg_tmp','$catimg_over_tmp','$caticon_tmp','1')";
	  	mysqli_query($connect, $sql) or die(mysqli_error($connect));
		complete("게시판을 추가 하였습니다.","bbs_pro_input.php?mode=update&code=$code");
	}


}else if($mode == "update"){

	if($titleimg['size'] > 0){

		file_check($titleimg['name']);

		$ext = substr($titleimg['name'],-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg['tmp_name'], $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
		$titleimg_sql = "titleimg='$titleimg_name', ";
	}

	$sql = "update wiz_bbsinfo set title='$title', $titleimg_sql
					header='$header',footer='$footer',category='$category',bbsadmin='$bbsadmin',
					lpermi='$lpermi',rpermi='$rpermi',wpermi='$wpermi',apermi='$apermi',cpermi='$cpermi',
					datetype_list='$datetype_list',datetype_view='$datetype_view',skin='$skin',
					permsg='$permsg',perurl='$perurl',pageurl='$pageurl',editor='$editor',usetype='$usetype',
					privacy='$privacy',upfile='$upfile',movie='$movie',comment='$comment',remail='$remail',
					imgview='$imgview',recom='$recom',abuse='$abuse',abtxt='$abtxt',
					simgsize='$simgsize',mimgsize='$mimgsize', wiz_bbsinfo.rows='$rows',lists='$lists',newc='$newc',
					hotc='$hotc',line='$line',subject_len='$subject_len',img_align='$img_align',
					btn_view='$btn_view',spam_check='$spam_check',view_list='$view_list' where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("게시판 정보를 수정하였습니다.","bbs_pro_input.php?mode=update&code=$code");


}else if($mode == "delete"){

	if($code == "qna" || $code == "faq" || $code == "notice" || $code == "review"){
		comalert("해당 게시판은 삭제할 수 없습니다.");
		exit;
	}

	$sql = "delete from wiz_bbsinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "delete from wiz_bbscat where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "delete from wiz_bbs where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 첨부파일, 카테고리 디렉토리 삭제
	nRmDir($upfile_path); nRmDir($category_path);

	complete("해당게시판이 삭제되었습니다.","bbs_pro_list.php");


}else if($mode == "del_titleimg"){

	$sql = "select titleimg from wiz_bbsinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	$titleimg_name = $row['titleimg'];
	unlink($upfile_path."/".$titleimg_name);

	$sql = "update wiz_bbsinfo set titleimg='' where code = '$code'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("타이틀 이미지를 삭제 하였습니다.","bbs_pro_input.php?mode=update&code=$code");

// 카테고리 입력
} else if(!strcmp($mode, "catinsert")) {

	if(!strcmp($gubun, "A")) {

		$sql = "select gubun from wiz_bbscat where code = '$code' and gubun = 'A'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		if(!empty($row['gubun'])) {
			error("이미 전체분류가 등록되어 있습니다.", "");
			exit;
		}

	}

	// 업로드 디렉토리 생성
	if(!is_dir($category_path)) mkdir($category_path, 0707);

	if($catimg['size'] > 0){

		file_check($catimg['name']);

		$catimg_tmp = $upfile_idx."_img.".substr($catimg['name'],-3);
		copy($catimg['tmp_name'], $category_path."/".$catimg_tmp);
		chmod($category_path."/".$catimg_tmp, 0606);

	}
	if($catimg_over['size'] > 0){

		file_check($catimg_over['name']);

		$catimg_over_tmp = $upfile_idx."_img_over.".substr($catimg_over['name'],-3);
		copy($catimg_over['tmp_name'], $category_path."/".$catimg_over_tmp);
		chmod($category_path."/".$catimg_over_tmp, 0606);

	}
	if($caticon['size'] > 0){

		file_check($caticon['name']);

		$caticon_tmp = $upfile_idx."_icon.".substr($caticon['name'],-3);
		copy($caticon['tmp_name'], $category_path."/".$caticon_tmp);
		chmod($category_path."/".$caticon_tmp, 0606);

	}

  $sql = "insert into wiz_bbscat (idx,gubun,code,catname,catimg,catimg_over,caticon,prior)
  				values('','$gubun','$code','$catname','$catimg_tmp','$catimg_over_tmp','$caticon_tmp','$prior')";
  mysqli_query($connect, $sql) or die(mysqli_error($connect));

  $idx = mysql_insert_id();

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("저장되었습니다.","category_input.php?code=$code&title=$title&idx=$idx&mode=catupdate");

// 카테고리 수정
} else if(!strcmp($mode, "catupdate")) {

	if(!strcmp($gubun, "A")) {

		$sql = "select gubun from wiz_bbscat where code = '$code' and gubun = 'A' and idx != '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		if(!empty($row['gubun'])) {
			error("이미 전체분류가 등록되어 있습니다.", "");
			exit;
		}

	}

	// 업로드 디렉토리 생성
	if(!is_dir($category_path)) mkdir($category_path, 0707);

	$sql = "select catimg,catimg_over,caticon from wiz_bbscat where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$cat_row = mysqli_fetch_array($result);

	for($ii = 0; $ii < count($delfile); $ii++) {

		if($cat_row[$delfile[$ii]] != ""){
			@unlink($category_path."/".$cat_row[$delfile[$ii]]);
			${$delfile[$ii]."_sql"} = " , $delfile[$ii] = '' ";
		}
	}

	if($catimg['size'] > 0){

		file_check($catimg['name']);

		$catimg_tmp = $upfile_idx."_img.".substr($catimg['name'],-3);
		copy($catimg['tmp_name'], $category_path."/".$catimg_tmp);
		chmod($category_path."/".$catimg_tmp, 0606);

		if($cat_row['catimg'] != ""){
			@unlink($category_path."/".$cat_row['catimg']);
		}
		$catimg_sql = " , catimg='$catimg_tmp' ";


	}
	if($catimg_over['size'] > 0){

		file_check($catimg_over['name']);

		$catimg_over_tmp = $upfile_idx."_img_over.".substr($catimg_over['name'],-3);
		copy($catimg_over['tmp_name'], $category_path."/".$catimg_over_tmp);
		chmod($category_path."/".$catimg_over_tmp, 0606);

		if($cat_row['catimg_over'] != ""){
			@unlink($category_path."/".$cat_row['catimg_over']);
		}
		$catimg_over_sql = " , catimg_over='$catimg_over_tmp' ";


	}
	if($caticon['size'] > 0){

		file_check($caticon['name']);

		$caticon_tmp = $upfile_idx."_icon.".substr($caticon['name'],-3);
		copy($caticon['tmp_name'], $category_path."/".$caticon_tmp);
		chmod($category_path."/".$caticon_tmp, 0606);

		if($cat_row['caticon'] != ""){
			@unlink($category_path."/".$cat_row['caticon']);
		}
		$caticon_sql = " , caticon='$caticon_tmp' ";

	}

  $sql = "update wiz_bbscat set gubun='$gubun', catname='$catname' $catimg_sql $catimg_over_sql $caticon_sql ,prior='$prior' where idx = '$idx'";
  mysqli_query($connect, $sql) or die(mysqli_error($connect));

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("수정되었습니다.","category_input.php?code=$code&title=$title&idx=$idx&mode=catupdate");

// 카테고리 삭제
} else if(!strcmp($mode, "catdelete")) {

	$sql = "select gubun, code, catimg, catimg_over, caticon from wiz_bbscat where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$cat_row = mysqli_fetch_array($result);

	if(!strcmp($cat_row['gubun'], "A")) {
		error("전체분류는 삭제할 수 없습니다.", "");
		exit;
	}

	@unlink($category_path."/".$cat_row['catimg']);
	@unlink($category_path."/".$cat_row['catimg_over']);
	@unlink($category_path."/".$cat_row['caticon']);

  $sql = "delete from wiz_bbscat where idx = '$idx'";
  mysqli_query($connect, $sql) or die(mysqli_error($connect));

	echo "<script>window.opener.document.location.href = window.opener.document.URL;</script>";
	complete("삭제되었습니다.","category.php?code=$code&title=$title");

}
?>