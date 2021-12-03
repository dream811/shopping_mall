<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if($sub_mode == "update"){
	$sql = "select * from wiz_tradecom where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$cominfo = mysqli_fetch_array($result);
}
?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

function inputCheck(frm){
   
   if(frm.com_name.value == ""){
      alert("상호를 입력하세요");
      frm.com_name.focus();
      return false;
   }
   if(frm.com_type.value == ""){
      alert("업체구분을 선택하세요");
      frm.com_type.focus();
      return false;
   }
}
/*
// 우편번호 찾기
function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}*/
// 우편번호 찾기
function searchZip() {
	kind = 'com_';
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

		<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">거래처관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">거래처 정보를 관리합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
      <form name="frm" action="shop_save.php" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="shop_trade">
      <input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
        <tr> 
          <td width="15%" class="t_name">사업자등록번호</td>
          <td width="35%" class="t_value"><input name="com_num" value="<?=$cominfo[com_num]?>" type="text" class="input"></td>
          <td width="15%" class="t_name">업체구분</td>
          <td width="35%" class="t_value">
            <select name="com_type">
            <option value="">:: 선택 ::
            <option value="BUY" <? if($cominfo[com_type] == "BUY") echo "selected"; ?>>매입처
            <option value="SAL" <? if($cominfo[com_type] == "SAL") echo "selected"; ?>>매출처
            <option value="DEL" <? if($cominfo[com_type] == "DEL") echo "selected"; ?>>배송업체
            <option value="OTH" <? if($cominfo[com_type] == "OTH") echo "selected"; ?>>기타
            </select>
          </td>
        </tr>
        <tr> 
          <td class="t_name">상호</td>
          <td class="t_value">
            <input name="com_name" value="<?=$cominfo[com_name]?>" type="text"  class="input">
          </td>
          <td class="t_name">대표자</td>
          <td class="t_value">
            <input name="com_owner" value="<?=$cominfo[com_owner]?>" type="text" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">사업장주소</td>
          <td class="t_value" colspan="3">
            <input name="com_post" type="text" value="<?=$cominfo[com_post]?>" size="5" class="input">
            <input type="button" value="우편번호 검색" onClick="searchZip();" class="btn-zipcode" /><br>
            <input name="com_address" value="<?=$cominfo[com_address]?>" type="text" size="50" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">업태</td>
          <td class="t_value">
            <input name="com_kind" value="<?=$cominfo[com_kind]?>" type="text" class="input">
          </td>
          <td class="t_name">종목</td>
          <td class="t_value">
             <input name="com_class" value="<?=$cominfo[com_class]?>" type="text" class="input">
          </td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr> 
          <td width="15%" class="t_name">전화번호</td>
          <td width="35%" class="t_value">
            <input name="com_tel" value="<?=$cominfo[com_tel]?>" type="text" class="input">
          </td>
          <td width="15%" class="t_name">팩스</td>
          <td width="35%" class="t_value">
            <input name="com_fax" value="<?=$cominfo[com_fax]?>" type="text" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">거래은행</td>
          <td class="t_value">
            <input name="com_bank" value="<?=$cominfo[com_bank]?>" type="text" class="input">
          </td>
          <td class="t_name">계좌번호</td>
          <td class="t_value">
            <input name="com_account" value="<?=$cominfo[com_account]?>" type="text" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">홈페이지</td>
          <td class="t_value" colspan="3">
            <input name="com_homepage" value="<?=$cominfo[com_homepage]?>" size="30" type="text" class="input">
          </td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr> 
          <td width="15%" class="t_name">담당자</td>
          <td width="35%" class="t_value">
            <input name="charge_name" value="<?=$cominfo[charge_name]?>" type="text" class="input">
          </td>
          <td width="15%" class="t_name">담당자 이메일</td>
          <td width="35%" class="t_value">
            <input name="charge_email" value="<?=$cominfo[charge_email]?>" type="text" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">담당자 휴대폰</td>
          <td class="t_value">
            <input name="charge_hand" value="<?=$cominfo[charge_hand]?>" type="text" class="input">
          </td>
          <td class="t_name">담당자 전화</td>
          <td class="t_value">
            <input name="charge_tel" value="<?=$cominfo[charge_tel]?>" type="text" class="input">
          </td>
        </tr>
        <tr> 
          <td class="t_name">기타사항</td>
          <td class="t_value" colspan="3">
          <textarea name="descript" cols="70" rows="5" class="textarea" style="width:100%"><?=$cominfo[descript]?></textarea>
          </td>
        </tr>
      </table>
      
    <div class="AW-btn-wrap">
        <button type="submit" class="on">확인</button>
        <a onClick="document.location='shop_trade.php';">목록</a>
    </div><!-- .AW-btn-wrap -->

</form>

<? include "../footer.php"; ?>