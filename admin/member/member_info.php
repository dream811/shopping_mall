<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "searchopt=$searchopt&searchkey=$searchkey&s_birthday=$s_birthday&s_memorial=$s_memorial&s_age=$s_age";
$param .= "&s_address=$s_address&s_job=$s_job&s_marriage=$s_marriage&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day";
$param .= "&next_year=$next_year&next_month=$next_month&next_day=$next_day&page=$page";
//------------------------------------------------------------------------------------------------------------------------------------

// 회원정보
$sql = "select * from wiz_member where id = '$id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$meminfo = mysqli_fetch_object($result);

// 총주문액(주문 테이블)
$sql = "select sum(total_price) as total_price from wiz_order where send_id = '$id' and status = 'SY'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$total_price = $row->total_price;

// 적립금
$sql = "select sum(reserve) as reserve from wiz_reserve where memid = '$id'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_object($result);
$reserve = $row->reserve;
?>
<link href="../style.css" rel="stylesheet" type="text/css">
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript">
<!--
function inputCheck(frm){

   if(frm.id.value == ""){
      alert("아이디를 입력하세요");
      frm.id.focus();
      return false;
   }
   <? if($mode=="insert"){ ?>
   if(frm.passwd.value == ""){
      alert("비밀번호를 입력하세요");
      frm.passwd.focus();
      return false;
   }
   <? } ?>
}

// 고객 메일발송
function sendEmail(seluser){
	var url = "send_email.php?seluser=" + seluser;
	window.open(url,"sendEmail","height=700, width=900, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 고객 sms발송
function sendSms(seluser){
	var url = "send_sms.php?seluser=" + seluser;
	window.open(url,"sendSms","height=500, width=600, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 회원별 구매내역
function orderList(id,name){
	var url = "member_order.php?id=" + id + "&name=" + name;
	window.open(url,"orderList","height=400, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 회원별 적립금내역
function reserveList(id,name){
	var url = "member_reserve.php?id=" + id + "&name=" + name;
	window.open(url,"reserveList","height=400, width=700, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 회원별 쿠폰내역
function couponList(id,name){
	var url = "member_coupon.php?id=" + id + "&name=" + name;
	window.open(url,"couponList","height=400, width=750, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

/*
// 주소찾기
function searchZip(kind){
	var url = "../member/search_zip.php?kind=" + kind;
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
*/
// 주소찾기
function searchZip(kind) {
	if(kind == undefined) kind = '';
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
			// 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
			eval('document.frm.'+kind+'post').value = data.zonecode;
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

					eval('document.frm.'+kind+'address').value = data.roadAddress;

                } else { // 사용자가 지번 주소를 선택했을 경우(J)

					eval('document.frm.'+kind+'address').value = data.jibunAddress;

                }

			//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
			//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
			//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
			//document.getElementById('addr').value = addr;

			if(eval('document.frm.'+kind+'address2') != null)
				eval('document.frm.'+kind+'address2').focus();
		}
	}).open();
}

// 아이디 중복확인
function idCheck(){
   var id = document.frm.id.value;
   var url = "../member/id_check.php?name=id&id=" + id;
   window.open(url, "idCheck", "width=450, height=150, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}
-->
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
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기본정보</td>
		  </tr>
		</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <form name="frm" action="member_save.php?<?=$param?>" method="post" onSubmit="return inputCheck(this);">
    <input type="hidden" name="tmp">
    <input type="hidden" name="mode" value="<?=$mode?>">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
            <tr>
              <td width="15%" class="t_name">아이디</td>
              <td width="35%" class="t_value">
              	<input name="id" type="text" value="<?=$meminfo->id?>" class="input" readonly>
              	<? if(strcmp($mode, "update")) { ?>
                <input type="button" value="중복체크" class="btn-zipcode" onCLick="idCheck()" />
                <? } ?>
             	</td>
              <td width="15%" class="t_name">비밀번호</td>
              <td width="35%" class="t_value"><input name="passwd" type="text" value="" class="input"></td>
            </tr>
            <tr>
              <td class="t_name">이름</td>
              <td class="t_value"><input name="name" type="text" value="<?=$meminfo->name?>" class="input"></td>
              <td class="t_name"></td>
              <td class="t_value">
              </td>
            </tr>
            <tr>
              <td class="t_name">이메일</td>
              <td class="t_value"><input name="email" type="text" value="<?=$meminfo->email?>" class="input"> <a href="javascript:sendEmail('<?=$meminfo->name?>:<?=$meminfo->email?>')"; class="AW-btn-s-black">발송</a></td>
              <td class="t_name">전화번호</td>
              <td class="t_value">
                <? list($tphone, $tphone2, $tphone3) = explode("-",$meminfo->tphone); ?>
                <input type="text" name="tphone" value="<?=$tphone?>" size="5" class="input"> -
                <input type="text" name="tphone2" value="<?=$tphone2?>" size="5" class="input"> -
                <input type="text" name="tphone3" value="<?=$tphone3?>" size="5" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name">휴대폰</td>
              <td class="t_value">
                <? list($hphone, $hphone2, $hphone3) = explode("-",$meminfo->hphone); ?>
                <input type="text" name="hphone" value="<?=$hphone?>"  size="5" class="input"> -
                <input type="text" name="hphone2" value="<?=$hphone2?>"  size="5" class="input"> -
                <input type="text" name="hphone3" value="<?=$hphone3?>"  size="5" class="input">
                <? if(!strcmp($shop_info->sms_use, "Y")) { ?>
                <a href="javascript:sendSms('<?=$meminfo->hphone?>')"; class="AW-btn-s-black">발송</a>
              	<? } ?>
              </td>
              <td class="t_name">FAX</td>
              <td class="t_value">
                <? list($fax, $fax2, $fax3) = explode("-",$meminfo->fax); ?>
                <input type="text" name="fax" value="<?=$fax?>"  size="5" class="input"> -
                <input type="text" name="fax2" value="<?=$fax2?>"  size="5" class="input"> -
                <input type="text" name="fax3" value="<?=$fax3?>"  size="5" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name">회원등급</td>
              <td class="t_value" colspan="3">
                <select name="level">
                <option value="">::선택::
                <?=level_list();?>
                </select>
                <script language="javascript">
                <!--
                 level = document.frm.level;
                 for(ii=0; ii<level.length; ii++){
                    if(level.options[ii].value == "<?=$meminfo->level?>")
                       level.options[ii].selected = true;
                 }
                -->
                </script>
              </td>
            </tr>
            <tr>
              <td class="t_name">우편번호</td>
              <td class="t_value" colspan="3">
                <input name="post" type="text" value="<?=$meminfo->post?>" size="5" class="input">
                <input type="button" value="우편번호 검색" class="btn-zipcode" onClick="searchZip('');" />
              </td>
            </tr>
            <tr>
              <td class="t_name">주소</td>
              <td class="t_value" colspan="3">
              <input name="address" type="text" value="<?=$meminfo->address?>" size="60" class="input"><br>
              <input name="address2" type="text" value="<?=$meminfo->address2?>" size="60" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name">추천인</td>
              <td class="t_value"><input name="recom" type="text" value="<?=$meminfo->recom?>" class="input"></td>
              <td class="t_name">최종방문일</td>
              <td class="t_value"><?=$meminfo->visit_time?></td>
            </tr>
            <tr>
              <td class="t_name">이메일 수신</td>
              <td class="t_value">
                <input type="radio" name="reemail" value="Y" <? if($meminfo->reemail == "Y") echo "checked"; ?>>예
                <input type="radio" name="reemail" value="N" <? if($meminfo->reemail == "N") echo "checked"; ?>>아니오
              </td>
              <td class="t_name">SMS 수신</td>
              <td class="t_value">
                <input type="radio" name="resms" value="Y" <? if($meminfo->resms == "Y") echo "checked"; ?>>예
                <input type="radio" name="resms" value="N" <? if($meminfo->resms == "N") echo "checked"; ?>>아니오
              </td>
            </tr>
            <tr>
              <td class="t_name">총주문액</td>
              <td class="t_value"><?=number_format($total_price)?>원 <button type="button" class="AW-btn-s-black" onCLick="orderList('<?=$id?>','<?=$meminfo->name?>')">상세보기</a></td>
              <td class="t_name">적립금</td>
              <td class="t_value"><?=number_format($reserve)?>원 <button type="button" class="AW-btn-s-black" onCLick="reserveList('<?=$id?>','<?=$meminfo->name?>')">상세보기</a></td>
            </tr>
						<? if($oper_info->coupon_use == "Y"){ ?>
						<tr>
							<td class="t_name">쿠폰내역</td>
							<td class="t_value" colspan="3">
								<button type="button" class="AW-btn-s-black" onCLick="couponList('<?=$id?>','<?=$meminfo->name?>')">상세보기</button>
							</td>
						</tr>
						<? } ?>
            <tr>
              <td height="25" class="t_name">관라자주석</td>
              <td class="t_value" colspan="3">
              <textarea name="comment" rows="5" cols="90" class="textarea" style="width:100%;"><?=$meminfo->comment?></textarea>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td class="tit_sub"><img src="../image/ics_tit.gif"> 부가정보</td>
		  </tr>
		</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
            <tr>
              <td width="15%" class="t_name"> 결혼 여부</td>
              <td width="35%" class="t_value">
                <input type="radio" name="marriage" value="N" <? if($meminfo->marriage == "N") echo "checked"; ?>>미혼
                <input type="radio" name="marriage" value="Y" <? if($meminfo->marriage == "Y") echo "checked"; ?>>기혼
              </td>
              <td width="15%" class="t_name">결혼기념일</td>
              <td width="35%" class="t_value">
                <? list($memorial, $memorial2, $memorial3) = explode("-", $meminfo->memorial); ?>
                <input name="memorial" value="<?=$memorial?>" type="text" size="4" maxlength="4" class="input">년
                <input name="memorial2" value="<?=$memorial2?>"  type="text" size="2" maxlength="2" class="input">월
                <input name="memorial3" value="<?=$memorial3?>"  type="text" size="2" maxlength="2" class="input">일
              </td>
            </tr>
            <tr>
              <td class="t_name">직업</td>
              <td class="t_value">
								<select name="job" class="optionjoin">
								<option value="" selected>항목을 선택 해 주세요</option>
								<option value="00">무직</option>
								<option value="10">학생</option>
								<option value="30">컴퓨터/인터넷</option>
								<option value="50">언론</option>
								<option value="70">공무원</option>
								<option value="90">군인</option>
								<option value="A0">서비스업</option>
								<option value="C0">교육</option>
								<option value="E0">금융/증권/보험업</option>
								<option value="G0">유통업</option>
								<option value="I0">예술</option>
								<option value="K0">의료</option>
								<option value="M0">법률</option>
								<option value="O0">건설업</option>
								<option value="Q0">제조업</option>
								<option value="S0">부동산업</option>
								<option value="U0">운송업</option>
								<option value="W0">농/수/임/광산업</option>
								<option value="Y0">가사</option>
								<option value="z0">기타</option>
								</select>
              </td>
              <td class="t_name">학력</td>
              <td class="t_value">
								<select name="scholarship" class="optionjoin">
								<option value="" selected>항목을 선택 해 주세요</option>
								<option value="0">없음</option>
								<option value="1">초등학교재학</option>
								<option value="2">초등학교졸업</option>
								<option value="4">중학교재학</option>
								<option value="6">중학교졸업</option>
								<option value="7">고등학교재학</option>
								<option value="9">고등학교졸업</option>
								<option value="H">대학교재학</option>
								<option value="J">대학교졸업</option>
								<option value="O">대학원재학</option>
								<option value="Z">대학원졸업이상</option>
								</select>
              </td>
            </tr>
            <script language="javascript">
              <!--
                job = document.frm.job;
                for(ii=0; ii<job.length; ii++){
                   if(job.options[ii].value == "<?=$meminfo->job?>")
                      job.options[ii].selected = true;
                }

                scholarship = document.frm.scholarship;
                for(ii=0; ii<scholarship.length; ii++){
                   if(scholarship.options[ii].value == "<?=$meminfo->scholarship?>")
                      scholarship.options[ii].selected = true;
                }
              -->
            </script>
            <tr>
              <td class="t_name">생년월일</td>
              <td class="t_value" colspan="3">
              <? list($birthday, $birthday2, $birthday3) = explode("-", $meminfo->birthday); ?>
               <input name="birthday" value="<?=$birthday?>" type="text" class="input" id="26" size="4" maxlength="4">년
               <input name="birthday2" value="<?=$birthday2?>" type="text" class="input" id="27" size="2" maxlength="2">월
               <input name="birthday3" value="<?=$birthday3?>" type="text" class="input" id="28" size="2" maxlength="2">일 (
               <input type="radio" name="bgubun" value="S" <? if($meminfo->bgubun == "S") echo "checked"; ?>>양력
               <input type="radio" name="bgubun" value="M" <? if($meminfo->bgubun == "M") echo "checked"; ?>>음력 )
              </td>
            </tr>
            <tr>
              <td class="t_name">관심분야</td>
              <td class="t_value" colspan="3">
               <?
                   $arrconsph = explode("/",$meminfo->consph);
                   for($ii=0; $ii<count($arrconsph); $ii++){
                      $tmpconsph[$arrconsph[$ii]] = true;
                   }
               ?>
               <input type="checkbox" name="consph[]" value="01" <? if($tmpconsph["01"]==true) echo "checked";?>> 건강
                <input type="checkbox" name="consph[]" value="02" <? if($tmpconsph["02"]==true) echo "checked";?>> 문화/예술
                <input type="checkbox" name="consph[]" value="03" <? if($tmpconsph["03"]==true) echo "checked";?>> 경제
                <input type="checkbox" name="consph[]" value="04" <? if($tmpconsph["04"]==true) echo "checked";?>> 연예/오락
                <input type="checkbox" name="consph[]" value="05" <? if($tmpconsph["05"]==true) echo "checked";?>> 뉴스
                <input type="checkbox" name="consph[]" value="06" <? if($tmpconsph["06"]==true) echo "checked";?>> 여행/레저<br>
                <input type="checkbox" name="consph[]" value="07" <? if($tmpconsph["07"]==true) echo "checked";?>> 생활
                <input type="checkbox" name="consph[]" value="08" <? if($tmpconsph["08"]==true) echo "checked";?>> 스포츠
                <input type="checkbox" name="consph[]" value="09" <? if($tmpconsph["09"]==true) echo "checked";?>> 교육
                <input type="checkbox" name="consph[]" value="10" <? if($tmpconsph["10"]==true) echo "checked";?>> 컴퓨터
                <input type="checkbox" name="consph[]" value="11" <? if($tmpconsph["11"]==true) echo "checked";?>> 학문
              </td>
            </tr>
          </table></td>
      </tr>
    </table>

    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>
		    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기업정보</td>
		  </tr>
		</table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
            <tr>
              <td width="15%" class="t_name"> 사업자등록번호</td>
              <td width="35%" class="t_value">
              	<input type="text" name="com_num" value="<?=$meminfo->com_num?>" class="input">
              </td>
              <td width="15%" class="t_name">상호</td>
              <td width="35%" class="t_value">
              	<input type="text" name="com_name" value="<?=$meminfo->com_name?>" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name"> 대표자명</td>
              <td class="t_value" colspan="3">
              	<input type="text" name="com_owner" value="<?=$meminfo->com_owner?>" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name">우편번호</td>
              <td class="t_value" colspan="3">
                <input name="com_post" type="text" value="<?=$meminfo->com_post?>" size="5" class="input">
                <input type="button" value="우편번호 검색" class="btn-zipcode" onClick="searchZip('com_');" />
              </td>
            </tr>
            <tr>
              <td class="t_name">주소</td>
              <td class="t_value" colspan="3">
              <input name="com_address" type="text" value="<?=$meminfo->com_address?>" size="60" class="input">
              </td>
            </tr>
            <tr>
              <td class="t_name"> 업태</td>
              <td class="t_value">
              	<input type="text" name="com_kind" value="<?=$meminfo->com_kind?>" class="input">
              </td>
              <td class="t_name">종목</td>
              <td class="t_value">
              	<input type="text" name="com_class" value="<?=$meminfo->com_class?>" class="input">
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="document.location='member_list.php?<?=$param?>';" href="javascript:history.go(-1);">목록</a>
</div><!-- .AW-btn-wrap -->
    </form>

<? include "../footer.php"; ?>