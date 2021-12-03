<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc"; ?>
<? include_once "$_SERVER[DOCUMENT_ROOT]/inc/admin_check.inc"; ?>
<html>
<head>
<title>파일/디렉토리 구조도</title>
<meta http-equiv="Content-Type" content="text/html;" charset="euc-kr">
<style>
<!--
	td,li {font-size:13px;font-family:"굴림","돋움";color:#000000;}
	.input {
	font-size:9pt;
	font-family:"굴림","돋움";
	color:#545454;
	border-width:1pt;
	border-style:solid;
	background-color:#ffffff;
	border-color:#cccccc;
	height:18px;
	}
-->
</style>
</head>
<body onLoad="goInput()">
<table width=700>
	<tr><td height="20"></td></tr>
	<tr>
		<td><h1>파일/디렉토리 구조도</h1></td>
		<td align="right">
			<? if($mode != "print"){ ?><input type="button" value="전체출력" onClick="window.open('<?=$_SERVER['PHP_SELF']?>?mode=print','','');"><? } ?>
		</td>
	</tr>
</table>

<?
if($mode == "edit"){

	$sql = "select idx from wiz_filedesc where fdir='$fdir'";
	$result = mysqli_query($connect, $sql);
	$exist = mysqli_num_rows($result);
	if($exist <= 0){
		$sql = "insert into wiz_filedesc(idx,fdir,fdesc) values('','$fdir','$fdesc')";
	}else{
		$sql = "update wiz_filedesc set fdesc='$fdesc' where fdir='$fdir'";
	}
	mysqli_query($connect, $sql);

}

$max_idx = 0;

$sql = "select * from wiz_filedesc";
$result = mysqli_query($connect, $sql);
while($row = mysqli_fetch_array($result)){
	$fdir = substr($row[fdir],1);
	$fdesc_list[$fdir][value] = $row[fdesc];
	$fdesc_list[$fdir]['idx'] = $row['idx'];

	if($max_idx < $row['idx']) $max_idx = $row['idx'];
}

?>

<script Language="Javascript">
<!--

// 마지막 입력 항목으로
function goInput() {
<? if($mode != "print"){ ?>
/*
	document.location = "#input<?=$max_idx?>";

	var last = eval("file_<?=$max_idx?>");
	last.focus();
*/
<? } ?>
}

//-->
</script>

<?php

$path = $_SERVER[DOCUMENT_ROOT]."/admin";
echo "<table border=0><tr><td><b>/admin</b></td></tr></table>\n";
list_dir($path,0);



function list_dir($path,$tab)
{
		global $fdesc_list, $mode;

		exec("ls -X $path", $file_array, $return_val);
		$total = count($file_array);
		$no = 0;

		while($total){

			$file_name = str_replace($path."/","",$file_array[$no]);
			$dir = $path."/".$file_name;
			$fdir = substr($dir,1);

      if(!empty($fdesc_list[$fdir][value])) echo "<a name='input".$fdesc_list[$fdir]['idx']."'>";

			if(is_dir($dir)){

				if($mode == "print") echo "<table border=0><tr><td width=".($tab*60+50).">&nbsp;</td><td width=200><b>+".$file_name."</b></td><td>".$fdesc_list[$fdir][value]."</td></tr></table>\n";
				else echo "<table border=0><tr><td width=".($tab*60+50).">&nbsp;</td><td width=200><b>+".$file_name."</b></td><td><input id=\"file_".$fdesc_list[$fdir]['idx']."\" type=\"text\" class=\"input\" value=\"".$fdesc_list[$fdir][value]."\" onChange=\"document.location='".$_SERVER['PHP_SELF']."?mode=edit&fdesc='+this.value+'&fdir=".$dir."';\"></td></tr></table>\n";
				if($file_name != "skin" && $file_name != "data"){
					$tab++;
					list_dir($path."/".$file_name,$tab);
					$tab--;
				}

			}else{

				if(
           substr($file_name,-3) == "html" ||
           substr($file_name,-3) == "htm" ||
           substr($file_name,-3) == "php"
          ){
					if($mode == "print") echo "<table border=0><tr><td width=".($tab*60+50).">&nbsp;</td><td width=200>".$file_name."</td><td>".$fdesc_list[$fdir][value]."</td></tr></table>\n";
					else echo "<table border=0><tr><td width=".($tab*60+50).">&nbsp;</td><td width=200>".$file_name."</td><td><input id=\"file_".$fdesc_list[$fdir]['idx']."\" type=\"text\" class=\"input\" value=\"".$fdesc_list[$fdir][value]."\" onChange=\"document.location='".$_SERVER['PHP_SELF']."?mode=edit&fdesc='+this.value+'&fdir=".$dir."';\"></td></tr></table>\n";
				}

			}

			$no++;
			$total--;

		}


}
?>