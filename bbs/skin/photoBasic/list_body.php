<?
/*
$no					: 글 넘버
$catname		: 카테고리
$re_space		: 답글 깊이
$re_icon		: 답글 아이콘
$subject		: 제목
$lock_icon	: 비밀글 아이콘
$new_icon		: 새글 아이콘
$hot_icon		: 인기글 아이콘
$name				: 이름
$email			: 이메일
$wdate			: 작성일
$count			: 조회수
$comment		: 댓글수
$recom			: 추천
$content		: 글내용
$upimg_s		: 소이미지

$viewImg		: 새창으로 큰 이미지 보기
$viewBbs		: 게시글 보기

$idx				: 게시물 증가값
$line				: 관리자 > 게시판관리 > 해당 게시판 > 줄바꿈 게시물 수에 입력한 값
						  해당 값만큼 게시물이 나오고 줄바꿈하게됩니다.
*/

?>
<? if($idx%$line == 0) echo "<tr>"; ?>
	
	<td width="20%" align="left" valign="top">
		<table width="150" cellpadding="0" cellspacing="0" border="0">
			<tr><td height="10" align="center"></td></tr>
			<tr>
			  <td>
			    <table width="150" cellpadding="4" cellspacing="0" bgcolor="E6E6E6">
			      <tr>
			        <td bgcolor="#ffffff" align="center" valign="center"><a href="<?=$viewBbs?>"><img src="<?=$upimg_s?>" width="140" height="105" border="0" /></a></td>
			      </tr>
			    </table>
			  </td>
			</tr>
			<tr><td height="5" align="center"></td></tr>
			<tr>
				<td height=20 align="left" style="word-break:break-all;">
			    <table width="100%" border="0" cellpadding="0" cellspacing="0">
			    	<tr>
			      	<td width="1%" align="left"><?=$checkbox_body?></td>
			        <td align="left"><?=$subject?> <?=$comment?></td>
			      </tr>
			      <?=$hide_recom_start?>
				    <tr><td align="left" colspan="2">추천 : <?=$recom?></td></tr>
				    <?=$hide_recom_end?>
			    </table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
		</table>
	</td>