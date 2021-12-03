<html>
<head>
<title>:: 위즈샵 설치 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="./style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function inputCheck(frm){

   if(frm.db_host.value == ""){
      alert("Mysql host 를 입력하세요");
      frm.db_host.focus();
      return false;
   }
   if(frm.db_name.value == ""){
      alert("Mysql name 를 입력하세요");
      frm.db_name.focus();
      return false;
   }
   if(frm.db_user.value == ""){
      alert("Mysql id 를 입력하세요");
      frm.db_user.focus();
      return false;
   }
   if(frm.db_pass.value == ""){
      alert("Mysql passwd 를 입력하세요");
      frm.admin_id.focus();
      return false;
   }

   if(frm.admin_id.value == ""){
      alert("관리자 아이디를 입력하세요");
      frm.admin_id.focus();
      return false;
   }

   if(frm.admin_pw.value == ""){
      alert("관리자 비밀번호를 입력하세요");
      frm.admin_pw.focus();
      return false;
   }

   if(frm.designer_id.value == ""){
      alert("디자이너 아이디를 입력하세요");
      frm.designer_id.focus();
      return false;
   }

   if(frm.designer_pw.value == ""){
      alert("디자이너 비밀번호를 입력하세요");
      frm.designer_pw.focus();
      return false;
   }

   if(frm.admin_id.value == frm.designer_id.value){

   	  alert("디자이너ID 와 관리자ID 를 동일하게 사용할 수 없습니다.");
      frm.admin_id.focus();
      return false;

   }

}
-->
</script>
</head>
<body>
<table width="100%"  height="11" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  bgcolor="#3bc1c1">&nbsp;</td>
    <td width="222" bgcolor="#1c86cc"></td>
    <td width="2" bgcolor="#ffffff"></td>
    <td width="75" bgcolor="#AEAEAE"></td>
  </tr>
</table>

<table><tr><td height="100"></td></tr></table>
<table width="510" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit"><img src="./image/ic_tit.gif" align="absmiddle">위즈샵설치</td>
  </tr>
</table>
<table width="510" align="center" border="0" cellspacing="1" cellpadding="0" class="t_style">
<form name="frm" action="install_ok.php" method="post" onSubmit="return inputCheck(this);">
	<tr>
	  <td width="25%" class="t_name">Mysql host</td>
	  <td width="75%" class="t_value"><input type="text" name="db_host" value="localhost" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">Mysql name</td>
	  <td class="t_value"><input type="text" name="db_name" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">Mysql id</td>
	  <td class="t_value"><input type="text" name="db_user" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">Mysql passwd</td>
	  <td class="t_value"><input type="text" name="db_pass" class="input"></td>
	</tr>
</table>

<table><tr><td height="5"></td></tr></table>
<table width="510" align="center" border="0" cellspacing="1" cellpadding="0" class="t_style">
	<tr>
	  <td width="25%" class="t_name">관리자 id</td>
	  <td width="75%" class="t_value"><input type="text" name="admin_id" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">관리자 passwd</td>
	  <td class="t_value"><input type="text" name="admin_pw" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">디자이너 id</td>
	  <td class="t_value"><input type="text" name="designer_id" class="input"></td>
	</tr>
	<tr>
	  <td class="t_name">디자이너 passwd</td>
	  <td class="t_value"><input type="text" name="designer_pw" class="input"></td>
	</tr>
	<tr>
		<td class="t_name"></td>
	  <td class="t_value" height="45">
	  	<font color="red">디자이너 [아이디/비번] 은 추후 관리를 위한 접속정보이며<br>디자이너로 로그인 시에만 <b>환경설정</b> 메뉴가 나타납니다.</font>
	  </td>
	</tr>
</table>

<table><tr><td height="5"></td></tr></table>
<table width="510" align="center" border="0" cellspacing="1" cellpadding="0" class="t_style">
	<tr>
	  <td width="25%" class="t_name">라이센스키</td>
	  <td width="75%" class="t_value" height="45">
	  	<input type="text" name="site_key" size="40" class="input"><br>
	  	설치후 입력가능 (설치후 2주간은 라이센스를 체크하지 않습니다.)
	  </td>
	</tr>
</table>
<br>
<table align="center">
	<tr>
		<td class="t_value" colspan="2" align="center">
		<?
		if((fileperms(".")==16839||fileperms(".")==16895) && (fileperms("../data")==16839||fileperms("../data")==16895)){
		?>
		<input type="image" src="./image/btn_confirm_l.gif"> &nbsp;
		<img src="./image/btn_cancel_l.gif" style="cursor:hand" onClick="document.frm.reset();">
		<?
		}else{
		?>
		<font color="red">
			현재 admin 폴더와 data 폴더가 707로 퍼미션이 되어있지 않습니다.<br>
			data 폴더의 경우 <u><strong>하위 폴더까지</strong></u> 권한을 변경해주시기 바랍니다. 예) #chmod 707 -R data<br>
			텔넷이나 FTP에서 퍼미션을 조정하세요.<br>
		<font>
		<input type="button" value=" 새로고침 " onClick="document.location='<?=$_SERVER['PHP_SELF']?>'">
		<?
		}
		?>
		<td>
</tr>
</form>
</table>

</body>
</html>
