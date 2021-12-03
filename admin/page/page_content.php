<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx){
   if(confirm('해당페이지를 삭제하시겠습니까?')){
      document.location = "page_save.php?mode=page_delete&idx=" + idx;
   }
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">페이지추가</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">새로운 페이지를 생성,수정,삭제합니다.</td>
			  </tr>
			</table>
			
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=6></td></tr>
        <tr class="t_th">
          <th width="8%">번호</td>
          <th width="15%">설명</td>
          <th>링크주소</td>
          <th width="10%">기능</td>
        </tr>
				<tr><td class="t_rd" colspan=6></td></tr>
	      <?
	      $sql = "select idx from wiz_content where type = 'new' order by idx desc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);
	
	      $rows = 12;
	      $lists = 5;
	    	if(!$page) $page = 1;
	    	$page_count = ceil($total/$rows);
	    	$start = ($page-1)*$rows;
	    	$no = $total-$start;
	
	      $sql = "select * from wiz_content where type = 'new' order by idx desc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	      while(($row = mysqli_fetch_object($result)) && $rows){
	      ?>
        <tr align="center">
          <td height="30" align="center"><?=$no?></td>
          <td><?=$row->title?></td>
          <td><a href="http://<?=$HTTP_HOST?>/content.php?con_idx=<?=$row->idx?>" target="_blank">http://<?=$HTTP_HOST?>/content.php?con_idx=<?=$row->idx?></a></td>
          <td>
          	<a onclick="document.location='page_content_input.php?mode=page_update&idx=<?=$row->idx?>'" class="AW-btn-s modify">수정</a>
			<a onclick="delContent('<?=$row->idx?>');" class="AW-btn-s delete">삭제</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      	<?
      		$no--;
          $rows--;
        }
      	?>
      </table>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"><a onClick="document.location='page_content_input.php?sub_mode=insert';" class="AW-btn">페이지 등록</a></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>

<? include "../footer.php"; ?>