<table width="<?=$bbs_width?>" border="0" cellpadding="0" cellspacing="0">
<?
$sql = "select idx,subject,from_unixtime(wdate, '%Y-%m-%d') as wdate, depno from wiz_bbs where code='$code' order by prino desc limit $bbs_num";
$result = mysqli_query($connect, $sql);
while($row = mysqli_fetch_array($result)){
	// $row['idx'] 			게시물번호
	// $row['subject']  제목
	// $row['wdate'] 		일자

	$re_icon		="";
	$re_space		="";

	if($row[depno] != 0) $re_icon = "<img src='/images/icon_re.gif' border='0' align='absmiddle'>";	// re
	for($ii=0; $ii < $row[depno]; $ii++) $re_space .= "&nbsp;&nbsp;";																		// respace

?>
<tr>
	<td height="<?=$bbs_height?>" align="left"><?=$re_space?><?=$re_icon?><a href="/bbs/view.php?code=<?=$code?>&idx=<?=$row['idx']?>"><?=cut_str($row['subject'],$bbs_len)?></a></td>
	<td align="right"><?=$row['wdate']?></td>
</tr>
<?
}
?>
</table>