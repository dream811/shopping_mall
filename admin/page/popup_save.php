<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";

if($mode == "insert"){

	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$sdate_day;
	if(!get_magic_quotes_gpc()) $content= addslashes($content);
	
	$sql = "insert into wiz_content(idx,type,isuse,scroll,posi_x,posi_y,size_x,size_y,sdate,edate,linkurl,popup_type,title,content,wdate)
									values('','$type', '$isuse', '$scroll', '$posi_x', '$posi_y', '$size_x', '$size_y', '$sdate', '$edate', '$linkurl', '$popup_type', '$title', '$content',now())";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("추가되었습니다.","popup_list.php");


}else if($mode == "update"){

	$sdate = $sdate_year."-".$sdate_month."-".$sdate_day;
	$edate = $edate_year."-".$edate_month."-".$edate_day;
	if(!get_magic_quotes_gpc()) $content= addslashes($content);
	
	if(!empty($type)) $where_sql = " where type = '$type' and idx = '$idx'";
	else $where_sql = " where idx = '$idx'";

	$sql = "update wiz_content set isuse='$isuse', scroll='$scroll', posi_x='$posi_x', posi_y='$posi_y', size_x='$size_x', size_y='$size_y',
							sdate='$sdate', edate='$edate', linkurl='$linkurl', popup_type='$popup_type', title='$title', content='$content' $where_sql";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("수정되었습니다.","");


}else if($mode == "delete"){

	$sql = "delete from wiz_content where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("삭제되었습니다.","");

}
?>