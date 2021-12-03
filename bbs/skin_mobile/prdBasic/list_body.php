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
$file_icon	: 첨부파일 아이콘
$name				: 이름
$email			: 이메일
$wdate			: 작성일
$count			: 조회수
$comment		: 댓글수
$recom			: 추천
$content		: 글내용
*/
?>

<? if($prdno%2 == 0) echo "<tr>"; ?>
<td width="48%" align="center" >
	<div class="M_prd_List">
		<a href="<?=$viewBbs?>">
		  <table>
				<tr>
				<td align="center" class="M_prd_List_td"><img src="<?=$upimg_s?>" width="120" height="80" alt="상품이미지"></td>
				</tr>
				<tr>
				<th align="center" class="M_prd_List_th"><?=$nosubject?></th>
				</tr>
			</table>
		</a>
	</div>
</td>
<?$prdno++;?>

