<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&searchkey=$searchkey";
//------------------------------------------------------------------------------------------------------------------------------------

?>

<script language="JavaScript" type="text/javascript">
<!--

// 탈퇴회원 삭제
function delMemout(idx){
	if(confirm('삭제하시겠습니까?')){
		document.location = 'member_save.php?mode=memoutdel&idx=' + idx;
	}
}

//-->
</script>


		<table border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td><img src="../image/ic_tit.gif"></td>
		    <td valign="bottom" class="tit">탈퇴회원</td>
		    <td width="2"></td>
		    <td valign="bottom" class="tit_alt">탈퇴회원 목록</td>
		  </tr>
		</table>			
		<br>	  
	  
      
		<table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
		<form name="searchForm" action="<?=$PHP_SELF?>" method="get">
		<input type="hidden" name="page" value="<?=$page?>">
		<input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
			<tr>
				<td width="15%" class="t_name">조건검색</td>
				<td width="85%" class="t_value">
				<select name="searchopt" class="select">
				 <option value="name" <? if($searchopt == "name") echo "selected"; ?>>고객명
				 <option value="memid" <? if($searchopt == "memid") echo "selected"; ?>>아이디
				 </select>
				 <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
				 <input type="submit" value="검색" class="AW-btn-search" />
				</td>
			</tr>
		</form>
		</table>
		
		<?
		
		if($searchkey != "") $searchkey_sql = " and $searchopt = '$searchkey' ";
		
		$sql = "select idx from wiz_bbs where code = 'memout' $searchkey_sql order by idx desc";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = mysqli_num_rows($result);
		
		$rows = 20;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		
		?>
		<br>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td class="t_rd" colspan="20"></td></tr>
		  <tr class="t_th">
		    <th width="8%" align="center">번호</th>
		    <th width="10%" align="center">회원명</th>
		    <th width="30%" align="center">탈퇴사유</th>
		    <th width="35%" align="center">충고내용</th>
		    <th width="10%" align="center">탈퇴일</th>
		    <th width="10%" align="center">기능</th>
		  </tr>
		  <tr><td class="t_rd" colspan="20"></td></tr>
		<?
		$sql = "select idx,memid,name,subject,content,from_unixtime(wdate) as wdate from wiz_bbs where code = 'memout' $searchkey_sql order by idx desc limit $start, $rows";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		
		while(($row = mysqli_fetch_object($result)) && $rows){
		$row->content = str_replace("\n","",$row->content);
		?>
		  <tr><td height="2"></td></tr>
		  <tr class="t_tr">
		    <td align="center"><?=$no?></td>
		    <td align="center"><?=$row->name?><br>(<?=$row->memid?>)</td>
		    <td align="center"><?=$row->subject?></td>
		    <td align="center"><?=$row->content?></td>
		    <td align="center"><?=$row->wdate?></td>
		    <td align="center"><a onclick="delMemout('<?=$row->idx?>');" class="AW-btn-s del">삭제</a></td>
		  </tr>
		  <tr><td height="2"></td></tr>
		  <tr><td colspan="20" class="t_line"></td></tr>
		<?
			$no--;
			$rows--;
		}
		
		if($total <= 0){
		?>
			<tr><td height=30 colspan=10 align=center>탈퇴회원이 없습니다.</td></tr>
			<tr><td colspan='20' class='t_line'></td></tr>
		<?
		}
		?>
		</table>

		<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="5"></td></tr>
		  <tr>
		    <td width="33%"></td>
		    <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
		    <td width="33%"></td>
		  </tr>
		</table>



<? include "../footer.php"; ?>