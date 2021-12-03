<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
$type = "popup";
if ($mode == "") $mode = "insert";
if ($mode == "update") {
  $sql = "select * from wiz_content where idx='$idx'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $popup_info = mysqli_fetch_object($result);
  if (!get_magic_quotes_gpc()) $popup_info->content = stripslashes($popup_info->content);
}
?>

<script language="JavaScript">
  <!--
  function inputCheck(frm) {

    if (frm.title.value == "") {
      alert("제목을 입력하세요");
      frm.title.focus();
      return false;
    }
    if (content.outputBodyHTML() == "") {
      alert("팝업내용을 입력하세요");
      return false;
    }

  }
  //
  -->
</script>

<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">팝업관리</td>
    <td width="2"></td>
    <td valign="bottom" class="tit_alt">팝업 등록,수정합니다.</td>
  </tr>
</table>
<br />
<form name="frm" action="popup_save.php" method="post" onSubmit="return inputCheck(this)" enctype="multipart/form-data">
  <input type="hidden" name="tmp">
  <input type="hidden" name="type" value="<?= $type ?>">
  <input type="hidden" name="mode" value="<?= $mode ?>">
  <input type="hidden" name="idx" value="<?= $idx ?>">

  <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">

    <tr>
      <td class="t_name">제목</td>
      <td class="t_value" colspan="3">
        <input type="text" name="title" value="<?= $popup_info->title ?>" size="80" class="input">
      </td>
    </tr>
    <tr>
      <td class="t_name">게시기간</td>
      <td class="t_value" colspan="3">
        <?
        $sdate_list = explode("-", $popup_info->sdate);
        $edate_list = explode("-", $popup_info->edate);

        if (empty($sdate_list[0])) $sdate_year = date('Y');
        else $sdate_year = $sdate_list[0];

        if (empty($sdate_list[1])) $sdate_month = date('m');
        else $sdate_month = $sdate_list[1];

        if (empty($sdate_list[2])) $sdate_day = date('d');
        else $sdate_day = $sdate_list[2];

        if (empty($edate_list[0])) $edate_year = date('Y') + 1;
        else $edate_year = $edate_list[0];

        if (empty($edate_list[1])) $edate_month = date('m');
        else $edate_month = $edate_list[1];

        if (empty($edate_list[2])) $edate_day = date('d');
        else $edate_day = $edate_list[2];

        if ($popup_info->posi_x == "") $posi_x = "100";
        else $posi_x = $popup_info->posi_x;
        if ($popup_info->posi_y == "") $posi_y = "100";
        else $posi_y = $popup_info->posi_y;

        if ($popup_info->size_x == "") $size_x = "340";
        else $size_x = $popup_info->size_x;
        if ($popup_info->size_y == "") $size_y = "400";
        else $size_y = $popup_info->size_y;

        ?>
        <select name="sdate_year" class="select2">
          <?
          for ($ii = 2014; $ii <= 2030; $ii++) {
            if ($ii == $sdate_year) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        년
        <select name="sdate_month" class="select2">
          <?
          for ($ii = 1; $ii <= 12; $ii++) {
            if ($ii < 10) $ii = "0" . $ii;
            if ($ii == $sdate_month) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        월
        <select name="sdate_day" class="select2">
          <?
          for ($ii = 1; $ii <= 31; $ii++) {
            if ($ii < 10) $ii = "0" . $ii;
            if ($ii == $sdate_day) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        일 ~
        <select name="edate_year" class="select2">
          <?
          for ($ii = 2014; $ii <= 2030; $ii++) {
            if ($ii == $edate_year) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        년
        <select name="edate_month" class="select2">
          <?
          for ($ii = 1; $ii <= 12; $ii++) {
            if ($ii < 10) $ii = "0" . $ii;
            if ($ii == $edate_month) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        월
        <select name="edate_day" class="select2">
          <?
          for ($ii = 1; $ii <= 31; $ii++) {
            if ($ii < 10) $ii = "0" . $ii;
            if ($ii == $edate_day) echo "<option value=$ii selected>$ii";
            else echo "<option value=$ii>$ii";
          }
          ?>
        </select>
        일&nbsp;
      </td>
    </tr>
    <tr>
      <td width="15%" class="t_name">팝업형태 <font color="red">*</font>
      </td>
      <td width="35%" class="t_value">
        <input name="popup_type" type="radio" value="W" size="6" class="input" <? if ($popup_info->popup_type == "W" || $popup_info->popup_type == "") echo "checked"; ?>> 일반팝업 &nbsp;
        <input name="popup_type" type="radio" value="L" size="6" class="input" <? if ($popup_info->popup_type == "L") echo "checked"; ?>> 레이어팝업
      </td>
      <td width="15%" class="t_name"></td>
      <td width="35%" class="t_value"></td>
    </tr>
    <tr>
      <td class="t_name">사용여부 <font color="red">*</font>
      </td>
      <td class="t_value">
        <input name="isuse" type="radio" value="Y" size="6" class="input" <? if ($popup_info->isuse == "Y" || $popup_info->isuse == "") echo "checked"; ?>> 사용함 &nbsp;
        <input name="isuse" type="radio" value="N" size="6" class="input" <? if ($popup_info->isuse == "N") echo "checked"; ?>> 사용안함
      </td>
      <td class="t_name">스크롤여부</td>
      <td class="t_value">&nbsp;
        <input name="scroll" type="radio" value="Y" size="6" class="input" <? if ($popup_info->scroll == "Y") echo "checked"; ?>> 허용함&nbsp;
        <input name="scroll" type="radio" value="N" size="6" class="input" <? if ($popup_info->scroll == "N" || $popup_info->scroll == "") echo "checked"; ?>> 허용안함
      </td>
    </tr>
    <tr>
      <td class="t_name">위치 <font color="red">*</font>
      </td>
      <td class="t_value">&nbsp;
        X : <input name="posi_x" type="text" value="<?= $posi_x ?>" size="6" class="input"> &nbsp;
        Y : <input name="posi_y" type="text" value="<?= $posi_y ?>" size="6" class="input">
      </td>
      <td class="t_name">크기</td>
      <td class="t_value">&nbsp;
        가로 : <input name="size_x" type="text" value="<?= $size_x ?>" size="6" class="input"> &nbsp;
        세로 : <input name="size_y" type="text" value="<?= $size_y ?>" size="6" class="input">
      </td>
    </tr>
    <tr>
      <td class="t_name">링크주소</td>
      <td class="t_value" colspan="3"><input type="text" name="linkurl" value="<?= $popup_info->linkurl ?>" size="60" class="input"></td>
    </tr>
    <tr>
      <td class="t_name">팝업내용</td>
      <td class="t_value" colspan="3">
        <?
        $edit_content = $popup_info->content;
        include "../webedit/WIZEditor.html";
        ?>
      </td>
    </tr>
  </table>

  <div class="AW-manage-checkinfo">
    <div class="tit">체크사항</div>
    <div class="cont">
      - 팝업형태에서 레이어팝업을 이용하여 창이아닌 레이어로 공지할 수 있습니다.<br />
      - 팝업을 생성하였으나 뜨지않을 경우 세가지를 체크해보세요. 게시기간, 사용여부, 브라우저>도구>인터넷옵션>쿠키삭제
    </div><!-- .cont -->
  </div><!-- .AW-manage-checkinfo -->

  <div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="document.location='popup_list.php';">목록</a>
  </div><!-- .AW-btn-wrap -->


</form>

<? include "../footer.php"; ?>