<? include "../../inc/common.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
if($copy == ""){
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>게시물이동</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){
	if(!confirm("게시물을 복사하시겠습니까?")){
		return false;
	}
}
-->
</script>
<body>
<table align="center" width="100%" border="0" cellspacing="10" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<form name="frm" action="<?=$PHP_SELF?>" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="copy" value="true">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="selbbs" value="<?=$selbbs?>">
			  <tr>
			    <td>
			      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
			        <tr>
			          <td width="50%" class="t_name">복사할 게시판</td>
			          <td width="50%" class="t_value">
			          
			          <select name="copy_code">
								<?
								$sql = "select code,title from wiz_bbsinfo where code!='$code'";
								$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
								while($row = mysqli_fetch_array($result)){
								?>
								<option value="<?=$row['code']?>"><?=$row['title']?></option>
								<?
								}
								?>
								</select>
				
			          </td>
			        </tr>
			      </table>
			    </td>
			  </tr>
			</table>

			<div class="AW-btn-wrap">
				<button type="submit" class="on">복사</button>
			</div><!-- .AW-btn-wrap -->
			</form>
		</td>
	</tr>
</table>
</body>
</html>
<?
}else{
	
	$upfile_path = WIZSHOP_PATH."/data/bbs/".$code;
	$copy_path = WIZSHOP_PATH."/data/bbs/".$copy_code;

	$tmppri = "";
	$selarr = explode("|",$selbbs);
	
	for($ii=count($selarr); $ii>=0; $ii--){
		
		if($selarr[$ii]!=""){

			$sql = "select max(prino) as prino from wiz_bbs where code = '$copy_code'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			if($row = mysqli_fetch_array($result)){
				$prino = $row[prino] + 1;
			}
			$grpno = $prino;

			$sql = "select * from wiz_bbs where idx='$selarr[$ii]'";
			//echo $sql."<br>";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_array($result);
			
			for($jj = 1; $jj <= 12; $jj++) {
				
				$upfile_idx = date('ymdhis').rand(1,9);						// 업로드파일명
	
				if(!empty($row["upfile".$jj])) {
					${"upfile".$jj."_name"} = $upfile_idx."_".$jj.".".substr($row["upfile".$jj],-3);
				
					@copy($upfile_path."/".$row["upfile".$jj], $copy_path."/".${"upfile".$jj."_name"});
					@copy($upfile_path."/M".$row["upfile".$jj], $copy_path."/M".${"upfile".$jj."_name"});
					@copy($upfile_path."/S".$row["upfile".$jj], $copy_path."/S".${"upfile".$jj."_name"});
				} else {
					${"upfile".$jj."_name"} = "";
				}
				
			}
			
			$sql = "insert into wiz_bbs (idx,prdcode,code,prino,grpno,depno,star,notice,category,memid,memgrp,
							name,email,tphone,hphone,zipcode,address,subject,content,addinfo1,addinfo2,addinfo3,addinfo4,
							addinfo5,ctype,privacy,upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,
							upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,
							upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,
							upfile12_name,movie1,movie2,movie3,passwd,count,recom,comment,ip,wdate) 
							values('','$row[prdcode]','$copy_code','$prino','$grpno','$depno','$row[star]','$row[notice]','$row[category]',
							'$row[memid]','$row[memgrp]','$row[name]','$row[email]','$row[tphone]','$row[hphone]','$row[zipcode]','$row[address]','$row['subject']',
							'$row['content']','$row[addinfo1]','$row[addinfo2]','$row[addinfo3]','$row[addinfo4]','$row[addinfo5]','$row[ctype]','$row[privacy]',
							'$upfile1_name','$upfile2_name','$upfile3_name','$upfile4_name','$upfile5_name','$upfile6_name','$upfile7_name','$upfile8_name',
							'$upfile9_name','$upfile10_name','$upfile11_name','$upfile12_name','$row[upfile1_name]','$row[upfile2_name]',
							'$row[upfile3_name]','$row[upfile4_name]','$row[upfile5_name]','$row[upfile6_name]','$row[upfile7_name]',
							'$row[upfile8_name]','$row[upfile9_name]','$row[upfile10_name]','$row[upfile11_name]','$row[upfile12_name]',
							'$row[movie1]','$row[movie2]','$row[movie3]','$row[passwd]','$row[count]','$row[recom]','$row[comment]','$row[ip]','$row['wdate']')";
   
			//echo $sql."<br>";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));

		}
	}
	
	echo "<script>alert('복사 되었습니다.');opener.document.location='bbs_list.php?code=$code';self.close();</script>";
	
}
?>