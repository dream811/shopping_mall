<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx){
   if(confirm('해당팝업을 삭제하시겠습니까?')){
      document.location = "popup_save.php?mode=delete&idx=" + idx;
   }
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">팝업관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">팝업 등록,수정합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th>제목</th>
          <th>공지기간</th>
          <th>등록일</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      $sql = "select idx from wiz_content where type = 'popup' order by idx desc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);
	
	      $rows = 12;
	      $lists = 5;
	    	$page_count = ceil($total/$rows);
	    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
	    	$start = ($page-1)*$rows;
	    	$no = $total-$start;
	
	      $sql = "select * from wiz_content where type = 'popup' order by idx desc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	      while(($row = mysqli_fetch_object($result)) && $rows){
	      ?>
	        <tr align="center" class="t_tr">
	          <td height="30" align="center"><?=$no?></td>
	          <td><?=$row->title?></td>
	          <td><?=$row->sdate?> ~ <?=$row->edate?></td>
	          <td><?=$row->wdate?></td>
	          <td>
                <a onclick="document.location='popup_input.php?mode=update&idx=<?=$row->idx?>'" class="AW-btn-s modify">수정</a>
                <a onclick="delContent('<?=$row->idx?>');" class="AW-btn-s del">삭제</a>
	          </td>
	        </tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      	$no--;
	      	$rows--;
	      }
	
	      if($total <= 0){
	      ?>
	          <tr><td colspan='5' align='center'>작성된 팝업이 없습니다.</td></tr>
	          <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      }
	      ?>
      </table>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"><a onClick="document.location='popup_input.php';" class="AW-btn">팝업등록</a></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>

<? include "../footer.php"; ?>