<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

$param = "page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey;

$usetype = "Y";

$upfile_path = "../../data/poll/$code";		// 업로드 파일위치

// 업로드 디렉토리 생성
if(!is_dir($upfile_path)) mkdir($upfile_path, 0707);

// 설문입력
if($mode == "insert"){
	
	$mainskin = "	
	<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
	<tr><td><b>{SUBJECT}</b></td></tr>
	<tr><td>{CONTENT}</td></tr>
	[LOOP]
	<tr><td><img src=\"/admin/bbsmain/image/point.gif\" align=\"absmiddle\"> {QUESTION}</td></tr>
	[LOOP2]
	<tr><td> {ANSWER} </td></tr>
	[/LOOP2]
	[/LOOP]
	<tr><td height=5></td></tr>
	<tr><td align=center>{VOTE_BTN} {VIEW_BTN}</td></tr>
	</table>";
	
	if($titleimg_size > 0){
		
		file_check($titleimg_name);
	
		$ext = substr($titleimg_name,-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg, $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
	}

   $sql = "insert into wiz_pollinfo (code,title,titleimg,header,footer,lpermi,rpermi,apermi,cpermi,skin,
   				permsg,perurl,mainskin,purl,usetype,spam_check,datetype_list,datetype_view,comment,rows,lists,
   				newc,subject_len,abuse,abtxt,wdate) 
   				values('$code','$title','$titleimg_name','$header','$footer','$lpermi','$rpermi','$apermi','$cpermi',
   				'$skin','$permsg','$perurl','$mainskin','$purl','$usetype','$spam_check','$datetype_list',
   				'$datetype_view','$comment','$rows','$lists','$newc','$subject_len','$abuse','$abtxt',now())";
   				
   mysqli_query($connect, $sql) or die(mysqli_error($connect));
		
   complete("설문을 추가 하였습니다.","pollinfo_input.php?mode=update&code=$code");
   

// 설문수정
}else if($mode == "update"){
	
	if($titleimg_size > 0){
		
		file_check($titleimg_name);
	
		$ext = substr($titleimg_name,-3);
		$titleimg_name = $code."_title.".$ext;
		copy($titleimg, $upfile_path."/".$titleimg_name);
		chmod($upfile_path."/".$titleimg_name, 0606);
		$titleimg_sql = "titleimg='$titleimg_name', ";
	}

  $sql = "update wiz_pollinfo set title='$title', $titleimg_sql header='$header',footer='$footer',
  				lpermi='$lpermi',rpermi='$rpermi',apermi='$apermi',cpermi='$cpermi',skin='$skin',
  				permsg='$permsg',perurl='$perurl',purl='$purl',usetype='$usetype',
  				spam_check='$spam_check',datetype_list='$datetype_list',datetype_view='$datetype_view',
  				comment='$comment',rows='$rows',lists='$lists',newc='$newc',subject_len='$subject_len',
  				abuse='$abuse',abtxt='$abtxt' where code = '$code'";      
  mysqli_query($connect, $sql) or die(mysqli_error($connect)); 
   
   complete("설문을 수정 하였습니다.","pollinfo_input.php?mode=$mode&code=$code&$param");


// 설문삭제
}else if($mode == "delete"){
   
   $sql = "delete from wiz_pollinfo where code = '$code'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
   $sql = "delete from wiz_poll where code = '$code'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
		exec("rm -rf ../../data/poll/$code");
		
   complete("설문을 삭제 하였습니다.","pollinfo_list.php?$param");

// 타이틀 이미지 삭제 
} else if(!strcmp($mode, "del_titleimg")) {
	
	$sql = "select titleimg from wiz_pollinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	$titleimg_name = $row[titleimg];
	unlink($upfile_path."/".$titleimg_name);

	$sql = "update wiz_pollinfo set titleimg='' where code = '$code'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("타이틀 이미지를 삭제 하였습니다.","pollinfo_input.php?mode=update&code=$code");

}
?>