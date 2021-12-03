<?
$sub_tit="회원가입";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<?php

$page_type = "join";
include "../../inc/page_info.inc"; 		// 페이지 정보

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
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="JavaScript" src="/js/util_lib.js"></script>
<script language="JavaScript">
<!--
// 입력값 체크
function inputCheck(frm){

   if(frm.name.value == ""){alert("이름을 입력하세요");frm.name.focus();return false;
   }else{
      if(!Check_nonChar(frm.name.value)){alert("이름에는 특수문자가 들어갈 수 없습니다");frm.name.focus();return false;}
   }

   if(frm.id.value.length < 3 || frm.id.value.length > 12){ alert("아이디는 3 ~ 12자리만 가능합니다."); frm.id.focus(); return false;
   }else{
      if(!Check_Char(frm.id.value)){ alert("아이디는 특수문자를 사용할수 없습니다."); frm.id.focus(); return false; }
   }
   if(frm.passwd.value.length < 4 || frm.passwd.value.length > 12){ alert("비밀번호는 4 ~ 12자리입니다"); frm.passwd.focus(); return false; }

   if(frm.passwd2.value.length < 4 || frm.passwd2.value.length > 12){ alert("비밀번호는 4 ~ 12자리입니다"); frm.passwd2.focus(); return false; }

   if(frm.passwd.value != frm.passwd2.value){alert("비밀번호가 일치하지 않습니다");frm.passwd.focus();return false;}

<? if($info_ess[resno] == "true"){ ?>

   if(frm.resno.value == ""){alert("주민번호를 입력하세요");frm.resno.focus();return false;}
   if(frm.resno2.value == ""){alert("주민번호를 입력하세요");frm.resno2.focus();return false;}
   if(!check_ResidentNO(frm.resno.value, frm.resno2.value)){alert("주민번호가 올바르지 않습니다");frm.resno.value == "";frm.resno2.value == "";frm.resno.focus();return false;}

<? } ?>

<? if($info_ess[email] == "true"){ ?>

   if(frm.email1.value == ""){alert("이메일을 입력하세요.");frm.email1.focus();return false;
   }else if(frm.email2.value == ""){alert("이메일을 입력하세요.");frm.email2.focus();return false;
   }else if(!check_Email(frm.email1.value+"@"+frm.email2.value)){frm.email1.focus();return false;}

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

<? if($info_ess[address] == "true"){ ?>

   if(frm.post.value == ""){alert("우편번호를 입력하세요");frm.post.focus();return false;}
   if(frm.post.value.length != 5){alert("우편번호가 올바르지 않습니다");frm.post.focus();return false;}
   if(frm.address.value == ""){alert("주소를 입력하세요");frm.address.focus();return false;}
   if(frm.address2.value == ""){alert("상세주소를 입력하세요");frm.address2.focus();return false;}

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
   var url = "/member/id_check.php?id=" + id;
   window.open(url, "idCheck", "width=410, height=280, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}

// 우편번호
function zipSearch(kind){
	if(kind == undefined) kind = '';
	new daum.Postcode({
	oncomplete: function(data) {
	//전체 주소에서 연결 번지 및 ()로 묶여 있는 부가정보를 제거하고자 할 경우,
	//아래와 같은 정규식을 사용해도 된다. 정규식은 개발자의 목적에 맞게 수정해서 사용 가능하다.
	//var addr = data.address.replace(/(\s|^)\(.+\)$|\S+~\S+/g, '');
	//document.getElementById('addr').value = addr;

	var frm;
	for(i=0;i<document.forms.length;i++){
	frm = document.forms[i];

	if(eval('frm.'+kind+'post')){
	eval('frm.'+kind+'post').value = data.zonecode;
	if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

	eval('frm.'+kind+'address').value = data.roadAddress;

	} else { // 사용자가 지번 주소를 선택했을 경우(J)

	eval('frm.'+kind+'address').value = data.jibunAddress;

	}
	//eval('frm.'+kind+'address1').value = data.address;
	if(eval('frm.'+kind+'address2') != null) eval('frm.'+kind+'address2').focus();
	}

	if(eval('frm.'+kind+'address')){
	if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우

	eval('frm.'+kind+'address').value = data.roadAddress;

	} else { // 사용자가 지번 주소를 선택했을 경우(J)

	eval('frm.'+kind+'address').value = data.jibunAddress;

	}
	//eval('frm.'+kind+'address').value = data.address;
	}
	}
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

<form name="frm" action="<?=$ssl?>/member/join_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">

<div class="agree_box">
	<h3>계정 정보 입력</h3>
	<dl class="data-table">
    	<dt>아이디 <i>*</i> <small>(특수문자를 제외한 모든 문자 사용가능)</small></dt>
        <dd>
        	<input name="id" type="text"  maxlength="12" onClick="idCheck();" style="width:10rem;" readonly />
            <button type="button" onClick="idCheck();">중복체크</button>
        </dd>
    </dl>

	<dl class="data-table">
    	<dt>비밀번호 <i>*</i> <small>(문자와 숫자를 조합하여 6~12자리)</small></dt>
        <dd><input name="passwd" type="password" minlength="6" /></dd>
    </dl>

	<dl class="data-table">
    	<dt>비밀번호 확인 <i>*</i></dt>
        <dd><input name="passwd2" type="password" /></dd>
    </dl>
    <? if($info_use[resno] == true){ ?>
    <dl>
	<dt>주민등록번호 <i>*</i></dt>
	<dd>
		<input type="text" name="resno" size="8" maxlength="6" class="input_style" onKeyup="jfocus(this.form);" <? if($resno1!="") echo "value='".$resno1."' readonly"; ?>>
		-
		<input type="password" name="resno2" size="10"  maxlength="7" class="input_style" <? if($resno2!="") echo "value='".$resno2."' readonly"; ?>>
	</dd>
    </dl>
	<? } ?>
</div>
<div class="gry_bar"></div>

<div class="agree_box">
  <h3>기본 정보 입력</h3>
  <? if($info_use[email] == true){ ?>
  <dl class="data-table">
    <dt>이메일 <? if($info_ess[email]) echo "<i>*</i>"; ?></dt>
    <dd>
        <input name="email1" type="text" style="width:80px;" class="input_style" /> @
        <input name="email2" type="text" style="width:80px;" class="input_style" />
        <select name="email3" onChange="this.form.email2.value=this.value">
          <option value="">직접입력</option>
          <?php
          $email_str = "naver.com,nate.com,dreamwiz.com,yahoo.co.kr,empal.com,unitel.co.kr,gmail.com,korea.com,chol.com,paran.com,freechal.com,hanmail.net,hotmail.com";

          $email_list = explode(",", $email_str);
          if(is_array($email_list)) {
          	foreach($email_list as $e_idx => $e_val) {
          		echo "<option value='".$e_val."'>".$e_val."</option>";
          	}
          }
          ?>
        </select>
		<div style="margin:10px 0 0;">
			메일정보수신&nbsp;
			<input name="reemail" type="radio" value="Y" checked id="email_Y" /><label for="email_Y">동의</label>
			<input name="reemail" type="radio" value="N" id="email_N" /><label for="email_N">동의안함</label>
		</div>
    </dd>
  </dl>
  <? } ?>
  <? if($info_use[tphone] == true){ ?>
  <dl class="data-table">
    <dt>연락처 <? if($info_ess[tphone]) echo "<i>*</i>"; ?></dt>
    <dd>
        <select name="tphone">
          <option value="">선택</option>
          <?php
          $num_str = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
          $num_list = explode(",", $num_str);
          if(is_array($num_list)) {
          	foreach($num_list as $n_idx => $num) {
          		echo "<option value='".$num."'>".$num."</option>";
          	}
          }
          ?>
        </select> -
        <input name="tphone2" type="text" style="width:80px;" class="input_style" /> -
        <input name="tphone3" type="text" style="width:80px;" class="input_style" />
	</dd>
    </dl>
	<? } ?>
	<? if($info_use[hphone] == true){ ?>
	  <dl class="data-table">
		<dt>휴대폰 <? if($info_ess[hphone]) echo "<i>*</i>"; ?></dt>
		<dd>
			<select name="hphone">
			  <option value="">선택</option>
			  <?php
			  $num_str = "010,011,016,017,018,019";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					echo "<option value='".$num."'>".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="hphone2" type="text" style="width:80px;" class="input_style" /> -
			<input name="hphone3" type="text" style="width:80px;" class="input_style" />
			<div style="margin:10px 0 0;">
				SMS수신&nbsp;
                <input name="resms" type="radio" value="Y" checked id="sms_Y" /><label for="sms_Y">동의</label>
                <input name="resms" type="radio" value="N" id="sms_N" /><label for="sms_N">동의안함</label>
			</div>
		</dd>
	</dl>
	<? } ?>

	<? if($info_use[fax] == true){ ?>
    <dl class="data-table">
		<dt>팩스  <? if($info_ess[fax]) echo "<i>*</i>"; ?></dt>
		<dd>
			<select name="fax">
			  <option value="">선택</option>
			  <?php
			  $num_str = "02,031,032,033,041,042,043,051,052,053,054,055,061,062,063,064";
			  $num_list = explode(",", $num_str);
			  if(is_array($num_list)) {
				foreach($num_list as $n_idx => $num) {
					echo "<option value='".$num."'>".$num."</option>";
				}
			  }
			  ?>
			</select> -
			<input name="fax2" type="text" style="width:80px;" class="input_style" /> -
			<input name="fax3" type="text" style="width:80px;" class="input_style" />
		</dd>
    </dl>
	<? } ?>
	<? if($info_use[address] == true){ ?>
    <dl class="data-table">
		<dt>주소 <? if($info_ess[address]) echo "<i>*</i>"; ?></dt>
		<dd>
			<div>
				<input name="post" type="text" style="width:80px;" class="input_style" maxlength="3" onClick="zipSearch('')" />
				<button type="button" onClick="zipSearch('');">우편번호 찾기</button>
			</div>
			<div style="margin:5px 0 0;"><input name="address" type="text" maxlength="80" /></div>
			<div style="margin:5px 0 0;"><input name="address2" type="text" maxlength="80" /></div>
		</dd>
    </dl>
	<? } ?>
	<dl class="data-table">
		<dt>추천인</dt>
		<dd><input name="recom" type="text" class="input_style" /></dd>
	</dl>

</div>
<div class="gry_bar"></div>
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
<div class="agree_box">
	<h3>추가 정보 입력</h3>
	<? if($info_use[birthday] == true){ ?>
    <dl class="data-table">
    <dt>생일 <? if($info_ess[birthday]) echo "<i>*</i>"; ?></dt>
    <dd>
		<select name="birthday">
			<?php
			for($ii = date("Y"); $ii >= 1900; $ii--) {
				echo "<option value='".$ii."'>".$ii."년</option>";
			}
			?>
		</select>
		<select name="birthday2">
			<?php
			for($ii = 1; $ii <= 12; $ii++) {
				echo "<option value='".$ii."'>".sprintf("%02d", $ii)."월</option>";
			}
			?>
		</select>
		<select name="birthday3">
			<?php
			for($ii = 1; $ii <= 31; $ii++) {
				echo "<option value='".$ii."'>".sprintf("%02d", $ii)."일</option>";
			}
			?>
		</select>
		<div style="margin:10px 0 0;">
			<input name="bgubun" value="S" type="radio" class="radio" checked id="b_S"><label for="b_S">양력</label>
			<input name="bgubun" value="M" type="radio" class="radio" id="b_M"><label for="b_M">음력</label>
		</div>
    </dd>
    </dl>
	<? } ?>
	<? if($info_use[marriage] == true){ ?>
    <dl class="data-table">
		<dt>결혼여부 <? if($info_ess[marriage]) echo "<i>*</i>"; ?></dt>
		<dd>
			<input name="marriage" type="radio" value="Y" id="mar_Y" checked /><label for="mar_Y">기혼</label>
			<input name="marriage" type="radio" value="N" id="mar_N" /><label for="mar_N">미혼</label>
		</dd>
    </dl>
	<? } ?>
	<? if($info_use[memorial] == true){ ?>
    <dl class="data-table">
    <dt>결혼기념일 <? if($info_ess[memorial]) echo "<i>*</i>"; ?></dt>
    <dd>
		<select name="memorial">
			<?php
			for($ii = date("Y"); $ii >= 1900; $ii--) {
				echo "<option value='".$ii."'>".$ii."년</option>";
			}
			?>
		</select>
		<select name="memorial2">
			<?php
			for($ii = 1; $ii <= 12; $ii++) {
				echo "<option value='".$ii."'>".sprintf("%02d", $ii)."월</option>";
			}
			?>
		</select>
		<select name="memorial3">
			<?php
			for($ii = 1; $ii <= 31; $ii++) {
				echo "<option value='".$ii."'>".sprintf("%02d", $ii)."일</option>";
			}
			?>
		</select>
    </dd>
    </dl>
	<? } ?>
	<? if($info_use[job] == true){ ?>
    <dl class="data-table">
		<dt>직업 <? if($info_ess[job]) echo "<i>*</i>"; ?></dt>
		<dd>
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
		</dd>
    </dl>
	<? } ?>
	<? if($info_use[scholarship] == true){ ?>
    <dl class="data-table">
    <dt>학력 <? if($info_ess[scholarship]) echo "<i>*</i>"; ?></dt>
    <dd>
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
     </dd>
    </dl>
	<? } ?>
	<? if($info_use[consph] == true){?>
    <dl class="data-table">
		<dt>관심분야 <? if($info_ess[consph]) echo "<i>*</i>"; ?></dt>
		<dd>
			<span><input type="checkbox" name="consph[]" value="01" id="con1"><label for="con1">건강</label></span>
			<span><input type="checkbox" name="consph[]" value="02" id="con2"><label for="con2">문화/예술</label></span>
			<span><input type="checkbox" name="consph[]" value="03" id="con3"><label for="con3">경제</label></span>
			<span><input type="checkbox" name="consph[]" value="04" id="con4"><label for="con4">연예/오락</label></span>
			<span><input type="checkbox" name="consph[]" value="05" id="con5"><label for="con5">뉴스</label></span>
			<span><input type="checkbox" name="consph[]" value="06" id="con6"><label for="con6">여행/레저</label></span>
			<span><input type="checkbox" name="consph[]" value="07" id="con7"><label for="con7">생활</label></span>
			<span><input type="checkbox" name="consph[]" value="08" id="con8"><label for="con8">스포츠</label></span>
			<span><input type="checkbox" name="consph[]" value="09" id="con9"><label for="con9">교육</label></span>
			<span><input type="checkbox" name="consph[]" value="10" id="con10"><label for="con10">컴퓨터</label></span>
			<span><input type="checkbox" name="consph[]" value="11" id="con11"><label for="con11">학문</label></span>
		</dd>
    </dl>
	<? } ?>
</div>
<?
}
?>
<div class="gry_bar"></div>
<!-- 기업정보 -->
<? if($info_use[company] != false) { ?>
<div class="agree_box">
	<h3>기업 정보 입력</h3>
	<dl class="data-table">
		<dt>사업자번호 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd><input name="com_num" type="text" class="input_style" /></dd>
	</dl>
	<dl class="data-table">
		<dt>회사명 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd><input name="com_name" type="text" class="input_style" /></dd>
	</dl>
	<dl class="data-table">
		<dt>대표자 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd><input name="com_owner" type="text" class="input_style" /></dd>
	</dl>
	<dl class="data-table">
		<dt>주소 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd>
			<input name="com_post" type="text" style="width:80px;" class="input_style" maxlength="3" onClick="zipSearch('com_')" />
			<button type="button" onClick="zipSearch('com_');">우편번호 찾기</button>
			<div style="margin:5px 0 0;">
				<input name="com_address" type="text" style="width:95%;" class="input_style" maxlength="80" />
			</div>
		</dd>
	</dl>
	<dl class="data-table">
		<dt>업태 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd><input name="com_class" type="text" class="input_style" /></dd>
	</dl>
	<dl class="data-table">
		<dt>종목 <? if($info_ess[company]) echo "<i>*</i>"; ?></dt>
		<dd><input name="com_kind" type="text" class="input_style" /></dd>
	</dl>
</div>
<?
}
?>

<!-- 자동등록 방지코드 -->
<?php
if($info_use[spam] != false) {
	$spam_check = str_replace("class='input'", "class='input_style'", $spam_check);
?>
<div class="agree_box">
	<dl class="data-table spam_check">
		<dt>자동등록 방지코드 <i><? if($info_ess[spam]) echo "<i>*</i>"; ?></i></dt>
		<dd><?=$spam_check?></dd>
	</dl>
</div>
<?
}
?>

<div class="button_common"><button type="submit">다음</button></div>

</form>

<? include "../inc/footer.php" ?>
