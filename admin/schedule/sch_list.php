<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

     <script language="JavaScript" type="text/javascript">
     <!--
     function deleteBbs(code){
       if(confirm('선택한 일정을 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
         document.location = 'sch_save.php?mode=delete&code=' + code + '&page=<?=$page?>';
       }
     }
     //-->
     </script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">일정목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">일정을 생성/수정/삭제합니다.</td>
	    </tr>
	  </table>			
	  <br>	  
      

			<?
			$level_info = level_info();

			$sql = "select * from wiz_bbsinfo where type='SCH' order by code";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 일정수 : <b><?=$total?></b></td>
          <td align="right"><input type="submit" class="sbtn" value="일정추가" onClick="document.location='sch_input.php?mode=insert';"></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="7%" class="l_style" height="25" align="center">번호</td>
          <td class="l_style" align="center">영문명</td>
          <td class="l_style" align="center">일정명</td>
          <td class="l_style" align="center">목록보기</td>
          <td class="l_style" align="center">내용보기</td>
          <td class="l_style" align="center">글쓰기</td>
          <td class="l_style" align="center">답글쓰기</td>
          <td class="l_style" align="center">코멘트쓰기</td>
          <td width="10%" class="l_style" align="center">기능</td>
        </tr>
		<?
			$sql = "select * from wiz_bbsinfo where type='SCH' order by code limit $start, $rows";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while($row = mysqli_fetch_object($result)){
		?>
		  <tr>
          <td height="30" align="center"><?=$no?></td>
          <td align="center"><a href="sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>"><?=$row->code?></a></td>
          <td align="center"><a href="sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>"><?=$row->title?></a></td>
          <td align="center"><?=$level_info[$row->lpermi][name]?></td>
          <td align="center"><?=$level_info[$row->rpermi][name]?></td>
          <td align="center"><?=$level_info[$row->wpermi][name]?></td>
          <td align="center"><?=$level_info[$row->apermi][name]?></td>
          <td align="center"><?=$level_info[$row->cpermi][name]?></td>
          <td align="center">
          <input type="button" value="수정" class="gbtn" onClick="document.location='sch_input.php?mode=update&code=<?=$row->code?>&page=<?=$page?>'">
          <input type="button" value="삭제" class="gbtn" onClick="deleteBbs('<?=$row->code?>');">
          </td>
        </tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
     <?
     		$no--;
      }

    	if($total <= 0){
    	?>
    		<tr><td height="30" colspan="10" align="center">등록된 일정이 없습니다.</td></tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
    	<?
    	}
      ?>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="center"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
        </tr>
      </table>


<? include "../footer.php"; ?>