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

// 탈퇴업체 삭제
function delMallout(idx){
	if(confirm('삭제하시겠습니까?')){
		document.location = 'mall_save.php?mode=malloutdel&idx=' + idx;
	}
}

// 탈퇴신청 승인
function mallOut(mallid) {
	if(confirm("탈퇴신청 승인 시 해당 업체의 상품 및 관련 정보가\n\n모두 삭제되며 복구되지 않습니다.\n\n탈퇴신청을 승인하시겠습니까?")) {
		document.location = 'mall_save.php?mode=mallout&mallid=' + mallid;
	}
}

//-->
</script>


		<table border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td><img src="../image/ic_tit.gif"></td>
		    <td valign="bottom" class="tit">탈퇴업체</td>
		    <td width="2"></td>
		    <td valign="bottom" class="tit_alt">탈퇴업체 목록</td>
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
         <option value="name" <? if($searchopt == "name") echo "selected"; ?>>업체명
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

			$sql = "select wb.idx from wiz_bbs as wb left join wiz_mall as wm on wb.memid = wm.id where wb.code = 'mallout' $searchkey_sql order by wb.idx desc";
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
		    <th width="10%" align="center">업체명</th>
		    <th align="center">탈퇴사유</th>
		    <th width="10%" align="center">연락처</th>
		    <th width="10%" align="center">탈퇴신청일</th>
		    <th width="10%" align="center">탈퇴승인일</th>
		    <th width="10%" align="center">기능</th>
		  </tr>
		  <tr><td class="t_rd" colspan="20"></td></tr>
		<?
		$sql = "select wb.idx,wb.memid,wb.name,wb.subject,wb.content,wb.tphone,
						from_unixtime(wb.wdate) as wdate, wb.addinfo1 as wdate2, wm.id
						from wiz_bbs as wb left join wiz_mall as wm on wb.memid = wm.id
						where wb.code = 'mallout' $searchkey_sql order by wb.idx desc limit $start, $rows";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while(($row = mysqli_fetch_object($result)) && $rows){
		$row->content = str_replace("\n","",$row->content);
		?>
		  <tr><td height="2"></td></tr>
		  <tr class="t_tr">
		    <td align="center"><?=$no?></td>
		    <td align="center"><?=$row->name?><br>(<?=$row->memid?>)</td>
		    <td align="center"><?=$row->content?></td>
		    <td align="center"><?=$row->tphone?></td>
		    <td align="center"><?=$row->wdate?></td>
        <td align="center"><?=$row->wdate2?></td>
		    <td align="center">
          <? if(!empty($row->id)) { ?>
	          	<a onclick="mallOut('<?=$row->memid?>');" class="AW-btn-s modify">수정</a>
          <? } ?>
          		<a onclick="delMemout('<?=$row->idx?>');" class="AW-btn-s modify">삭제</a>
		    </td>
		  </tr>
		  <tr><td height="2"></td></tr>
		  <tr><td colspan="20" class="t_line"></td></tr>
		<?
			$no--;
			$rows--;
		}

		if($total <= 0){
		?>
			<tr class="t_tr"><td height=30 colspan=10 align=center>탈퇴회원이 없습니다.</td></tr>
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