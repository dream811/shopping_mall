<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?

// 페이지 파라메터 (검색조건이 변하지 않도록)
//--------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&keyword=$keyword&birthday=$birthday&memorial=$memorial&age=$age";
$param .= "&address=$address&job=$job&marriage=$marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
$param .= "&level=$level&resms=$resms";
//--------------------------------------------------------------------------------------------------

?>
<script language="JavaScript" src="../webedit/WIZEditor.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function smsCheck(frm){

	if(frm.se_num.value == ""){
		alert("보내는분 번호를 입력하세요");
		frm.se_num.focus();
		return false;
	}
	if(frm.sms_msg.value == ""){
		alert("내용을 입력하세요");
		frm.sms_msg.focus();
		return false;
	}
	frm.action = "member_save.php?<?=$param?>";
	frm.method = "post";
	frm.submit();

}

function calByte(aquery){

	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}

		smsForm.sms_byte.value = tcount+"/80 bytes";

		if(tcount > 80) {
			alert("메시지내용은 80 바이트 이상 전송할 수 없습니다.");

			cutText(smsForm.sms_msg.value);

			return;
		}
	}
	if ( temp == 0 ) {

		smsForm.sms_byte.value = "0/80 bytes";

	}
}

function cutText(aquery) {

	var tmpStr;
	var temp=0;
	var onechar;
	var tcount = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(t=0; t<temp; t++){
		onechar = tmpStr.charAt(t);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}
		if(tcount > 80) {
			tmpStr = tmpStr.substring(0, t);
			break;
		}
	}

	document.smsForm.sms_msg.value = tmpStr;

	calByte(tmpStr);
}

function checkSmsmsg(){

	var tmpStr = document.smsForm.sms_msg.value;

	calByte(tmpStr);

}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">SMS발송</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">검색후 회원에게 SMS를 발송합니다.</td>
	    </tr>
	  </table>

	  <br>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
    <input type="hidden" name="tmp">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
      <tr>
        <td bgcolor="ffffff">

        <table width="100%" cellpadding="3" cellspacing="1" class="t_style" border="0">
          <tr>
            <td width="15%" class="t_name">오늘 생일</td>
            <td width="35%" class="t_value"><input type="checkbox" name="birthday" value="Y" <? if($birthday == "Y") echo "checked"; ?>>예</td>
            <td width="15%" class="t_name">오늘 결혼기념일</td>
            <td width="35%" class="t_value"><input type="checkbox" name="memorial" value="Y" <? if($memorial == "Y") echo "checked"; ?>>예</td>
          </tr>
          <tr>
            <td class="t_name">나이</td>
            <td class="t_value">
	            <select name="age">
	            <option value="">연령대를 선택하세요
	            <option value="10" <? if($age == "10") echo "selected"; ?>>10세 ~ 19세
	            <option value="20" <? if($age == "20") echo "selected"; ?>>20세 ~ 29세
	            <option value="30" <? if($age == "30") echo "selected"; ?>>30세 ~ 39세
	            <option value="40" <? if($age == "40") echo "selected"; ?>>40세 ~ 49세
	            <option value="50" <? if($age == "50") echo "selected"; ?>>50세 ~ 59세
	            <option value="60" <? if($age == "60") echo "selected"; ?>>60세 ~ 69세
	            </select>
            </td>
            <td class="t_name">지역</td>
            <td class="t_value">
	            <select name="address">
		         	<option value="">각 시단위를 선택하세요.</option>
							<option value="서울" <? if($address == "서울") echo "selected"; ?>>서울</option>
							<option value="부산" <? if($address == "부산") echo "selected"; ?>>부산</option>
							<option value="전북" <? if($address == "전북") echo "selected"; ?>>전북</option>
							<option value="대구" <? if($address == "대구") echo "selected"; ?>>대구</option>
							<option value="인천" <? if($address == "인천") echo "selected"; ?>>인천</option>
							<option value="광주" <? if($address == "광주") echo "selected"; ?>>광주</option>
							<option value="대전" <? if($address == "대전") echo "selected"; ?>>대전</option>
							<option value="울산" <? if($address == "울산") echo "selected"; ?>>울산</option>
							<option value="강원" <? if($address == "강원") echo "selected"; ?>>강원</option>
							<option value="경기" <? if($address == "경기") echo "selected"; ?>>경기</option>
							<option value="경남" <? if($address == "경남") echo "selected"; ?>>경남</option>
							<option value="경북" <? if($address == "경북") echo "selected"; ?>>경북</option>
							<option value="전남" <? if($address == "전남") echo "selected"; ?>>전남</option>
							<option value="제주" <? if($address == "제주") echo "selected"; ?>>제주</option>
							<option value="충남" <? if($address == "충남") echo "selected"; ?>>충남</option>
							<option value="충북" <? if($address == "충북") echo "selected"; ?>>충북</option>
						  </select>
            </td>
          </tr>

          <tr>
            <td class="t_name">직업</td>
            <td class="t_value">
	            <select name="job" class="optionjoin">
	            <option value="">직업을 선택하세요.</option>
	            <option value="00" <? if($job == "00") echo "selected"; ?>>무직</option>
	            <option value="10" <? if($job == "10") echo "selected"; ?>>학생</option>
	            <option value="30" <? if($job == "30") echo "selected"; ?>>컴퓨터/인터넷</option>
	            <option value="50" <? if($job == "50") echo "selected"; ?>>언론</option>
	            <option value="70" <? if($job == "70") echo "selected"; ?>>공무원</option>
	            <option value="90" <? if($job == "90") echo "selected"; ?>>군인</option>
	            <option value="A0" <? if($job == "A0") echo "selected"; ?>>서비스업</option>
	            <option value="C0" <? if($job == "C0") echo "selected"; ?>>교육</option>
	            <option value="E0" <? if($job == "E0") echo "selected"; ?>>금융/증권/보험업</option>
	            <option value="G0" <? if($job == "G0") echo "selected"; ?>>유통업</option>
	            <option value="I0" <? if($job == "I0") echo "selected"; ?>>예술</option>
	            <option value="K0" <? if($job == "K0") echo "selected"; ?>>의료</option>
	            <option value="M0" <? if($job == "M0") echo "selected"; ?>>법률</option>
	            <option value="O0" <? if($job == "O0") echo "selected"; ?>>건설업</option>
	            <option value="Q0" <? if($job == "Q0") echo "selected"; ?>>제조업</option>
	            <option value="S0" <? if($job == "S0") echo "selected"; ?>>부동산업</option>
	            <option value="U0" <? if($job == "U0") echo "selected"; ?>>운송업</option>
	            <option value="W0" <? if($job == "W0") echo "selected"; ?>>농/수/임/광산업</option>
	            <option value="Y0" <? if($job == "Y0") echo "selected"; ?>>가사</option>
	            <option value="z0" <? if($job == "z0") echo "selected"; ?>>기타</option>
	            </select>
            </td>
            <td class="t_name">결혼여부</td>
            <td class="t_value">
	            <input type="radio" name="marriage" value="N" <? if($marriage == "N") echo "checked"; ?>>미혼
	            <input type="radio" name="marriage" value="Y" <? if($marriage == "Y") echo "checked"; ?>>기혼
            </td>
          </tr>

          <tr>
            <td class="t_name">가입일</td>
            <td colspan="3" class="t_value">
            <select name="prev_year">
             <?
                if(empty($next_year)) $next_year = date("Y");
                if(empty($next_month)) $next_month = date("n");
                if(empty($next_day)) $next_day = date("d");

                for($ii=2005; $ii <= date('Y'); $ii++){
                  if($ii == $prev_year) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               년
               <select name="prev_month">
                 <?
                for($ii=1; $ii <= 12; $ii++){
                  if($ii<10) $ii = "0".$ii;
                  if($ii == $prev_month) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               월
               <select name="prev_day">
                 <?
                for($ii=1; $ii <= 31; $ii++){
                  if($ii<10) $ii = "0".$ii;
                  if($ii == $prev_day) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               일 ~
               <select name="next_year">
                 <?
                for($ii=2005; $ii <= date('Y'); $ii++){
                  if($ii == $next_year) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               년
               <select name="next_month">
                 <?
                for($ii=1; $ii <= 12; $ii++){
                  if($ii<10) $ii = "0".$ii;
                  if($ii == $next_month) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               월
               <select name="next_day">
                 <?
                for($ii=1; $ii <= 31; $ii++){
                  if($ii<10) $ii = "0".$ii;
                  if($ii == $next_day) echo "<option value=$ii selected>$ii";
                  else echo "<option value=$ii>$ii";
                }
             ?>
               </select>
               일
            </td>
          </tr>
          <tr>
            <td class="t_name">조건검색</td>
            <td colspan="3" class="t_value">
            <select name="level">
            <option value=""> 등급선택
            <?=level_list();?>
            </select>
            <script language="javascript">
            <!--
            level = document.searchForm.level;
            for(ii=0; ii<level.length; ii++){
             if(level.options[ii].value == "<?=$level?>")
               level.options[ii].selected = true;
             }
            -->
            </script>

            <select name="searchopt" class="select">
            <option value="name" <? if($searchopt == "name") echo "selected"; ?>>고객명
            <option value="id" <? if($searchopt == "id") echo "selected"; ?>>아이디
            <option value="resno" <? if($searchopt == "resno") echo "selected"; ?>>주민번호
            <option value="email" <? if($searchopt == "email") echo "selected"; ?>>이메일
            <option value="tphone" <? if($searchopt == "tphone") echo "selected"; ?>>전화번호
            <option value="hphone" <? if($searchopt == "hphone") echo "selected"; ?>>휴대폰
            </select>
	          <input type="text" name="keyword" value="<?=$keyword?>" class="input">
	          <input type="submit" value="검색" class="AW-btn-search" />
            </td>
          </tr>
          <tr>
            <td class="t_name"><font color=red><b>SMS 수신</b></font></td>
            <td colspan="3" class="t_value">
            	<input type="radio" name="resms" value="RJ" <? if($resms == "RJ" || $resms == "") echo "checked"; ?>>수신거부회원 제외
            	<input type="radio" name="resms" value="RA" <? if($resms == "RA") echo "checked"; ?>>회원전체
            </td>
        	</tr>
        </table>

      </tr>
    </form>
    </table>

    <br>
    <?
    	$sql = "select id from wiz_member";
    	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    	$all_total = mysqli_num_rows($result);

			$today = date('n-d');
			$toyear = date('Y');

			$age_syear = substr($toyear-($age+9),-2)+1;
			$age_eyear = substr($toyear-$age,-2)+2;

			$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
			$join_edate = $next_year."-".$next_month."-".$next_day;

			$sql = "select id from wiz_member where id != '' ";
			if($level != "") 		$sql .= " and level = '$level'";
			if($prev_year != "") $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
			if($searchopt != "") $sql .= " and $searchopt like '%$keyword%'";
			if($birthday == "Y") $sql .= " and birthday like '%$today'";
			if($memorial == "Y") $sql .= " and memorial like '%$today'";
			if($age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
			if($address != "")   $sql .= " and address like '%$address%'";
			if($job != "")       $sql .= " and job = '$job'";
			if($marriage != "")  $sql .= " and marriage = '$marriage'";
			if($resms == "RJ" || $resms == "")	$sql .= " and resms != 'N'";
			$sql .=" order by wdate desc";

			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$rows = 6;
			$lists = 5;
     	$page_count = ceil($total/$rows);
     	if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
     	$start = ($page-1)*$rows;
     	$no = $total-$start;
    ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>총 회원수 : <b><?=$all_total?></b> , 검색 회원수 : <b><?=$total?></b></td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th align="center">번호</th>
        <th align="center">이름</th>
        <th align="center">아이디</th>
        <th align="center">휴대폰</th>
        <th align="center">이메일</th>
        <th align="center">방문수</th>
        <th align="center">SMS수신</th>
        <th align="center">가입일</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
	   	<?
			$sql = "select id,passwd,name,hphone,email,visit,reemail,resms,wdate from wiz_member where id != '' ";
			if($level != "") 		$sql .= " and level = '$level'";
			if($prev_year != "") $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
			if($searchopt != "") $sql .= " and $searchopt like '%$keyword%'";
			if($birthday == "Y") $sql .= " and birthday like '%$today'";
			if($memorial == "Y") $sql .= " and memorial like '%$today'";
			if($age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
			if($address != "")   $sql .= " and address like '%$address%'";
			if($job != "")       $sql .= " and job = '$job'";
			if($marriage != "")  $sql .= " and marriage = '$marriage'";
			if($resms == "RJ" || $resms == "")		$sql .= " and resms != 'N'";
			$sql .=" order by wdate desc limit $start, $rows";

			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	   	while(($row = mysqli_fetch_object($result)) && $rows){
	   		if($row->resms == "Y") $row->resms = "예";
	   		else $row->resms = "아니오";
	   	?>
      <tr class="t_tr">
        <td align="center" height="30"><?=$no?></td>
        <td align="center"><?=$row->name?></td>
        <td align="center"><?=$row->id?></td>
        <td align="center"><?=$row->hphone?></td>
        <td align="center"><?=$row->email?></td>
        <td align="center"><?=$row->visit?></td>
        <td align="center"><?=$row->resms?></td>
        <td align="center"><?=substr($row->wdate,0,10)?> &nbsp;</td>
      </tr>
      <tr><td colspan="20" class="t_line"></td></tr>
   <?
   		$no--;
       $rows--;
    }

  	if($total <= 0){
  		echo "<tr><td height=30 colspan=10 align=center>등록된 회원이 없습니다.</td></tr>";
  		echo "<tr><td colspan='20' class='t_line'></td></tr>";
  	}
    ?>
    </table>

  	<table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
  		<tr><td height="5"></td></tr>
    	<tr>
    	  <td width="33%"></td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
        <td width="33%"></td>
      </tr>
    </table>

    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="smsForm" onSubmit="return smsCheck(this)">
    <input type="hidden" name="mode" value="memsms">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
            <tr>
              <td width="20%" class="t_name">보내는분 번호</td>
              <td width="80%" class="t_value" colspan="3">
              <input type="text" name="se_num" value="<?=$shop_info->shop_tel?>" size="60" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name">내용</td>
              <td class="t_value" colspan="3">
                <textarea name="sms_msg" rows="12" cols="60" class="textarea" onKeyDown="checkSmsmsg();"></textarea>
                <input type="text" name="sms_byte" size="11" style="height:14px; border: 1px solid #91FBFF; ; font-size:8pt; font-family:돋움; background-color:#91FBFF" value="0/80 bytes" onfocus="this.blur()">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    
    
<div class="AW-btn-wrap">
    <button type="submit" class="on">발송하기</button>
</div><!-- .AW-btn-wrap -->


    </form>

<? include "../footer.php"; ?>