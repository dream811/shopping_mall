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
		<td align="left" style="padding:14px 0 14px 10px; text-align:left;">
			<span><?=$checkbox_body?></span>
			<span class="faq_ttl">Q </span>
			<?=$catname?> <?=$re_space?><?=$re_icon?>
			<a href="javascript:faqShow('<?=$no?>');"><?=$row['subject']?></a> <?=$comment?> <?=$lock_icon?> <?=$new_icon?> <?=$hot_icon?>
			
			<table width="100%" cellspacing="0" cellpadding="0" id="faq<?=$no?>" class="faq_inner" style="display:none">
				<tr>
					<td width="15" valign="top" align="left" style="padding:16px 0 16px 30px;"><span class="faq_ttl">A </span></td>
					<td align="left" style="text-align:left; word-break:break-all;"><?=$content?></td>
					<td align="right" valign="top" style="padding:16px 30px 16px 0"><?=$modify_btn?>&nbsp;<?=$delete_btn?></td>
				</tr>
			</table>
		</td>
	</tr>
