<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
$mode = "mall_info";

$sql = "select * from wiz_mall where id = '$wiz_mall['id']'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$mall_info = mysqli_fetch_object($result);
?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript">
<!--
function inputCheck(frm) {

	if(frm.passwd.value.length < 4 || frm.passwd.value.length > 12){ alert("비밀번호는 4 ~ 12자리입니다"); frm.passwd.focus(); return false; }

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

	if(frm.manager.value == ""){alert("이름을 입력하세요");frm.manager.focus();return false;}

	if(frm.acc_name.value == ""){alert("예금주를 입력하세요");frm.acc_name.focus();return false;}
	if(frm.acc_bank.value == ""){alert("은행명을 입력하세요");frm.acc_bank.focus();return false;}
	if(frm.acc_num.value == ""){alert("계좌번호를 입력하세요");frm.acc_num.focus();return false;}

	if(frm.com_tel.value == ""){alert("전화번호를 입력하세요");frm.com_tel.focus();return false;
	}else if(!Check_Num(frm.com_tel.value)){alert("지역번호는 숫자만 가능합니다.");frm.com_tel.focus();return false;}

	if(frm.com_tel2.value == ""){alert("전화번호를 입력하세요");frm.com_tel2.focus();return false;
	}else if(!Check_Num(frm.com_tel2.value)){alert("국번은 숫자만 가능합니다.");frm.com_tel2.focus();return false;}

	if(frm.com_tel3.value == ""){alert("전화번호를 입력하세요");frm.com_tel3.focus();return false;
	}else if(!Check_Num(frm.com_tel3.value)){alert("전화번호는 숫자만 가능합니다");frm.com_tel3.focus();return false;}

	if(frm.com_hp.value == ""){alert("휴대전화번호를 입력하세요");frm.com_hp.focus();return false;}
	if(frm.com_fax.value == ""){alert("FAX번호를 입력하세요");frm.com_fax.focus();return false;}

	if(frm.email.value == ""){alert("이메일을 입력하세요.");frm.email.focus();return false;
	}else if(!check_Email(frm.email.value)){frm.email.focus();return false;}

}
/*
function searchZip(){
	document.frm.address.focus();
	var url = "search_zip.php?kind=";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
*/
function searchZip() {
	kind = '';
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
-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">기본정보설정</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">입점업체 기본정보를 설정합니다.</td>
			  </tr>
			</table>


      
<form name="frm" action="shop_save.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="<?=$mode?>">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">아이디</td>
                <td width="35%" class="t_value"><input name="id" type="text" value="<?=$mall_info->id?>" class="input" readonly></td>
                <td width="15%" class="t_name">비밀번호</td>
                <td width="35%" class="t_value"><input name="passwd" type="text" value="<?=$mall_info->passwd?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">업체명</td>
                <td class="t_value"><input name="com_name" type="text" value="<?=$mall_info->com_name?>" class="input"></td>
                <td align="left" class="t_name">사업자등록번호</td>
                <td class="t_value"><input type="text" name="com_num" value="<?=$mall_info->com_num?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">업태</td>
                <td class="t_value"><input name="com_kind" type="text" value="<?=$mall_info->com_kind?>" class="input"></td>
                <td align="left" class="t_name">업종</td>
                <td class="t_value"><input type="text" name="com_class" value="<?=$mall_info->com_class?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">대표자</td>
                <td class="t_value"><input name="com_owner" type="text" value="<?=$mall_info->com_owner?>" class="input"></td>
                <td align="left" class="t_name">담당자</td>
                <td class="t_value"><input name="manager" type="text" value="<?=$mall_info->manager?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">예금주</td>
                <td class="t_value"><input name="acc_name" type="text" value="<?=$mall_info->acc_name?>" class="input"></td>
                <td align="left" class="t_name">은행명</td>
                <td class="t_value"><input type="text" name="acc_bank" value="<?=$mall_info->acc_bank?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">계좌번호</td>
                <td class="t_value"><input name="acc_num" type="text" value="<?=$mall_info->acc_num?>" class="input"></td>
                <td align="left" class="t_name">홈페이지</td>
                <td class="t_value">http://<input type="text" name="homepage" value="<?=$mall_info->homepage?>" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">이메일</td>
                <td class="t_value"><input name="email" type="text" value="<?=$mall_info->email?>" class="input"></td>
                <td align="left" class="t_name">전화번호</td>
                <td class="t_value">
                  <? list($com_tel, $com_tel2, $com_tel3) = explode("-",$mall_info->com_tel); ?>
                  <input type="text" name="com_tel" value="<?=$com_tel?>" size="5" class="input"> -
                  <input type="text" name="com_tel2" value="<?=$com_tel2?>" size="5" class="input"> -
                  <input type="text" name="com_tel3" value="<?=$com_tel3?>" size="5" class="input">
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name">휴대폰</td>
                <td class="t_value">
                  <? list($com_hp, $com_hp2, $com_hp3) = explode("-",$mall_info->com_hp); ?>
                  <input type="text" name="com_hp" value="<?=$com_hp?>"  size="5" class="input"> -
                  <input type="text" name="com_hp2" value="<?=$com_hp2?>"  size="5" class="input"> -
                  <input type="text" name="com_hp3" value="<?=$com_hp3?>"  size="5" class="input">
                </td>
                <td align="left" class="t_name">FAX</td>
                <td class="t_value">
                  <? list($com_fax, $com_fax2, $com_fax3) = explode("-",$mall_info->com_fax); ?>
                  <input type="text" name="com_fax" value="<?=$com_fax?>"  size="5" class="input"> -
                  <input type="text" name="com_fax2" value="<?=$com_fax2?>"  size="5" class="input"> -
                  <input type="text" name="com_fax3" value="<?=$com_fax3?>"  size="5" class="input">
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name">우편번호</td>
                <td class="t_value" colspan="3">                  
                  <input name="post" type="text" value="<?=$mall_info->post?>" size="5" class="input">
				  <input type="button" value="우편번호 검색" onclick="searchZip();">
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name">주소</td>
                <td class="t_value" colspan="3">
                <div><input name="address" type="text" value="<?=$mall_info->address?>" size="60" class="input"></div>
                <div style="margin:3px 0 0;"><input name="address2" type="text" value="<?=$mall_info->address2?>" size="60" class="input"></div>
                </td>
              </tr>
              <tr>
                <td align="left" class="t_name">신청일</td>
                <td class="t_value"><?=$mall_info->wdate?></td>
                <td align="left" class="t_name">승인일</td>
                <td class="t_value"><?=$mall_info->adate?></td>
              </tr>
              <tr>
                <td align="left" class="t_name">승인여부</td>
                <td class="t_value" colspan="3">
                	<?php
                	if(!strcmp($mall_info->status, "Y")) echo "승인";
                	else echo "<font color='red'>미승인</font>";
                	?>
                </td>
              </tr>
            </table></td>
        </tr>
      </table>

      <br>

	<div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on">
        <a href="javascript:history.go(-1);">취소</a>
    </div>

</form>
<? include "../footer.php"; ?>