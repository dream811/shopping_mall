<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/shop_info.inc"; 		// 상점 정보

$page_type = "join";
$now_position = "<a href=/>Home</a> &gt; 회원가입 &gt; <strong>정보입력</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

// 자동등록글체크
get_spam_check();

// 입력정보 사용여부
$info_tmp = explode("/",$page_info->addinfo);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_use[$info_tmp[$ii]] = true;
}

// 입력정보 필수여부
$info_tmp = explode("/",$page_info->addinfo2);
for($ii=0; $ii<count($info_tmp); $ii++){
	$info_ess[$info_tmp[$ii]] = true;
}
?>
<script language="JavaScript" src="/js/util_lib.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function inputCheck(frm){

   if(frm.id.value.length < 3 || frm.id.value.length > 12){ alert("아이디는 3 ~ 12자리만 가능합니다."); frm.id.focus(); return false;
   }else{
      if(!Check_Char(frm.id.value)){ alert("아이디는 특수문자를 사용할수 없습니다."); frm.id.focus(); return false; }
   }
   if(frm.passwd.value.length < 4 || frm.passwd.value.length > 12){ alert("비밀번호는 4 ~ 12자리입니다"); frm.passwd.focus(); return false; }

   if(frm.passwd2.value.length < 4 || frm.passwd2.value.length > 12){ alert("비밀번호는 4 ~ 12자리입니다"); frm.passwd2.focus(); return false; }

   if(frm.passwd.value != frm.passwd2.value){alert("비밀번호가 일치하지 않습니다");frm.passwd.focus();return false;}

   if(frm.name.value == ""){alert("이름을 입력하세요");frm.name.focus();return false;
   }else{
      if(!Check_nonChar(frm.name.value)){alert("이름에는 특수문자가 들어갈 수 없습니다");frm.name.focus();return false;}
   }

<? if($info_ess[resno] == "true"){ ?>

   if(frm.resno.value == ""){alert("주민번호를 입력하세요");frm.resno.focus();return false;}
   if(frm.resno2.value == ""){alert("주민번호를 입력하세요");frm.resno2.focus();return false;}
   if(!check_ResidentNO(frm.resno.value, frm.resno2.value)){alert("주민번호가 올바르지 않습니다");frm.resno.value == "";frm.resno2.value == "";frm.resno.focus();return false;}

<? } ?>

<? if($info_ess[address] == "true"){ ?>

   if(frm.post.value == ""){alert("우편번호를 입력하세요");frm.post.focus();return false;}
   //if(frm.post2.value == ""){alert("우편번호를 입력하세요");frm.post2.focus();return false;}
   if(frm.post.value.length != 5){alert("우편번호가 올바르지 않습니다");frm.post.focus();return false;}
   if(frm.address.value == ""){alert("주소를 입력하세요");frm.address.focus();return false;}
   if(frm.address2.value == ""){alert("상세주소를 입력하세요");frm.address2.focus();return false;}

<? } ?>

<? if($info_ess[tphone] == "true"){ ?>

   if(frm.tphone.value == ""){alert("전화번호를 입력하세요");frm.tphone.focus();return false;
   }else if(!Check_Num(frm.tphone.value)){alert("지역번호는 숫자만 가능합니다.");frm.tphone.focus();return false;}

   if(frm.tphone2.value == ""){alert("전화번호를 입력하세요");frm.tphone2.focus();return false;
   }else if(!Check_Num(frm.tphone2.value)){alert("국번은 숫자만 가능합니다.");frm.tphone2.focus();return false;}

   if(frm.tphone3.value == ""){alert("전화번호를 입력하세요");frm.tphone3.focus();return false;
   }else if(!Check_Num(frm.tphone3.value)){alert("전화번호는 숫자만 가능합니다");frm.tphone3.focus();return false;}

<? } ?>

<? if($info_ess[hphone] == "true"){ ?>

   if(frm.hphone.value == ""){alert("휴대폰번호를 입력하세요");frm.hphone.focus();return false;
   }else if(!Check_Num(frm.hphone.value)){alert("휴대폰번호는 숫자만 가능합니다.");frm.hphone.focus();return false;}

   if(frm.hphone2.value == ""){alert("휴대폰번호를 입력하세요");frm.hphone2.focus();return false;
   }else if(!Check_Num(frm.hphone2.value)){alert("휴대폰번호는 숫자만 가능합니다.");frm.hphone2.focus();return false;}

   if(frm.hphone3.value == ""){alert("휴대폰번호를 입력하세요");frm.hphone3.focus();return false;
   }else if(!Check_Num(frm.hphone3.value)){alert("휴대폰번호는 숫자만 가능합니다");frm.hphone3.focus();return false;}

<? } ?>

<? if($info_ess[fax] == "true"){ ?>

   if(frm.fax.value == ""){alert("FAX번호를 입력하세요");frm.fax.focus();return false;
   }else if(!Check_Num(frm.fax.value)){alert("FAX번호는 숫자만 가능합니다.");frm.fax.focus();return false;}

   if(frm.fax2.value == ""){alert("FAX번호를 입력하세요");frm.fax2.focus();return false;
   }else if(!Check_Num(frm.fax2.value)){alert("FAX번호는 숫자만 가능합니다.");frm.fax2.focus();return false;}

   if(frm.fax3.value == ""){alert("FAX번호를 입력하세요");frm.fax3.focus();return false;
   }else if(!Check_Num(frm.fax3.value)){alert("FAX번호는 숫자만 가능합니다");frm.fax3.focus();return false;}

<? } ?>

<? if($info_ess[email] == "true"){ ?>

   if(frm.email.value == ""){alert("이메일을 입력하세요.");frm.email.focus();return false;
   }else if(!check_Email(frm.email.value)){frm.email.focus();return false;}

<? } ?>

<? if($info_ess[birthday] == "true"){ ?>

   if(frm.birthday.value == ""){alert("생년월일을 입력하세요.");frm.birthday.focus();return false;}
   if(frm.birthday2.value == ""){alert("생년월일을 입력하세요.");frm.birthday2.focus();return false;}
   if(frm.birthday3.value == ""){alert("생년월일을 입력하세요.");frm.birthday3.focus();return false;}
   if(frm.bgubun[0].checked == false && frm.bgubun[1].checked == false){alert("양력 음력을 선택하세요.");return false;}

<? } ?>

<? if($info_ess[marriage] == "true"){ ?>
   if(frm.marriage[0].checked == false && frm.marriage[1].checked == false){alert("결혼여부를 선택하세요.");return false;}

<? } ?>

<? if($info_ess[marriage] == "true"){ ?>

   if(frm.memorial.value == ""){ alert("결혼기념일을 입력하세요.");frm.memorial.focus();return false;}
   if(frm.memorial2.value == ""){alert("결혼기념일을 입력하세요.");frm.memorial2.focus();return false;}
   if(frm.memorial3.value == ""){alert("결혼기념일을 입력하세요.");frm.memorial3.focus();return false;}

<? } ?>

<? if($info_ess[job] == "true"){ ?>

   if(frm.job.value == ""){alert("직업을 선택하세요.");frm.job.focus();return false;}

<? } ?>

<? if($info_ess[scholarship] == "true"){ ?>

   if(frm.scholarship.value == ""){alert("학력을 선택하세요.");frm.scholarship.focus();return false;}

<? } ?>

<? if($info_ess[consph] == "true"){ ?>

	var consphLen=frm['consph[]'].length;

	if(consphLen == undefined){
	  if( frm['consph[]'].checked == false ){alert("관심분야가 선택되지 않았습니다.");frm['consph[]'].focus();return false;  }
	}else {
	  var ChkLike=0;
	  for(i=0;i<consphLen;i++){if( frm['consph[]'][i].checked == true ){ ChkLike=1; break;}}
	  if( ChkLike==0 ){alert("관심분야는 한개 이상 선택하셔야 합니다.");frm['consph[]'][0].focus();return false; }
	}

<? } ?>

<? if($info_ess[company] == "true"){ ?>

   if(frm.com_num.value == ""){ alert("사업자등록번호를 입력하세요.");frm.com_num.focus();return false;}
   if(frm.com_name.value == ""){ alert("상호를 입력하세요.");frm.com_name.focus();return false;}
   if(frm.com_owner.value == ""){ alert("대표자명을 입력하세요.");frm.com_owner.focus();return false;}

   if(frm.com_post.value == ""){alert("우편번호를 입력하세요");frm.com_post.focus();return false;}
   //if(frm.com_post2.value == ""){alert("우편번호를 입력하세요");frm.com_post2.focus();return false;}
   if(frm.com_post.value.length != 5){alert("우편번호가 올바르지 않습니다");frm.post.focus();return false;}
   if(frm.com_address.value == ""){alert("주소를 입력하세요");frm.com_address.focus();return false;}

   if(frm.com_kind.value == ""){ alert("업태를 입력하세요.");frm.com_kind.focus();return false;}
   if(frm.com_class.value == ""){ alert("종목을 입력하세요.");frm.com_class.focus();return false;}

<? } ?>

	 if(frm.recom.value != ""){
	    if(frm.recom.value == frm.id.value){
	       alert("자기 자신은 추천인이 될 수 없습니다.");
	       frm.recom.value = "";
	       return false;
	    }
	 }

<? if($info_ess[spam] == "true"){ ?>

  if (frm.vcode != undefined && (hex_md5(frm.vcode.value) != md5_norobot_key)) {
  	alert("자동등록방지코드를 정확히 입력해주세요.");
    frm.vcode.focus();
    return false;
	}

<? } ?>

}

// 아이디 중복확인
function idCheck(){
   var id = document.frm.id.value;
   var url = "id_check.php?id=" + id;
   window.open(url, "idCheck", "width=410, height=280, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}

// 우편번호 찾기
/*
function zipSearch(kind){
	var address = eval("document.frm."+kind+"address");
	address.focus();
	var url = "zip_search.php?kind="+kind;
	window.open(url, "zipSearch", "width=427, height=400, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
}*/
// 우편번호 찾기
function zipSearch(kind) {
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


// 주민번호 자동포커스
function jfocus(frm){
	if(frm.resno2 != null){
		var str = frm.resno.value.length;
		if(str == 6) frm.resno2.focus();
	}
}
//-->
</script>
<div class="AW_ttl clearfix">
	<h2>회원가입 정보 입력</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>회원가입 정보 입력</span>
	</div>
</div>

<form name="frm" action="<?=$ssl?>/member/join_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
<h2 class="member_ttl">기본정보 입력</h2>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_member_table">
	<tr>
		<td width="18%" height="32" class="tit">아이디 *</td>
		<td colspan="9" class="val">
			<input name="id" type="text" class="input" size="15" maxlength="12" onClick="idCheck();" readonly>
  			<button type="button" class="post_btn" onclick="idCheck();">중복확인</button>
	  		<span class="form_sub">(3~12 영문, 숫자, 가입 후 ID변경은 불가함을 알려드립니다.)</span>
		</td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">비밀번호 *</td>
		<td colspan="9" class="val">
			<input name="passwd" type="password" class="input" size="15" />
			<span class="form_sub">(특수문자 및 한글입력은 불가하며 대소문자를 구별합니다. 6자리 이상 입력해주시기 바랍니다)</span> 
		</td>
	</tr>
	<tr>
	<td width="18%" height="32" class="tit">비밀번호 확인 *</td>
	<td colspan="9" class="val">
  		<input name="passwd2" type="password" class="input" size="15" />
		<span class="form_sub">비밀번호 확인을 위해 다시 한 번 입력해 주시기 바랍니다.</span></td>
	</tr>
             
	<tr>
		<td width="18%" height="32" class="tit">이름 *</td>
		<td colspan="9" class="val">
			<input name="name" type="text" class="input"  <? if($name!="") echo "value='".$name."' readonly"; ?>size="15" />
		</td>
	</tr>
              
	<? if($info_use[resno] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">주민등록번호 <? if($info_ess[resno]) echo "*"; ?></td>
		<td colspan="9" class="val">
		<input type="text" name="resno" size="8" maxlength="6" class="input" onKeyup="jfocus(this.form);" <? if($resno1!="") echo "value='".$resno1."' readonly"; ?>> 
		- <input type="password" name="resno2" size="10"  maxlength="7" class="input" <? if($resno2!="") echo "value='".$resno2."' readonly"; ?>>
		</td>
	</tr>
	<? } ?>
              
	<? if($info_use[address] == true){ ?>
	<tr>
		<td width="18%" class="tit">주소 <? if($info_ess[address]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input type="text" name="post" size="5" maxlength="5" class="input" onClick="zipSearch('')">
			<button type="button" class="post_btn" onclick="zipSearch('');">우편번호 찾기</button><br />
			<input type="text" name="address" size="50" maxlength="80" class="input"><br />
			<input type="text" name="address2" size="50" maxlength="80" class="input">
		</td>
	</tr>
	<? } ?>

	<? if($info_use[tphone] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">전화번호 <? if($info_ess[tphone]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="tphone" type="text" class="input" size="4" /> -
			<input name="tphone2" type="text" class="input" size="5" /> -
			<input name="tphone3" type="text" class="input" size="5" />
		</td>
	</tr>
	<? } ?>
							
	<? if($info_use[hphone] == true){ ?>
	<tr>
		<td width="18%" class="tit">휴대폰 번호 <? if($info_ess[hphone]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="hphone" type="text" class="input" size="4" /> -
			<input name="hphone2" type="text" class="input" size="5" /> -
			<input name="hphone3" type="text" class="input" size="5" />
			<div style="margin:6px 0 0;">
				<span class="form_sub"><strong>문자 정보 서비스를 받으시겠습니까?</strong></span>
				<input name="resms" type="radio" class="radio" value="Y" checked id="sms_Y"><label for="sms_Y">수신함</label>
				<input name="resms" type="radio" class="radio" value="N" id="sms_N"><label for="sms_N">수신하지 않음</label><br />
				<span class="red">* 주문확인,배송 진행상황,알리미 등록,이벤트 공지 서비스 제공 해 드립니다.</span>
			</div>
		</td>
	</tr>
	<? } ?>
							
	<? if($info_use[fax] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">팩스  <? if($info_ess[fax]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="fax" type="text" class="input" size="4" /> -
			<input name="fax2" type="text" class="input" size="5" /> -
			<input name="fax3" type="text" class="input" size="5" />
		</td>
	</tr>
	<? } ?>
							
	<? if($info_use[email] == true){ ?>
	<tr>
		<td width="18%" class="tit">이메일 <? if($info_ess[email]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="email" type="text" class="input" size="30" />
			<div style="margin:6px 0 0;">
				<strong>이메일 서비스를 받으시겠습니까?</strong>
				<input name="reemail" type="radio" class="radio" value="Y" checked id="mail_Y"><label for="mail_Y">예</label>
				<input name="reemail" type="radio" class="radio" value="N" id="mail_N"><label for="mail_N">아니오</label><br />
				<span class="red">* 주문,결제,이벤트 정보제공, 단 유효하지 않은 이메일은 서비스 불가</span>
			</div>
		</td>
	</tr>
	<? } ?>
							
	<tr>
		<td width="18%" height="32" class="tit">추천인</td>
		<td colspan="9" class="val"><input name="recom" type="text" class="input" size="15" /></td>
	</tr>
</table>
            
            
<!--추가정보-->
<?
if($info_use[birthday] != false ||
$info_use[marriage] != false ||
$info_use[memorial] != false ||
$info_use[job] != false ||
$info_use[scholarship] != false ||
$info_use[consph] != false
){
?>
<h2 class="member_ttl">추가정보 입력</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
	<? if($info_use[birthday] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">생년월일 <? if($info_ess[birthday]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="birthday" type="text" class="input" size="5"> 년
			<input name="birthday2" type="text" class="input" size="3"> 월
			<input name="birthday3" type="text" class="input" size="3"> 일 &nbsp; 
			(
			<input name="bgubun" value="S" type="radio" class="radio" id="birth_S" checked><label for="birth_S">양력</label>
			<input name="bgubun" value="M" type="radio" class="radio"id="birth_M"><label for="birth_M">음력</label>)
		</td>
	</tr>
	<? } ?>

	<? if($info_use[marriage] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">결혼여부 <? if($info_ess[marriage]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="marriage" value="Y" type="radio" class="radio" id="mar_Y" checked><label for="mar_Y">미혼</label>
			<input name="marriage" value="N" type="radio" class="radio" id="mar_N"><label for="mar_N">기혼</label>
		</td>
	</tr>
	<? } ?>

	<? if($info_use[memorial] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">결혼기념일 <? if($info_ess[memorial]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input name="memorial" type="text" class="input" size="5"> 년
			<input name="memorial2" type="text" class="input" size="3"> 월
			<input name="memorial3" type="text" class="input" size="3"> 일 
		</td>
	</tr>
	<? } ?>

	<? if($info_use[job] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">직업 <? if($info_ess[job]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<select name="job" class="select">
				<option selected>항목을 선택 해 주세요</option>
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
	</tr>
	<? } ?>

	<? if($info_use[scholarship] == true){ ?>
	<tr>
		<td width="18%" height="32" class="tit">학력 <? if($info_ess[scholarship]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<select name="scholarship">
				<option selected>항목을 선택 해 주세요</option>
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
	<? } ?>

	<? if($info_use[consph] == true){?>
	<tr>
		<td width="18%" height="32" class="tit">관심분야 <? if($info_ess[consph]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input type="checkbox" name="consph[]" value="01" id="con1"><label for="con1">건강</label>
			<input type="checkbox" name="consph[]" value="02" id="con2"><label for="con2">문화/예술</label>
			<input type="checkbox" name="consph[]" value="03" id="con3"><label for="con3">경제</label>
			<input type="checkbox" name="consph[]" value="04" id="con4"><label for="con4">연예/오락</label>
			<input type="checkbox" name="consph[]" value="05" id="con5"><label for="con5">뉴스</label>
			<input type="checkbox" name="consph[]" value="06" id="con6"><label for="con6">여행/레저</label><br>
			<input type="checkbox" name="consph[]" value="07" id="con7"><label for="con7">생활</label>
			<input type="checkbox" name="consph[]" value="08" id="con8"><label for="con8">스포츠</label>
			<input type="checkbox" name="consph[]" value="09" id="con9"><label for="con9">교육</label>
			<input type="checkbox" name="consph[]" value="10" id="con10"><label for="con10">컴퓨터</label>
			<input type="checkbox" name="consph[]" value="11" id="con11"><label for="con11">학문</label>
		</td>
	</tr>
	<? } ?>
</table>
<?
}
?>
          	
<!-- 기업정보 -->
<? if($info_use[company] != false) { ?>
<h2 class="member_ttl">기업정보 입력</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
	<tr>
		<td width="18%" height="32" class="tit">사업자번호 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val"><input name="com_num" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">회사명 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val"><input name="com_name" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">대표자 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val"><input name="com_owner" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">주소 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val">
			<input type="text" name="com_post" size="5" class="input">
			<button type="button" class="post_btn" onclick="zipSearch('com_');">우편번호 찾기</button><br />
			<input type=text name="com_address" size="50" class="input">
		</td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">업태 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val"><input name="com_class" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">종목 <? if($info_ess[company]) echo "*"; ?></td>
		<td colspan="9" class="val"><input name="com_kind" type="text" class="input" size="20"></td>
	</tr>
</table>
<? } ?>
          	
          	
<!-- 자동등록 방지코드 -->
<? if($info_use[spam] != false) { ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
  <tr>
	<td width="18%" height="32" class="tit">자동등록방지코드 <? if($info_ess[spam]) echo "*"; ?></td>
	<td colspan="9" class="val"><?=$spam_check?></td>
  </tr>
</table>
<? } ?>

<div class="AW_btn_area clearfix">
	<button type="submit" class="submit_btn">회원가입</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">취소</button>
</div>		
            
</form>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>