<?
$day = "";
$first_day	= date("w",mktime(0,0,0,$month,1,$year)) + 1;  
$total_day = date("t", mktime(0, 0, 0, $month, 1, $year));

$start_month	= $year."-".$month."-01";
$last_month = $year."-".$month."-".$total_day;

$sql = "select *, from_unixtime(wdate, '%e') as day_idx from wiz_bbs where code='$code' and from_unixtime(wdate, '%Y-%m-%d') between '$start_month' and '$last_month' order by wdate";
$result = mysqli_query($connect, $sql);

$day_bak = "";
while ($row = mysqli_fetch_array($result)) {
	
	//$day_idx = str_replace("0","",substr($row['wdate'],8,2));
	$day_idx = $row[day_idx];
	if($day_bak == $day_idx) $seq_idx++;
	else $seq_idx = 0;
	$day_bak = $day_idx;
	
	$schedule_list[$day_idx][$seq_idx]['subject'] = cut_str($row['subject'],10);
	$schedule_list[$day_idx][$seq_idx]['idx'] = $row['idx'];

}
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
  <tr height="25" align="center">
    <td width="14%" class="t_name"><b><font color=red>Sun</font></b></td>
    <td width="14%" class="t_name"><b>Mon</b></td>
    <td width="14%" class="t_name"><b>Tue</b></td>
    <td width="14%" class="t_name"><b>Wed</b></td>
    <td width="14%" class="t_name"><b>Thu</b></td>
    <td width="14%" class="t_name"><b>Fir</b></td>
    <td width="14%" class="t_name"><b><font color=blue>Sat</font></b></td>
  </tr>
<?
if(($first_day > 5) && ((floor($total_day / 5) == 6) || (floor($total_day / 5) == 7))) {
	$line = 6;
	if($total_day == 30 && $first_day == 6)
	$line--;
} else if(($first_day == 1) && ($total_day == 28)) {
	$line = 4;
} else {
	$line = 5;
}

$k = 0;

for ($i=1; $i<=$line; $i++) {
	
	echo "<tr height=80>";
	
	for ($j=1; $j<=7; $j++) {
		
		if ((!$day) && ($j == $first_day)) $day = 1;

		$date_color = "";
		if ($j == 7) $date_color = "blue";
		if ($j == 1) $date_color = "red";

		if ($day > 0){
?>
				<td valign="top" bgcolor="#ffffff" class="verdana">
					<a href="input.php?code=<?=$code?>&wdate=<?=$year?>-<?=$month?>-<?=sprintf('%02d', $day)?>"><font size="5" color="<?=$date_color?>"><strong><?=$day?></strong></font></a><br>
					<? for($k=0;$k<count($schedule_list[$day]);$k++){ ?>
					&nbsp;<a href="view.php?code=<?=$code?>&idx=<?=$schedule_list[$day][$k]['idx']?>"><?=$schedule_list[$day][$k]['subject']?></a><br>
					<? } ?>
				</td>
<?
		} else {
			echo "<td bgcolor='#ffffff'></td>";
		}

		if ($day != $total_day) {
			if (($day > 0) && ($day < $total_day)) $day++;
		} else {
			$day = "&nbsp;";
		}
	}
	
	echo "</tr>";
}
		

?>
</table>