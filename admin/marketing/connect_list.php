<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="javascript">
<!--
function delConnect(){
	if(confirm("접속자분석 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=dellist';
	}
}
function analyToday(){

	var pdate = "<?=date("Y-m-d")?>";
	var mdate = "<?=date("Y-m-d")?>";
	var plist = pdate.split("-");
	var nlist = mdate.split("-");

	prev_year = document.frm.prev_year;
	for(ii=0; ii<prev_year.length; ii++){
	   if(prev_year.options[ii].value == plist[0])
	      prev_year.options[ii].selected = true;
	}
	prev_month = document.frm.prev_month;
	for(ii=0; ii<prev_month.length; ii++){
	   if(prev_month.options[ii].value == plist[1])
	      prev_month.options[ii].selected = true;
	}
	prev_day = document.frm.prev_day;
	for(ii=0; ii<prev_day.length; ii++){
	   if(prev_day.options[ii].value == plist[2])
	      prev_day.options[ii].selected = true;
	}

	next_year = document.frm.next_year;
	for(ii=0; ii<next_year.length; ii++){
	   if(next_year.options[ii].value == nlist[0])
	      next_year.options[ii].selected = true;
	}
	next_month = document.frm.next_month;
	for(ii=0; ii<next_month.length; ii++){
	   if(next_month.options[ii].value == nlist[1])
	      next_month.options[ii].selected = true;
	}
	next_day = document.frm.next_day;
	for(ii=0; ii<next_day.length; ii++){
	   if(next_day.options[ii].value == nlist[2])
	      next_day.options[ii].selected = true;
	}

	document.frm.submit();

}

function analyPeriod(){
	var pdate = "2004-01-01";
	var mdate = "<?=date("Y-m-d")?>";
	var plist = pdate.split("-");
	var nlist = mdate.split("-");

	prev_year = document.frm.prev_year;
	for(ii=0; ii<prev_year.length; ii++){
	   if(prev_year.options[ii].value == plist[0])
	      prev_year.options[ii].selected = true;
	}
	prev_month = document.frm.prev_month;
	for(ii=0; ii<prev_month.length; ii++){
	   if(prev_month.options[ii].value == plist[1])
	      prev_month.options[ii].selected = true;
	}
	prev_day = document.frm.prev_day;
	for(ii=0; ii<prev_day.length; ii++){
	   if(prev_day.options[ii].value == plist[2])
	      prev_day.options[ii].selected = true;
	}

	next_year = document.frm.next_year;
	for(ii=0; ii<next_year.length; ii++){
	   if(next_year.options[ii].value == nlist[0])
	      next_year.options[ii].selected = true;
	}
	next_month = document.frm.next_month;
	for(ii=0; ii<next_month.length; ii++){
	   if(next_month.options[ii].value == nlist[1])
	      next_month.options[ii].selected = true;
	}
	next_day = document.frm.next_day;
	for(ii=0; ii<next_day.length; ii++){
	   if(next_day.options[ii].value == nlist[2])
	      next_day.options[ii].selected = true;
	}

	document.frm.submit();

}
-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">접속자분석</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">사이트 접속 로그를 분석합니다.</td>
	    </tr>
	  </table>
	  <br>


      <table width="100%" border="0" cellpadding="2" cellspacing="6">
      <form name="frm" action="<?=$PHP_SELF?>" method="get">
        <tr>
          <td bgcolor="ffffff">
          <table width="100%" cellspacing="1" cellpadding="3" border="0" class="t_style">
          <tr>
          <td width="15%" class="t_name">&nbsp; 분석방법</td>
          <td width="85%" class="t_value">
          <input type="radio" name="analy_type" value="OT" onClick="analyToday();" <? if($analy_type == "OT" || $analy_type == "") echo "checked"; ?>>오늘
          <input type="radio" name="analy_type" value="OH" onClick="analyPeriod();" <? if($analy_type == "OH") echo "checked"; ?>>시간대별
          <input type="radio" name="analy_type" value="OD" onClick="analyPeriod();" <? if($analy_type == "OD") echo "checked"; ?>>일별
          <input type="radio" name="analy_type" value="OM" onClick="analyPeriod();" <? if($analy_type == "OM") echo "checked"; ?>>월별
          <input type="radio" name="analy_type" value="OY" onClick="analyPeriod();" <? if($analy_type == "OY") echo "checked"; ?>>년별
          </td>
          </tr>
          <tr>
          <td class="t_name">&nbsp; 기간</td>
          <td class="t_value">

          <select name="prev_year" class="select2">
         <?
            if($analy_type == ""){
               if(empty($prev_year)) $prev_year = date("Y");
               if(empty($prev_month)) $prev_month = date("m");
               if(empty($prev_day)) $prev_day = date("d");
         		}
            if(empty($next_year)) $next_year = date("Y");
            if(empty($next_month)) $next_month = date("m");
            if(empty($next_day)) $next_day = date("d");

            for($ii=2005; $ii <= date('Y'); $ii++){
              if($ii == $prev_year) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           년
           <select name="prev_month" class="select2">
             <?
            for($ii=1; $ii <= 12; $ii++){
              if($ii<10) $ii = "0".$ii;
              if($ii == $prev_month) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           월
           <select name="prev_day" class="select2">
             <?
            for($ii=1; $ii <= 31; $ii++){
              if($ii<10) $ii = "0".$ii;
              if($ii == $prev_day) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           일 ~
           <select name="next_year" class="select2">
             <?
            for($ii=2005; $ii <= date('Y'); $ii++){
              if($ii == $next_year) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           년
           <select name="next_month" class="select2">
             <?
            for($ii=1; $ii <= 12; $ii++){
              if($ii<10) $ii = "0".$ii;
              if($ii == $next_month) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           월
           <select name="next_day" class="select2">
             <?
            for($ii=1; $ii <= 31; $ii++){
              if($ii<10) $ii = "0".$ii;
              if($ii == $next_day) echo "<option value=$ii selected>$ii";
              else echo "<option value=$ii>$ii";
            }
         ?>
           </select>
           일&nbsp;
           <input type="submit" value="검색" class="AW-btn-search" />
           <input type="button" onClick="delConnect();" value="접속자분석 초기화" class="AW-btn" />
          </td>
          </table>
          </td>
        </tr>
      </form>
      </table>

        <?
        $chart_mode="connect";
        include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
        ?>
      <!--table width='780' height="340" border='0' cellspacing='0' cellpadding='0' background="../image/data.gif">
      <tr>
      <td width=50></td>
      <td align=center valign=bottom>
      <table width='710' height=223 border='0' cellspacing='0' cellpadding='0'>
      <?

		if(!empty($prev_year)){
       	    $prev_period = $prev_year."".$prev_month."".$prev_day."00";
       	    $next_period = $next_year."".$next_month."".$next_day."24";
    	}

			// 접속시간별
			if($analy_type == "OH" || $analy_type == "OT" || $analy_type == ""){

				$substring_sql = "substring(time,9,2)";
				$time_gubun = "시";
				$pr_start = 0; $pr_end = 23;

			}else if($analy_type == "OD"){

				$substring_sql = "substring(time,7,2)";
				$time_gubun = "일";
				$pr_start = 1; $pr_end = 31;

			}else if($analy_type == "OM"){

				$substring_sql = "substring(time,5,2)";
				$time_gubun = "월";
				$pr_start = 1; $pr_end = 12;

			}else if($analy_type == "OY"){

				$substring_sql = "substring(time,1,4)";
				$time_gubun = "년";
				$pr_start = 2005; $pr_end = 2020;

			}

			$period_sql = " where time >= '$prev_period' and time <= '$next_period' ";

      $sql = "select sum(cnt) as total_cnt from wiz_contime";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $row = mysqli_fetch_object($result);
      $total_cnt = $row->total_cnt;

      $sql = "select sum(cnt) as cnt, $substring_sql as time from wiz_contime $period_sql group by $substring_sql order by $substring_sql asc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);
			$maxper = 0;
      while($row = mysqli_fetch_object($result)){
      	$row->time = $row->time/1;
      	$percent = ceil(($row->cnt/$total_cnt)*100);
      	if($percent > $maxper) $maxper = $percent;
      	$cnt_list[$row->time]['cnt'] = $row->cnt;
      	$cnt_list[$row->time][percent] = $percent;
			}

			if($maxper > 80) $graphper = 1;
			else if($maxper > 60) $graphper = 2;
			else if($maxper > 40) $graphper = 3;
			else if($maxper > 20) $graphper = 4;
			else if($maxper > 0) $graphper = 5;

			// 접속시간별 24 -> 0
			if($analy_type == "OH" || $analy_type == "OT" || $analy_type == ""){
				$cnt_list[24] = $cnt_list[0];
			}

			for($pr_start; $pr_start <= $pr_end; $pr_start++){
      ?>
        <td valign="bottom" align="center">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center"><?=$cnt_list[$pr_start]['cnt']?><br><img src='../image/mark_bar.gif' height='<?=($cnt_list[$pr_start][percent]*$graphper)?>' width='15'></td>
            </tr>
            <tr><td height="20"></td></tr>
            <tr>
              <td align="center"><?=$pr_start?></td>
            </tr>
          </table>
        </td>
      <?
      }
   		?>
   	  </tr>
   	  <tr><td height="23"></td></tr>
   	</table>
   	<td width=20 valign="bottom" style="padding:0 0 23px;"><?=$time_gubun?></td>
   	</td>
   	</tr>
   	</table-->
    
    
    <br>

   	<?
   		// 오늘 방문자
      $sql = "select time, sum(cnt) as cnt from wiz_contime group by substring(time,1,8) order by substring(time,1,8) desc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $visit_cnt = mysqli_fetch_object($result);
      $today_cnt = $visit_cnt->cnt;

      // 어제 방문자
      $visit_cnt = mysqli_fetch_object($result);
      $yester_cnt = $visit_cnt->cnt;

	   	// 총 접속자 수
	   	$sql = "select sum(cnt) as total_cnt from wiz_contime";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $row = mysqli_fetch_object($result);
      $total_cnt = $row->total_cnt;

	   	// 카운터 시작일
	   	$sql = "select min(time) as stime from wiz_contime";
	   	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	   	$row = mysqli_fetch_object($result);
	   	$stime = $row->stime;

	   	// 이번달 방문자
	   	$tmonth = date('Ym')."0000";
	   	$sql = "select sum(cnt) as month_cnt from wiz_contime where time >= '$tmonth'";
	   	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	   	$row = mysqli_fetch_object($result);
	   	$month_cnt = $row->month_cnt;

	   	$start_day = mktime(0,0,0,substr($stime,4,2),substr($stime,6,2),substr($stime,0,4));
	   	$end_day = mktime(0,0,0,date('m'),date('d'),date('Y'));
			$total_period = ($end_day - $start_day)/(3600*24);
			if($total_period == "") $total_period = 1;
   	?>
   	<table width='100%' border='0' cellspacing='1' cellpadding='0' class="t_style">
		<tr>
		  <td width="25%" align='center' height="25" class="t_name">총 접속자 수</td>
		  <td width="25%" class="t_value">&nbsp; <font color=064F92><B><?=$total_cnt?></b></font>명</td>
		  <td width="25%" align='center' class="t_name">평균 접속자 수</td>
		  <td width="25%" class="t_value">&nbsp; <font color=064F92><B><?=round($total_cnt/$total_period,3)?></b></font>명</td>
		</tr>
		<tr>
		  <td height="25" align='center' class="t_name">오늘 접속자 수</td>
		  <td class="t_value">&nbsp; <font color=064F92><B><?=$today_cnt?></b></font>명</td>
		  <td align='center' class="t_name">어제 접속자 수</td>
		  <td class="t_value">&nbsp; <font color=064F92><B><?=$yester_cnt?></b></font>명</td>
		</tr>
		<tr>
		  <td height="25" align='center' class="t_name">이번달 접속자 수</td>
		  <td class="t_value">&nbsp; <font color=064F92><B><?=$month_cnt?></b></font>명</td>
		  <td align='center' class="t_name">이번달 평균 접속자 수</td>
		  <td class="t_value">&nbsp; <font color=064F92><B><?=round($month_cnt/date('d'),3)?></b></font>명</td>
		</tr>
		</table>


<? include "../footer.php"; ?>