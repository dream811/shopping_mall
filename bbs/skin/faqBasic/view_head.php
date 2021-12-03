<script language="javascript">
<!--
function viewImg(img){
   var url = "/admin/bbs/view_img.php?code=<?=$code?>&img=" + img;
   window.open(url, "viewImg", "height=100, width=100, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
}
//-->
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td height="27"></td></tr>
  <tr>
    <td colspan="4" height="2" bgcolor="#a9a9a9"></td>
  </tr>
  <tr>
    <td align="center" bgcolor="#f9f9f9" height="30" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>이름</strong></td>
    <td align="left" style="padding-left:10px; border-right:1px solid #d7d7d7;"><?=$name?></td>
    <td align="center" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>이메일</strong></td>
    <td align="left" style="padding-left:10px;"><?=$email?></td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>          
  <tr>
    <td width="15%" align="center" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>작성일</strong></td>
    <td width="35%" align="left" style="padding-left:10px; border-right:1px solid #d7d7d7;"><?=$wdate?></td>
    <td width="15%" align="center" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>조회수</strong></td>
    <td width="35%" align="left" style="padding-left:10px;"><?=$count?></td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>
  <?=$hide_upfile_start?>
  <tr>
    <td align="center" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>파일첨부</strong></td>
    <td colspan="3" align="left" style="padding-left:10px;"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?> <?=$upfile4?> <?=$upfile5?> <?=$upfile6?> <?=$upfile7?> <?=$upfile8?> <?=$upfile9?> <?=$upfile10?> <?=$upfile11?> <?=$upfile12?></td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>
  <?=$hide_upfile_end?>   
  <tr>
    <td align="center" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>제목</strong></td>
    <td colspan="3" style="padding-left:10px;">
    <table width="100%" border="0">
    	<tr>
    		<td width="80%" align="left"><?=$catname?><?=$subject?></td>
    		<td width="20%" align="right" style="padding-right:10px"><?=$hide_recom_start?>추천:<?=$recom?><?=$hide_recom_end?></td>
    	</tr>
    </table>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>           
  <tr>
    <td height="50" colspan="4">
    	<table width="100%" border="0" cellpadding="10" cellspacing="0">
        <tr>
          <td align="left" style="padding-top:5px">
          <?=$upimg1?><?=$upimg2?><?=$upimg3?><?=$upimg4?><?=$upimg5?><?=$upimg6?>
        	<?=$upimg7?><?=$upimg8?><?=$upimg9?><?=$upimg10?><?=$upimg11?><?=$upimg12?>
      		<?=$movie1?><?=$movie2?><?=$movie3?>
      		<?=$content?>
          </td>
        </tr>
    	</table>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>          
  <tr>
    <td width="15%" align="center" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>이전글</strong></td>
    <td width="85%" align="left" colspan="3" style="padding-left:10px;"><?=$prev?></td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>          
  <tr>
    <td align="center" height="30" bgcolor="#f9f9f9" style="padding-left:10px; border-right:1px solid #d7d7d7;"><strong>다음글</strong></td>
    <td align="left" colspan="3" style="padding-left:10px;"><?=$next?></td>
  </tr>
  <tr>
    <td colspan="4" height="1" bgcolor="#d7d7d7"></td>
  </tr>
  <tr>
  	<td height="10"></td>
  </tr>
</table>