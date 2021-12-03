<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$level_info = level_info();
?>

<script language="JavaScript" type="text/javascript">
<!--
function deleteBbs(code){
   if(confirm('선택한 게시판을 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
      document.location = 'bbs_pro_save.php?mode=delete&code=' + code;
   }
}
//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">게시판목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">게시판을 생성/수정/삭제합니다.</td>
	    </tr>
	  </table>
	  <br>

			<?
			$sql = "select code from wiz_bbsinfo where type!='SCH'";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 20;
			$lists = 5;
			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page || $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			$no = $total-$start;
			?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 게시판수 : <b><?=$total?></b></td>
          <td align="right">
          	<? if(strpos($wiz_admin['permi'], "08-03") !== false || !strcmp($wiz_admin['designer'], "Y")){ ?>
          	<? 	if(!strcmp($shop_info->addbbs_use, "Y")) { ?>
            <a onClick="document.location='bbs_pro_input.php?mode=insert';" class="AW-btn">게시판 추가</a>
          	<? } ?>
          	<? } ?>
          </td>
        </tr>
        <tr>
        	<td colspan="2" height="5"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="10%">영문명</th>
          <th>게시판명</th>
          <th width="8%">스킨</th>
          <th width="8%">사용</th>
          <th width="8%">게시판타입</th>
          <th width="8%">목록보기</th>
          <th width="8%">읽기</th>
          <th width="8%">쓰기</th>
          <th width="20%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan="20"></td></tr>

				<?
				$sql = "select * from wiz_bbsinfo where type!='SCH' limit $start, $rows";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

				while(($row = mysqli_fetch_object($result)) && $rows){

					switch($row->type) {
						case "BBS" : $bbstype = "게시판"; break;
						case "PRD" : $bbstype = "상품Q&A"; break;
						case "RV" : $bbstype = "상품후기"; break;
					}

					if(!strcmp($row->skin, "photoBasic")) $bbstype = "포토게시판";
					else $bbstype = "게시판";

          if(!isset($param)) $param = "";
				?>
			  <tr class="t_tr">
          <td height="30" align="center"><?=$no?></td>
          <td align="center"><?=$row->code?></td>
          <td align="center"><?=$row->title?></td>
          <td align="center"><?=$row->skin?></td>
          <td align="center"><? if($row->usetype == "Y") echo "사용함"; else echo "사용안함"; ?></td>
          <td align="center"><?=$bbstype?></td>
          <td align="center"><?=$level_info[$row->lpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->rpermi]['name']?></td>
          <td align="center"><?=$level_info[$row->wpermi]['name']?></td>
          <td align="center">
            <a onClick="document.location='bbs_list.php?code=<?=$row->code?>'" class="AW-btn-s">게시물관리</a>
            <a onClick="document.location='bbs_pro_input.php?mode=update&code=<?=$row->code?>'" class="AW-btn-s modify">수정</a>
            <a onClick="deleteBbs('<?=$row->code?>');" class="AW-btn-s del">삭제</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	     <?
	     		$no--;
	        $rows--;
	      }

	    	if($total <= 0){
	    		echo "<tr><td height=30 colspan=10 align=center>등록된 게시판이 없습니다.</td></tr>";
	    		echo "<tr><td colspan='20' class='t_line'></td></tr>";
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
          <td width="33%"></td>
          <td width="33%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
          <td width="33%"></td>
        </tr>
      </table>



<? include "../footer.php"; ?>