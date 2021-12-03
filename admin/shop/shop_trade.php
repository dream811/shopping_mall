<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delTradecom(idx){
   if(confirm('해당거래처를 삭제하시겠습니까?')){
      document.location = "shop_save.php?mode=shop_trade&sub_mode=delete&idx=" + idx;
   }
}
//-->
</script>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">거래처관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">거래처를 추가/수정/삭제 합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="8%">구분</th>
          <th>거래처명</th>
          <th width="15%">담당자</th>
          <th width="15%">휴대폰</th>
          <th width="15%">전화번호</th>
          <th width="15%">팩스</th>
          <th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      function custom_type($type){
			   if($type == "BUY") return "매입처";
			   else if($type == "SAL") return "매출처";
			   else if($type == "DEL") return "배송업체";
			   else if($type == "OTH") return "기타";
				}
	
	      $sql = "select idx from wiz_tradecom order by com_type asc, idx desc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);
	
	      $rows = 12;
	      $lists = 5;
	    	$page_count = ceil($total/$rows);
	    	if(!isset($page) ||$page < 1 || $page > $page_count) $page = 1;
	    	$start = ($page-1)*$rows;
	    	$no = $total-$start;
	    	
	      $sql = "select * from wiz_tradecom order by com_type asc, idx desc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	    	
	      while(($row = mysqli_fetch_array($result)) && $rows){
	      ?>
        <tr align="center" class="t_tr"> 
          <td height="30" align="center"><?=custom_type($row['com_type'])?></td>
          <td><?=$row['com_name']?></td>
          <td><?=$row['charge_name']?></td>
          <td><?=$row['charge_hand']?></td>
          <td><?=$row['charge_tel']?></td>
          <td><?=$row['com_fax']?></td>
          <td>
          	<a onclick="document.location='shop_trade_input.php?sub_mode=update&idx=<?=$row['idx']?>';" class="AW-btn-s modify">수정</a>
            <a onclick="delTradecom('<?=$row['idx']?>');" class="AW-btn-s del">삭제</a>
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
      		<td width="33%"><a onClick="document.location='shop_trade_input.php?sub_mode=insert';" class="AW-btn">거래처 추가</a></td>
      		<td width="33%"> <? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>

<? include "../footer.php"; ?>