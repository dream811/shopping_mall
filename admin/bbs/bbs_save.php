<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../../inc/bbs_info.inc"; ?>

<?

$upfile_path = "../../data/bbs/".$code;						// 업로드파일 위치
$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명

$S_width = $bbs_info['simgsize']; $S_height = $bbs_info['simgsize'];	// 스몰섬네일 크기
$M_width = $bbs_info['mimgsize']; $M_height = $bbs_info['mimgsize'];	// 중간섬네일 크기

// 검색 파라미터
$param = "code=$code&idx=$idx&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey";

if($mode == "insert"){

	include "./bbs_upfile.inc";

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	if($row = mysqli_fetch_array($result)){
		$prino = $row['prino'] + 1;
	}
	$grpno = $prino;

	$memid = $wiz_admin['id'];
	$memgrp = $wiz_admin['id'];
	if(!get_magic_quotes_gpc()) $content= addslashes($content);
	if($bbs_info['editor'] == "Y") $ctype = "H";

	if($wdate == "") $wdate = date('Y-m-d H:i:s');


  $sql = "insert into wiz_bbs (idx,prdcode,code,prino,grpno,depno,star,notice,category,memid,memgrp,name,
  				email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,
  				addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,
  				upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,
  				upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,
  				upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate)
  				values('$idx','$prdcode','$code','$prino','$grpno','$depno','$star','$notice','$category','$memid',
  				'$memgrp','$name','$email','$tphone','$hphone','$zipcode','$address','$subject','$content',
  				'$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy',
  				'$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp',
  				'$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp',
  				'$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name',
  				'$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name',
  				'$upfile11_name','$upfile12_name','$movie1_tmp','$movie2','$movie3','$passwd','$count',
  				'$recom','$comment','$REMOTE_ADDR',unix_timestamp('$wdate'))";

  mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("게시물이 작성되었습니다.","bbs_list.php?code=$code");

}else if($mode == "update"){

	include "./bbs_upfile.inc";
	if(!get_magic_quotes_gpc()) $content= addslashes($content);

	if(!isset($star)) $star = "";
	if(!isset($notice )) $notice = "";
	if(!isset($tphone )) $tphone = "";
	if(!isset($hphone )) $hphone = "";
	if(!isset($zipcode )) $zipcode = "";
	if(!isset($address )) $address = "";
	if(!isset($addinfo1 )) $addinfo1 = "";
	if(!isset($addinfo2 )) $addinfo2 = "";
	if(!isset($addinfo3 )) $addinfo3 = "";
	if(!isset($addinfo4)) $addinfo4 = "";
	if(!isset($addinfo5 )) $addinfo5 = "";
	if(!isset($privacy )) $privacy = "";
	if(!isset($upfile1_sql )) $upfile1_sql = "";
	if(!isset($upfile2_sql )) $upfile2_sql = "";
	if(!isset($upfile3_sql )) $upfile3_sql = "";
	if(!isset($upfile_sql )) $upfile_sql = "";
	if(!isset($movie1_sql )) $movie1_sql = "";
	if(!isset($movie2 )) $movie2 = "";
	if(!isset($movie3 )) $movie3 = "";

	$sql = "update wiz_bbs set star='$star',notice='$notice',category='$category',name='$name',email='$email',tphone='$tphone',hphone='$hphone',zipcode='$zipcode',address='$address',subject='$subject',content='$content',addinfo1='$addinfo1',addinfo2='$addinfo2',addinfo3='$addinfo3',addinfo4='$addinfo4',addinfo5='$addinfo5',ctype='$ctype',privacy='$privacy' $upfile1_sql $upfile2_sql $upfile3_sql $upfile_sql $movie1_sql ,movie2='$movie2',movie3='$movie3',passwd='$passwd',count='$count',wdate=unix_timestamp('$wdate') where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("게시물이 수정되었습니다.","bbs_input.php?code=$code&mode=update&idx=$idx");

}else if($mode == "reply"){

	$sql = "select idx,grpno,prino,depno,memid,memgrp,name,email,prdcode from wiz_bbs where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	$re_name = $row['name'];
	$re_email = $row['email'];

	$grpno = $row['grpno'];
	$prino = $row['prino'];
	$depno = ++$row['depno'];

	$prdcode = $row['prdcode'];

	include "./bbs_upfile.inc";

	$memid = $wiz_admin['id'];
	$memgrp = $row['memgrp'].",".$memid;
	if($privacy == "Y") $memid = $row['memid'];
	if(!get_magic_quotes_gpc()) $content= addslashes($content);
	if($bbs_info['editor'] == "Y") $ctype = "H";

	$sql = "update wiz_bbs set prino = prino+1 where code = '$code' and prino >= '$prino'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "insert into wiz_bbs(idx,prdcode,code,prino,grpno,depno,star,notice,category,memid,memgrp,name,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate)
					values('','$prdcode','$code','$prino','$grpno','$depno','$star','$notice','$category','$memid','$memgrp','$name','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name','$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('$wdate'))";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("답글이 작성되었습니다.","bbs_list.php?code=$code&page=$page");

}else if($mode == "delete"){

	$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,movie1 from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	for($ii = 1; $ii <= $upfile_max; $ii++) {

		if($bbs_row['upfile'.$ii] != ""){
			@unlink($upfile_path."/".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/S".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/M".$bbs_row['upfile'.$ii]);
		}

	}

	if($bbs_row['movie1'] != ""){
		@unlink($upfile_path."/".$bbs_row['movie1']);
	}

	$sql = "delete from wiz_bbs where code = '$code' and idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("게시물이 삭제되었습니다.","bbs_list.php?code=$code&$param");

// 다중삭제
}else if($mode == "delbbs"){

	$array_selbbs = explode("|",$selbbs);
	for($ii=0;$ii<count($array_selbbs);$ii++){

		$idx = $array_selbbs[$ii];
		$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,movie1 from wiz_bbs where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$bbs_row = mysqli_fetch_array($result);

		for($jj = 1; $jj <= $upfile_max; $jj++) {

			if($bbs_row['upfile'.$jj] != ""){
				@unlink($upfile_path."/".$bbs_row['upfile'.$jj]);
				@unlink($upfile_path."/S".$bbs_row['upfile'.$jj]);
				@unlink($upfile_path."/M".$bbs_row['upfile'.$jj]);
			}

		}

		if($bbs_row['movie1'] != ""){
			@unlink($upfile_path."/".$bbs_row['movie1']);
		}

		$sql = "delete from wiz_bbs where idx='$idx'";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));

	}

	complete("게시물이 삭제되었습니다.","bbs_list.php?code=$code");

// 첨부파일 삭제
}else if($mode == "delfile"){

	$sql = "select upfile1,upfile2,upfile3,movie1 from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	for($ii = 1; $ii <= $upfile_max; $ii++) {

		if($file != "upfile".$ii){
			@unlink($upfile_path."/".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/S".$bbs_row['upfile'.$ii]);
			@unlink($upfile_path."/M".$bbs_row['upfile'.$ii]);
			$upfile_sql = " upfile".$ii." = '', upfile".$ii."_name = ''";
		}

	}

	if($file == "movie1"){
		$upfile_sql = " movie1 = '' ";
		@unlink($upfile_path."/".$bbs_row['movie1']);
	}

	$sql = "update wiz_bbs set $upfile_sql where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("파일이 삭제되었습니다.","bbs_input.php?mode=update&$param");

// 코멘트 입력
} else if ($mode == "comment") {

	$ctype = "BBS";

	$sql = "insert into wiz_comment(idx,ctype,cidx,prdcode,star,id,name,content,passwd,wdate,wip)
					values('', '$ctype', '$cidx', '$prdcode', '$star', '".$wiz_session['id']."', '$name', '$content', '$passwd', now(), '$_SERVER[REMOTE_ADDR]')";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where ctype='BBS' and cidx='$cidx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$comment = mysqli_num_rows($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("댓글을 작성하였습니다.", "bbs_view.php?code=$code&idx=$cidx");

// 코멘트 삭제
} else if($mode == "delco"){

	$sql = "delete from wiz_comment where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where ctype='BBS' and cidx='$cidx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$comment = mysqli_num_rows($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("댓글을 삭제하였습니다.", "bbs_view.php?code=$code&idx=$cidx");

}

?>