<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
$level_info = level_info();

// 페이지 파라메터 (검색조건이 변하지 않도록)
if(!isset($detailsearch)) $detailsearch = "";
if(!isset($level)) $level = "";
if(!isset($searchopt)) $searchopt = "";
if(!isset($searchkey)) $searchkey = "";
if(!isset($s_birthday)) $s_birthday = "";
if(!isset($s_memorial)) $s_memorial = "";
if(!isset($s_age)) $s_age = "";
if(!isset($s_address)) $s_address = "";
if(!isset($s_job)) $s_job = "";
if(!isset($s_marriage)) $s_marriage = "";
if(!isset($prev_year)) $prev_year = "";
if(!isset($prev_month)) $prev_month = "";
if(!isset($prev_day)) $prev_day = "";
if(!isset($next_year)) $next_year = "";
if(!isset($next_month)) $next_month = "";
if(!isset($next_day)) $next_day = "";
if(!isset($page)) $page = "";
//------------------------------------------------------------------------------------------------------------------------------------
$param = "detailsearch=$detailsearch&level=$level&searchopt=$searchopt&searchkey=$searchkey&s_birthday=$s_birthday&s_memorial=$s_memorial&s_age=$s_age";
$param .= "&s_address=$s_address&s_job=$s_job&s_marriage=$s_marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//------------------------------------------------------------------------------------------------------------------------------------

?>

<script language="JavaScript" type="text/javascript">
<!--

// 주문상태 변경
function chgStatus(status){
   document.searchForm.status.value = status;
   document.searchForm.submit();
}

//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
function selectAll(){
	
	var i; 	
	
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].elements['id'] != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택회원 삭제
function userDelete(){

	var i;
	var seluser = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					seluser = seluser + document.forms[i].elements['id'].value + "|";
				}
			}
	}
	
	if(seluser == ""){
		alert("삭제할 회원을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 회원을 정말 삭제하시겠습니까?")){
			document.location = "member_save.php?mode=deluser&seluser=" + seluser;
		}
	}
}


// 상세검색
function showDetailsearch(){
   
   if(detailsearch.style.display == ''){
    detailsearch.style.display = 'none';
    document.searchForm.detailsearch.value = "none";
  }else{
    detailsearch.style.display='';
    document.searchForm.detailsearch.value = "show";
  }
  
}

// 회원정보 엑셀다운
function excelDown(){
	var url = "member_excel.php?<?=$param?>";
	window.open(url,"excelDown","height=300, width=600, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 회원정보 엑셀입력
function excelUp() {
	var url = "member_excel_up.php";
	window.open(url,"excelUp","height=400, width=560, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 고객 메일발송
function sendEmail(seluser){
   
   if(seluser == ""){
      var i;
   	var seluser = "";
   	for(i=0;i<document.forms.length;i++){
   		if(document.forms[i].elements['id'] != null){
   			if(document.forms[i].select_checkbox){
   				if(document.forms[i].select_checkbox.checked)
   					seluser = seluser + document.forms[i].name.value + ":" + document.forms[i].email.value + ",";
   				}
   			}
   	}
	}
	
   if(seluser == ""){
		alert("이메일 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "send_email.php?seluser=" + seluser;
	   window.open(url,"sendEmail","height=700, width=900, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
	
}

// 고객 sms발송
function sendSms(seluser){
   
   if(seluser == ""){
      var i;
   	var seluser = "";
   	for(i=0;i<document.forms.length;i++){
   		if(document.forms[i].elements['id'] != null){
   			if(document.forms[i].select_checkbox){
   				if(document.forms[i].select_checkbox.checked)
   					seluser = seluser + document.forms[i].hphone.value + ",";
   				}
   			}
   	}
	}
	
   if(seluser == ""){
		alert("SMS 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "send_sms.php?seluser=" + seluser;
	   window.open(url,"sendSms","height=500, width=500, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
	
}

// 회원별 구매내역
function orderList(id,name){
	var url = "member_order.php?id=" + id + "&name=" + name;
	window.open(url,"orderList","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}


// 회원별 적립금내역
function reserveList(id,name){
	var url = "member_reserve.php?id=" + id + "&name=" + name;
	window.open(url,"reserveList","height=400, width=600, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">회원목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">회원정보를 관리합니다.</td>
	    </tr>
	  </table>			
	  <br>	  
            
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="detailsearch" value="<?=$detailsearch?>">
      <tr>
        <td bgcolor="ffffff">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
        <td class="t_name">&nbsp; 가입일</td>
        <td class="t_value">
					<select name="prev_year">
					<?
					if(!isset($next_year) || empty($next_year)) $next_year = date("Y");
					if(!isset($next_month) || empty($next_month)) $next_month = date("n");
					if(!isset($next_day) || empty($next_day)) $next_day = date("d");
					
					if(!isset($prev_year) || empty($prev_year)) $prev_year = date("Y");
					if(!isset($prev_month) || empty($prev_month)) $prev_month = date("n");
					if(!isset($prev_day) || empty($prev_day)) $prev_day = date("d");
					
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
        <td width="15%" class="t_name">조건</td>
        <td width="85%" class="t_value">
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
          <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
          <input type="submit" value="검색" class="AW-btn-search" />
          <input type="button" onClick="showDetailsearch()" class="AW-btn" value="상세검색" />
        </td>
        </tr>
        </table>
        <table><tr><td></td></tr></table>
        <div id='detailsearch' style="display: <? if(empty($detailsearch)) echo "none"; else echo $detailsearch; ?>">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
        <td width="15%" class="t_name">&nbsp; 오늘 생일</td>
        <td width="85%" class="t_value"><input type="checkbox" name="s_birthday" value="Y" <? if($s_birthday == "Y") echo "checked"; ?>>예</td>
        </tr>
        <tr>
	        <td class="t_name">&nbsp; 오늘 결혼기념일</td>
	        <td class="t_value"><input type="checkbox" name="s_memorial" value="Y" <? if($s_memorial == "Y") echo "checked"; ?>>예</td>
        <tr>
	        <td class="t_name">&nbsp; 나이</td>
	        <td class="t_value">
	           <select name="s_age">
		         <option value="">연령대를 선택하세요
		         <option value="10" <? if($s_age == "10") echo "selected"; ?>>10세 ~ 19세
		         <option value="20" <? if($s_age == "20") echo "selected"; ?>>20세 ~ 29세
		         <option value="30" <? if($s_age == "30") echo "selected"; ?>>30세 ~ 39세
		         <option value="40" <? if($s_age == "40") echo "selected"; ?>>40세 ~ 49세
		         <option value="50" <? if($s_age == "50") echo "selected"; ?>>50세 ~ 59세
		         <option value="60" <? if($s_age == "60") echo "selected"; ?>>60세 ~ 69세
		         </select>
	        </td>
        </tr>
        <tr>
	        <td class="t_name">&nbsp; 지역</td>
	        <td class="t_value">
	        	<select name="s_address">
		      	<option value="">각 시단위를 선택하세요.</option>
						<option value="서울" <? if($s_address == "서울") echo "selected"; ?>>서울</option>
						<option value="부산" <? if($s_address == "부산") echo "selected"; ?>>부산</option>
						<option value="전북" <? if($s_address == "전북") echo "selected"; ?>>전북</option>
						<option value="대구" <? if($s_address == "대구") echo "selected"; ?>>대구</option>
						<option value="인천" <? if($s_address == "인천") echo "selected"; ?>>인천</option>
						<option value="광주" <? if($s_address == "광주") echo "selected"; ?>>광주</option>
						<option value="대전" <? if($s_address == "대전") echo "selected"; ?>>대전</option>
						<option value="울산" <? if($s_address == "울산") echo "selected"; ?>>울산</option>
						<option value="강원" <? if($s_address == "강원") echo "selected"; ?>>강원</option>
						<option value="경기" <? if($s_address == "경기") echo "selected"; ?>>경기</option>
						<option value="경남" <? if($s_address == "경남") echo "selected"; ?>>경남</option>
						<option value="경북" <? if($s_address == "경북") echo "selected"; ?>>경북</option>
						<option value="전남" <? if($s_address == "전남") echo "selected"; ?>>전남</option>
						<option value="제주" <? if($s_address == "제주") echo "selected"; ?>>제주</option>
						<option value="충남" <? if($s_address == "충남") echo "selected"; ?>>충남</option>
						<option value="충북" <? if($s_address == "충북") echo "selected"; ?>>충북</option>
				   </select>
			 		</td>
			 	</tr>
        <tr>
	        <td class="t_name">&nbsp; 직업</td>
	        <td class="t_value">
						<select name="s_job" class="optionjoin">
						<option value="">직업을 선택하세요.</option>
						<option value="00" <? if($s_job == "00") echo "selected"; ?>>무직</option>
						<option value="10" <? if($s_job == "10") echo "selected"; ?>>학생</option>
						<option value="30" <? if($s_job == "30") echo "selected"; ?>>컴퓨터/인터넷</option>
						<option value="50" <? if($s_job == "50") echo "selected"; ?>>언론</option>
						<option value="70" <? if($s_job == "70") echo "selected"; ?>>공무원</option>
						<option value="90" <? if($s_job == "90") echo "selected"; ?>>군인</option>
						<option value="A0" <? if($s_job == "A0") echo "selected"; ?>>서비스업</option>
						<option value="C0" <? if($s_job == "C0") echo "selected"; ?>>교육</option>
						<option value="E0" <? if($s_job == "E0") echo "selected"; ?>>금융/증권/보험업</option>
						<option value="G0" <? if($s_job == "G0") echo "selected"; ?>>유통업</option>
						<option value="I0" <? if($s_job == "I0") echo "selected"; ?>>예술</option>
						<option value="K0" <? if($s_job == "K0") echo "selected"; ?>>의료</option>
						<option value="M0" <? if($s_job == "M0") echo "selected"; ?>>법률</option>
						<option value="O0" <? if($s_job == "O0") echo "selected"; ?>>건설업</option>
						<option value="Q0" <? if($s_job == "Q0") echo "selected"; ?>>제조업</option>
						<option value="S0" <? if($s_job == "S0") echo "selected"; ?>>부동산업</option>
						<option value="U0" <? if($s_job == "U0") echo "selected"; ?>>운송업</option>
						<option value="W0" <? if($s_job == "W0") echo "selected"; ?>>농/수/임/광산업</option>
						<option value="Y0" <? if($s_job == "Y0") echo "selected"; ?>>가사</option>
						<option value="z0" <? if($s_job == "z0") echo "selected"; ?>>기타</option>
						</select>
	      	</td>
	      </tr>
        <tr>
        <td class="t_name">&nbsp; 결혼여부</td>
        <td class="t_value">
          <input type="radio" name="s_marriage" value="N" <? if($s_marriage == "N") echo "checked"; ?>>미혼
          <input type="radio" name="s_marriage" value="Y" <? if($s_marriage == "Y") echo "checked"; ?>>기혼
        </td>
      	</tr>
        </table>
        </div>
        
      	</td>
      </tr>
    </form>
    </table>
    
    <br>
    <?
		$today = date('n-d');
		$toyear = date('Y');
		
		$age_syear = (int)substr((int)$toyear-((int)$s_age+9),-2)+1;
		$age_eyear = (int)substr((int)$toyear-(int)$s_age,-2)+2;
		
		$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
		$join_edate = $next_year."-".$next_month."-".$next_day;
		
		$sql = "select count(id) as all_total from wiz_member";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_object($result);
		$all_total = $row->all_total;
     
		$sql = "select id from wiz_member wm";
		$sql .= " where wm.id != ''";
		if($level != "") $sql .= " and wm.level = '$level'";
		if($prev_year != "") $sql .= " and wm.wdate > '$join_sdate' and wm.wdate <= '$join_edate 23:59:59'";
		if($searchopt != "")   $sql .= " and wm.$searchopt like '%$searchkey%'";
		if($s_birthday == "Y") $sql .= " and wm.birthday like '%$today'";
		if($s_memorial == "Y") $sql .= " and wm.memorial like '%$today'";
		if($s_age != "")       $sql .= " and wm.resno > '$age_syear' and wm.resno < '$age_eyear'";
		if($s_address != "")   $sql .= " and wm.address like '%$s_address%'";
		if($s_job != "")       $sql .= " and wm.job = '$s_job'";
		if($s_marriage != "")  $sql .= " and wm.marriage = '$s_marriage'";
		$sql .=" order by wm.wdate desc";

  	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  	$total = mysqli_num_rows($result);

		$rows = 20;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
		$start = ((int)$page-1)*(int)$rows;
		$no = $total-$start;
		?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>총 회원수 : <b><?=$all_total?></b> , 검색 회원수 : <b><?=$total?></b></td>
        <td align="right">
        	<a onClick="excelDown();" class="AW-btn">엑셀파일저장</a>
        	<a onClick="excelUp();" class="AW-btn">엑셀회원등록</a>
        	<a onClick="document.location='member_info.php?mode=insert';" class="AW-btn">회원등록</a>
        </td>
      </tr>
      <tr><td colspan="2" height="5"></td></tr>
    </table> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="30" align="center"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
        <th align="center">번호</th>
        <th align="center">이름</th>
        <th align="center">아이디</th>
        <th align="center">휴대폰</th>
        <th align="center">이메일</th>
        <th align="center">적립금</th>
        <th align="center">주문액</th>
        <th align="center">방문수</th>
        <th align="center">가입일</th>
        <th align="center">기능</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
      </form>
			<?
			$sql = "select id,passwd,name,hphone,email,level,visit,wdate from wiz_member wm";
			$sql .= " where wm.id != ''";
			if($level != "") $sql .= " and wm.level = '$level'";
			if($prev_year != "") $sql .= " and wm.wdate > '$join_sdate' and wm.wdate <= '$join_edate 23:59:59'";
			if($searchopt != "")   $sql .= " and wm.$searchopt like '%$searchkey%'";
			if($s_birthday == "Y") $sql .= " and wm.birthday like '%$today'";
			if($s_memorial == "Y") $sql .= " and wm.memorial like '%$today'";
			if($s_age != "")       $sql .= " and wm.resno > '$age_syear' and wm.resno < '$age_eyear'";
			if($s_address != "")   $sql .= " and wm.address like '%$s_address%'";
			if($s_job != "")       $sql .= " and wm.job = '$s_job'";
			if($s_marriage != "")  $sql .= " and wm.marriage = '$s_marriage'";
			$sql .=" order by wm.wdate desc limit $start, $rows";
			
	  	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	  	
			while(($row = mysqli_fetch_object($result)) && $rows){
				
				if($shop_info->sms_use == "Y") $hphone = "<a href=javascript:sendSms('".$row->hphone."');>".$row->hphone."</a>";
				else $hphone = $row->hphone;
			?>
     	<form name="frm<?=$no?>">
      <input type="hidden" name="id" value="<?=$row->id?>">
      <input type="hidden" name="name" value="<?=$row->name?>">
      <input type="hidden" name="email" value="<?=$row->email?>">
      <input type="hidden" name="hphone" value="<?=$row->hphone?>">
      <tr class="t_tr"> 
        <td height="30" align="center"><input type="checkbox" name="select_checkbox"></td>
        <td align="center"><a href="member_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$no?></a></td>
        <td align="center"><a href="member_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$row->name?></a></td>
        <td align="center"><a href="member_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$row->id?></a></td>
        <td align="center"><?=$hphone?></td>
        <td align="center"><a href="javascript:sendEmail('<?=$row->name?>:<?=$row->email?>')"><?=$row->email?></a></td>
        <td align="center"><a href="javascript:reserveList('<?=$row->id?>','<?=$row->name?>');">보기</a></td>
        <td align="center"><a href="javascript:orderList('<?=$row->id?>','<?=$row->name?>');">보기</a></td>
        <td align="center"><?=$row->visit?></td>
        <td align="center"><?=substr($row->wdate,0,10)?> &nbsp;</td>
        <td align="center"><a onClick="document.location='member_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>';" class="AW-btn-s modify">보기</a></td>
      </tr>
      <tr><td colspan="20" class="t_line"></td></tr>
      </form>
   	<?
   		$no--;
			$rows--;
    }                  
  	if($total <= 0){
  	?>
  		<tr><td height=30 colspan=11 align=center>등록된 회원이 없습니다.</td></tr>
  		<tr><td colspan='20' class='t_line'></td></tr>
  	<?
  	}
    ?>
    </table>
    
    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
      <tr> 
        <td width="33%">
        	<a onClick="userDelete();" class="AW-btn">회원삭제</a>
        	<a onClick="sendEmail('');" class="AW-btn">이메일발송</a>
            <? if(!strcmp($shop_info->sms_use, "Y")) { ?>
        	<a onClick="sendSms('');" class="AW-btn">SMS발송</a>
            <? } ?>
        </td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
        <td width="33%"></td>
      </tr>
    </table>


<? include "../footer.php"; ?>