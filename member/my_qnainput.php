<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";		// 로그인 체크
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이쇼핑 &gt; <strong>1:1 문의게시판</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인
include "../inc/mem_info.inc"; 			// 회원 정보

if($sub_mode == "") $sub_mode = "insert";

$sql = "select * from wiz_consult where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$qna_info = mysqli_fetch_object($result);

?>
<script language="javascript">
<!--
function inputCheck(frm){
	
	if(frm.subject.value == ""){
		alert("제목을 입력하세요.");
		frm.subject.focus();
		return false;
	}
	
	if(frm.question.value == ""){
		alert("내용을 입력하세요.");
		frm.question.focus();
		return false;
	}

}
-->
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">
    	
    	<table width="100%" border="0" cellpadding="0" cellspacing="0"> 
    	<form name="frm" action="my_save.php" method="post" onSubmit="return inputCheck(this)">
			<input type="hidden" name="idx" value="<?=$idx?>">
			<input type="hidden" name="mode" value="my_qna">
			<input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
			  <tr>
			    <td colspan="4" height="2" bgcolor="#a9a9a9"></td>
			  </tr>                  
			  <tr>
			    <td align="center" width="15%" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>제목 *</strong></td>
			    <td align="left" width="85%" colspan="3" style="padding-left:10px;"><input name="subject" value="<?=$qna_info->subject?>" type="text" size="60" class="input" /></td>
			  </tr>
			  <tr>
			    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
			  <tr>
			    <td align="center" height="130" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>내용 *</strong></td>
			    <td align="left" colspan="3" style="padding-left:10px;"><textarea name="question" cols="85" rows="8" class="input"><?=$qna_info->question?></textarea></td>
			  </tr>
			  <tr>
			    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
  		</table>
  		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  			<tr><td height="10"></td></tr>
			  <tr>
			    <td align="center"><input type="image" src="/images/member/btn_confirm.gif" border="0">&nbsp;<img src="/images/member/btn_list.gif" border="0" onClick="history.go(-1)" style="cursor:hand"></td>
			  </tr>
			</form>
			</table>
  		
    </td>
  </tr>
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>