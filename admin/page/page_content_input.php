<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
$type = "new";
if($mode == "") $mode = "page_insert";
if($mode == "page_update"){
	$sql = "select * from wiz_content where idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$page_info = mysqli_fetch_object($result);
	if(!get_magic_quotes_gpc()) $page_info->content = stripslashes($page_info->content);
}
?>

<script language="JavaScript" src="../webedit/webedit.js"></script>
<script language="JavaScript" src="/js/valueCheck.js"></script>
<script language="JavaScript">
<!--
function inputCheck(frm){
   
   if(frm.title.value == ""){
      alert("설명을 입력하세요");
      frm.title.focus();
      return false;
   }
   if(content.outputBodyHTML() == ""){
      alert("내용을 입력하세요");
      return false;
   }
   
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">페이지추가</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">새로운 페이지를 생성,수정,삭제합니다.</td>
			  </tr>
			</table>
			
      <br>
      <form name="frm" action="page_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="type" value="<?=$type?>">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
        <tr> 
          <td width="15%" class="t_name">설명</td>
          <td width="85%" class="t_value">
            <input type="text" name="title" value="<?=$page_info->title?>" size="80" class="input">
          </td>
        </tr>
        <?
        if($idx != ""){
        ?>
        <tr> 
          <td class="t_name">링크주소</td>
          <td class="t_value">
            <a href="http://<?=$HTTP_HOST?>/content.php?con_idx=<?=$idx?>" target="_blank">http://<?=$HTTP_HOST?>/content.php?con_idx=<?=$idx?></a>
          </td>
        </tr>
        <?
        }
        ?>
        <tr>
          <td class="t_name">내용</td>
          <td class="t_value" colspan="3">
          <?
          $edit_content = $page_info->content;
          include "../webedit/WIZEditor.html";
          ?>
          </td>
        </tr>
      </table>
      
        <div class="AW-btn-wrap">
            <button type="submit" class="on">확인</button>
            <a onClick="document.location='page_content.php';">취소</a>
        </div><!-- .AW-btn-wrap -->

      </form>

<? include "../footer.php"; ?>