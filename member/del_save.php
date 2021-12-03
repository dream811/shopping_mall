<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/oper_info.inc";			// 운영정보
include "../inc/util.inc";		      // 유틸라이브러리

if($mode == 'insert'){
	$post = $send_post."-".$send_post2;
	$address = $send_address."|".$send_address2;
	$tphone = $tphone1."-".$tphone2."-".$tphone3;
	$hphone = $hphone1."-".$hphone2."-".$hphone3;

	$query = "insert into wiz_dellist(idx,id,name,delname,address,tphone,hphone,post) values('','$wiz_session['id']', '$name', '$delname', '$address', '$tphone', '$hphone','$post')";
	mysqli_query($connect, $query) or mysqli_error($connect);

	complete("저장되었습니다.","/member/del_list.php");	
}

else if($mode == 'viewinsert'){

	$post = $send_post."-".$send_post2;
	$address = $send_address."|".$send_address2;
	$tphone = $tphone1."-".$tphone2."-".$tphone3;
	$hphone = $hphone1."-".$hphone2."-".$hphone3;

	$query = "insert into wiz_dellist(idx,id,name,delname,address,tphone,hphone,post) values('','$wiz_session['id']', '$name', '$delname', '$address', '$tphone', '$hphone','$post')";
	mysqli_query($connect, $query) or mysqli_error($connect);

	echo "<script>alert('저장되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";
}
else if($mode == 'update'){

	$post = $send_post."-".$send_post2;
	$address = $send_address."|".$send_address2;
	$tphone = $tphone1."-".$tphone2."-".$tphone3;
	$hphone = $hphone1."-".$hphone2."-".$hphone3;
	
	$query = "update wiz_dellist set id='$wiz_session['id']', name='$name', delname='$delname', address='$address', tphone='$tphone', hphone='$hphone',post='$post' where idx='$idx' and id='$wiz_session['id']'";
	mysqli_query($connect, $query) or mysqli_error($connect);

	complete("수정되었습니다.","/member/del_list.php");
}
else if($mode == 'delete'){
	$sql = "select id from wiz_dellist where idx='$idx' and id='$wiz_session['id']'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_object($result);

	if($row->id != $wiz_session['id']){
		error("잘못된 접근 입니다.");
		exit;
	}
	else{
		$query = "delete from wiz_dellist where idx='$idx' and id='$wiz_session['id']'";
		mysqli_query($connect, $query) or mysqli_error($connect);

		complete("삭제되었습니다.","/member/del_list.php");		
	}

}
?>