<html>
<head>
<title>:: 상품 아이콘 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="javascript">
<!--
function iconDel(){
	var selicon = "";
	var prdicon = document.frm.prdicon;
	for(ii=0;ii < prdicon.length;ii++){
		if(prdicon[ii].checked == true){
			selicon = prdicon[ii].value;
		}
	}
	document.location = "prd_save.php?mode=icondel&prdicon=" + selicon;
}
function inputCheck(frm){
	if(frm.upfile.value == ""){
		alert("등록할 아이콘이 없습니다.");
		return false;
	}
}
-->
</script>
</head>
<body style="padding:10px;">
	
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$product?>상품 아이콘</td>
  </tr>
</table>
<form name="frm" action="prd_save.php" method="post" onSubmit="return inputCheck(this);" enctype="multipart/form-data">
<input type="hidden" name="mode" value="prdicon">
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="2" class="t_style">
  <tr>
  	<td align="center" class="t_value">
  	<table>
		<?
		$no = 0;
		if($handle = opendir('../../data/prdicon')){ 
			while(false !== ($file_name = readdir($handle))){ 
				if($file_name != "." && $file_name != ".."){ 
					if($no%5 == 0) echo "<tr>";
		?>
		<td><input type="radio" name="prdicon" value="<?=$file_name?>"><img src="/data/prdicon/<?=$file_name?>" border="0"></td>
		<?
					$no++;
				} 
			} 
			closedir($handle); 
		} 
		?>
	  </table>
	  </td>
  </tr>
  <tr>
  	<td align="center" class="t_value">
  		선택아이콘 <a onClick="iconDel();" class="AW-btn-s del">삭제</a>
  	</td>
  </tr>
</table><br>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="2" class="t_style">
  <tr>
  	<td class="t_name">아이콘 등록</td>
  	<td class="t_value"><input type="file" name="upfile" class="input"> <input type="submit" class="AW-btn-s-black" value="등록하기" /></td>
  </tr>
</table>
</form>


</body>
</html>