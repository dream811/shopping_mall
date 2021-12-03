<script language="javascript">
<!--
function viewImg(img){
   var url = "/admin/bbs/view_img.php?code=<?=$code?>&img=" + img;
   window.open(url, "viewImg", "height=100, width=100, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
}
//-->
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_common_table view">
  <tr>
    <th align="center"><strong>이름</strong></th>
    <td align="left" style="text-align:left; padding-left:10px;"><?=$name?></td>
    <th align="center"><strong>이메일</strong></th>
    <td align="left" style="text-align:left; padding-left:10px;"><?=$email?></td>
  </tr>
  <tr>
    <th width="15%" align="center" height="30"><strong>작성일</strong></th>
    <td width="35%" align="left" style="text-align:left; padding-left:10px;"><?=$wdate?></td>
    <th width="15%" align="center"><strong>조회수</strong></th>
    <td width="35%" align="left" style="text-align:left; padding-left:10px;"><?=$count?></td>
  </tr>
  <?=$hide_upfile_start?>
  <tr>
    <th align="center" height="30"><strong>파일첨부</strong></th>
    <td colspan="3" align="left" style="text-align:left; padding-left:10px;"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?> <?=$upfile4?> <?=$upfile5?> <?=$upfile6?> <?=$upfile7?> <?=$upfile8?> <?=$upfile9?> <?=$upfile10?> <?=$upfile11?> <?=$upfile12?></td>
  </tr>
  <?=$hide_upfile_end?>   
  <tr>
    <th align="center" height="30"><strong>제목</strong></th>
    <td colspan="3" style="text-align:left; padding-left:10px;">
    	<?=$catname?><?=$subject?>
    	<?=$hide_recom_start?>추천:<?=$recom?><?=$hide_recom_end?>
	</td>		
  </tr>
  <?=$hide_star_start?>
  <tr>
    <th align="center" height="30"><strong>평점</strong></th>
    <td colspan="3" style="text-align:left; padding-left:10px;"><?=$star?></td>
  </tr>
  <?=$hide_star_end?>    
  <tr>
    <td height="50" colspan="4" style="padding:20px 0;">
	    <?=$upimg1?><?=$upimg2?><?=$upimg3?><?=$upimg4?><?=$upimg5?><?=$upimg6?>
		<?=$upimg7?><?=$upimg8?><?=$upimg9?><?=$upimg10?><?=$upimg11?><?=$upimg12?>
		<?=$movie1?><?=$movie2?><?=$movie3?>
		<?=$content?>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_common_table view">
	<tr>
		<th width="15%" align="center" height="30"><strong>이전글</strong></th>
		<td width="85%" align="left" colspan="3" style="text-align:left; padding-left:10px;">
			<?=$prev?>
		</td>
	</tr>
	<tr>
		<th align="center" height="30"><strong>다음글</strong></th>
		<td align="left" colspan="3" style="text-align:left; padding-left:10px;">
			<?=$next?>
		</td>
	</tr>
</table>