<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?

$code = "review";
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$review_info = mysqli_fetch_array($result);

$code = "qna";
$sql = "select * from wiz_bbsinfo where code = '$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$qna_info = mysqli_fetch_array($result);

?>

<script language="JavaScript" type="text/javascript">
<!--

// 적립금 비율 다시적용
function setReserve(){

	var frm = document.frm;
	var reserve_per = frm.reserve_per.value;

	if(!isNaN(reserve_per) && reserve_per != ""){
		if(confirm("모든 상품의 적립금이 상품가격의 "+reserve_per+"% 로 일괄적용 됩니다.\n\n진행하시겠습니까?")){
			document.location = "shop_save.php?mode=setreserve&reserve_per=" + reserve_per;
		}
	}else{
		alert("숫자를 입력하세요");
		frm.reserve_per.value = "";
		frm.reserve_per.focus();
	}

}

//PG사 클릭시 설명문구
function pg_dec(no){
	var pgdoc=document.getElementById("pgdec");
	if(no == "1"){
		//데이콤 설명
		pgdoc.innerHTML = "<b>데이콤 관리자 > 계약정보 > 상점정보관리 > '승인결과전송여부' 를 전송(웹전송)</b>으로 꼭 변경하세요.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
	}else if(no =="2"){
		//KCP설명
		pgdoc.innerHTML = "<b>KCP에서 발급받은 아이디를 입력하세요.</b><br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
	}else if(no =="3"){
		//이니시스 설명
		pgdoc.innerHTML = "<b>이니시스에서 받으신 키파일을 /shop/INICIS/key/아이디명</b>으로 업로드하시고<br>발급 받은 가맹점 아이디를 꼭입력하여주세요.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
	}else if(no =="4"){
		//올더게이트 설명
		pgdoc.innerHTML = "<b>올더게이트에서 발급받은 아이디를 입력하세요.</b><br>실거래 아이디가 아닐경우 오류메세지가 나타날수 있습니다.<br>반드시 변경해야 카드결제 연동이 정상적으로 이루어집니다.";
	}
}

// 은행계좌번호 등록
function accIns() {

	var url = "pay_account.php";
  window.open(url,"pay_Account","height=270, width=440, left=600,top=450, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}
// 택배사 등록
function delIns() {

	var url = "del_oper.php";
  window.open(url,"del_oper","height=220, width=700, left=100,top=450, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}
// 은행계좌번호 수정
function accMod(no) {

	var url = "pay_account.php?mode=update&no=" + no;
  window.open(url,"pay_Account","height=270, width=440, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}

// 은행계좌번호 삭제
function accDel(no) {

	if(confirm("은행계좌번호를 삭제하시겠습니까?")) {
		document.location = "pay_account.php?save=true&mode=delete&no=" + no;
	}

}
// 택배사 수정
function delMod(no) {

	var url = "del_oper.php?mode=update&no=" + no;
  window.open(url,"del_oper","height=220, width=700, left=100,top=450,menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no");

}

// 택배사 삭제
function delDel(no) {

	if(confirm("해당택배사를 삭제하시겠습니까?")) {
		document.location = "del_oper.php?save=true&mode=delete&no=" + no;
	}

}
-->
</script>


			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">운영정보설정</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">운영에 필요한 정보를 설정합니다.</td>
			  </tr>
			</table>

      <a name="pay"></a>
      <br>
<form name="frm" action="shop_save.php" method="post">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="oper_info">
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 결제정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
      
        <tr>
          <td width="15%" class="t_name">결제방법</td>
          <td width="85%" class="t_value">
						<?
						$pay_list = explode("/",$oper_info->pay_method);
						for($ii=0; $ii<count($pay_list); $ii++){
						$pay_method[$pay_list[$ii]] = true;
						}
						?>
            <input type="checkbox" name="pay_method[]" value="PB" <? if($pay_method["PB"]==true) echo "checked";?>>무통장입금&nbsp;
            <input type="checkbox" name="pay_method[]" value="PC" <? if($pay_method["PC"]==true) echo "checked";?>>카드결제&nbsp;
            <input type="checkbox" name="pay_method[]" value="PN" <? if($pay_method["PN"]==true) echo "checked";?>>계좌이체&nbsp;
            <input type="checkbox" name="pay_method[]" value="PV" <? if($pay_method["PV"]==true) echo "checked";?>>가상계좌&nbsp;
            <input type="checkbox" name="pay_method[]" value="PH" <? if($pay_method["PH"]==true) echo "checked";?>>휴대폰결제
          </td>
        </tr>
        <tr>
          <td class="t_name">결제시스템</td>
          <td class="t_value">
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td class="t_name">
          				<input type="radio" name="pay_test" value="Y" <? if(!strcmp($oper_info->pay_test, "Y")) echo "checked" ?>> 테스트
          			</td>
          			<td class="t_value">
          				결제모듈을 테스트로 이용합니다.  <br>
          				<!--2. 일부 결제 테스트는 실제 결제가 이루어지므로 주의하시기 바랍니다.<br>
          				3. 올더게이트 결제 테스트는 오류메세지를 출력할수도 있습니다.-->
          			</td>
          		</tr>
          		<tr>
          			<td width="120" class="t_name">
          				<input type="radio" name="pay_test" value="N" <? if(!strcmp($oper_info->pay_test, "N")) echo "checked" ?>> PG업체 연동
          			</td>
          			<td class="t_value">
          				
          				
			          	<input name="pay_agent" value="DACOM" type="radio" <? if($oper_info->pay_agent == "DACOM") echo "checked"; ?>> LG유플러스 (<a href="http://ecredit.uplus.co.kr" target="_blank">http://ecredit.uplus.co.kr</a>) <a href="http://pgweb.uplus.co.kr/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop2" target="_blank" class="AW-btn">제휴 가입하기</a><br>

			          	<table width="600" border="0" cellpadding="4" cellspacing="6" bgcolor="#efefef" class="top5">
									<tr>
									<td>
									<font color="red">수수료 (카드결제 <s>3.3%</s> =>3.1%, 계좌이체 2.0%, 가상계좌 400원)</font><br>
									- 위 제휴가입하기 버튼을 클릭해서 가입해야만 수수료 할인이 적용됩니다.<br>
									- 진행절차 ( 
									<a href="http://pgweb.uplus.co.kr/pg/wmp/Home/application/apply_testid.jsp?cooperativecode=wizshop2" target="_blank">제휴가입</a> > 
									<a href="http://pgweb.uplus.co.kr:8080/pg/wmp/Home2014/renew_register/applyRenewal_agreementInfo3.jsp" target="_blank">등록비결제</a> > 
									<a href="http://pgweb.uplus.co.kr:8080/pg/wmp/Home2014/renew_register/presentation01.jsp" target="_blank">구비서류준비</a> > 등기발송) 문의 1544-7772<br><br>
									
									<font color="red">주의1 : 제휴가입을 통해 가입해야 결제시스템이 정상작동 합니다.</font><br><br>
		
									<font color="red">주의2 : 승인결과 전송 "결제창2.0"으로 변경</font><br>
									- "LG유플러 관리자 > 상점정보관리 > 승인 결과 전송 여부" 를 반드시 "결제창2.0" 으로 선택하세요<br>
									- "결제창2.0" 을 선택하지 않으면 결제가 정상적으로 이루어지지 않습니다.<br>
		
									</td>
									</tr>
									</table>
									
			          	<input name="pay_agent" value="KCP" type="radio" <? if($oper_info->pay_agent == "KCP") echo "checked"; ?> onclick="javascript:pg_dec('2')"> KCP (http://www.payplus.co.kr) <a href="https://admin8.kcp.co.kr/hp.HomePageAction.do?cmd=apply&host_id=애니위즈" target="_blank" class="AW-btn">제휴 가입하기</a><br>
			          	<!--<input name="pay_agent" value="INICIS" type="radio" <? if($oper_info->pay_agent == "INICIS") echo "checked"; ?> onclick="javascript:pg_dec('3')"> INICIS (http://www.inicis.com)<br>
			          	<input name="pay_agent" value="ALLTHEGATE" type="radio" <? if($oper_info->pay_agent == "ALLTHEGATE") echo "checked"; ?> onclick="javascript:pg_dec('4')"> AlltheGate (올더게이트)-->
			          </td>
			        </tr>
			      </table>
          </td>
        </tr>
        <tr>
          <td class="t_name">상점 ID</td>
          <td class="t_value">
            <input name="pay_id" value="<?=$oper_info->pay_id?>" type="text" class="input">
          </td>
        </tr>
        <tr>
          <td class="t_name">상점 mertkey</td>
          <td class="t_value">
            <input name="pay_key" value="<?=$oper_info->pay_key?>" type="text" size="50" class="input"> &nbsp;
          </td>
        </tr>
        <tr>
          <td class="t_name">은행계좌번호</td>
          <td class="t_value">
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td height="25" align="right">
                    	<input type="button" onClick="accIns()" class="AW-btn" value="은행계좌번호 등록" />
          			</td>
          		</tr>
          	</table>
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td height="25" align="center" class="t_name">은행명</td>
          			<td align="center" class="t_name">계좌번호</td>
          			<td align="center" class="t_name">예금주</td>
          			<td align="center" class="t_name">기능</td>
          		</tr>
		          <?php
		          $pay_account = explode("\n", $oper_info->pay_account);
		          for($ii = 0; $ii < count($pay_account); $ii++) {
		          	$account = explode("^", $pay_account[$ii]);
		          	if(!empty($account[0])) {
		          ?>
		          <tr>
          			<td height="20" align="center" class="t_value"><?=$account[1]?></td>
          			<td align="center" class="t_value"><?=$account[2]?></td>
          			<td align="center" class="t_value"><?=$account[3]?></td>
          			<td align="center" class="t_value">
                    	<a onClick="accMod('<?=$account[0]?>')" class="AW-btn-s modify">수정</a>
                    	<a onClick="accDel('<?=$account[0]?>')" class="AW-btn-s del">삭제</a>
          			</td>
          		</tr>
		          <?php
		          	}
		          }
		          ?>
          	</table>
            <!--textarea cols="50" rows="5" name="pay_account" class="textarea"><?=$oper_info->pay_account?></textarea//-->
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">가맹점 ID</td>
          <td><font color=black>: 결제 시스템사에서 부여받은 가맹점 고유 ID를 입력하세요</font></td>
        </tr>
        <tr>
          <td width="10"></td>
          <td valign="top" class="alert">은행계좌번호</td>
          <td>: 주문시 사용할 은행계좌를 입력합니다.</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 에스크로</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">
          	<input name="pay_escrow" value="Y" type="radio" <? if($oper_info->pay_escrow == "Y") echo "checked"; ?> > 사용함
          	<input name="pay_escrow" value="N" type="radio" <? if($oper_info->pay_escrow == "N") echo "checked"; ?> > 사용안함
          </td>
        </tr>
        <tr>
          <td class="t_name">에스크로 수신url</td>
          <td class="t_value">
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td class="t_name">LG데이콤</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/dacom/escrow_save.php</td>
          		</tr>
          		<tr>
          			<td class="t_name">KCP (PayPlus)</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/kcp/escrow_save.php</td>
          		</tr>
          		<!--tr>
          			<td class="t_name">INICIS (이니시스)</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/INICIS/escrow_save.php</td>
          		</tr//-->
          	</table>
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">무통장입금</td>
          <td><font color=black>: 에스크로 사용시 10만원이상의 주문에서는 무통장입금 방법이 사라집니다.</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">KCP를 사용하시는 경우</td>
          <td><font color=black>: 상점관리자 "쇼핑몰관리" > "정보변경" > "공통 리턴 URL정보" > "공통 리턴URL 변경후" 부분에 에스크로 수신url을 입력</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 가상계좌수신</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellpadding="5" cellspacing="1" class="t_style">
        <tr>
          <td width="15%" class="t_name">가상계좌 수신url</td>
          <td width="85%" class="t_value">
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td class="t_name">LG데이콤</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/dacom/order_update_vir.php</td>
          		</tr>
          		<tr>
          			<td class="t_name">KCP (PayPlus)</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/kcp/escrow_save.php</td>
          		</tr>
          		<!--tr>
          			<td class="t_name">INICIS (이니시스)</td>
          			<td class="t_value">http://<?=$HTTP_HOST?>/shop/INICIS/order_update_vir.php</td>
          		</tr-->
          	</table>
          </td>
        </tr>
      </table>
      <!--table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">KCP 상점관리자</td>
          <td><font color=black>:  "쇼핑몰관리" > "정보변경" > "공통 리턴 URL정보" > "공통 리턴URL 변경후" 부분에 가상계좌 수신url을 입력</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">INICIS 상점관리자</td>
          <td><font color=black>:  "승인" > "가상계좌" > "입금통보방식" > "URL 수신 사용" 선택 > "입금내역통보URL 필드" 부분에 가상계좌 수신url을 입력</td>
        </tr>
      </table-->

      <br>

      <a name="del"></a>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 배송정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">

		<!--
        <tr>
          <td class="t_name">배송추적설정</td>
          <td class="t_value">
          	<input name="del_trace" value="<?=$oper_info->del_trace?>" type="text" size="80" class="input">
          </td>
        </tr>
		-->
        <tr>
          <td width="15%" class="t_name">택배사정보</td>
          <td class="t_value">
			
            	
          	<table width="100%" border="0" cellspacing="2" cellpadding="1">
          		<tr>
          			<td height="25" align="right">
                    	<input type="button" onClick="delIns()" value="택배사 추가" class="AW-btn" />
          			</td>
          		</tr>
          	</table>
            
          	<table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#eeeeee">
          		<tr>
          			<td width="15%" height="25" align="center" class="t_name">택배사</td>
          			<td align="center" class="t_name">배송추적URL</td>
          			<td width="10%" align="center" class="t_name">기능</td>
          		</tr>
		          <?php
		          $del_info = explode("\n", $oper_info->del_trace);
		          for($ii = 0; $ii < count($del_info); $ii++) {
		          	$dellist = explode("^", $del_info[$ii]);
		          	if(!empty($dellist[0])) {
		          ?>
		          <tr>
          			<td height="20" align="center" class="t_value"><?=$dellist[1]?></td>
          			<td align="" class="t_value"><?=$dellist[2]?></td>
          			<td align="center" class="t_value">
                    	<a onClick="delMod('<?=$dellist[0]?>')" class="AW-btn-s modify">수정</a>
                    	<a onClick="delDel('<?=$dellist[0]?>')" class="AW-btn-s del">삭제</a>
          			</td>
          		</tr>
		          <?php
		          	}
		          }
		          ?>
          	</table>
            <!--textarea cols="50" rows="5" name="pay_account" class="textarea"><?=$oper_info->pay_account?></textarea//-->
          </td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기본 배송정책</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">배송무료</td>
          <td width="85%" class="t_value">
          <input type="radio" name="del_method" value="DA" <? if($oper_info->del_method == "DA") echo "checked"; ?>>
          배송비 전액무료</td>
        </tr>
        <tr>
          <td class="t_name">수신자부담</td>
          <td class="t_value">
          <input type="radio" name="del_method" value="DB" <? if($oper_info->del_method == "DB") echo "checked"; ?>>
          수신자부담 (착불)</td>
        </tr>
        <tr>
          <td class="t_name">고정값</td>
          <td class="t_value">
          <input type="radio" name="del_method" value="DC" <? if($oper_info->del_method == "DC") echo "checked"; ?>>
          <input name="del_fixprice" type="text" value="<?=$oper_info->del_fixprice?>" class="input">원</td>
        </tr>
        <tr>
          <td class="t_name">구매가격별</td>
          <td class="t_value">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <input type="radio" name="del_method" value="DD" <? if($oper_info->del_method == "DD") echo "checked"; ?>>
                  <input type="text" name="del_staprice" value="<?=$oper_info->del_staprice?>" class="input">
                </td>
                <td>&nbsp;이상구매시 <input type="text" name="del_staprice2" value="<?=$oper_info->del_staprice2?>" class="input"> 원</td>
              </tr>
              <tr>
                <td></td>
                <td>&nbsp;이하구매시 <input type="text" name="del_staprice3" value="<?=$oper_info->del_staprice3?>" class="input"> 원</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="t_name">지역할증</td>
          <td class="t_value">
          <table width="100%" border="0" cellspacing="5" cellpadding="0">
            <tr>
              <td width="10"></td>
              <td>우편번호</td>
              <td>할증료</td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost1" type="text" value="<?=$oper_info->del_extrapost1?>" class="input" size="9"> 부터
              <input name="del_extrapost12" type="text" value="<?=$oper_info->del_extrapost12?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice1" type="text" value="<?=$oper_info->del_extraprice1?>" class="input" size="20">원
              </td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost2" type="text" value="<?=$oper_info->del_extrapost2?>" class="input" size="9"> 부터
              <input name="del_extrapost22" type="text" value="<?=$oper_info->del_extrapost22?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice2" type="text" value="<?=$oper_info->del_extraprice2?>" class="input" size="20">원
              </td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost3" type="text" value="<?=$oper_info->del_extrapost3?>" class="input" size="9"> 부터
              <input name="del_extrapost32" type="text" value="<?=$oper_info->del_extrapost32?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice3" type="text" value="<?=$oper_info->del_extraprice3?>" class="input" size="20">원
              </td>
            </tr>
          </table>
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">배송료 선택</td>
          <td>: 배송료를 4가지 형태로 구분하며 각 상황별 배송료 설정을 합니다.</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td valign="top" class="alert">지역할증</td>
          <td>: 각지역별로 할증 배송료를 설정 합니다. 북제주군 한경면인 경우 695840 부터 695844 까지 2000원</td>
        </tr>
      </table>


      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품별 배송정책</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">무료배송 상품</td>
          <td width="85%" class="t_value">
          <input type="radio" name="del_prd" value="DA" <? if($oper_info->del_prd == "DA") echo "checked"; ?>> 무료배송 상품과 함께 주문할 경우, 전체 배송비를 무료로합니다.<br>
          <input type="radio" name="del_prd" value="DB" <? if($oper_info->del_prd == "DB") echo "checked"; ?>> 무료배송 상품과 함께 주문할 경우, 무료배송 상품을 제외한 상품은 배송비를 부과합니다.
          </td>
        </tr>
        <tr>
          <td class="t_name">상품별 배송비</td>
          <td class="t_value">
          <input type="radio" name="del_prd2" value="DA" <? if($oper_info->del_prd2 == "DA") echo "checked"; ?>> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비를 합산한 금액을 배송비로 지정합니다.<br>
          <input type="radio" name="del_prd2" value="DB" <? if($oper_info->del_prd2 == "DB") echo "checked"; ?>> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비 중 더 큰 배송비를 전체 배송비로 지정합니다.
          </td>
        </tr>
      </table>

      <br>
      <a name="res"></a>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 적립금정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td class="t_name">사용여부</td>
          <td class="t_value" colspan="3">
          <input type="radio" name="reserve_use" value="Y" <? if($oper_info->reserve_use == "Y") echo "checked"; ?>>사용함
          <input type="radio" name="reserve_use" value="N" <? if($oper_info->reserve_use == "N") echo "checked"; ?>>사용안함</td>
        </tr>
        <tr>
          <td width="15%" class="t_name">회원가입 적립금</td>
          <td width="35%" class="t_value"><input name="reserve_join" type="text" value="<?=$oper_info->reserve_join?>" class="input"></td>
          <td width="15%" class="t_name">추천인 적립금</td>
          <td width="35%" class="t_value"><input name="reserve_recom" type="text" value="<?=$oper_info->reserve_recom?>" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">최소사용 적립금</td>
          <td class="t_value"><input name="reserve_min" type="text" value="<?=$oper_info->reserve_min?>" class="input"></td>
          <td class="t_name">1회 최대사용 적립금</td>
          <td class="t_value"><input name="reserve_max" type="text" value="<?=$oper_info->reserve_max?>" class="input"></td>
        </tr>
        <tr>
          <td class="t_name">상품구매시 적립금</td>
          <td class="t_value"><input name="reserve_buy" type="text" value="<?=$oper_info->reserve_buy?>" class="input"> %</td>
          <td class="t_name">적립금 일괄적용</td>
          <td class="t_value">
            <input name="reserve_per" type="text" value="<?=$oper_info->reserve_per?>" class="input" size="10"> % &nbsp;
            <input type="button" onClick="setReserve();" value="적용" class="AW-btn-s" />
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">사용여부</td>
          <td>: 상품구입시 적립금 누적/사용 , 회원가입시, 추천인인경우 등 적립금 사용이 가능합니다.</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td valign="top" class="alert">상품구매시 적립금</td>
          <td>: 상품등록시 판매금액에 작성한 퍼센트를 적용하여 적립금이 자동계산됩니다.</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td valign="top" class="alert">적립금 일괄적용</td>
          <td>: 현재 등록되어있는 상품에 적립금을 작성한 퍼센트로 다시 적용합니다.</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품평 설정</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
	          <input type="radio" name="review_usetype" value="Y" <? if($review_info['usetype'] == "Y") echo "checked"; ?>>사용함
	          <input type="radio" name="review_usetype" value="N" <? if($review_info['usetype'] == "N") echo "checked"; ?>>사용안함
          </td>
          <td width="15%" class="t_name">작성권한</td>
          <td width="35%" class="t_value">
            <select name="review_wpermi">
            <option value="">전체</option>
            <?=level_list();?>
            <option value="-1">구매회원</option>
            <option value="0">관리자</option>
            </select>
          </td>
        </tr>
      </table>
      <script language="javascript">
        <!--
          wpermi = document.frm.review_wpermi;
          for(ii=0; ii<wpermi.length; ii++){
             if(wpermi.options[ii].value == "<?=$review_info['wpermi']?>")
                wpermi.options[ii].selected = true;
          }
        -->
      </script>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품Q&A 설정</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
	          <input type="radio" name="qna_usetype" value="Y" <? if($qna_info['usetype'] == "Y") echo "checked"; ?>>사용함
	          <input type="radio" name="qna_usetype" value="N" <? if($qna_info['usetype'] == "N") echo "checked"; ?>>사용안함
          </td>
          <td width="15%" class="t_name">작성권한</td>
          <td width="35%" class="t_value">
            <select name="qna_wpermi">
            <option value="">전체</option>
            <?=level_list();?>
            <option value="0">관리자</option>
            </select>
          </td>
        </tr>
      </table>
      <script language="javascript">
        <!--
          wpermi = document.frm.qna_wpermi;
          for(ii=0; ii<wpermi.length; ii++){
             if(wpermi.options[ii].value == "<?=$qna_info['wpermi']?>")
                wpermi.options[ii].selected = true;
          }
        -->
      </script>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 세금계산서 설정</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="35%" class="t_value">
	          <input type="radio" name="tax_use" value="Y" <? if($oper_info->tax_use == "Y") echo "checked"; ?>>사용함
	          <input type="radio" name="tax_use" value="N" <? if($oper_info->tax_use == "N") echo "checked"; ?>>사용안함
          </td>
          <td width="15%" class="t_name">발급시점</td>
          <td width="35%" class="t_value">
	          <input type="radio" name="tax_status" value="OY" <? if($oper_info->tax_status == "OY") echo "checked"; ?>>결제완료
	          <input type="radio" name="tax_status" value="DC" <? if($oper_info->tax_status == "DC") echo "checked"; ?>>배송완료
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 관련상품 사용여부</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">사용여부</td>
          <td width="85%" class="t_value">
	          <input type="radio" name="prdrel_use" value="Y" <? if($oper_info->prdrel_use == "Y") echo "checked"; ?>>사용함
	          <input type="radio" name="prdrel_use" value="N" <? if($oper_info->prdrel_use == "N") echo "checked"; ?>>사용안함
          </td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 입점업체 설정</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">입점업체 가입승인</td>
          <td width="35%" class="t_value">
          <input type="radio" name="mall_join" value="Y" <? if($oper_info->mall_join == "Y") echo "checked"; ?>>관리자 승인 후
          <input type="radio" name="mall_join" value="N" <? if($oper_info->mall_join == "N") echo "checked"; ?>>가입신청 즉시
          </td>
          <td width="15%" class="t_name">입점업체 상품승인</td>
          <td width="35%" class="t_value">
          <input type="radio" name="mall_prd" value="Y" <? if($oper_info->mall_prd == "Y") echo "checked"; ?>>관리자 승인 후
          <input type="radio" name="mall_prd" value="N" <? if($oper_info->mall_prd == "N") echo "checked"; ?>>상품등록 즉시
          </td>
        </tr>
        <tr>
          <td class="t_name">상품당 수수료설정</td>
          <td class="t_value">
          <input type="radio" name="mall_rate" value="Y" <? if($oper_info->mall_rate == "Y") echo "checked"; ?>>상품당 수수료설정 가능
          <input type="radio" name="mall_rate" value="N" <? if($oper_info->mall_rate == "N") echo "checked"; ?>>상품당 수수료설정 불가능
          </td>
          <td class="t_name">상품진열 승인여부</td>
          <td class="t_value">
          <input type="radio" name="mall_showset" value="Y" <? if($oper_info->mall_showset == "Y") echo "checked"; ?>>상품진열 설정가능
          <input type="radio" name="mall_showset" value="N" <? if($oper_info->mall_showset == "N") echo "checked"; ?>>상품진열 설정불가능
          </td>
        </tr>
        <tr>
          <td class="t_name">쿠폰할인액 부담설정</td>
          <td class="t_value">
          <input type="radio" name="mall_dis" value="S" <? if($oper_info->mall_dis == "S") echo "checked"; ?>>쇼핑몰
          <input type="radio" name="mall_dis" value="M" <? if($oper_info->mall_dis == "M") echo "checked"; ?>>입점업체
          <td class="t_name">적립금 부담설정</td>
          <td class="t_value">
          <input type="radio" name="mall_reserve" value="S" <? if($oper_info->mall_reserve == "S") echo "checked"; ?>>쇼핑몰
          <input type="radio" name="mall_reserve" value="M" <? if($oper_info->mall_reserve == "M") echo "checked"; ?>>입점업체
          </td>
          </td>
        </tr>
      </table>
      
    <div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on" />
        <a href="javascript:history.go(-1);">취소</a>
    </div><!-- .AW-btn-wrap -->

</form>
<? include "../footer.php"; ?>