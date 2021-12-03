<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select * from wiz_design";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$dsn_info = mysqli_fetch_object($result);
?>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script language="javascript">
<!--
/*
function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}
*/
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

-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">기본정보설정</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">사이트 기본정보를 설정합니다.</td>
			  </tr>
			</table>

      <br>
<form name="frm" action="shop_save.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="shop_info">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 관리자정보</td>
			  </tr>
			</table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">쇼핑몰 이름</td>
                <td class="t_value" colspan="3"><input name="shop_name" value="<?=$shop_info->shop_name?>" type="text" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">쇼핑몰 URL</td>
                <td class="t_value" colspan="3"><input name="shop_url" type="text" value="<?=$shop_info->shop_url?>" size="60" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">관리자 이메일</td>
                <td class="t_value" colspan="3"><input name="shop_email" type="text" value="<?=$shop_info->shop_email?>" size="60" class="input"></td>
              </tr>
              <tr>
                <td width="15%" class="t_name">관리자 전화번호</td>
                <td width="35%" class="t_value"><input name="shop_tel" type="text" value="<?=$shop_info->shop_tel?>" size="28" class="input"></td>
                <td width="15%" class="t_name">관리자 휴대폰</td>
                <td width="35%" class="t_value"><input name="shop_hand" type="text" value="<?=$shop_info->shop_hand?>" class="input"></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30"><font color=red>- 관리자 이메일,휴대폰번호로 회원가입,탈퇴,폼메일 등 사이트에서 일어나는 상황을 통보받습니다.</font></td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 메타태크</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="5" class="t_style">
        <tr>
          <td width="15%" class="t_name">쇼핑몰 Title</td>
          <td width="85%" class="t_value"><input name="site_title" value="<?=$dsn_info->site_title?>" size="30" type="text" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">검색키워드</td>
          <td class="t_value"><input name="site_keyword" type="text" value="<?=$dsn_info->site_keyword?>" size="180" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">소개글</td>
          <td class="t_value"><input name="site_intro" type="text" value="<?=$dsn_info->site_intro?>" size="180" class="input"></td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 네이버 웹마스터도구</td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
				<tr>
          <td width="15%" class="t_name">HTML태그 (메타태그)</td>
          <td width="85%" class="t_value">
          	&lt;meta name="naver-site-verification" content="<input name="naver_key" type="text" value="<?=$shop_info->naver_key?>" size="50" class="input">"/&gt;
          </td>
        </tr>
			</table>
			
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 사업자정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">사업자등록번호</td>
                <td width="35%" class="t_value"><input name="com_num" type="text" value="<?=$shop_info->com_num?>" class="input"></td>
                <td width="15%" class="t_name">인감</td>
                <td width="35%" class="t_value">
                	<? if(is_file("../../data/config/com_seal.gif")){ ?> <img src='/data/config/com_seal.gif'><br> <? } ?>
                	<input name="com_seal" type="file" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">상호</td>
                <td class="t_value"><input name="com_name" type="text" value="<?=$shop_info->com_name?>" class="input"></td>
                <td class="t_name">대표자명</td>
                <td class="t_value"><input name="com_owner" type="text" value="<?=$shop_info->com_owner?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">우편번호</td>
                <td class="t_value" colspan="3">
                  <input name="com_post" type="text" value="<?=$shop_info->com_post?>" size="5" class="input">
                  <input type="button" value="우편번호 검색" onClick="searchZip();" class="btn-zipcode" />
                </td>
              </tr>
              <tr>
                <td class="t_name">주소</td>
                <td class="t_value" colspan="3"><input name="com_address" type="text" value="<?=$shop_info->com_address?>" size="50" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">업태</td>
                <td class="t_value"><input name="com_kind" type="text" value="<?=$shop_info->com_kind?>" class="input"></td>
                <td class="t_name">종목</td>
                <td class="t_value"><input name="com_class" type="text" value="<?=$shop_info->com_class?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">전화번호</td>
                <td class="t_value"><input name="com_tel" type="text" value="<?=$shop_info->com_tel?>" class="input"></td>
                <td class="t_name">팩스번호</td>
                <td class="t_value"><input name="com_fax" type="text" value="<?=$shop_info->com_fax?>" class="input"></td>
              </tr>
            </table></td>
        </tr>
      </table>

			<? if(!strcmp($shop_info->estimate_use, "Y")) { ?>
			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 견적서정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name" width="17%">비고</td>
                <td width="85%" class="t_value">
                	<textarea name="estimate_bigo" rows="5" cols="112" class="textarea" style="width:100%"><?=$shop_info->estimate_bigo?></textarea>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="30">- 견적서 출력 시 비고에 보여지는 내용을 입력합니다.</td>
        </tr>
      </table><br>
    	<? } ?>
      
    <div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on" />
        <a href="javascript:history.go(-1);">취소</a>
    </div><!-- .AW-btn-wrap -->


</form>
<? include "../footer.php"; ?>