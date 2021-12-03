<?
include "../../inc/common.inc";
include "../../inc/util.inc";

if($excelup != "ok"){
?>
<html>
<head>
<title>:: 회원엑셀 업로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script Language="Javascript">
<!--

function inputCheck(frm) {
	if(frm.upfile.value == "") {
		alert("파일을 첨부해주세요.");
		frm.upfile.focus();
		return false;
	}
}

//-->
</script>
</head>

<body style="padding:10px;">
<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
<input type="hidden" name="excelup" value="ok">
<table width="100%" align="center" border="0" cellpadding="3" cellspacing="6" class="t_style">

  <tr>
    <td bgcolor="ffffff">
    <table><tr><td></td></tr></table>
     <table cellspacing="2" cellpadding="0" border="0">
       <tr>
        <td width="100"><font color="2369C9"><b>파일첨부</b></font></td>
        <td>
        	<input type="file" name="upfile" > <a href="mem_sample.xls"><b><font color="black">[샘플다운로드]</font></b></a>
        </td>
		</tr>
       <tr>
        <td valign="top"><font color="2369C9"><b></b></font></td>
        <td>
        	<font color="red">다운로드 받은 샘플 형식에 맞춰서 입력하시기 바랍니다.</font>
        </td>
		</tr>
    </table>
   </td>
 </tr>
</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="self.close();">닫기</a>
</div><!-- .AW-btn-wrap -->


</form>

<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
        1. <a href="mem_sample.xls"><b><font color="black">[샘플다운로드]</font></b></a>를 클릭하여 샘플파일을 다운로드합니다.<br>
        2. 각 항목에 값을 입력합니다.<br>
        3. 파일 > 다른이름으로저장 > 파일형식을 <b><font color="black">"텍스트 (탭으로 분리) (*.txt)"</font></b>로 저장합니다.<br>
        4. 작성한 파일을 첨부후 확인 버튼을 클릭합니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->

</body>
</html>
<?
}else{


	$upfile_path = $_SERVER['DOCUMENT_ROOT']."/data";		// 파일이미지 업로드 경로
	
	if($upfile[size] > 0){
		
		copy($upfile[tmp_name], $upfile_path."/tmp_mem.xls");
		chmod($upfile_path."/tmp_mem.xls", 0606);

		$meminfo = file($upfile_path."/tmp_mem.xls");

		for($ii = 1; $ii < count($meminfo); $ii++) {

			$member = explode("\t", $meminfo[$ii]);
			
			$id = $member[0];
			$passwd = $member[1];
			$name = $member[2];
			$email = $member[3];
			$tphone = $member[4];
			$hphone = $member[5];
			$fax = $member[6];
			$post = $member[7];
			$address = $member[8];
			$address2 = $member[9];
			$reemail = $member[10];
			$resms = $member[11];
			$birthday = $member[12];
			$bgubun = $member[13];
			$marriage = $member[14];
			$memorial = $member[15];
			$job = $member[16];
			$scholarship = $member[17];
			$tmpconsph = $member[18];
			$level = $member[19];
			$recom = $member[20];
			$visit = $member[21];
			$visit_time = $member[22];
			$comment = $member[23];
			$com_num = $member[24];
			$com_name = $member[25];
			$com_owner = $member[26];
			$com_post = $member[27];
			$com_address = $member[28];
			$com_kind = $member[29];
			$com_class = $member[30];
			$wdate = $member[31];

			$passwd = md5($passwd);

   		$sql = "insert into wiz_member(id,passwd,name,email,tphone,hphone,fax,post,address,address2,reemail,resms,birthday,bgubun,
   								marriage,memorial,scholarship,job,consph,conprd,level,recom,visit,visit_time,comment,com_num,com_name,com_owner,com_post,com_address,com_kind,com_class,wdate)
									values('$id', '$passwd', '$name', '$email', '$tphone', '$hphone', '$fax', '$post', '$address', '$address2', '$reemail', '$resms',
									'$birthday', '$bgubun', '$marriage', '$memorial', '$scholarship', '$job', '$tmpconsph', '$tmpconprd',
									'$level', '$recom', '$visit', '$visit_time', '$comment','$com_num','$com_name','$com_owner','$com_post','$com_address','$com_kind','$com_class', now())";

			//echo $sql;
			mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
		}
		
		unlink($upfile_path."/tmp_mem.xls");
		echo "<script>alert('입력되었습니다.');window.opener.document.location.href = window.opener.document.URL;self.close();</script>";
		
	}
	

}
?>