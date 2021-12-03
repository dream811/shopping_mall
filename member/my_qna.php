<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   	// 유틸 라이브러리

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이페이지 &gt; <strong>1:1 문의게시판</strong>";

include "../inc/page_info.inc"; 	// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/mem_info.inc"; 		// 회원 정보

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">

			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tr>
			    <td colspan="10" height="2" bgcolor="#a9a9a9"></td>
			  </tr>
			  <tr bgcolor="#f9f9f9">
			    <td width="15%" height="30" align="center"><strong>처리현황</strong></td>
			    <td align="center"><strong>제목</strong></td>
			    <td width="15%" align="center"><strong>작성일</strong></td>
			  </tr>
			  <tr>
			    <td colspan="10" height="1" bgcolor="#d7d7d7"></td>
			  </tr>
				<?
				if($searchkey) $search_sql = " and $searchopt like '%$searchkey%' ";

				$sql = "select idx from wiz_consult where memid = '$wiz_session['id']' $search_sql order by idx desc";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$total = mysqli_num_rows($result);

				$rows = 12;
				$lists = 5;
				$page_count = ceil($total/$rows);
				if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;

				$sql = "select * from wiz_consult where memid = '$wiz_session['id']' $search_sql order by idx desc limit $start, $rows";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

				while(($row = mysqli_fetch_object($result)) && $rows){
					if($row->status == "N") $row->status = "접수완료";
					else $row->status = "답변완료";
				?>
				<tr>
				  <td align="center" height="28"><?=$row->status?></td>
				  <td align="left" style="padding-left:10px;"><a href="my_qnaview.php?idx=<?=$row->idx?>"><?=$row->subject?></a></td>
				  <td align="center"><?=$row->wdate?></td>
				</tr>
				<tr>
				  <td colspan="10" height="1" bgcolor="#d7d7d7"></td>
				</tr>
				<?
				   $rows--;
				}
				if($total <= 0){
				?>
					<tr><td colspan="5" align="center" height="50">작성한 문의가 없습니다.</td></tr>
					<tr><td colspan="5" bgcolor="#dddddd" height="1"></td></tr>
				<?
				}
				?>

				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
				  	<td width="20%"></td>
				    <td width="60%" height="50" align="center">
							<? print_pagelist($page, $lists, $page_count, $param); ?>
				    </td>
				    <td width="20%" align="right"><a href="/member/my_qnainput.php"><img src="/images/member/btn_write.gif" border=0></a></td>
				  </tr>
				</table>

				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				  <tr>
				    <td height="50" align="center" bgcolor="#f9f9f9" style="border-top:1px solid #a9a9a9; border-bottom:1px solid #d7d7d7; padding:5px 0px;">

				        <table width="0%" border="0" cellpadding="0" cellspacing="0">
				        <form name="sfrm" action="<?=$PHP_SELF?>">
				      	<input type="hidden" name="code" value="<?=$code?>">
				      	<input type="hidden" name="category" value="<?=$category?>">
				          <tr>
				            <td style="padding-right:10px;"><img src="/images/member/search_tit.gif" width="47" height="9" border="0" /></td>
				            <td style="padding-right:5px;">
											<select name="searchopt" class="select">
											<option value="subject">제 목</option>
											<option value="content">내 용</option>
											</select>
											<script language="javascript">
											<!--
											searchopt = document.sfrm.searchopt;
											for(ii=0; ii<searchopt.length; ii++){
											 if(searchopt.options[ii].value == "<?=$searchopt?>")
											    searchopt.options[ii].selected = true;
											}
											-->
											</script>
				            </td>
				            <td style="padding-right:10px;"><input name="searchkey" type="text" class="search_input" value="<?=$searchkey?>" size="50"></td>
				            <td><input type="image" src="/images/member/btn_search.gif" border="0" align="absmiddle" width="75" height="21" /></td>
				          </tr>
				        </form>
				        </table>

				    </td>
				  </tr>
				</table>

		</td>
  </tr>
</table>
<?

include "../inc/footer.inc"; 		// 하단디자인

?>