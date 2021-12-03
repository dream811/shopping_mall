<?
include_once $_SERVER['DOCUMENT_ROOT']."/inc/common.inc";

$sql = "select title,linkurl,content from wiz_content where idx = '$idx'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_object($result);
?>
<script language="javascript">
<!--
  function popupClose<?=$idx?>(){
    setCookie("popupDayClose<?=$idx?>", "true", 1);
    popup<?=$idx?>.style.display = 'none';
    document.getElementById("popContent<?=$idx?>").innerHTML="";
  }
//-->
</script>
<div id="popup<?=$popup_info->idx?>" style='z-index:999; position:absolute; left: <?=$popup_info->posi_x?>px; top: <?=$popup_info->posi_y?>px; width: <?=$popup_info->size_x?>px; height: <?=$popup_info->size_y?>px'>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    
      <table border="0" cellpadding="0" cellspacing="0" onclick="javascript:document.location = '<?=$row->linkurl?>';" style="cursor:pointer">
      <tr><td id="popContent<?=$idx?>"><?=$row->content?></td></tr>
      </table>

      <table width="100%" height="25" bgcolor="#909090" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="right"><font color=#ffffff>오늘하루 열지않음</font> <input type="checkbox" onClick="popupClose<?=$idx?>();">&nbsp; </td>
        </tr>
      </table>

      </td>
  </tr>
</table>
</div>
<script language="javascript">
<!--
if(readCookie('popupDayClose<?=$idx?>')){
  popup<?=$popup_info->idx?>.style.display = 'none';
}
-->
</script>