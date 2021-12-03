<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$param = "page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey;
?>
<script language="JavaScript" type="text/javascript">
<!--
function delConfirm(code){
	if(confirm("삭제 하시겠습니까?")){
		document.location = "pollinfo_save.php?mode=delete&code=" + code;
	}
}
//-->
</script>


	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">설문목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">설문을 검색 관리합니다.</td>
	    </tr>
	  </table>			
	  <br>	  
      
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
      </table>
			<?
			$sql = "select count(code) as all_total from wiz_pollinfo";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_object($result);
			$all_total = $row->all_total;

			if($searchopt != "") $search_sql .= " where $searchopt like '%$searchkey%' ";

			$sql = "select * from wiz_pollinfo $search_sql order by code desc";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;

			if($start>1) mysql_data_seek($result,$start);

			?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 설문수 : <b><?=$all_total?></b></td>
          <td align="right">
          <input type="button" class="sbtn" value="설문등록" onClick="document.location='pollinfo_input.php?mode=insert';">
          </td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%" height="25" align="center" class="l_style">번호</td>
          <td width="20%" align="center" class="l_style">코드</td>
          <td align="center" class="l_style">설문명</td>
          <td width="20%" align="center" class="l_style">스킨</td>
          <td width="12%" align="center" class="l_style">기능</td>
        </tr>
			<?
			while(($row = mysqli_fetch_array($result)) && $rows){
			?>
        <tr>
          <td height="30" align="center"><?=$no?></td>
          <td align="center"><?=$row['code']?></td>
          <td align="center"><a href="pollinfo_input.php?mode=update&code=<?=$row['code']?>&page=<?=$page?>&<?=$param?>"><?=$row['title']?></a></td>
          <td align="center"><?=$row[skin]?></td>
          <td align="center">
          <img src="../image/btn_edit_s.gif" style="cursor:hand" onClick="document.location='pollinfo_input.php?mode=update&code=<?=$row['code']?>&page=<?=$page?>&<?=$param?>';">
          <img src="../image/btn_delete_s.gif" style="cursor:hand" onClick="delConfirm('<?=$row['idx']?>')">
          </td>
        </tr>
        <tr><td height="1" colspan="11"  bgcolor="EBEBEB"></td></tr>
			<?
				$no--;
				$rows--;
			}

			if($total <= 0){
			?>
    	  <tr><td height="30" colspan="10" align="center">등록된 설문이 없습니다.</td></tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
			<?
			}
			?>
      </table>

      <br>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><? print_pagelist($page, $lists, $page_count, $param); ?></td>
        </tr>
      </table>


<? include "../footer.php"; ?>