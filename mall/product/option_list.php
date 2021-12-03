<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<html>
<head>
<title>:: 옵션항목 관리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function chgOption(frm){
	var idx = frm.opttitle.value;
	document.location = "option_list.php?idx=" + idx + "&optno=<?=$optno?>";
}

function selOption(frm){

	var opttext = "";
	var optvalue = "";
	var optlist = "";
	var optlist_2 = "";
	var opttitle = frm.opttitle.options[frm.opttitle.selectedIndex].text;
	var optcode = frm.optcode.value;
	var tmp_optcode = optcode.split("\n");
	var objReg = /[^A-Za-z0-9_ㄱ-ㅎ가-힝]/g;

<?
	if($optno == "opt1"){
?>
	for(ii=0; ii < tmp_optcode.length; ii++){

		tmp_optcode[ii] = tmp_optcode[ii].replace(objReg,'');
		optvalue = "" + tmp_optcode[ii] + "^0^0^^";
		opttext = "" + tmp_optcode[ii] + " - 0원 : 0개^^";

		optlist = optlist + optvalue;
		optlist_2 = optlist_2 + opttext;
	}

	opener.document.frm.opttitle.value = opttitle;

	opener.document.frm.optlist.value = optlist;
	opener.document.frm.opttext.value = optlist_2;
	opener.document.frm.opttitle.focus();

<?
	}else if($optno == "opt3"){
?>

	opener.document.frm.opttitle3.value = opttitle;
	var tbl = opener.document.getElementById('opt3');

	for(ii=0; ii < tmp_optcode.length; ii++){

		var row = tbl.insertRow();

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell();
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode3_opt[]\" value=\"" + tmp_optcode[ii] +"\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode3_res[]\">";
		}
	}

<?
	}else if($optno == "opt4"){
?>

	opener.document.frm.opttitle4.value = opttitle;
	var tbl = opener.document.getElementById('opt4');

	for(ii=0; ii < tmp_optcode.length; ii++){

		var row = tbl.insertRow();

		for (i=0;i<tbl.rows[0].cells.length;i++){
			cell = row.insertCell();
			cell.innerHTML = "&nbsp; &nbsp;항목 : <input type=\"text\" class=\"input\" name=\"optcode4_opt[]\" value=\"" + tmp_optcode[ii] +"\">";
			cell.innerHTML += " 추가가격 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_pri[]\">";
			cell.innerHTML += " 추가적립금 : <input type=\"text\" size=\"10\" class=\"input\" name=\"optcode4_res[]\">";
		}
	}

<?
	}else if($optno == "opt5"){
?>

	for(ii=0; ii < tmp_optcode.length; ii++){

		tmp_optcode[ii] = tmp_optcode[ii].replace(objReg,'');
		optvalue = ""+tmp_optcode[ii]+",";

		optlist = optlist + optvalue;
	}
	optlist = optlist.substring(0,optlist.length-1);
	opener.document.frm.opttitle5.value = opttitle;
	opener.document.frm.optcode5.value = optlist;

<?
	}else if($optno == "opt6"){
?>

	for(ii=0; ii < tmp_optcode.length; ii++){

		tmp_optcode[ii] = tmp_optcode[ii].replace(objReg,'');
		optvalue = ""+tmp_optcode[ii]+",";

		optlist = optlist + optvalue;
	}
	optlist = optlist.substring(0,optlist.length-1);
	opener.document.frm.opttitle6.value = opttitle;
	opener.document.frm.optcode6.value = optlist;
<?
	}else if($optno == "opt7"){
?>

	for(ii=0; ii < tmp_optcode.length; ii++){

		tmp_optcode[ii] = tmp_optcode[ii].replace(objReg,'');
		optvalue = ""+tmp_optcode[ii]+",";

		optlist = optlist + optvalue;
	}
	optlist = optlist.substring(0,optlist.length-1);
	opener.document.frm.opttitle7.value = opttitle;
	opener.document.frm.optcode7.value = optlist;
<?php
}
?>
	self.close();
}
//-->
</script>
</head>
<body>
<table><tr><td height="4"></td></table>

<table width="98%" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 옵션목록</td>
  </tr>
</table>
<table width="98%" align="center" cellspacing="1" cellpadding="3" align="center" class="t_style">
<form name="frm">
  <tr>
    <td width="80" class="t_name">&nbsp; 옵션명</td>
    <td class="t_value">
      <select name="opttitle" onChange="chgOption(this.form);">
      <?
      $sql = "select idx, opttitle, optcode from wiz_option order by idx desc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $no = 0;
	   while($row = mysqli_fetch_object($result)){
	   	if($idx == "" && $no == 0) $optcode = $row->optcode;
	   	if($idx == $row->idx) $selected = "selected";
	   	else $selected = "";
	   	echo "<option value='$row->idx' $selected>$row->opttitle\n";

	   	$no++;
	   }
      ?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="t_name">&nbsp; 옵션항목</td>
    <td class="t_value">
    <?
    if($idx != ""){
	    $sql = "select * from wiz_option where idx='$idx'";
	    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	    $row = mysqli_fetch_object($result);
	    $optcode = $row->optcode;
    }
    ?>
    <textarea name="optcode" rows="8" cols="30"  class="textarea"><?=$optcode?></textarea><br>
    * 한줄에 하나의 옵션을 입력하세요
    </td>
  </tr>
</table>

<br>
<table width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="2" align="center">
      <img src="../image/btn_confirm_l.gif" style="cursor:hand" onClick="selOption(document.frm);"> &nbsp;
      <img src="../image/btn_close_l.gif" style="cursor:hand" onClick="self.close();">
    </td>
  </tr>
</form>
</table>

</body>
</html>