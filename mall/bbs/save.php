<?

include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc";					// util lib
include "../../inc/mall_check.inc"; // 입점업체 로그인 체크

$code = $_REQUEST["code"];

include "../../inc/bbs_info.inc"; 	 		// 게시판 정보

$upfile_path = "../../data/bbs/".$code;	// 업로드파일 위치

// 검색 파라미터
$param = "code=$code";
if($page != "") $param .= "&page=$page";
if($searchkey != "") $param .= "&searchopt=$searchopt&searchkey=$searchkey";

//SQL 입력값 문자열 필터
$name = sql_filter($_POST["name"]);
$email = sql_filter($_POST["email"]);
$tphone = sql_filter($_POST["tphone"]);
$hphone = sql_filter($_POST["hphone"]);
$zipcode = sql_filter($_POST["zipcode"]);
$address = sql_filter($_POST["address"]);
$subject = sql_filter($_POST["subject"]);
$content = sql_filter($_POST["content"]);
$reply = sql_filter($_POST["reply"]);

$addinfo1 = sql_filter($_POST["addinfo1"]);
$addinfo2 = sql_filter($_POST["addinfo2"]);
$addinfo3 = sql_filter($_POST["addinfo3"]);
$addinfo4 = sql_filter($_POST["addinfo4"]);
$addinfo5 = sql_filter($_POST["addinfo5"]);

////////////////////////////////////////////////////////////////////////////////
// 글작성
////////////////////////////////////////////////////////////////////////////////
if($mode == "write"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($bbs_info[spam_check], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST[tmp_vcode]) || empty($_POST[vcode])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST[tmp_vcode], md5($_POST[vcode]))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($wpermi < $mem_level) {

		// 구매회원 체크
		if(!strcmp($wpermi, "-1")) {

			$sql = "select count(idx) as cnt from wiz_basket as wb left join wiz_order as wo on wb.orderid = wo.orderid
							where wb.prdcode = '$prdcode' and wo.status = 'DC'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_array($result);

			if($row['cnt'] <= 0) {
				error($bbs_info[permsg],$bbs_info[perurl]);
			}

		} else {
			error($bbs_info[permsg],$bbs_info[perurl]);
		}
	}

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	include "../../bbs/upfile.inc";		// 첨부파일 업로드

	// 입력데이터
	$memid = $wiz_mall['id'];
	$memgrp = $wiz_mall['id'];
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_mall[passwd];
	if($bbs_info[editor] == "Y") $ctype = "H";


	$sql = "select max(prino) as prino from wiz_bbs where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	if($row = mysqli_fetch_array($result)){
		$prino = $row[prino] + 1;
	}
	$grpno = $prino;

	$sql = "insert into wiz_bbs(idx,prdcode,code,prino,grpno,depno,star,notice,category,mallid,memid,memgrp,name,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate)
					values('','$prdcode','$code','$prino','$grpno','$depno','$star','$notice','$category','$mallid','$memid','$memgrp','$name','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name',
					'$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('".date('Y-m-d H:i:s')."'))";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	echo "<script>document.location='list.php?code=$code';</script>";

////////////////////////////////////////////////////////////////////////////////
// 게시물 수정
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "modify"){

	$sql = "select memid,passwd from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);


	// 수정권한 체크
	if(
		$mem_level == "0" || 																																				// 전체관리자
		($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||		// 게시판관리자
		($bbs_row[memid] != "" && $bbs_row[memid] == $wiz_mall['id']) || 													// 자신의글
		($bbs_row[passwd] != "" && $bbs_row[passwd] == $passwd)																			// 비밀번호일치
	){
	}else{
		error("비밀번호가 일치하지 않습니다.");
	}


	// 첨부파일 업로드
	include "../../bbs/upfile.inc";		// 첨부파일 업로드

	// 입력데이터
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($bbs_info[editor] == "Y") $ctype = "H";

	$sql = "update wiz_bbs set star='$star',notice='$notice',category='$category',name='$name',email='$email',tphone='$tphone',hphone='$hphone',zipcode='$zipcode',address='$address',subject='$subject',content='$content',addinfo1='$addinfo1',addinfo2='$addinfo2',addinfo3='$addinfo3',addinfo4='$addinfo4',addinfo5='$addinfo5',ctype='$ctype',privacy='$privacy' $upfile_sql $movie1_sql,movie2='$movie2',movie3='$movie3' where idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if($privacy == "Y" && ($bbs_row[memid] == "" || $wiz_mall['id'] == "")) $param .= "&passwd=$passwd";

	comalert("수정 되었습니다.","view.php?idx=$idx&$param");




////////////////////////////////////////////////////////////////////////////////
// 답글작성
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "reply"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($bbs_info[spam_check], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST[tmp_vcode]) || empty($_POST[vcode])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST[tmp_vcode], md5($_POST[vcode]))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($apermi < $mem_level) error($bbs_info[permsg],$bbs_info[perurl]);

	// 욕설체크
	check_abuse($subject); check_abuse($content);

	include "../../bbs/upfile.inc";		// 첨부파일 업로드


	$sql = "select idx,prdcode,grpno,prino,depno,memid,memgrp,name,email from wiz_bbs where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);
	$re_name = $row[name];
	$re_email = $row[email];

	$grpno = $row[grpno];
	$prino = $row[prino];
	$depno = ++$row[depno];

	$prdcode = $row[prdcode];

	// 입력데이타
	$memid = $wiz_mall['id'];
	$memgrp = $row[memgrp].",".$memid;
	if($privacy == "Y") $memid = $row[memid];
	$name = str_replace("\"","&quot;",$name);
	$subject = str_replace("\"","&quot;",$subject);
	if($passwd == "") $passwd = $wiz_mall[passwd];
	if($bbs_info[editor] == "Y") $ctype = "H";

	$sql = "update wiz_bbs set prino = prino+1 where code = '$code' and prino >= '$prino'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$sql = "insert into wiz_bbs(idx,prdcode,code,grpno,prino,depno,star,notice,category,mallid,memid,memgrp,name,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate)
					values('','$prdcode','$code','$grpno','$prino','$depno','$star','$notice','$category','$mallid','$memid','$memgrp','$name','$email','$tphone','$hphone','$zipcode','$address','$subject','$content','$addinfo1','$addinfo2','$addinfo3','$addinfo4','$addinfo5','$ctype','$privacy','$upfile1_tmp','$upfile2_tmp','$upfile3_tmp','$upfile4_tmp','$upfile5_tmp','$upfile6_tmp','$upfile7_tmp','$upfile8_tmp','$upfile9_tmp','$upfile10_tmp','$upfile11_tmp','$upfile12_tmp','$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name','$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name',
					'$movie1_tmp','$movie2','$movie3','$passwd','$count','$recom','$comment','$REMOTE_ADDR',unix_timestamp('".date('Y-m-d H:i:s')."'))";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	echo "<script>document.location='list.php?$param';</script>";

////////////////////////////////////////////////////////////////////////////////
// 게시물 삭제
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "delete"){

	$sql = "select memid,passwd,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,movie1 from wiz_bbs where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
	($bbs_row[memid] != "" && $bbs_row[memid] == $wiz_mall['id']) || 												// 자신의글
	($bbs_row[passwd] != "" && $bbs_row[passwd] == $passwd)																		// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}


	for($ii = 1; $ii <= $upfile_max; $ii++) {

		if($bbs_row[upfile.$ii] != ""){
			@unlink($upfile_path."/".$bbs_row[upfile.$ii]);
			@unlink($upfile_path."/S".$bbs_row[upfile.$ii]);
			@unlink($upfile_path."/M".$bbs_row[upfile.$ii]);
		}

	}

	if($bbs_row[movie1] != ""){
		@unlink($upfile_path."/".$bbs_row[movie1]);
	}

	$sql = "delete from wiz_bbs where idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("삭제 되었습니다.","list.php?$param");

////////////////////////////////////////////////////////////////////////////////
// 코멘트 입력
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "comment"){

	// 스팸글 차단
	$pos = strpos($HTTP_REFERER, $HTTP_HOST);
  if($pos === false) error("잘못된 경로 입니다.");

  if(!strcmp($bbs_info[spam_check], "Y")) {

	  // 자동등록방지 코드 검사
	  if(empty($_POST[tmp_vcode]) || empty($_POST[vcode])) {
	  	error("자동등록방지 코드가 존재하지 않습니다.");
	  } else if(strcmp($_POST[tmp_vcode], md5($_POST[vcode]))) {
	  	error("자동등록방지 코드가 일치하지 않습니다.");
	  }
	}

	// 작성권한 체크
	if($cpermi < $mem_level) error($bbs_info[permsg],$bbs_info[perurl]);

	// 욕설체크
	check_abuse($name); check_abuse($content);

	$ctype = "BBS";

	// 입력데이터
	$memid = $wiz_mall['id'];

	if(empty($name)) $name = $wiz_mall[name];

	$name = str_replace("\"","&quot;",$name);
	if($passwd == "") $passwd = $wiz_mall[passwd];

	$sql = "insert into wiz_comment(idx,ctype,cidx,prdcode,star,id,name,content,passwd,wdate,wip)
					values('', '$ctype', '$idx', '$prdcode', '$star', '$wiz_mall['id']', '$name', '$content', '$passwd', now(), '$_SERVER[REMOTE_ADDR]')";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$comment = mysqli_num_rows($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("댓글을 작성하였습니다.", "view.php?code=$code&idx=$idx");

////////////////////////////////////////////////////////////////////////////////
// 코멘트 삭제
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "delco"){

	$sql = "select id,passwd from wiz_comment where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	// 삭제권한 체크
	if(
	$mem_level == "0" ||																																			// 전체관리자
	($bbs_info[bbsadmin] != "" && strpos($bbs_info[bbsadmin],$wiz_mall['id']) !== false)  ||	// 게시판관리자
	($row['id'] != "" && $row['id'] == $wiz_mall['id']) || 																// 자신의글
	($row[passwd] != "" && $row[passwd] == $passwd)																						// 비밀번호일치
	){
	}else{
		if($passwd) error("비밀번호가 일치하지 않습니다.");
		else error("권한이 없습니다.");
	}

	$sql = "delete from wiz_comment where idx='$idx'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

  // 댓글수 업데이트
	$sql = "select idx from wiz_comment where cidx='$cidx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$comment = mysqli_num_rows($result);

	$sql = "update wiz_bbs set comment = '$comment' where idx = '$cidx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("댓글을 삭제하였습니다.", "view.php?code=$code&idx=$bbs_idx");

////////////////////////////////////////////////////////////////////////////////
// 추천하기
////////////////////////////////////////////////////////////////////////////////
}else if($mode == "recom"){

	if(strlen($HTTP_COOKIE_VARS["bbs_recom".$idx])==0){

		$sql = "update wiz_bbs set recom = recom + 1 where idx='$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

      setcookie("bbs_recom".$idx, "$idx", time()+60*60*24*365);

		echo "<script>alert('추천되었습니다.');document.location='view.php?code=$code&idx=$idx&recom=ok';</script>";

	}else{

		echo "<script>alert('한번만 추천가능합니다..');document.location='view.php?code=$code&idx=$idx';</script>";

	}

}



?>