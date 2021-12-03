<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select guide from wiz_mall where id='$wiz_mall['id']'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$mall_info = mysqli_fetch_object($result);
?>
			<script language="JavaScript">
			<!--
			function inputCheck(frm){
				content.outputBodyHTML();
			}
			-->
			</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">구매가이드</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">상품상세 구매가이드를 설정합니다.</td>
			  </tr>
			</table>


<form name="frm" action="shop_save.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="guide">

      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
      

        <tr>
          <td width="15%" class="t_name">구매가이드</td>
          <td width="85%" class="t_value">
	          <?
	          $edit_height = "500";
	          $edit_content = $mall_info->guide;
	          include "../../admin/webedit/WIZEditor.html";
	          ?>
          </td>
        </tr>
      </table>

	<div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on">
        <a href="javascript:history.go(-1);">취소</a>
    </div>

</form>
<? include "../footer.php"; ?>