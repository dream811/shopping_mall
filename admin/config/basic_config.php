<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="javascript">
<!--
function searchZip(){
	document.frm.com_address.focus();
	var url = "../member/search_zip.php?kind=com_";
	window.open(url,"searchZip","height=350, width=367, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
}

function inputCheck(frm){
	if(frm.designer_id.value == ""){
		alert("디자이너 아이디를 입력하세요.");
		frm.designer_id.focus();
		return false;
	}
	//if(frm.designer_pw.value == ""){
	//	alert("디자이너 비밀번호를 입력하세요.");
	//	frm.designer_pw.focus();
	//	return false;
	//}
}

// 아이디 중복확인
function idCheck(){
   var id = document.frm.designer_id.value;
   var url = "../member/id_check.php?name=designer_id&id=" + id;
   window.open(url, "idCheck", "width=400, height=150, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=150, top=150");
}

-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">환경설정</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">사이트 기본정보를 설정합니다.</td>
			  </tr>
			</table>

<form name="frm" action="basic_save.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="shop_info">
			
			<div class="config-btnwrap">
            	<a href="'/guide/" target="_blank">관리자메뉴얼</a>
            	<a href="/admin/desc_table.php?mode=print" target="_blank">디비스키마</a>
            	<a href="/admin/desc_file.php?mode=print" target="_blank">파일구조도</a>
            </div><!-- .config-btnwrap -->

      <table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="tit_sub"><img src="../image/ics_tit.gif" align="absmiddle"> 최종 업데이트 날짜</td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">최종 업데이트 날짜</td>
          <td width="85%" class="t_value">
          	<?=$shop_info->up_date?>
          </td>
        </tr>
      </table>
			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 라이센스키 등록</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
      
        <tr>
          <td width="15%" class="t_name">라이센스 키</td>
          <td width="85%" class="t_value">
          	<div class="buy-license-wrap">
                <textarea name="site_key" class="textarea"><?=$shop_info->site_key?></textarea>
                <a href="http://www.anywiz.co.kr" target="_blank" class="btn-buy-license">라이센스키 구입</a>
            </div><!-- .buy-license-wrap -->
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>
          	<!-- - 라이센스 키 를 등록하지 않을경우 위즈홈 설치 2주일 후 부터 관리자 기능을 사용할 수 없습니다.<br//-->
          	- 도메인이 변경될 경우 라이센스 키를 다시 발급받아야 합니다.<br>
          	- 도메인이 여러개인경우 한라인에 하나씩 추가할 수 있습니다.
          </td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 로고및타이틀</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr> 
                <td width="15%" class="t_name">관리자 로고</td>
                <td width="85%" class="t_value">
                <img src='/data/config/admin_logo.gif'><br><br><input name="admin_logo" type="file" class="input">
                </td>
              </tr>
              <tr>
                <td width="15%" class="t_name">관리자 타이틀</td>
                <td width="85%" class="t_value"><input name="admin_title" type="text" value="<?=$shop_info->admin_title?>" size="80" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">관리자 카피라잇</td>
                <td class="t_value"><textarea name="admin_footer" rows="3" cols="80" class="textarea"><?=$shop_info->admin_footer?></textarea></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 디자이너 아이디/비밀번호</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">디자이너 아이디</td>
          <td width="85%" class="t_value"><input name="designer_id" type="text" value="<?=$shop_info->designer_id?>" maxlength="20" class="input" readonly> <input type="button" value="중복체크" onCLick="idCheck()" /></td>
        </tr>
        <tr>
          <td class="t_name">디자이너 비밀번호</td>
          <td class="t_value"><input name="designer_pw" type="text" value="" maxlength="20" class="input"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>
          	- 디자이너 아이디/비밀번호으로 로그인시에만 환경설정 메뉴가 나타며 일반관리자는 보이지 않습니다.<br>
          	- 사이트 제작 완료후 관리자 비번이 변경되었더라도 디자이너 정보로 접속하므로 관리자 접속에 편리합니다.</td>
        </tr>
      </table>
      
      <!--br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 메뉴 사용여부</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
      	<tr>
          <td width="15%" class="t_name">디자인관리</td>
          <td width="85%" class="t_value">     
          	<input type="radio" name="design_use" value="Y" <? if(!strcmp($shop_info->design_use, "Y") || empty($shop_info->design_use)) echo "checked" ?>> 사용
          	<input type="radio" name="design_use" value="N" <? if(!strcmp($shop_info->design_use, "N")) echo "checked" ?>> 사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">일정관리</td>
          <td class="t_value">     
          	<input type="radio" name="sch_use" value="Y" <? if(!strcmp($shop_info->sch_use, "Y") || empty($shop_info->sch_use)) echo "checked" ?>> 사용
          	<input type="radio" name="sch_use" value="N" <? if(!strcmp($shop_info->sch_use, "N")) echo "checked" ?>> 사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">설문관리</td>
          <td class="t_value">     
          	<input type="radio" name="poll_use" value="Y" <? if(!strcmp($shop_info->poll_use, "Y") || empty($shop_info->poll_use)) echo "checked" ?>> 사용
          	<input type="radio" name="poll_use" value="N" <? if(!strcmp($shop_info->poll_use, "N")) echo "checked" ?>> 사용안함
          </td>
        </tr>
      </table>
      <br>
      
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>
          	- 사용여부에 따라 메뉴을 보이거나 숨길 수 있습니다.
          </td>
        </tr>
      </table-->
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 게시판추가 사용여부</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">     	
          	<input type="radio" name="addbbs_use" value="Y" <? if(!strcmp($shop_info->addbbs_use, "Y") || empty($shop_info->addbbs_use)) echo "checked" ?>> 사용
          	<input type="radio" name="addbbs_use" value="N" <? if(!strcmp($shop_info->addbbs_use, "N")) echo "checked" ?>> 사용안함
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>
          	- 게시판추가를 사용하지 않는 경우 "게시판관리 > 게시판목록"에서 게시판을 추가할 수 없습니다.
          </td>
        </tr>
      </table>
      
      <br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 견적서 사용여부</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">     	
          	<input type="radio" name="estimate_use" value="Y" <? if(!strcmp($shop_info->estimate_use, "Y") || empty($shop_info->estimate_use)) echo "checked" ?>> 사용
          	<input type="radio" name="estimate_use" value="N" <? if(!strcmp($shop_info->estimate_use, "N")) echo "checked" ?>> 사용안함
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>
          	- "견적서"를 사용하는 경우 장바구니에서 견적서를 출력할 수 있습니다.
          </td>
        </tr>
      </table>
      
      <!--br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 관리자 로그인후 이동페이지</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">로그인후 이동페이지</td>
          <td width="85%" class="t_value">
          	<table>
          	<tr><td>기본페이지 : /admin/main/main.php</td></tr>
          	<tr><td>http://<?=$HTTP_HOST?><input name="start_page" type="text" value="<?=$shop_info->start_page?>" size="50" class="input"></td></tr>
          	</table>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>- 관리자 로그인 후 작성한 주소로 이동합니다.</td>
        </tr>
        <tr> 
          <td>- 클라이언트요청 또는 메뉴의 중요도에 따라 관리자 로그인후 이동페이지를 설정합니다. </td>
        </tr>
      </table-->

      <br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> SMS 사용</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">
          	<input type="radio" name="sms_use" value="Y" <? if($shop_info->sms_use == "Y") echo "checked"; ?>>사용함&nbsp;
          	<input type="radio" name="sms_use" value="N" <? if($shop_info->sms_use == "N") echo "checked"; ?>>사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">SMS아이디</td>
          <td class="t_value"><input type="text" name="sms_id" value="<?=$oper_info->sms_id?>" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">SMS비밀번호</td>
          <td class="t_value"><input type="text" name="sms_pw" value="<?=$oper_info->sms_pw?>" class="input"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>- SMS서비스는 애니위즈에서 서비스 해드리며 SMS서비스 업체와 계약이 되있습니다.</td>
        </tr>
        <tr> 
          <td>- SMS를 사용하는경우 상점관리에 "SMS관리" 메뉴가 생성되며 충전및발송 가능횟수를 조회할 수 있습니다.</td>
        </tr>
        <tr> 
          <td>- 회원관리에 "SMS발송" 메뉴가 생성되어 전체발송이 가능하며 회원목록에서 개별,선택발송이 가능합니다.</td>
        </tr>
      </table>

      <br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 실명인증 사용</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">
          	<input type="radio" name="namecheck_use" value="Y" <? if($shop_info->namecheck_use == "Y") echo "checked"; ?>>사용함&nbsp;
          	<input type="radio" name="namecheck_use" value="N" <? if($shop_info->namecheck_use == "N") echo "checked"; ?>>사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">실명인증 아이디</td>
          <td class="t_value"><input type="text" name="namecheck_id" value="<?=$shop_info->namecheck_id?>" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">실명인증 비밀번호</td>
          <td class="t_value"><input type="text" name="namecheck_pw" value="<?=$shop_info->namecheck_pw?>" class="input"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>- 실명인증을 사용하는 경우 회원가입 약관페이지에서 실명을 체크하게 됩니다.</td>
        </tr>
        <tr> 
          <td>- 실명인증은 한국신용평가정보(주)에서 제공하며 <a href="http://www.namecheck.co.kr" target="_blank">http://www.namecheck.co.kr</a>에서 신청하실 수 있습니다.</td>
        </tr>
        <tr> 
          <td>- 신청 후 발급받은 아이디와 비밀번호를 입력 저장하면 바로 실명인증 체크가 가능합니다.</td>
        </tr>
        <tr> 
          <td><font color=red>주의) 신청후 받은 cb_namecheck 파일을 /member 폴더에 업로드(<b>전송타입 꼭 바이너리</b>)후 707퍼미션을 줍니다.</font></td>
        </tr>
      </table>
      
      <br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> SSL 사용</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
          	<input type="radio" name="ssl_use" value="Y" <? if($shop_info->ssl_use == "Y") echo "checked"; ?>>사용함&nbsp;
          	<input type="radio" name="ssl_use" value="N" <? if($shop_info->ssl_use == "N") echo "checked"; ?>>사용안함
          </td>
          <td width="15%" class="t_name">포트번호</td>
          <td width="35%" class="t_value">
          	<input type="text" name="ssl_port" value="<?=$shop_info->ssl_port?>" class="input">
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="6">
        <tr> 
          <td>- SSL을 사용하는 경우 기본적으로 서버에 SSL이 적용이 되어있어야합니다.</td>
        </tr>
        <tr> 
          <td>- 확인 방법 https://해당도메인 ex) https://<?=$HTTP_HOST?> </td>
        </tr>
      </table>
      
    <div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on" />
        <a href="javascript:history.go(-1);">취소</a>
    </div><!-- .AW-btn-wrap -->

</form>
<? include "../footer.php"; ?>