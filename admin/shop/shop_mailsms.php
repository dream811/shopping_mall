<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" src="/js/util_lib.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function delConfirm(code){
	
	if(confirm("정말 삭제하시겠습니까?")){
		document.location = "shop_save.php?mode=mailsms&sub_mode=delete&code=" + code;
	}
	
}
-->
</script>


			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메일<? if(!strcmp($shop_info->sms_use, "Y")) { ?>/SMS<? } ?>설정</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">각 상황별 메일내용을 설정합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td colspan=20 class="t_rd"></td></tr>
        <tr class="t_th">
          <th width="8%">번호</th>
          <th width="30%">분류명</th>
          <th width="12%">메일고객</th>
          <th width="12%">메일관리자</th>
          <? if(!strcmp($shop_info->sms_use, "Y")) { ?>
          <th width="12%">SMS고객</th>
          <th width="12%">SMS관리자</th>
        <? } ?>
          <th width="10%">기능</th>
        </tr>
        <tr><td colspan=20 class="t_rd"></td></tr>
      <?
      function getYesNo($yn){
      	if($yn == "N") return "받지않음";
      	else if($yn == "Y") return "받음";
      }
      
      $no = 1;
      $sql = "select * from wiz_mailsms order by subject asc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $no = mysqli_num_rows($result);
      while($row = mysqli_fetch_object($result)){
      ?>
        <tr class="t_tr">
          <td align="center"><?=$no?></td>
          <td>&nbsp; <?=$row->subject?></td>
          <td align="center"><?=getYesNo($row->email_cust)?></td>
          <td align="center"><?=getYesNo($row->email_oper)?></td>
          <? if(!strcmp($shop_info->sms_use, "Y")) { ?>
          <td align="center"><?=getYesNo($row->sms_cust)?></td>
          <td align="center"><?=getYesNo($row->sms_oper)?></td>
        <? } ?>
          <td align="center">
          	<a onclick="document.location='shop_mailsms_input.php?code=<?=$row->code?>&sub_mode=update';" class="AW-btn-s modify">수정</a>
            <a onclick="delConfirm('<?=$row->code?>');" class="AW-btn-s del">삭제</a>
          </td>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      	$no--;
   	}
   	?>
   	</table>

<? include "../footer.php"; ?>