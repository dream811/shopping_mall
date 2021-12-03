<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<script type="text/javascript" src="/admin/js/utils.js"></script>
<?

//메인 게시판 통계
if($chart_mode=='border'){

    $prev_period = date('Ym')."0100";
    $next_period = date('Ym').date('t')."00";
    $cprev_period = date('Y-m-')."01 00:00:00";
    $cnext_period = date('Y-m-').date('t')." 23:59:59";
    // 일별
    $substring_sql	= "SUBSTR(DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H'), 7, 2)";
    $time_gubun		= "일";
    $pr_start		= 1;
    $pr_end			= 31;

    $period_sql = " where DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') >= '$prev_period' and DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') <= '$next_period' ";


    $ii=0;

    $sql = "select count(*) as cnt, $substring_sql as wdate 
        from wiz_bbs,wiz_bbsinfo
        $period_sql and wiz_bbsinfo.code = wiz_bbs.code and type='BBS' group by $substring_sql order by $substring_sql asc";
    //echo $sql;
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    $total = mysqli_num_rows($result);
    while($row = mysqli_fetch_assoc($result)){

    $row['wdate']			= intval($row['wdate']);
    $cnt_list[$row['wdate']]	= intval($row['cnt']);

    }

    $csql = "select count(*) as cnt, substring(wdate,9,2) as wdate 
        from wiz_comment 
        where 
        wdate >= '$cprev_period' and wdate <= '$cnext_period' 
        group by substring(wdate,9,2) 
        order by substring(wdate,9,2) asc";

    $cresult = mysqli_query($connect, $csql) or error(mysqli_error($connect));
    $ctotal = mysqli_num_rows($cresult);
    while($crow = mysqli_fetch_assoc($cresult)){
    $crow['wdate']			= intval($crow['wdate']);
    $ccnt_list[$crow['wdate']]	= intval($crow['cnt']);
    }

    $year=date('Y');
    $month=date('m');

    $month_count=date("t", mktime (0,0,0,$month,1,$year));  // return 30

    $bbs_count=array();
    $comment_count=array();
    for($ii=1;$ii<=$month_count;$ii++){

    if(!isset($cnt_list[$ii]) || $cnt_list[$ii]=='')$cnt_list[$ii]=0;
    if(!isset($ccnt_list[$ii]) ||$ccnt_list[$ii]=='')$ccnt_list[$ii]=0;


    $bbs_count[$ii]=$cnt_list[$ii];
    $comment_count[$ii]=$ccnt_list[$ii];
    }
?>

<div>
    <canvas id="myChart" style="display: block; width: 100%; height: 300px;"></canvas>
</div>

<script>

var color = Chart.helpers.color;

var ctx = document.getElementById('myChart');

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['<?=implode("','", array_keys($bbs_count))?>'],
			datasets: [{
				label: '게시글수',
				backgroundColor: color(window.chartColors.red).alpha(1).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [<?=implode(",", $bbs_count)?>]
			}, {
				label: '코멘트수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $comment_count)?>]
			}]

		};
        
</script>
<?
//메인 접속통계
}else if($chart_mode=='common'){
$arrChartData = explode('|', $chart_param);
if(count($arrChartData) < 5) $arrChartData[] = "";
list($mode, $analy_type, $prev_date, $next_date, $search_engin) = $arrChartData;


    $prev_period = $prev_date.'00';
	$next_period = $next_date.'24';

	// 접속시간별
	if($analy_type == "OH" || !$analy_type){

		$substring_sql	= "SUBSTRING(time, 9, 2)";
		$time_gubun		= "시";
		$pr_start		= 0;
		$pr_end			= 23;
	}
	// 일별
	else if($analy_type == "OD"){

		$substring_sql	= "SUBSTRING(time, 7, 2)";
		$time_gubun		= "일";
		$pr_start		= 1;
		$pr_end			= 31;
	}
	// 월별
	else if($analy_type == "OM"){

		$substring_sql	= "SUBSTRING(time, 5, 2)";
		$time_gubun		= "월";
		$pr_start		= 1;
		$pr_end			= 12;
	}
	// 년별
	else if($analy_type == "OY"){

		$substring_sql	= "SUBSTRING(time, 1, 4)";
		$time_gubun		= "년";
		$pr_start		= date('Y') - 5;
		$pr_end			= date('Y') + 5;
	}

	$period_sql = " WHERE time >= '".str_replace('-', '', $prev_period)."' AND time <= '".str_replace('-', '', $next_period)."' ";



	$query	= "SELECT SUM(cnt) AS cnt, ".$substring_sql." AS time FROM wiz_contime ".$period_sql." GROUP BY ".$substring_sql." ORDER BY ".$substring_sql;
	$result	= mysqli_query($connect, $query) or error(mysqli_error($connect));
	while($row = mysqli_fetch_assoc($result)){

		$row['time']			= intval($row['time']);
		$cnt_list[$row['time']]	= intval($row['cnt']);
	}
   
    
    
    for($ii=1;$ii<=$pr_end;$ii++){
        
        if($cnt_list[$ii]=='')$cnt_list[$ii]=0;


        $com_count[$ii]=$cnt_list[$ii];
    }
    
    

?>
<div >
    <canvas id="myChart2" style="display: block; width: 100%; height: 300px;"></canvas>
</div>

<script>

var color = Chart.helpers.color;

var ctx = document.getElementById('myChart2');

		var color = Chart.helpers.color;
		var barChartData2 = {
			labels: ['<?=implode("','", array_keys($com_count))?>'],
			datasets: [{
				label: '방문자수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $com_count)?>]
			}]

		};

		window.onload = function() {

            var ctx = document.getElementById('myChart');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
                    maintainAspectRatio: false,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					},scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                    
				}
			});


			var ctx = document.getElementById('myChart2');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData2,
				options: {
					responsive: true,
                    maintainAspectRatio: false,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					},scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
				}
			});

		};
</script>
<?}else if($chart_mode=='bbs_connect'){

    $prev_period2 =str_replace("-","" , $prev_date);
	$prev_period = $prev_period2."010100";
	$next_period = $prev_period2."123100";
	$cprev_period = $prev_date."01-01 00:00:00";
	$cnext_period = $prev_date."12-31 23:59:59";
	


	// 일별
	$substring_sql	= "SUBSTR(DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H'), 5, 2)";
	$time_gubun		= "월";
	$pr_start		= 1;
	$pr_end			= 12;
	
	$period_sql = " where DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') >= '$prev_period' and DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') <= '$next_period' ";
	

	if($analy_type){
	//$code_sql="and wiz_bbs.code = '$analy_type'";
	//$code_sql2="and code = '$analy_type'";
	}
	// board
	$sql = "select count(*) as cnt, $substring_sql as wdate 
			from wiz_bbs,wiz_bbsinfo
			$period_sql and wiz_bbsinfo.code = wiz_bbs.code and type='BBS' $code_sql group by $substring_sql order by $substring_sql asc";
	//echo $sql;
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  
	$total = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result)){

		$row['wdate']			= intval($row['wdate']);
		$cnt_list[$row['wdate']]	= intval($row['cnt']);
	}



	// comment
	$csql = "select count(*) as cnt, substring(wdate,6,2) as wdate 
			from wiz_comment 
			where 
			wdate >= '$cprev_period' and wdate <= '$cnext_period' /*$code_sql2*/
			group by substring(wdate,6,2) 
			order by substring(wdate,6,2) asc";
    
	$cresult = mysqli_query($connect, $csql) or error(mysqli_error($connect));
	$ctotal = mysqli_num_rows($cresult);
	while($crow = mysqli_fetch_assoc($cresult)){
		$crow['wdate']			= intval($crow['wdate']);
		$ccnt_list[$crow['wdate']]	= intval($crow['cnt']);
	}
    

    for($ii=1;$ii<=$pr_end;$ii++){
        
        if($cnt_list[$ii]=='')$cnt_list[$ii]=0;
        if($ccnt_list[$ii]=='')$ccnt_list[$ii]=0;

        $bbs_count[$ii]=$cnt_list[$ii];
        $com_count[$ii]=$ccnt_list[$ii];
    }



?>
<div>
    <canvas id="myChart" style="display: block; width: 100%; height: 400px;" ></canvas>
</div>

<script>

var color = Chart.helpers.color;

var ctx = document.getElementById('myChart');

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['<?=implode("','", array_keys($bbs_count))?>'],
			datasets: [{
				label: '게시글수',
				backgroundColor: color(window.chartColors.red).alpha(1).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [<?=implode(",", $bbs_count)?>]
			}, {
				label: '코멘트수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $com_count)?>]
			}]

		};
     

		window.onload = function() {
			var ctx = document.getElementById('myChart');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
                    maintainAspectRatio: false,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					}
				}
			});

		};
    
</script>

<?}else if($chart_mode=='bbs_connect2'){


	$prev_period2 =str_replace("-","" , $prev_date);
	$prev_period = $prev_period2."0100";
	$next_period = $prev_period2."3100";
	$cprev_period = $prev_date."01 00:00:00";
	$cnext_period = $prev_date."31 23:59:59";
	


	// 일별
	$substring_sql	= "SUBSTR(DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H'), 7, 2)";
	$time_gubun		= "일";
	$pr_start		= 1;
	$pr_end			= 31;
	
	$period_sql = " where DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') >= '$prev_period' and DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d%H') <= '$next_period' ";
	

	if($analy_type){
	//$code_sql="and wiz_bbs.code = '$analy_type'";
	//$code_sql2="and code = '$analy_type'";
	}
	// board
	$sql = "select count(*) as cnt, $substring_sql as wdate 
			from wiz_bbs,wiz_bbsinfo
			$period_sql and wiz_bbsinfo.code = wiz_bbs.code and type='BBS' $code_sql group by $substring_sql order by $substring_sql asc";
	//echo $sql;
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	while($row = mysqli_fetch_assoc($result)){

		$row['wdate']			= intval($row['wdate']);
		$cnt_list[$row['wdate']]	= intval($row['cnt']);
	}

	// comment
	$csql = "select count(*) as cnt, substring(wdate,9,2) as wdate 
			from wiz_comment 
			where 
			wdate >= '$cprev_period' and wdate <= '$cnext_period' /*$code_sql2*/
			group by substring(wdate,9,2) 
			order by substring(wdate,9,2) asc";

	$cresult = mysqli_query($connect, $csql) or error(mysqli_error($connect));
	$ctotal = mysqli_num_rows($cresult);
	while($crow = mysqli_fetch_assoc($cresult)){
		$crow['wdate']			= intval($crow['wdate']);
		$ccnt_list[$crow['wdate']]	= intval($crow['cnt']);
	}
    
    
    $M_str=substr($prev_date_M,0,1);
    
    if($M_str==0){
    $prev_date_M=substr($prev_date_M,1,1);
    }
 
    $month_count=date("t", mktime (0,0,0,$prev_date_M,1,$prev_date_Y));  // return 30
    
  
    for($ii=1;$ii<=$month_count;$ii++){
        
        if($cnt_list[$ii]=='')$cnt_list[$ii]=0;
        if($ccnt_list[$ii]=='')$ccnt_list[$ii]=0;

        $bbs_count[$ii]=$cnt_list[$ii];
        $com_count[$ii]=$ccnt_list[$ii];
    }

?>


<div>
    <canvas id="myChart" style="display: block; width: 100%; height: 400px;"></canvas>
</div>

<script>

var color = Chart.helpers.color;

var ctx = document.getElementById('myChart');

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['<?=implode("','", array_keys($bbs_count))?>'],
			datasets: [{
				label: '게시글수',
				backgroundColor: color(window.chartColors.red).alpha(1).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: [<?=implode(",", $bbs_count)?>]
			}, {
				label: '코멘트수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $com_count)?>]
			}]

		};
     

		window.onload = function() {
			var ctx = document.getElementById('myChart');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
                    maintainAspectRatio: false,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					}
				}
			});

		};
    
</script>
<?}else if($chart_mode=='member_join'){


    $prev_period2 =str_replace("-","" , $prev_date);
	$prev_period = $prev_period2."010100";
	$next_period = $prev_period2."123100";
	$cprev_period = $prev_date."-01-01 00:00:00";
	$cnext_period = $prev_date."-12-31 23:59:59";

    $substring_sql	= "SUBSTRING(wdate, 6, 2)";
    $time_gubun		= "월";
    $pr_start		= 1;
    $pr_end			= 12;

    
    $period_sql = " WHERE wdate >= '$cprev_period' AND wdate <= '$cnext_period' ";

	/*$query	= "SELECT SUM(cnt) AS total_cnt FROM wiz_contime";
	$result	= mysqli_query($connect, $query) or error(mysqli_error($connect));
	$row	= mysqli_fetch_object($result);
	$total_cnt = $row->total_cnt;*/

	$query	= "SELECT count(*) AS cnt, ".$substring_sql." AS time FROM wiz_member ".$period_sql." GROUP BY ".$substring_sql." ORDER BY ".$substring_sql;
	$result	= mysqli_query($connect, $query) or error(mysqli_error($connect));
	while($row = mysqli_fetch_assoc($result)){

		$row['time']			= intval($row['time']);
		$cnt_list[$row['time']]	= intval($row['cnt']);
       
	}


     for($ii=1;$ii<=$pr_end;$ii++){
        
        if(!isset($cnt_list[$ii]) || $cnt_list[$ii]=='')$cnt_list[$ii]=0;
      

        $member_count[$ii]=$cnt_list[$ii];
    }
    


?>

<div>
    <canvas id="myChart" style="display: block; width: 700px; height: 200px;"></canvas>
</div>

<script>

var color = Chart.helpers.color;

var ctx = document.getElementById('myChart');

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['<?=implode("','", array_keys($member_count))?>'],
			datasets: [{
				label: '가입자 수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $member_count)?>]
			}]

		};
        

		window.onload = function() {
			var ctx = document.getElementById('myChart');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					}
				}
			});

		};
        
</script>

<?}else if($chart_mode=='connect'){

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
    
    

	$period_sql = " WHERE time >= '".str_replace('-', '', $prev_period)."' AND time <= '".str_replace('-', '', $next_period)."' ";

	

	$query	= "SELECT SUM(cnt) AS cnt, ".$substring_sql." AS time FROM wiz_contime ".$period_sql." GROUP BY ".$substring_sql." ORDER BY ".$substring_sql;
	$result	= mysqli_query($connect, $query) or error(mysqli_error($connect));
	while($row = mysqli_fetch_assoc($result)){

		$row['time']			= intval($row['time']);
		$cnt_list[$row['time']]	= intval($row['cnt']);
        
	}

    if($analy_type !='OY'){

        for($ii=1;$ii<=$pr_end;$ii++){
        
            if($cnt_list[$ii]=='')$cnt_list[$ii]=0;
            $member_count[$ii]=$cnt_list[$ii];
        }
    
    }else{
        
        for($ii=$pr_start;$ii<=$pr_end;$ii++){
        
            if($cnt_list[$ii]=='')$cnt_list[$ii]=0;
            $member_count[$ii]=$cnt_list[$ii];
        }
    
    }

?>

<div style="width:100%;">
    <canvas id="myChart" style="display: block; width: 700px; height: 200px;"></canvas>
</div>
<script>
var color = Chart.helpers.color;

var ctx = document.getElementById('myChart');

		var color = Chart.helpers.color;
		var barChartData = {
			labels: ['<?=implode("','", array_keys($member_count))?>'],
			datasets: [{
				label: '접속자 수',
				backgroundColor: color(window.chartColors.blue).alpha(1).rgbString(),
				borderColor: window.chartColors.blue,
				borderWidth: 1,
				data: [<?=implode(",", $member_count)?>]
			}]

		};
        

		window.onload = function() {
			var ctx = document.getElementById('myChart');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: ''
					},scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
				}
			});

		};
        
</script>

<?}else if($chart_mode=='browser'){


	$sql2 = "select sum(cnt) as cnt,browser from wiz_conother where browser != '' GROUP BY browser order by cnt desc";
	$result2 = mysqli_query($connect, $sql2) or error(mysqli_error($connect));

    for($i = 0; $row2 = mysqli_fetch_assoc($result2); $i++){



        $row2['browser']			= $row2['browser'];
		$cnt_list[$row2['browser']]	= $row2['cnt'];

		
	}
    

?>
	<canvas id="chart-area"  width="1" height="60"></canvas>	
	<script>
		
		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [<?=implode(",", $cnt_list)?>],
					backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.yellow,
						window.chartColors.green,
						window.chartColors.blue
					],
					label: 'Dataset 1'
				}],
				labels: ['<?=implode("','", array_keys($cnt_list))?>']
			},
			options: {
				responsive: true,
                maintainAspectRatio: false,
			}
		};
     
		
	</script>

<?}else if($chart_mode=='os'){

    $sql2 = "select sum(cnt) as cnt,os from wiz_conother where os != '' GROUP BY os order by cnt desc";
	$result2 = mysqli_query($connect, $sql2) or error(mysqli_error($connect));

	for($i = 0; $row2 = mysqli_fetch_assoc($result2); $i++){

        $row2['os']			= $row2['os'];
		$cnt_list2[$row2['os']]	= $row2['cnt'];

		
	}
?>
<canvas id="chart-area2"  width="1" height="60"></canvas>
    <script>
		
		var config2 = {
			type: 'pie',
			data: {
				datasets: [{
					data: [<?=implode(",", $cnt_list2)?>],
					backgroundColor: [
						window.chartColors.red,
						window.chartColors.orange,
						window.chartColors.yellow,
						window.chartColors.green,
						window.chartColors.blue
					],
					label: 'Dataset 1'
				}],
				labels: ['<?=implode("','", array_keys($cnt_list2))?>']
			},
			options: {
				responsive: true,
                maintainAspectRatio: false,
			}
		};

		window.onload = function() {
            var ctx = document.getElementById('chart-area').getContext('2d');
			window.myPie = new Chart(ctx, config);

			var ctx = document.getElementById('chart-area2').getContext('2d');
			window.myPie = new Chart(ctx, config2);
		};

		
	</script>
<?}else if($chart_mode=='refer'){

    $prev_period = $prev_date;
	$next_period = $next_date;
	$period_sql = " wdate >= '".$prev_period."' AND wdate <= '".$next_period."' ";

	

	// 접속경로별
	if($analy_type == "RA" || $analy_type == ""){

		$group_sql	= "referer";
	}

	else if($analy_type == "RB"){

		$group_sql	= "host";
	}
    
	$query2	= "SELECT sum(cnt) AS cnt, ".$group_sql." FROM wiz_conrefer WHERE host LIKE '%".$search_engin."%' AND ".$period_sql." GROUP BY ".$group_sql." ORDER BY cnt DESC";

    $result2	= mysqli_query($connect, $query2) or error(mysqli_error($connect));
	for($i = 0; $row2 = mysqli_fetch_assoc($result2); $i++){

		if(!$row2[$group_sql])	$row2[$group_sql] = "즐겨찾기나 직접방문";
	        $row2[$group_sql]			= $row2[$group_sql];
		    $cnt_list[$row2[$group_sql]]	= $row2['cnt'];
        

	}
    


?>
<canvas id="chart-area"  width="1" height="200"></canvas>
<script>
    
    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [<?=implode(",", $cnt_list)?>],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue
                ],
                label: 'Dataset 1'
            }],
            labels: ['<?=implode("','", array_keys($cnt_list))?>']
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    };
    
    window.onload = function() {
        var ctx = document.getElementById('chart-area').getContext('2d');
        window.myPie = new Chart(ctx, config);
    };
    

    
</script>

<?}?>