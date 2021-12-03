<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?

$param = "code=".$code."&page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey;

// 설문입력
if($mode == "insert"){
   
   $sql = "insert into wiz_poll(idx,code,polluse,pollmain,sdate,edate,apermi,cpermi,subject,content,wdate,cnt) 
				values('','$code','$polluse','$pollmain','$sdate','$edate','$apermi','$cpermi','$subject','$content',now(),0)"; 
      
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		
	$sql = "select max(idx) as idx from wiz_poll";
	 $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	 $row = mysqli_fetch_array($result);
	 $idx = $row['idx'];
	    
   complete("설문을 추가 하였습니다.","poll_input.php?mode=update&idx=$idx&code=$code");
   

// 설문수정
}else if($mode == "update"){

   $sql = "update wiz_poll set polluse='$polluse',pollmain='$pollmain',sdate='$sdate',edate='$edate',apermi='$apermi',cpermi='$cpermi',subject='$subject',content='$content' where idx = '$idx'";

   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
   complete("설문을 수정 하였습니다.","poll_input.php?mode=$mode&idx=$idx&$param");


// 설문삭제
}else if($mode == "delete"){
   
   $sql = "delete from wiz_poll where idx = '$idx'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
   $sql = "delete from wiz_polldata where pidx = '$idx'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
   complete("설문을 삭제 하였습니다.","poll_list.php?$param");




}else if($mode == "question"){

	if($smode == "insert"){
		
		$sql = "insert into wiz_polldata(idx,pidx,question,answer01,count01,answer02,count02,answer03,count03,answer04,count04,answer05,count05,answer06,count06,answer07,count07,answer08,count08,answer09,count09,answer10,count10) 
				values('','$pidx','$question','$answer01','$count01','$answer02','$count02','$answer03','$count03','$answer04','$count04','$answer05','$count05','$answer06','$count06','$answer07','$count07','$answer08','$count08','$answer09','$count09','$answer10','$count10')";       
   	
   	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   	
   	echo "<script>alert('질문을 추가 하였습니다.');history.go(-1);opener.document.location.reload();</script>";
   	
   	//complete("질문을 추가 하였습니다.","");
   
   
	}else if($smode == "update"){
		
		$sql = "update wiz_polldata set question='$question',
   			answer01='$answer01',count01='$count01',answer02='$answer02',count02='$count02',answer03='$answer03',count03='$count03',answer04='$answer04',count04='$count04',answer05='$answer05',count05='$count05',
   			answer06='$answer06',count06='$count06',answer07='$answer07',count07='$count07',answer08='$answer08',count08='$count08',answer09='$answer09',count09='$count09',answer10='$answer10',count10='$count10' where idx = '$idx'";
		//echo $sql;
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		
		echo "<script>alert('설문을 수정 하였습니다.');document.location='poll_question.php?pidx=$pidx&mode=question&smode=update&idx=$idx';opener.document.location.reload();</script>";

	}else if($smode == "delete"){
		
		$sql = "delete from wiz_polldata where idx = '$idx'";
		
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		
		complete("설문을 삭제 하였습니다.","poll_input.php?mode=update&idx=$pidx");
		
	}

// 코멘트 입력
} else if ($mode == "comment") {
	
	$ctype = "POLL";
	
	$sql = "insert into wiz_comment(idx,ctype,cidx,prdcode,star,id,name,content,passwd,wdate,wip) 
					values('', '$ctype', '$cidx', '$prdcode', '$star', '$wiz_session['id']', '$name', '$content', '$passwd', now(), '$_SERVER[REMOTE_ADDR]')";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("댓글을 작성하였습니다.", "poll_input.php?mode=update&code=$code&idx=$cidx");
	
// 코멘트 삭제
} else if($mode == "delco"){
	
	$sql = "delete from wiz_comment where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	comalert("댓글을 삭제하였습니다.", "poll_input.php?mode=update&code=$code&idx=$cidx");

}

?>