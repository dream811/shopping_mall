<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="javascript">
<!--
function delConnect(){
	if(confirm("접속경로분석 모든 데이타가 삭제됩니다. 초기화 하시겠습니까?")){
		document.location = 'connect_save.php?mode=delrefer';
	}
}
function openParameter(){
	var url = "connect_param.php";
	window.open(url,"orderList","height=350, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">접속경로분석</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">사이트 접속 경로를 분석합니다.</td>
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
          <input type="radio" name="analy_type" value="RA" onClick="this.form.search_engin.value='';this.form.submit();" <? if($analy_type == "RA" || $analy_type == "") echo "checked"; ?>>접속경로
          <input type="radio" name="analy_type" value="RB" onClick="this.form.search_engin.value='';this.form.submit();" <? if($analy_type == "RB") echo "checked"; ?>>검색엔진,링크사이트
          <input type="radio" name="analy_type" value="RC" onClick="this.form.search_engin.value='';this.form.submit();" <? if($analy_type == "RC") echo "checked"; ?>>검색키워드
          　<input type="button" onClick="openParameter();" class="AW-btn" value="검색파라메터 설정" />
          </td>
          </tr>
          <tr>
          <td class="t_name">&nbsp; 검색엔진</td>
          <td class="t_value">
            <select name="search_engin">
            <option value="">:: 검색엔진 선택 ::
            <option value="yahoo" <? if($search_engin == "yahoo") echo "selected"; ?>>Yahoo
            <option value="naver" <? if($search_engin == "naver") echo "selected"; ?>>Naver
            <option value="empas" <? if($search_engin == "empas") echo "selected"; ?>>Empas
            <option value="daum" <? if($search_engin == "daum") echo "selected"; ?>>Daum
            <option value="msn" <? if($search_engin == "msn") echo "selected"; ?>>Msn
            <option value="google" <? if($search_engin == "google") echo "selected"; ?>>Google
            <option value="nate" <? if($search_engin == "nate") echo "selected"; ?>>Nate
            <option value="korea" <? if($search_engin == "korea") echo "selected"; ?>>Korea.com
            <option value="dreamwiz" <? if($search_engin == "dreamwiz") echo "selected"; ?>>DreamWiz
            <option value="netian" <? if($search_engin == "netian") echo "selected"; ?>>Netian
            </select>
            <input type="submit" value="검색" class="AW-btn-search" />
            <input type="button" class="AW-btn" onClick="delConnect();" value="접속경로분석 초기화" />
          </td>
          </tr>
          </table>
          </td>
        </tr>
      </form>
      </table>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr><td></td></tr>
      </table>
      <?

      if(!empty($prev_year)){
         $prev_period = $prev_year."".$prev_month."".$prev_day."00";
         $next_period = $next_year."".$next_month."".$next_day."24";
         $period_sql = " where time >= '$prev_period' and time <= '$next_period' ";
      }

		// 접속시간별
		if($analy_type == "RA" || $analy_type == ""){

			$sql = "select sum(cnt) as total_cnt from wiz_conrefer";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row = mysqli_fetch_object($result);
			$total_cnt = $row->total_cnt;

			echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' >\n";
			echo "	<tr><td class='t_rd' colspan='20'></td></tr>";
			echo "  <tr class='t_th'> \n";
			echo "    <th width='7%'>번호</th>";
			echo "    <th width='73%'>방문경로</th>\n";
			echo "    <th width='10%'>접속자수</th>\n";
			echo "    <th width='10%'>비율</th>\n";
			echo "  </tr>\n";
			echo "	<tr><td class='t_rd' colspan='20'></td></tr>";

			$sql = "select cnt from wiz_conrefer where host like '%$search_engin%' order by cnt desc";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$lists = 5;
			$rows = 20;
			if(empty($page)) $page = 1;
			$total = mysqli_num_rows($result);
			$page_count = ceil($total/$rows);
			$start = ($page-1)*$rows;
			$no = $total-$start;

			$sql = "select * from wiz_conrefer where host like '%$search_engin%' order by cnt desc limit $start, $rows";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while(($row = mysqli_fetch_object($result)) && $rows){

				$percent = ceil(($row->cnt/$total_cnt)*100);

				if(empty($row->referer)) $row->referer = "즐겨찾기나 직접방문";

            echo "<tr class=t_tr> \n";
            echo "  <td align='center' height='30'>$no</td>";
            echo "  <td style='word-break:break-all;'><a href='$row->referer' target='_blank'>$row->referer</a></td>\n";
            echo "  <td align='center'>".$row->cnt."</td>\n";
            echo "  <td align='center'>".$percent."%</td>\n";
            echo "</tr>\n";
            echo "<tr><td colspan='20' class='t_line'></td></tr>\n";

      		$no--;
            $rows--;
        }
        if($total <= 0){
    		 echo "<tr><td height='30' colspan=10 align=center>접속경로 정보가 없습니다.</td></tr>";
    		 echo "<tr><td colspan='20' class='t_line'></td></tr>\n";
    	}
        echo "</table>\n";

      }else if($analy_type == "RB"){

      	$sql = "select sum(cnt) as total_cnt from wiz_conrefer where referer <> ''";
      	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      	$row = mysqli_fetch_object($result);
      	$total_cnt = $row->total_cnt;

				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>";
				echo "  <tr class='t_th'> \n";
				echo "    <th width='7%'>번호</th>";
				echo "    <th width='43%'>검색엔진</th>\n";
				echo "    <th width='10%'>접속자수</th>\n";
				echo "    <th width='10%'>비율</th>\n";
				echo "    <th width='30%'>그래프</th>\n";
				echo "  </tr>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>";

         $sql = "select sum(cnt) as cnt from wiz_conrefer where host <> '' and host like '%$search_engin%' group by host order by cnt desc";
         $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

         $lists = 5;
         $rows = 20;
         $total = mysqli_num_rows($result);
         $page_count = ceil($total/$rows);
         if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
         $start = ($page-1)*$rows;
         $no = $total-$start;

         $sql = "select sum(cnt) as cnt, host from wiz_conrefer where host <> '' and host like '%$search_engin%' group by host order by cnt desc limit $start, $rows";
         $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

       	while(($row = mysqli_fetch_object($result)) && $rows){

         	if(($no%2) == 0) $bgcolor="#ffffff";
         	else $bgcolor="#F3F3F3";

         	$percent = ceil(($row->cnt/$total_cnt)*100);

            echo "<tr class=t_tr> \n";
            echo "  <td align='center' height='30'>$no</td>";
            echo "  <td><a href='http://$row->host' target='_blank'>$row->host</a></td>\n";
            echo "  <td align='center'>$row->cnt</td>\n";
            echo "  <td align='center'>".$percent."%</td>\n";
            echo "  <td><img src='../image/mark_bar.gif' width='".($percent*2)."' height='10'></td>\n";
            echo "</tr>\n";
            echo "<tr><td colspan='20' class='t_line'></td></tr>\n";

      		$no--;
          $rows--;
        }
        if($total <= 0){
    		 echo "<tr><td height='30' colspan=10 align=center>접속경로 정보가 없습니다.</td></tr>";
    		 echo "<tr><td colspan='20' class='t_line'></td></tr>\n";
    	  }
        echo "</table>\n";

      }else if($analy_type == "RC"){

			// 분석할 파라메터 가져오기
       	$sql = "select con_parameter from wiz_operinfo";
       	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	   		$row = mysqli_fetch_object($result);
	   		$parameter = explode(",",$row->con_parameter);

      	$sql = "select sum(cnt) as total_cnt from wiz_conrefer where referer <> ''";
      	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      	$row = mysqli_fetch_object($result);
      	$total_cnt = $row->total_cnt;

				echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>";
				echo "  <tr class='t_th'> \n";
				echo "    <th width='7%'>번호</th>";
				echo "    <th width='53%'>키워드</th>\n";
				echo "    <th width='10%'>접속자수</th>\n";
				echo "    <th width='10%'>비율</th>\n";
				echo "    <th width='20%'>그래프</th>\n";
				echo "  </tr>\n";
				echo "	<tr><td class='t_rd' colspan='20'></td></tr>";

      	$sql = "select * from wiz_conrefer where host like '%$search_engin%' order by cnt desc";
      	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      	$key_list_tmp = Array();
		$no = 0;
      	while($row = mysqli_fetch_object($result)){

      		if($row->referer != ""){

      			for($ii=0; $ii < count($parameter) && $parameter[$ii] != ""; $ii++){

         			$key_start = strpos($row->referer, $parameter[$ii]."=");
         			if($key_start > 0){
         				$key_start = $key_start + strlen($parameter[$ii]."=");
         				$key_end =  strpos($row->referer, "&", $key_start);
         				if($key_end <= 0) $key_end = strlen($row->referer);

         				$keyword = substr($row->referer, $key_start, $key_end-$key_start);
         				$keyword = str_replace("%u", "%", $keyword);
						$keyword = urldecode($keyword);
         				$keyword = str_conv($keyword, "EUC-KR");

         				$key_list_tmp[$no][name] = $keyword;
         				$key_list_tmp[$no]['cnt'] = $row->cnt;

      					$no++;
         			}

      			}

      		}


      	}

      	if(count($key_list_tmp) > 1) sort($key_list_tmp);

      	$key_name_tmp = "";
      	$key_cnt_tmp = 0;
      	$no = -1;

      	for($ii=0; $ii < count($key_list_tmp); $ii++){

      		if($key_name_tmp != $key_list_tmp[$ii][name]){
      			$no++;
      			$key_name_tmp = $key_list_tmp[$ii][name];
      			$key_list[$no]['cnt'] = $key_list_tmp[$ii]['cnt'];
      			$key_list[$no][name] = $key_list_tmp[$ii][name];
      		}else{
      			$key_list[$no]['cnt'] += $key_list_tmp[$ii]['cnt'];
      		}
      	}

      	if(count($key_list) > 0) rsort($key_list);
      	$no = count($key_list);

      	$lists = 5;
         $rows = 20;
         if(empty($page)) $page = 1;
         $total = count($key_list);
         $page_count = ceil($total/$rows);
         $start = ($page-1)*$rows;
         $no = $total-$start;

         $cnt = 0;

         if($total_cnt > 0){

      	for($ii=$start; $ii < count($key_list) && $rows > 0; $ii++){

      		if(!empty($key_list[$ii][name])){

      			$percent = ceil(($key_list[$ii]['cnt']/$total_cnt)*100);

      			echo "<tr class=t_tr> \n";
            	echo "  <td align='center'>$no</td>";
            	echo "  <td height='30'>".$key_list[$ii][name]."</td>\n";
            	echo "  <td align='center'>".$key_list[$ii]['cnt']."</td>\n";
            	echo "  <td align='center'>".$percent."%</td>\n";
            	echo "  <td><img src='../image/mark_bar.gif' width='".($percent*2)."' height='10'></td>\n";
            	echo "</tr>\n";
            	echo "<tr><td colspan='20' class='t_line'></td></tr>\n";

            	$cnt++;
            }

            $rows--;
            $no--;
      	}

      	}

      	if($cnt <= 0){
    		  echo "<tr><td height='30' colspan=10 align=center>접속경로 정보가 없습니다.</td></tr>";
    		  echo "<tr><td colspan='20' class='t_line'></td></tr>\n";
    	   }
         echo "</table>\n";

      }
   	?>
      <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td></td>
        </tr>
      </table>
	  <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
      	<tr>
          <td width="33%"></td>
          <td width="33%"><? print_pagelist($page, $lists, $page_count, "&analy_type=$analy_type&search_engin=$search_engin"); ?></td>
          <td width="33%" align="right"></td>
        </tr>
      </table>


<? include "../footer.php"; ?>