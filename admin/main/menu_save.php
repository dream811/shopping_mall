<?
include_once "$_SERVER[DOCUMENT_ROOT]/inc/common.inc";
include_once "$_SERVER[DOCUMENT_ROOT]/inc/util.inc";
include_once "$_SERVER[DOCUMENT_ROOT]/admin/inc/admin_check.inc";
for($ii = 0; $ii < count($url); $ii++) {
	if(strcmp($url[$ii]."^".$urlname[$ii]."^".$used[$ii]."^^", "^^^^")) {

		if(empty($urlname[$ii])) $urlname[$ii] = 0;
		if(empty($used[$ii])) $used[$ii] = "N";

		$urlinfo .= $url[$ii]."^".$urlname[$ii]."^".$used[$ii]."^^";
	}
}


$sql = "update wiz_quicklink set info = '$urlinfo'";
mysqli_query($connect, $sql);
alert("저장되었습니다.","/admin");
?>