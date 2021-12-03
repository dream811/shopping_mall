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
	<tr>
		<td align="center" height="35"><?=$no?></td>
		<td style="text-align:left;">
			<span class="img_box"><?=$prdimg?></span>
			<div class="ttl">
				<div style="font-size:12px; color:#999;"><?=$prdname?></div>
				<?=$catname?> <?=$re_space?><?=$re_icon?><?=$subject?> <?=$comment?> <?=$lock_icon?> <?=$new_icon?> <?=$hot_icon?> <?=$file_icon?>
			</div>
		</td>
		<td align="center"><?=$name?></td>
		<td align="center"><?=$wdate?></td>
		<td align="center"><?=$count?></td>
		<?=$hide_recom_start?>
		<td align="center"><?=$recom?></td>
		<?=$hide_recom_end?>
	</tr>   
	