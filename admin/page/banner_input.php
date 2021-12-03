<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if($mode == "") $mode = "insert";

$sql = "select title from wiz_bannerinfo where name='$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$banner_info = mysqli_fetch_object($result);
?>

<script language="JavaScript">
<!--
function inputCheck(frm){

	<? if($mode == "ban_group_insert" || $mode == "ban_group_update") { ?>
	if(frm.title.value == ""){
		alert("그룹이름을 입력하세요.");
		frm.title.focus();
		return false;
	}
	if(frm.name.value == ""){
		alert("코드를 입력하세요.");
		frm.name.focus();
		return false;
	}
	if(frm.padding.value != "" && !Check_Num(frm.padding.value)) {
		alert("배너간격은 숫자만 입력하세요.");
		frm.padding.focus();
		return false;
	}
	<? } else { ?>
	content.outputBodyHTML();
	<? } ?>
}
//-->
</script>

			<?
			if($mode == "ban_group_insert" || $mode == "ban_group_update") {
				if($mode == "ban_group_update"){
				  $sql = "select * from wiz_bannerinfo where idx='$idx'";
				  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				  $ban_info = mysqli_fetch_object($result);
				}
			?>
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">배너그룹관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">배너그룹 상세설정 합니다.</td>
        </tr>
      </table>

      <br>
<form name="frm" action="banner_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">

        <tr>
          <td width="15%" height="25" align="left" class="t_name">그룹이름 <font color="red">*</font></td>
          <td width="35%" class="t_value"><input type="text" name="title" value="<?=$ban_info->title?>" size="30" class="input" >
          </td>
          <td width="15%" align="left" class="t_name">코드(영문) <font color="red">*</font></td>
          <td width="35%" class="t_value"><input type="text" name="name" value="<?=$ban_info->name?>" size="30" class="input" <? if(!strcmp($mode, 'ban_group_update')) echo "readonly" ?>>
          </td>
        </tr>
        <tr>
          <td height="25" align="left" class="t_name">배너형태 <font color="red">*</font></td>
          <td class="t_value">
            <input type="radio" name="types" value="W" size="80" <? if($ban_info->types == "W") echo "checked"; ?>> 가로형 &nbsp;
            <input type="radio" name="types" value="H" size="80" <? if($ban_info->types == "H" || $ban_info->types == "") echo "checked"; ?>> 세로형
          </td>
          <td class="t_name">배너간격</td>
          <td class="t_value"><input type="text" name="padding" value="<?=$ban_info->padding?>" size="30" class="input">
          </td>
        </tr>
        <tr>
          <td height="25" align="left" class="t_name">사용여부</td>
          <td width="550" class="t_value">
            <input type="radio" name="isuse" value="Y" size="80" <? if($ban_info->isuse == "Y" || $ban_info->isuse == "") echo "checked"; ?>> 사용함 &nbsp;
            <input type="radio" name="isuse" value="N" size="80" <? if($ban_info->isuse == "N") echo "checked"; ?>> 사용안함
          </td>
          <td height="25" align="left" class="t_name">배너갯수</td>
          <td class="t_value">
          <select name="types_num">
          <option value="1" <? if($ban_info->types_num == "1") echo "selected"; ?>>1
          <option value="2" <? if($ban_info->types_num == "2") echo "selected"; ?>>2
          <option value="3" <? if($ban_info->types_num == "3") echo "selected"; ?>>3
          <option value="4" <? if($ban_info->types_num == "4") echo "selected"; ?>>4
          <option value="5" <? if($ban_info->types_num == "5") echo "selected"; ?>>5
          <option value="6" <? if($ban_info->types_num == "6") echo "selected"; ?>>6
          <option value="7" <? if($ban_info->types_num == "7") echo "selected"; ?>>7
          <option value="8" <? if($ban_info->types_num == "8") echo "selected"; ?>>8
          <option value="9" <? if($ban_info->types_num == "9") echo "selected"; ?>>9
          </select>
          </td>
        </tr>
      </table>

    <div class="AW-btn-wrap">
    	<button type="submit" class="on">확인</button>
        <a onClick="document.location='banner_list.php';">목록</a>
    </div><!-- .AW-btn-wrap -->



    <div class="AW-manage-checkinfo">
        <div class="tit">체크사항</div>
        <div class="cont">
            - 코드(영문)는 반드시 영문으로 작성하고 변경이 불가합니다.<br />
            - 배너간격은 배너와 배너 사이의 간격을 조절합니다.<br />
            - 배너형태가 가로형인 경우 배너개수만큼 가로로 배너가 나오고 줄바꿈이 됩니다.
        </div><!-- .cont -->
    </div><!-- .AW-manage-checkinfo -->

			<?
			  } else if($mode == "insert" || $mode == "update") {
			    if($mode == "update"){
				    $sql = "select * from wiz_banner where idx='$idx'";
				    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				    $ban_info = mysqli_fetch_object($result);
				    if(!get_magic_quotes_gpc()) $ban_info->de_html = stripslashes($ban_info->de_html);
			    }
			?>
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">배너관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">배너 상세설정 합니다.</td>
        </tr>
      </table>

			<br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$banner_info->title?></td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
      <form name="frm" action="banner_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
        <tr>
          <td width="17%" height="25" align="left" class="t_name">디자인방법</td>
          <td width="33%" class="t_value">
          <select name="de_type">
          <option value="IMG" <? if($ban_info->de_type == "IMG" || $ban_info->de_type == "") echo "selected"; ?>>이미지
          <option value="HTML" <? if($ban_info->de_type == "HTML") echo "selected"; ?>>HTML
          </select>
          </td>
          <td width="17%" height="25" align="left" class="t_name">사용여부</td>
          <td width="33%" class="t_value">
            <input type="radio" name="isuse" value="Y" size="80" <? if($ban_info->isuse == "Y" || $ban_info->align == "") echo "checked"; ?>> 사용함 &nbsp;
            <input type="radio" name="isuse" value="N" size="80" <? if($ban_info->isuse == "N") echo "checked"; ?>> 사용안함
          </td>
        </tr>
        <tr>
          <td width="17%" height="25" align="left" class="t_name">코드명 <font color="red">*</font></td>
          <td width="33%" class="t_value">
            <select name="name">
           <?
              $sql = "select * from wiz_bannerinfo";
	            $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	            while(($row = mysqli_fetch_object($result))){
	         ?>
	           <option value="<?=$row->name?>" <? if($row->name == $code) echo "selected"; ?>><?=$row->name?>
	         <?
	            }
	         ?>
            </select>
          </td>
          <td width="17%" height="30" align="left" class="t_name">우선순위</td>
          <td width="33%" class="t_value">
          <select name="prior">
          <option value="1" <? if($ban_info->prior == "1") echo "selected"; ?>>1
          <option value="2" <? if($ban_info->prior == "2") echo "selected"; ?>>2
          <option value="3" <? if($ban_info->prior == "3") echo "selected"; ?>>3
          <option value="4" <? if($ban_info->prior == "4") echo "selected"; ?>>4
          <option value="5" <? if($ban_info->prior == "5") echo "selected"; ?>>5
          <option value="6" <? if($ban_info->prior == "6") echo "selected"; ?>>6
          <option value="7" <? if($ban_info->prior == "7") echo "selected"; ?>>7
          <option value="8" <? if($ban_info->prior == "8") echo "selected"; ?>>8
          <option value="9" <? if($ban_info->prior == "9") echo "selected"; ?>>9
          <option value="10" <? if($ban_info->prior == "10") echo "selected"; ?>>10
          <option value="11" <? if($ban_info->prior == "11") echo "selected"; ?>>11
          <option value="12" <? if($ban_info->prior == "12") echo "selected"; ?>>12
          <option value="13" <? if($ban_info->prior == "13") echo "selected"; ?>>13
          <option value="14" <? if($ban_info->prior == "14") echo "selected"; ?>>14
          <option value="15" <? if($ban_info->prior == "15") echo "selected"; ?>>15
          <option value="16" <? if($ban_info->prior == "16") echo "selected"; ?>>16
          <option value="17" <? if($ban_info->prior == "17") echo "selected"; ?>>17
          <option value="18" <? if($ban_info->prior == "18") echo "selected"; ?>>18
          <option value="19" <? if($ban_info->prior == "19") echo "selected"; ?>>19
          <option value="20" <? if($ban_info->prior == "20") echo "selected"; ?>>20
          </select>
          </td>
        </tr>
        <tr>
          <td width="17%" height="25" align="left" class="t_name">링크주소</td>
          <td width="550" class="t_value" colspan="3">
          <input type="text" name="link_url" value="<?=$ban_info->link_url?>" size="60" class="input"> &nbsp;
          <input type="checkbox" name="link_target" value="_BLANK" <? if($ban_info->link_target == "_BLANK") echo "checked"; ?>> 새창으로
          </td>
        </tr>
        <tr>
          <td width="17%" height="25" align="left" class="t_name">배너이미지</td>
          <td width="550" class="t_value" colspan="3">
          <?
          if($ban_info->de_img != "") echo "<img src='/data/banner/$ban_info->de_img' style='max-width:800px;'><br>";
          ?>
          <input type="file" name="de_img" class="input">
          </td>
        </tr>
        <tr>
          <td align="left" class="t_name">배너내용</td>
          <td class="t_value" colspan="3">
          <?
          $edit_content = $ban_info->de_html;
          $edit_height = "300";
          include "../webedit/WIZEditor.html";
          ?>
          </td>
        </tr>
      </table>

    <div class="AW-btn-wrap">
    	<button type="submit" class="on">확인</button>
        <a onClick="document.location='banner.php?code=<?=$code?>';">목록</a>
    </div><!-- .AW-btn-wrap -->



			<?
			}
			?>
</form>
<? include "../footer.php"; ?>