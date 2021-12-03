<? include "../../inc/common.inc"; ?>

<? include "../../inc/util.inc"; ?>

<? include "../../inc/shop_info.inc"; ?>

<? include "../../inc/admin_check.inc"; ?>

<? include "../header.php"; ?>

<script language="javascript">

<!--

function delConnect(){

	if(confirm("OS/브라우저 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){

		document.location = 'connect_save.php?mode=delos';

	}

}

-->

</script>

   		<table border="0" cellspacing="0" cellpadding="2">

        <tr>

          <td><img src="../image/ic_tit.gif"></td>

          <td valign="bottom" class="tit">OS/브라우저</td>

          <td width="2"></td>

          <td valign="bottom" class="tit_alt">OS/브라우저를 분석합니다.</td>

        </tr>

      </table>

      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">

        <tr><td></td></tr>

      </table>

      <br>

      <table width="100%" border="0" cellspacing="0" cellpadding="2">

        <tr>

          <td><table border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 브라우저</td></tr></table></td>

          <td align="right"><a onClick="delConnect();" class="AW-btn-s-black">접속자분석 초기화</a></td>

        </tr>

      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

      	<tr><td class="t_rd" colspan=4></td></tr>

        <tr class="t_th">

          <th width="15%">브라우저</td>

          <th width="15%">접속자수</td>

          <th width="15%">비율</td>

          <th  width="55%">그래프</td>

        </tr>

        <tr><td class="t_rd" colspan=4></td></tr>

      <?

        $sql = "select browser from wiz_conother where browser != ''";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $total = mysqli_num_rows($result);


		$sql = "select sum(cnt) as sumcnt from wiz_conother where browser != ''";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);
		$sumcnt = $row[sumcnt];

        $rows = 12;
        $lists = 5;
        
    	$page_count = ceil($total/$rows);

    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;

    	$start = ($page-1)*$rows;

    	$no = $total-$start;

    	

        $sql = "select * from wiz_conother where browser != '' order by cnt desc limit $start, $rows";

        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

      

        while($row = mysqli_fetch_array($result)){

            $percent = ceil(($row['cnt']/$sumcnt)*100);

            ?>

            <tr align="center"> 
                <td height="30"><?=$row[browser]?></td>
                <td><?=$row['cnt']?>명</td>
                <td><?=$percent?>%</td>
                <td style="text-align:left;" rowspan="<?=$total?>" >
                  <!-- <img src="../image/mark_bar.gif" width="<?=$percent*3?>" height="10"> -->
                <?  
                    $chart_mode="browser";
                    include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
                ?> 
                </td>
            </tr>
          <?

                $no--;

            }
        if($total <= 0){
        ?>
        <tr><td height="30" colspan="10" align="center">데이타가 없습니다.</td></tr>
        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>
        <?
        }
        ?>
      </table>

      <br><br>

      <table width="100%" border="0" cellspacing="0" cellpadding="2">

        <tr>

          <td class="tit_sub"><img src="../image/ics_tit.gif"> OS</td>

        </tr>

      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

      	<tr><td class="t_rd" colspan=6></td></tr>

        <tr class="t_th">

          <th width="15%">OS</td>

          <th width="15%">접속자수</td>

          <th width="15%">비율</td>

          <th width="55%">그래프</td>

        </tr>

        <tr><td class="t_rd" colspan=6></td></tr>


      <?

      $sql = "select browser from wiz_conother where os != ''";

      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

      $total = mysqli_num_rows($result);

			

			$sql = "select sum(cnt) as sumcnt from wiz_conother where os != ''";

			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$row = mysqli_fetch_array($result);

			$sumcnt = $row[sumcnt];

			

      $rows = 12;

      $lists = 5;

    	$page_count = ceil($total/$rows);

    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;

    	$start = ($page-1)*$rows;

    	$no = $total-$start;

    	

      $sql = "select * from wiz_conother where os != '' order by cnt desc limit $start, $rows";

      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

      

      while($row = mysqli_fetch_array($result)){

      	$percent = ceil(($row['cnt']/$sumcnt)*100);

      ?>

        <tr align="center"> 

          <td height="30"><?=$row[os]?></td>

          <td><?=$row['cnt']?>명</td>

          <td><?=$percent?>%</td>

          <td style="text-align:left;" rowspan="<?=$total?>" >
          
          <?
            $chart_mode="os";
            include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
          ?>
          </td>

        </tr>

       

      <?

      		$no--;

        }

        if($total <= 0){

      ?>

        <tr><td height="30" colspan="10" align="center">데이타가 없습니다.</td></tr>

        <tr><td height="1" colspan="10" bgcolor="EBEBEB"></td></tr>

      <?

        }

      ?>

      </table>

      

<? include "../footer.php"; ?>