<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";		// 로그인 체크
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이쇼핑 &gt; <strong>1:1 문의게시판</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인
include "../inc/mem_info.inc"; 			// 회원정보

$sql = "select * from wiz_consult where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$qna_info = mysqli_fetch_object($result);

$qna_info->question = nl2br($qna_info->question);
$qna_info->answer = nl2br($qna_info->answer);
?>
<script language="javascript">
<!--
function delConfrm(){
	if(confirm("삭제하시겠습니까.")){
		document.location = "my_save.php?mode=my_qna&sub_mode=delete&idx=<?=$idx?>";
	}
}
-->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">
    	
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td height="27"></td></tr>
			  <tr>
			    <td colspan="4" height="2" bgcolor="#a9a9a9"></td>
			  </tr>
			  <tr>
			    <td align="center" width="15%" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>제목</strong></td>
			    <td align="left" colspan="3" width="85%" style="padding-left:10px;"><?=$qna_info->subject?></td>
			  </tr>
			  <tr>
			    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
			  <tr>
			    <td align="center" width="15%" height="60" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>문의내용</strong></td>
			    <td align="left" colspan="3" width="85%" style="padding-left:10px;"><?=$qna_info->question?></td>
			  </tr>
			  <tr>
			    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
			  <tr>
			    <td align="center" width="15%" height="60" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>답변</strong></td>
			    <td align="left" colspan="3" width="85%" style="padding-left:10px;"><?=$qna_info->answer?></td>
			  </tr>
			  <tr>
			    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  			<tr><td height="10"></td></tr>
			  <tr>
			    <td align="right">
			    	<a href="my_qna.php"><img src="/images/member/btn_list.gif" border="0"></a>
			    	<a href="my_qnainput.php?sub_mode=modify&idx=<?=$idx?>"><img src="/images/member/btn_modify.gif" border="0"></a>
	   				<a href="javascript:delConfrm();"><img src="/images/member/btn_delete.gif" border="0"></a>
			    </td>
			  </tr>
			</form>
			</table>
			
		</td>
	</tr>
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>