<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";

if($exceldown != "ok"){
?>
<html>
<head>
<title>:: 회원정보 다운로드 ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function selBasic(frm){

	frm.c_id.checked = true;
	frm.c_passwd.checked = true;
	frm.c_name.checked = true;
	frm.c_resno.checked = true;
	frm.c_email.checked = true;
	frm.c_tphone.checked = true;
	frm.c_hphone.checked = true;
	frm.c_level.checked = false;
	frm.c_post.checked = true;
	frm.c_address.checked = true;
	frm.c_recom.checked = false;
	frm.c_reserve.checked = true;
	frm.c_reemail.checked = false;
	frm.c_resms.checked = false;

	frm.c_marriage.checked = false;
	frm.c_memorial.checked = false;
	frm.c_job.checked = false;
	frm.c_scholarship.checked = false;
	frm.c_birthday.checked = false;
	frm.c_consph.checked = false;

}

function selAll(frm){

	frm.c_id.checked = true;
	frm.c_passwd.checked = true;
	frm.c_name.checked = true;
	frm.c_resno.checked = true;
	frm.c_email.checked = true;
	frm.c_tphone.checked = true;
	frm.c_hphone.checked = true;
	frm.c_level.checked = true;
	frm.c_post.checked = true;
	frm.c_address.checked = true;
	frm.c_recom.checked = true;
	frm.c_reserve.checked = true;
	frm.c_reemail.checked = true;
	frm.c_resms.checked = true;

	frm.c_marriage.checked = true;
	frm.c_memorial.checked = true;
	frm.c_job.checked = true;
	frm.c_scholarship.checked = true;
	frm.c_birthday.checked = true;
	frm.c_consph.checked = true;

}
//-->
</script>
</head>

<body style="padding:10px;">
<form name="frm" action="" method="post">
<input type="hidden" name="exceldown" value="ok">

<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="keyword" value="<?=$keyword?>">
<input type="hidden" name="birthday" value="<?=$birthday?>">
<input type="hidden" name="memorial" value="<?=$memorial?>">
<input type="hidden" name="age" value="<?=$age?>">
<input type="hidden" name="address" value="<?=$address?>">
<input type="hidden" name="job" value="<?=$job?>">
<input type="hidden" name="marriage" value="<?=$marriage?>">

<input type="hidden" name="prev_year" value="<?=$prev_year?>">
<input type="hidden" name="prev_month" value="<?=$prev_month?>">
<input type="hidden" name="prev_day" value="<?=$prev_day?>">

<input type="hidden" name="next_year" value="<?=$next_year?>">
<input type="hidden" name="next_month" value="<?=$next_month?>">
<input type="hidden" name="next_day" value="<?=$next_day?>">

<table width="100%" border="0" cellpadding="3" cellspacing="6" class="t_style" align="center">

  <tr>
    <td bgcolor="ffffff">
    <table><tr><td></td></tr></table>
     <table cellspacing="2" cellpadding="0" border="0">
       <tr>
        <td width="100"><font color="2369C9"><b>선택구분</b></font></td>
        <td><input type="radio" name="sel_gubun" onClick="selBasic(this.form);" checked><font color="red"><b>기본선택</b></font></td>
		  <td><input type="radio" name="sel_gubun" onClick="selAll(this.form);"><font color="red"><b>전체선택</b></font></td>
		  <td></td>
		  <td></td>
		</tr>
		<tr><td height="6"></td></tr>
      <tr>
        <td width="80"><font color="2369C9"><b>기본정보</b></font></td>
        <td width="100"><input type="checkbox" name="c_id" value="Y" checked>아이디</td>
		  <td width="100"><input type="checkbox" name="c_passwd" value="Y" checked>비밀번호</td>
		  <td width="100"><input type="checkbox" name="c_name" value="Y" checked>이름</td>
		  <td width="100"><input type="checkbox" name="c_resno" value="Y" checked>주민번호</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="c_email" value="Y" checked>이메일</td>
		  <td><input type="checkbox" name="c_tphone" value="Y" checked>전화번호</td>
		  <td><input type="checkbox" name="c_hphone" value="Y" checked>휴대폰</td>
		  <td><input type="checkbox" name="c_level" value="Y">회원등급</td>
		</tr>
       <tr>
		  <td></td>
		  <td><input type="checkbox" name="c_post" value="Y" checked>우편번호</td>
		  <td><input type="checkbox" name="c_address" value="Y" checked>주소</td>
		  <td><input type="checkbox" name="c_recom" value="Y">추천인</td>
		  <td><input type="checkbox" name="c_reserve" value="Y" checked>적립금</td>
		</tr>
		<tr>
		  <td></td>
		  <td><input type="checkbox" name="c_reemail" value="Y">이메일 수신</td>
		  <td><input type="checkbox" name="c_resms" value="Y">SMS 수신</td>
		  <td></td>
		  <td></td>
		</tr>
	   <tr><td height="6"></td></tr>
		<tr>
		   <td><font color="2369C9"><b>기타정보</b></font></td>
			<td><input type="checkbox" name="c_marriage" value="Y">결혼 여부</td>
			<td><input type="checkbox" name="c_memorial" value="Y">결혼기념일</td>
			<td><input type="checkbox" name="c_job" value="Y">직업</td>
			<td><input type="checkbox" name="c_scholarship" value="Y">학력</td>
		</tr>
		<tr>
		  <td></td>
			<td><input type="checkbox" name="c_birthday" value="Y">생년월일</td>
			<td><input type="checkbox" name="c_consph" value="Y">관심분야</td>
			<td></td>
			<td></td>
		</tr>
    </table>
   </td>
 </tr>
</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="self.close();">닫기</a>
</div><!-- .AW-btn-wrap -->

</form>

</td></tr></table>
</body>
</html>
<?
}else{

	$level_info = level_info();

	$filename = "회원정보[".date('Ymd')."].xls";

	header( "Content-type: application/vnd.ms-excel" );
	header( "Content-Disposition: attachment; filename=$filename" );
	header( "Content-Description: PHP4 Generated Data" );

	$sql = "select id from wiz_member";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$all_total = mysqli_num_rows($result);


	$today = date('n-d');
	$toyear = date('Y');

	$age_syear = substr($toyear-($s_age+9),-2)+1;
	$age_eyear = substr($toyear-$s_age,-2)+2;

	$join_sdate = $prev_year."-".$prev_month."-".$prev_day;
	$join_edate = $next_year."-".$next_month."-".$next_day;


	$sql = "select * from wiz_member where id != ''";
	if($level != "") $sql .= " and level = '$level'";
	if($prev_year != "") $sql .= " and wdate > '$join_sdate' and wdate <= '$join_edate 23:59:59'";
	if($searchopt != "")   $sql .= " and $searchopt like '%$searchkey%'";
	if($s_birthday == "Y") $sql .= " and birthday like '%$today'";
	if($s_memorial == "Y") $sql .= " and memorial like '%$today'";
	if($s_age != "")       $sql .= " and resno > '$age_syear' and resno < '$age_eyear'";
	if($s_address != "")   $sql .= " and address like '%$s_address%'";
	if($s_job != "")       $sql .= " and job = '$s_job'";
	if($s_marriage != "")  $sql .= " and marriage = '$s_marriage'";
	$sql .=" order by wdate desc";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);


	if($c_id == "Y") $excel_title .= "아이디	";
	if($c_passwd == "Y") $excel_title .= "비밀번호	";
	if($c_name == "Y") $excel_title .= "이름	";
	if($c_resno == "Y") $excel_title .= "주민번호	";
	if($c_email == "Y") $excel_title .= "이메일	";
	if($c_tphone == "Y") $excel_title .= "전화번호	";
	if($c_hphone == "Y") $excel_title .= "휴대폰	";
	if($c_level == "Y") $excel_title .= "회원등급	";
	if($c_post == "Y") $excel_title .= "우편번호	";
	if($c_address == "Y") $excel_title .= "주소	";
	if($c_recom == "Y") $excel_title .= "추천인	";
	if($c_reserve == "Y") $excel_title .= "적립금	";
	if($c_reemail == "Y") $excel_title .= "이메일 수신	";
	if($c_resms == "Y") $excel_title .= "SMS수신	";

	if($c_marriage == "Y") $excel_title .=  "결혼여부	";
	if($c_memorial == "Y") $excel_title .=  "결혼기념일	";
	if($c_job == "Y") $excel_title .=  "직업	";
	if($c_scholarship == "Y") $excel_title .=  "학력	";
	if($c_birthday == "Y") $excel_title .=  "생년월일	";
	if($c_consph == "Y") $excel_title .=  "관심분야	";

	echo $excel_title."\n";


	while($row = mysqli_fetch_object($result)){

		$re_sql = "select sum(reserve) as reserve from wiz_reserve where memid='$row->id'";
		$re_result = mysqli_query($connect, $re_sql);
		$re_row = mysqli_fetch_object($re_result);
		$reserve = $re_row->reserve;

		$level = $level_info[$row->level][name];
		$excel_data = "";

		$consph = "";
		$job = "";
		$scholarship = "";

    $arrconsph = explode("/",$row->consph);

     for($ii=0; $ii<count($arrconsph); $ii++){

     		$tmpconsph = "";

	     	switch($arrconsph[$ii]) {
	     		case "01" : $tmpconsph = "건강"; break;
	     		case "02" : $tmpconsph = "문화/예술"; break;
	     		case "03" : $tmpconsph = "경제"; break;
	     		case "04" : $tmpconsph = "연예/오락"; break;
	     		case "05" : $tmpconsph = "뉴스"; break;
	     		case "06" : $tmpconsph = "여행/레저"; break;
	     		case "07" : $tmpconsph = "생활"; break;
	     		case "08" : $tmpconsph = "스포츠"; break;
	     		case "09" : $tmpconsph = "교육"; break;
	     		case "10" : $tmpconsph = "컴퓨터"; break;
	     		case "11" : $tmpconsph = "학문"; break;
	     	}

	     	if(!empty($tmpconsph)) $consph .= $tmpconsph.",";

     }

    switch($row->job) {
    	case "00" : $job = "무직"; break;
    	case "10" : $job = "학생"; break;
    	case "30" : $job = "컴퓨터/인터넷"; break;
    	case "50" : $job = "언론"; break;
    	case "70" : $job = "공무원"; break;
    	case "90" : $job = "군인"; break;
    	case "A0" : $job = "서비스업"; break;
    	case "C0" : $job = "교육"; break;
    	case "E0" : $job = "금융/증권/보험업"; break;
    	case "G0" : $job = "유통업"; break;
    	case "I0" : $job = "예술"; break;
    	case "K0" : $job = "의료"; break;
    	case "M0" : $job = "법률"; break;
    	case "O0" : $job = "건설업"; break;
    	case "Q0" : $job = "제조업"; break;
    	case "S0" : $job = "부동산업"; break;
    	case "U0" : $job = "운송업"; break;
    	case "W0" : $job = "농/수/임/광산업"; break;
    	case "Y0" : $job = "가사"; break;
    	case "z0" : $job = "기타"; break;
    }

    switch($row->scholarship) {
    	case "0" : $scholarship = "없음"; break;
    	case "1" : $scholarship = "초등학교재학"; break;
    	case "2" : $scholarship = "초등학교졸업"; break;
    	case "4" : $scholarship = "중학교재학"; break;
    	case "6" : $scholarship = "중학교졸업"; break;
    	case "7" : $scholarship = "고등학교재학"; break;
    	case "9" : $scholarship = "고등학교졸업"; break;
    	case "H" : $scholarship = "대학교재학"; break;
    	case "J" : $scholarship = "대학교졸업"; break;
    	case "O" : $scholarship = "대학원재학"; break;
    	case "Z" : $scholarship = "대학원졸업이상"; break;
    }

		if($c_id == "Y") $excel_data .= $row->id."	";
		if($c_passwd == "Y") $excel_data .= $row->passwd."	";
		if($c_name == "Y") $excel_data .= $row->name."	";
		if($c_resno == "Y") $excel_data .= $row->resno."	";
		if($c_email == "Y") $excel_data .= $row->email."	";
		if($c_tphone == "Y") $excel_data .= $row->tphone."	";
		if($c_hphone == "Y") $excel_data .= $row->hphone."	";
		if($c_level == "Y") $excel_data .= $level."	";
		if($c_post == "Y") $excel_data .= $row->post."	";
		if($c_address == "Y") $excel_data .= $row->address." ".$row->address2."	";
		if($c_recom == "Y") $excel_data .= $row->recom."	";
		if($c_reserve == "Y") $excel_data .= $reserve."	";
		if($c_reemail == "Y") $excel_data .= $row->reemail."	";
		if($c_resms == "Y") $excel_data .= $row->resms."	";

		if($c_marriage == "Y") $excel_data .= $row->marriage."	";
		if($c_memorial == "Y") $excel_data .= $row->memorial."	";
		if($c_job == "Y") $excel_data .= $job."	";
		if($c_scholarship == "Y") $excel_data .= $scholarship."	";
		if($c_birthday == "Y") $excel_data .= $row->birthday."	";
		if($c_consph == "Y") $excel_data .= $consph."	";

		echo $excel_data."\n";

	}

}
?>