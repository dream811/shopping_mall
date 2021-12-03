<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$type = "center";
$sql = "select * from wiz_page where type='$type'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$page_info = mysqli_fetch_object($result);
if(!get_magic_quotes_gpc()) $page_info->content = stripslashes($page_info->content);
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
			    <td valign="bottom" class="tit">고객센터</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">고객센터 페이지를 설정합니다.</td>
			  </tr>
			</table>
			
      <br>
      <form name="frm" action="page_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="update">
      <input type="hidden" name="type" value="<?=$type?>">
      <input type="hidden" name="page" value="page_center.php">
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
		<!--
        <tr> 
         <td width="15%" class="t_name">상단이미지</td>
         <td width="85%" class="t_value">
          <?
          if($page_info->subimg != "") echo "<img src='/data/subimg/$page_info->subimg' width='500'> <a href='page_save.php?mode=delimg&type=$type&subimg=$page_info->subimg'><font color='red'>[삭제]</font></a><br>";
          ?>
         <input type="file" name="subimg" class="input"> &nbsp; 
         </td>
        </tr>
		-->
        <tr>
          <td width="15%" class="t_name">안내메세지</td>
          <td class="t_value">
          <?
          $edit_content = $page_info->content;
          include "../webedit/WIZEditor.html";
          ?>
          </td>
        </tr>
      </table>
      
    <div class="AW-btn-wrap">
        <button type="submit" class="on">확인</button>
        <a onClick="history.go(-1);">취소</a>
    </div><!-- .AW-btn-wrap -->

      </form>

<? include "../footer.php"; ?>                