<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

$usetype = "Y";

$upfile_path = "../../data/bbs/$code";		// 업로드 파일위치

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

if($mode == "insert"){

	if($titleimg_size > 0){
		
		file_check($titleimg_name);
	
		$ext = substr($titleimg_name,-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg, $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
	}

	$type = "SCH";

  $sql = "insert into wiz_bbsinfo (code,type,title,titleimg,header,footer,category,bbsadmin,lpermi,rpermi,
  				wpermi,apermi,cpermi,datetype_list,datetype_view,skin,permsg,perurl,pageurl,editor,usetype,
  				privacy,upfile,movie,comment,remail,imgview,recom,abuse,abtxt,simgsize,mimgsize,rows,lists,newc,
  				hotc,line,subject_len,img_align,btn_view,spam_check)
  				values('$code','$type','$title','$titleimg_name','$header','$footer','$category','$bbsadmin',
  				'$lpermi','$rpermi','$wpermi','$apermi','$cpermi','$datetype_list','$datetype_view','$skin',
  				'$permsg','$perurl','$pageurl','$editor','$usetype','$privacy','$upfile','$movie','$comment',
  				'$remail','$imgview','$recom','$abuse','$abtxt','$simgsize','$mimgsize','$rows','$lists','$newc',
  				'$hotc','$line','$subject_len','$img_align','$btn_view','$spam_check')";

	$result = mysqli_query($connect, $sql) or error("이미등록된 일정입니다.");

	complete("일정을 추가 하였습니다.","sch_list.php");


}else if($mode == "update"){

	if($titleimg_size > 0){
		
		file_check($titleimg_name);
	
		$ext = substr($titleimg_name,-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg, $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
		$titleimg_sql = "titleimg='$titleimg_name', ";
	}
	
  $sql = "update wiz_bbsinfo set title='$title', $titleimg_sql header='$header',footer='$footer',
  				category='$category',bbsadmin='$bbsadmin',lpermi='$lpermi',rpermi='$rpermi',wpermi='$wpermi',
  				apermi='$apermi',cpermi='$cpermi',datetype_list='$datetype_list',datetype_view='$datetype_view',
  				skin='$skin',permsg='$permsg',perurl='$perurl',pageurl='$pageurl',editor='$editor',
  				usetype='$usetype',privacy='$privacy',upfile='$upfile',movie='$movie',comment='$comment',
  				remail='$remail',imgview='$imgview',recom='$recom',abuse='$abuse',abtxt='$abtxt',
  				simgsize='$simgsize',mimgsize='$mimgsize',rows='$rows',lists='$lists',newc='$newc',hotc='$hotc',
  				line='$line',subject_len='$subject_len',img_align='$img_align',btn_view='$btn_view',
  				spam_check='$spam_check' where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("일정 정보를 수정하였습니다.","sch_input.php?mode=update&code=$code&page=$page");

}else if($mode == "delete"){

	$sql = "delete from wiz_bbsinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$sql = "delete from wiz_bbscat where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$sql = "delete from wiz_bbs where code = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	exec("rm -rf ../../data/bbs/$code");
	exec("rm -rf ../../data/category/$code");

	complete("해당일정을 삭제하였습니다.","sch_list.php");

}else if($mode == "del_titleimg"){

	$sql = "select titleimg from wiz_bbsinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	$titleimg_name = $row[titleimg];
	unlink($upfile_path."/".$titleimg_name);

	$sql = "update wiz_bbsinfo set titleimg='' where code = '$code'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("타이틀 이미지를 삭제 하였습니다.","sch_input.php?mode=update&code=$code");
	
}
?>