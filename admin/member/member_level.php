<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function deleteLevel(idx,level){
	if(confirm('회원등급을 삭제하시겠습니까?\n\n삭제할 등급에 속한 회원은 아래 등급으로 수정됩니다.')){
		document.location="level_save.php?mode=delete&idx=" + idx + "&level=" + level;
	}
}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">등급관리</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">회원등급을 설정합니다.</td>
	    </tr>
	  </table>
	  		
	  <br>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 등급목록</td></tr></table></td>
        <td align="right">
        	<a onClick="document.location='level_input.php?mode=insert';" class="AW-btn">등급추가</a>
        </td>
      </tr>
      <tr><td colspan="2" height="5"></td></tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="5%">번호</th>
        <th align="center">등급명</th>
        <th width="20%">등급레벨</th>
        <th width="20%">등급할인액(%)</th>
        <th width="10%">기능</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
			<?
		 	$sql = "select * from wiz_level order by level asc, idx asc";
		 	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		 	$total = mysqli_num_rows($result);
		 	$no = $total;
			while($row = mysqli_fetch_object($result)){
		
				if($row->distype == "W") $row->distype = "원";
				else  $row->distype = "% ";
			?>
      <tr class="t_tr"> 
        <td height="30" align="center"><?=$no?></td>
        <td align="center"><?=$row->name?></td>
        <td align="center"><?=$row->level?></td>
        <td align="center"><?=$row->discount?><?=$row->distype?></td>
        <td align="center">
        <?
        if($row->level != 0){
        ?>
        	<a onclick="document.location='level_input.php?mode=update&idx=<?=$row->idx?>';" class="AW-btn-s modify">수정</a>
            <a onclick="deleteLevel('<?=$row->idx?>','<?=$row->level?>');" class="AW-btn-s del">삭제</a>
        <?
        }
        ?>
        </td>
      </tr>
      <tr><td colspan="20" class="t_line"></td></tr>
   	<? 
   		$no--;
    }

  	if($total <= 0){
  	?>
  		<tr><td height=30 colspan=10 align=center>회원등급이 없습니다.</td></tr>
  		<tr><td colspan='20' class='t_line'></td></tr>
  	<?
  	}
    ?>
    </table>

<? include "../footer.php"; ?>