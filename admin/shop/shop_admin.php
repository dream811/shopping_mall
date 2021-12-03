<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delAdmin(id){
   if(confirm('해당관리자를 삭제하시겠습니까?')){
      document.location = "shop_save.php?mode=shop_admin&sub_mode=delete&id=" + id;
   }
}
//-->
</script>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">관리자목록</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">관리자를 추가/수정/삭제 합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=6></td></tr>
        <tr class="t_th">
          <th width="8%">번호</td>
          <th width="15%">아이디</td>
          <th>성명</td>
          <th width="20%">마지막접속</td>
          <th width="20%">등록일</td>
          <th width="10%">기능</td>
        </tr>
				<tr><td class="t_rd" colspan=6></td></tr>
	      <?
	      $sql = "select id from wiz_admin order by wdate desc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);
	
	      $rows = 12;
	      $lists = 5;
	    	$page_count = ceil($total/$rows);
	    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
	    	$start = ($page-1)*$rows;
	    	$no = $total-$start;
	    	
	      $sql = "select * from wiz_admin order by wdate desc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      
	      while(($row = mysqli_fetch_array($result)) && $rows){
	      ?>
	        <tr align="center" class="t_tr"> 
	          <td height="30" align="center"><?=$no?></td>
	          <td><?=$row['id']?></td>
	          <td><?=$row['name']?></td>
	          <td><?=$row['last']?></td>
	          <td><?=$row['wdate']?></td>
	          <td>
              	<a onclick="document.location='shop_admin_input.php?sub_mode=update&id=<?=$row['id']?>'" class="AW-btn-s modify">수정</a>
				<a onclick="delAdmin('<?=$row['id']?>');" class="AW-btn-s del">삭제</a>
	          </td>
	        </tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      	$no--;
	      	$rows--;
	      }
	      if($total <= 0){
	      ?>
	        <tr><td height="30" colspan="10" align="center">등록된 관리자가 없습니다.</td></tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      }
	      ?>
      </table>
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"><a onClick="document.location='shop_admin_input.php?sub_mode=insert';" class="AW-btn">관리자 등록</a></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>
                            

<? include "../footer.php"; ?>