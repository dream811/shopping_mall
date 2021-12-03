<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

	<table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td><img src="../image/ic_tit.gif"></td>
      <td valign="bottom" class="tit">회원관리</td>
      <td width="2"></td>
      <td valign="bottom" class="tit_alt">회원을 여러가지 조건으로 분석합니다.</td>
    </tr>
  </table>			
  <br>	  
  
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기간별통계</td>
	  </tr>
	</table>
	  	
	<form name="frm" action="<?=$PHP_SELF?>" method="get">
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<th width="15%" class="t_name">기간</th>
		<td width="85%" class="t_value">
			<?
			function zeroFill($val, $zeroNum) {
				return sprintf('%'.$zeroNum.'d', $val);
			}
			$nowy=date("Y");
			$nowm=date("m");
			if(!isset($prev_date) || !$prev_date){
				$prev_date=$nowy;
			}
		
			?>
			<select name="prev_date">
			<?
			for($ii="2014"; $ii<=$nowy; $ii++){
			?>
			<option value="<?=$ii?>" <?if($ii==$prev_date)echo"selected"?>><?=$ii?></option>
			<?
			}
			?>
			</select>

			<button style="height:22px;vertical-align:bottom;" type="submit" class="AW-btn-search">검색</button>

		</td>
	</tr>
	</table>
	</form>
	<br />
	
  <?
  $chart_mode="member_join";
  include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
  ?>
	<?

	$cprev_period = $prev_date."-01-01 00:00:00";
	$cnext_period = $prev_date."-12-31 23:59:59";
	
	$period_sql = " WHERE wdate >= '$cprev_period' AND wdate <= '$cnext_period' ";
	
	$sql = "select * from wiz_member $period_sql ";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	?>

	<br>
	<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
	<tr>
		<th width="15%" height="30" class="t_name"><?=$prev_date?>년 총 가입자 수</th>
		<td width="35%" class="t_value">&nbsp; <font color=064F92><B><?=$total?></b></font></td>
		<th width="15%" class="t_name"><?=$prev_date?>년 평균 가입자 수</th>
		<td width="35%" class="t_value">&nbsp; <font color=064F92><B><?=$total/date("m")?></b></font></td>
	</tr>
	</table>

	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td class="tit_sub"><img src="../image/ics_tit.gif"> 등급별통계</td>
	  </tr>
	</table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<form>
    	<tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="15%">등급</th>
        <th width="15%">회원수</th>
        <th width="15%">비율</th>
        <th width="55%">그래프</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
    </form>
      <?
      $sql = "select id from wiz_member";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $member_all = mysqli_num_rows($result);
		if($member_all <= 0) $member_all = 1;
			
      $sql = "select idx,level,name from wiz_level  order by level asc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      while($row = mysqli_fetch_object($result)){
      	
      	$m_sql = "select id from wiz_member where level = '$row->idx'";
      	$m_result = mysqli_query($connect, $m_sql) or error(mysqli_error($connect));
      	$member_cnt = mysqli_num_rows($m_result);
      	
      	$member_percent = ($member_cnt/$member_all)*100;
      ?>
      <tr align="center" class="t_tr"> 
        <td height="35"><?=$row->name?></td>
        <td><?=$member_cnt?></td>
        <td><?=round($member_percent,2)?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$member_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="20" class="t_line"></td></tr>
      <?
      }
      ?>
    </table>

  <br>
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
	  <tr>
	    <td class="tit_sub"><img src="../image/ics_tit.gif"> 지역별통계</td>
	  </tr>
	</table>
	
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<form>
    	<tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="15%">지역</th>
        <th width="15%">회원수</th>
        <th width="15%">비율</th>
        <th width="55%">그래프</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
    	</form>
    
    <?
       $sql = "select substring(address,1,2) as address, count(address) areatotal from wiz_member group by substring(address,1,2) order by areatotal";
       $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
       $total = 0;
       while($row = mysqli_fetch_object($result)){
           $arr_address[$row->address] = $row->areatotal;
           $total += $row->areatotal;
       }
       if($total == 0) $total = 1;
        $arr_address["서울"] = isset($arr_address["서울"]) ? $arr_address["서울"] : 0;
        $arr_address["경기"] = isset($arr_address["경기"]) ? $arr_address["경기"] : 0;
        $arr_address["인천"] = isset($arr_address["인천"]) ? $arr_address["인천"] : 0;
        $arr_address["대전"] = isset($arr_address["대전"]) ? $arr_address["대전"] : 0;
        $arr_address["대구"] = isset($arr_address["대구"]) ? $arr_address["대구"] : 0;
        $arr_address["광주"] = isset($arr_address["광주"]) ? $arr_address["광주"] : 0;
        $arr_address["울산"] = isset($arr_address["울산"]) ? $arr_address["울산"] : 0;
        $arr_address["부산"] = isset($arr_address["부산"]) ? $arr_address["부산"] : 0;
        $arr_address["제주"] = isset($arr_address["제주"]) ? $arr_address["제주"] : 0;
        $arr_address["강원"] = isset($arr_address["강원"]) ? $arr_address["강원"] : 0;
        $arr_address["경북"] = isset($arr_address["경북"]) ? $arr_address["경북"] : 0;
        $arr_address["경남"] = isset($arr_address["경남"]) ? $arr_address["경남"] : 0;
        $arr_address["전북"] = isset($arr_address["전북"]) ? $arr_address["전북"] : 0;
        $arr_address["전남"] = isset($arr_address["전남"]) ? $arr_address["전남"] : 0;
        $arr_address["충북"] = isset($arr_address["충북"]) ? $arr_address["충북"] : 0;
        $arr_address["충남"] = isset($arr_address["충남"]) ? $arr_address["충남"] : 0;
       $posi1_percent = ($arr_address["서울"]/$total)*100;
       $posi2_percent = ($arr_address["경기"]/$total)*100;
       $posi3_percent = ($arr_address["인천"]/$total)*100;
       $posi4_percent = ($arr_address["대전"]/$total)*100;
       $posi5_percent = ($arr_address["대구"]/$total)*100;
       $posi6_percent = ($arr_address["광주"]/$total)*100;
       $posi7_percent = ($arr_address["울산"]/$total)*100;
       $posi8_percent = ($arr_address["부산"]/$total)*100;
       $posi9_percent = ($arr_address["제주"]/$total)*100;
       $posi10_percent = ($arr_address["강원"]/$total)*100;
       $posi11_percent = ($arr_address["경북"]/$total)*100;
       $posi12_percent = ($arr_address["경남"]/$total)*100;
       $posi13_percent = ($arr_address["전북"]/$total)*100;
       $posi14_percent = ($arr_address["전남"]/$total)*100;
       $posi15_percent = ($arr_address["충북"]/$total)*100;
       $posi16_percent = ($arr_address["충남"]/$total)*100;
       
       $posi1_percent = substr($posi1_percent,0,strpos($posi1_percent,'.')+3);
       $posi2_percent = substr($posi2_percent,0,strpos($posi2_percent,'.')+3);
       $posi3_percent = substr($posi3_percent,0,strpos($posi3_percent,'.')+3);
       $posi4_percent = substr($posi4_percent,0,strpos($posi4_percent,'.')+3);
       $posi5_percent = substr($posi5_percent,0,strpos($posi5_percent,'.')+3);
       $posi6_percent = substr($posi6_percent,0,strpos($posi6_percent,'.')+3);
       $posi7_percent = substr($posi7_percent,0,strpos($posi7_percent,'.')+3);
       $posi8_percent = substr($posi8_percent,0,strpos($posi8_percent,'.')+3);
       $posi9_percent = substr($posi9_percent,0,strpos($posi9_percent,'.')+3);
       $posi10_percent = substr($posi10_percent,0,strpos($posi10_percent,'.')+3);
       $posi11_percent = substr($posi11_percent,0,strpos($posi11_percent,'.')+3);
       $posi12_percent = substr($posi12_percent,0,strpos($posi12_percent,'.')+3);
       $posi13_percent = substr($posi13_percent,0,strpos($posi13_percent,'.')+3);
       $posi14_percent = substr($posi14_percent,0,strpos($posi14_percent,'.')+3);
       $posi15_percent = substr($posi15_percent,0,strpos($posi15_percent,'.')+3);
       $posi16_percent = substr($posi16_percent,0,strpos($posi16_percent,'.')+3);
       
       
    ?>
      <tr align="center" class="t_tr">
        <td>서울</td>
        <td><?=$arr_address["서울"]?></td>
        <td><?=$posi1_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi1_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>경기</td>
        <td><?=$arr_address["경기"]?></td>
        <td><?=$posi2_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi2_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>인천</td>
        <td><?=$arr_address["인천"]?></td>
        <td><?=$posi3_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi3_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>대전</td>
        <td><?=$arr_address["대전"]?></td>
        <td><?=$posi4_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi4_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>대구</td>
        <td><?=$arr_address["대구"]?></td>
        <td><?=$posi5_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi5_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>광주</td>
        <td><?=$arr_address["광주"]?></td>
        <td><?=$posi6_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi6_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>울산</td>
        <td><?=$arr_address["울산"]?></td>
        <td><?=$posi7_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi7_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>부산</td>
        <td><?=$arr_address["부산"]?></td>
        <td><?=$posi8_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi8_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>제주</td>
        <td><?=$arr_address["제주"]?></td>
        <td><?=$posi9_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi9_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>강원</td>
        <td><?=$arr_address["강원"]?></td>
        <td><?=$posi10_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi10_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>경북</td>
        <td><?=$arr_address["경북"]?></td>
        <td><?=$posi11_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi11_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>경남</td>
        <td><?=$arr_address["경남"]?></td>
        <td><?=$posi12_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi12_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>전북</td>
        <td><?=$arr_address["전북"]?></td>
        <td><?=$posi13_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi13_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>전남</td>
        <td><?=$arr_address["전남"]?></td>
        <td><?=$posi14_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi14_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>충북</td>
        <td><?=$arr_address["충북"]?></td>
        <td><?=$posi15_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi15_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      <tr align="center" height="35">
        <td>충남</td>
        <td><?=$arr_address["충남"]?></td>
        <td><?=$posi16_percent?>%</td>
        <td style="text-align:left;"><img src="../image/mark_bar.gif" width="<?=$posi16_percent*2?>" height="10"></td>
      </tr>
      <tr><td colspan="4" class="t_line"></td></tr>
      </tbody>
    </table>

<? include "../footer.php"; ?>