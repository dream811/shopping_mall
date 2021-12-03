<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   // 유틸 라이브러리

$now_position = "<a href=/>Home</a> &gt; 입점신청 &gt; <strong>정보입력</strong>";
$page_type = "malljoin";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인

// 자동등록글체크
get_spam_check();

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

   if(frm.manager.value == ""){alert("담당자를 입력하세요");frm.manager.focus();return false;}

   if(frm.acc_name.value == ""){alert("예금주를 입력하세요");frm.acc_name.focus();return false;}
   if(frm.acc_bank.value == ""){alert("은행명을 입력하세요");frm.acc_bank.focus();return false;}
   if(frm.acc_num.value == ""){alert("계좌번호를 입력하세요");frm.acc_num.focus();return false;}

   if(frm.com_tel.value == ""){alert("전화번호를 입력하세요");frm.com_tel.focus();return false;}
   if(frm.com_tel2.value == ""){alert("전화번호를 입력하세요");frm.com_tel2.focus();return false;}
   if(frm.com_tel3.value == ""){alert("전화번호를 입력하세요");frm.com_tel3.focus();return false;}

   if(frm.com_hp.value == ""){alert("휴대전화번호를 입력하세요");frm.com_hp.focus();return false;}
   if(frm.com_hp2.value == ""){alert("휴대전화번호를 입력하세요");frm.com_hp2.focus();return false;}
   if(frm.com_hp3.value == ""){alert("휴대전화번호를 입력하세요");frm.com_hp3.focus();return false;}

   if(frm.email.value == ""){alert("이메일을 입력하세요.");frm.email.focus();return false;
   }else if(!check_Email(frm.email.value)){frm.email.focus();return false;}

   if(frm.com_num.value == ""){ alert("사업자등록번호를 입력하세요.");frm.com_num.focus();return false;}
   if(frm.com_name.value == ""){ alert("상호를 입력하세요.");frm.com_name.focus();return false;}
   if(frm.com_owner.value == ""){ alert("대표자명을 입력하세요.");frm.com_owner.focus();return false;}

   if(frm.post.value == ""){alert("우편번호를 입력하세요");frm.post.focus();return false;}
   //if(frm.post2.value == ""){alert("우편번호를 입력하세요");frm.post2.focus();return false;}
   if(frm.post.value.length != 5){alert("우편번호가 올바르지 않습니다");frm.post.focus();return false;}
   if(frm.address.value == ""){alert("주소를 입력하세요");frm.address.focus();return false;}
   if(frm.address2.value == ""){alert("나머지 주소를 입력하세요");frm.address2.focus();return false;}

   if(frm.com_kind.value == ""){ alert("업태를 입력하세요.");frm.com_kind.focus();return false;}
   if(frm.com_class.value == ""){ alert("종목을 입력하세요.");frm.com_class.focus();return false;}

  if (frm.vcode != undefined && (hex_md5(frm.vcode.value) != md5_norobot_key)) {
  	alert("자동등록방지코드를 정확히 입력해주세요.");
    frm.vcode.focus();
    return false;
	}

}

// 아이디 중복확인
function idCheck(){
   var id = document.frm.id.value;
   var url = "id_check.php?id=" + id;
   window.open(url, "idCheck", "width=410, height=280, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}
/*
// 우편번호 찾기
function zipSearch(kind){
	var address = eval("document.frm."+kind+"address");
	address.focus();
	var url = "zip_search.php?kind="+kind;
	window.open(url, "zipSearch", "width=427, height=400, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
}
*/
// 주소찾기 
function zipSearch(kind) {
	
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

//-->
</script>
<div class="AW_ttl clearfix">
	<h2>입점업체 정보 입력</h2>
	<div class="his_bar clearfix">
		<a href="/" class="home_ico">Home</a><span>입점업체 정보 입력</span>
	</div>
</div>
<form name="frm" action="<?=$ssl?>/member/mall_save.php" method="post" onSubmit="return inputCheck(this)">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
<h2 class="member_ttl">기본정보 입력</h2>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
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
		<td colspan="9" class="val"><input name="passwd2" type="password" class="input" size="15" />
			<span class="form_sub">비밀번호 확인을 위해 다시 한 번 입력해 주시기 바랍니다.</span></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">담당자 *</td>
		<td colspan="9" class="val"><input name="manager" type="text" class="input" size="15" /></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">예금주 *</td>
		<td colspan="9" class="val"><input name="acc_name" type="text" class="input" size="15" /></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">은행명 *</td>
		<td colspan="9" class="val"><input name="acc_bank" type="text" class="input" size="15" /></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">계좌번호 *</td>
		<td colspan="9" class="val"><input name="acc_num" type="text" class="input" size="15" /></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">전화번호 *</td>
		<td colspan="9" class="val"><input name="com_tel" type="text" class="input" size="4" /> -
			<input name="com_tel2" type="text" class="input" size="5" /> -
			<input name="com_tel3" type="text" class="input" size="5" />
		</td>
	</tr>
	<tr>
		<td width="18%" class="tit">휴대폰 번호 *</td>
		<td colspan="9" class="val"><input name="com_hp" type="text" class="input" size="4" /> -
			<input name="com_hp2" type="text" class="input" size="5" /> -
			<input name="com_hp3" type="text" class="input" size="5" /></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">팩스</td>
		<td colspan="9" class="val"><input name="com_fax" type="text" class="input" size="4" /> -
			<input name="com_fax2" type="text" class="input" size="5" /> -
			<input name="com_fax3" type="text" class="input" size="5" /></td>
	</tr>

	<tr>
		<td width="18%" class="tit">이메일 *</td>
		<td colspan="9" class="val"><input name="email" type="text" class="input" size="30" /></td>
	</tr>
	<tr>
		<td width="18%" class="tit">홈페이지</td>
		<td colspan="9" class="val">http://<input name="homepage" type="text" class="input" size="30" /></td>
	</tr>

</table>

<!-- 기업정보 -->
<h2 class="member_ttl">기업정보 입력</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
	<tr>
		<td width="18%" height="32" class="tit">사업자번호 *</td>
		<td colspan="9" class="val"><input name="com_num" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">회사명 *</td>
		<td colspan="9" class="val"><input name="com_name" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">대표자 *</td>
		<td colspan="9" class="val"><input name="com_owner" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">주소 *</td>
		<td colspan="9" class="val">
			<input type="text" name="post" size="5" maxlength="5" class="input" onClick="zipSearch('')">
			<button type="button" class="post_btn" onclick="zipSearch('');">우편번호 찾기</button><br />
			<input type="text" name="address" size="50" maxlength="80" class="input"><br />
			<input type="text" name="address2" size="50" maxlength="80" class="input">
		</td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">업태 *</td>
		<td colspan="9" class="val"><input name="com_class" type="text" class="input" size="20"></td>
	</tr>
	<tr>
		<td width="18%" height="32" class="tit">종목 *</td>
		<td colspan="9" class="val"><input name="com_kind" type="text" class="input" size="20"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="AW_member_table">
	<tr>
		<td width="18%" height="32" class="tit">자동등록방지코드 *</td>
		<td colspan="9" class="val">
			<?=$spam_check?>
		</td>
	</tr>
</table>

<div class="AW_btn_area clearfix">
	<button type="submit" class="submit_btn">확인</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">취소</button>
</div>
</form>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>