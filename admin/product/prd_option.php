<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="javascript">
<!--
function openOption(mode,idx){
	var url = "option_input.php?mode=" + mode + "&idx=" + idx;
  window.open(url,"","height=430, width=450, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");
}
function delOption(idx){
	if(confirm('옵션을 삭제하시겠습니까?')){
		document.location="option_save.php?mode=delete&idx=" + idx;
	}
}
-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">옵션항목관리</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">옵션항목을 설정합니다.</td>
			  </tr>
			</table>
			
			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 옵션목록</td></tr></table></td>
			    <td align="right"><a onClick="openOption('insert','');" class="AW-btn">옵션등록</a></td>
			  </tr>
              <tr><td colspan="2" height="5"></td></tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td class="t_rd" colspan="20"></td></tr>
        <tr class="t_th">
          <th width="8%">No</th>
          <th width="20%">옵션명</th>
          <th>옵션항목</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan="20"></td></tr>
        <?
        $sql = "select idx from wiz_option order by idx desc";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        
        $total = mysqli_num_rows($result);

        $rows = 20;
        $lists = 5;

				$page_count = ceil($total/$rows);
				if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				
        $sql = "select * from wiz_option order by idx desc limit $start, $rows";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        
        while(($row = mysqli_fetch_object($result)) && $rows){
        ?>
        <tr class="t_tr"> 
          <td  align="center"><?=$no?></td>
          <td height="30" align="center"><?=$row->opttitle?></td>
          <td height="30" align="center"><?=$row->optcode?></td>
          <td>
          		<a onClick="openOption('update','<?=$row->idx?>')" class="AW-btn-s modify">수정</a>
          		<a onClick="delOption('<?=$row->idx?>')" class="AW-btn-s del">삭제</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
        <?
     		$no--;
         $rows--;
         }
                              
       	if($total <= 0){
       	?>
       		<tr><td height='30' colspan=7 align=center>등록된 옵션항목이 없습니다.</td></tr>
       		<tr><td colspan="20" class="t_line"></td></tr>
       	<?
       	}
        ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
      		<td width="33%"></td>
      	</tr>
     	</table>

      
<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
    	- 상품등록시 옵션목록 가져오기를 통하여 쉽게 옵션을 등록할 수 있습니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->

<? include "../footer.php"; ?>