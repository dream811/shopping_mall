<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="javascript">
<!--
function inputCheck(frm){
   
  //if(frm.sms_id.value == ""){
	//	alert("sms아이디를 입력하세요.");
	//	frm.sms_id.focus();
	//	return false;
	//}
   
}

function popJoin(stype) {
	
	if(stype == "J"){
		document.smsjoin.action = "http://www.icodekorea.com/res/join_company_fix.php";
		window.open("", "popJoin", "height=700, width=617, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	}else{
		window.open("", "popJoin", "height=600, width=617, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	}
	
	document.smsjoin.submit();

}

function popFill() {
	
<? if(!empty($oper_info->sms_id) && !empty($oper_info->sms_pw)) { ?>
	window.open("", "popFill", "height=500, width=667, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
	document.smsfill.submit();
<? } else { ?>
	alert("sms아이디와 비밀번호를 입력하세요.");
<? } ?>

}

function popLog() {
	
<? if(!empty($oper_info->sms_id) && !empty($oper_info->sms_pw)) { ?>
   window.open("", "popLog", "height=550, width=750, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=50, top=50");
   document.smslog.submit();
<? } else { ?>
	alert("sms아이디와 비밀번호를 입력하세요.");
<? } ?>
	
}

-->
</script>


      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">SMS관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">SMS를 충전합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form action="shop_save.php" method="post" onSubmit="return inputCheck(this)">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="smsfill">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
            	<tr>
                <td width="17%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sms종류</td>
                <td colspan="3" class="t_value">
                	<table width="100%">
                		<tr>
                			<td>
                				<input name="sms_type" type="radio" value="C" <? if($oper_info->sms_type == "" || $oper_info->sms_type == "C") echo "checked"; ?>><b>충전제</b> (건당 25원, 10만원 충전시 4000건 발송)
                			</td>
                			<td>
                            	<a onClick="popJoin('C')" class="AW-btn">충전식신청</a>
                            	<a onClick="popFill()" class="AW-btn">충전하기</a>
                			</td>
                			<td rowspan="2" align="right">
                            	<a onClick="popLog()" class="AW-btn">발송결과조회</a>
                			</td>
                		</tr>
                		<tr>
                			<td>
                				<input name="sms_type" type="radio" value="J" <? if($oper_info->sms_type == "J") echo "checked"; ?>><b>정액제</b> (건당 20원, 월50,000원으로 2500건)
                			</td>
                			<td>
                            	<a onClick="popJoin('J')" class="AW-btn">정액제신청</a>
                			</td>
                		</tr>
                	</table>
                </td>
              </tr>
              <tr>
                <td width="17%" align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sms아이디</td>
                <td colspan="3" class="t_value">Any_<input name="sms_id" type="text" value="<?=str_replace("Any_","",$oper_info->sms_id)?>" style="width:163" class="input"></td>
              </tr>
              <tr>
                <td align="left" class="t_name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;sms비밀번호</td>
                <td colspan="3" class="t_value"><input name="sms_pw" type="password" value="<?=$oper_info->sms_pw?>" size="30" class="input"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      
    <div class="AW-btn-wrap">
    	<button type="submit" class="on">확인</button>
    </div><!-- .AW-btn-wrap -->
</form>


<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
    	<b>주의사항</b><br />
        - sms 아이디,비밀번호를 입력후 저장해야 충전이 가능합니다.<br />
        - 발송결과조회을 클릭하시면 발송한 sms에 대한 내용을 확인할 수 있습니다.<br />
        - 발송이 되지 않는 경우 방화벽에서 IP(211.172.232.124)와 Port(7192~7195)를 확인해보세요.<br />
        - 시스템상에서 잔액이 없을 시 자동으로 서비스가 종료됩니다.<br />
        - 회신번호는 "기본정보>관리자 휴대폰" 번호이므로 사용하고 계신 번호로 변경하시기 바랍니다.<br />
        - SMS서비스는 <a href="http://icodekorea.com" target="_blank">아이코드(http://icodekorea.com)</a>와 제휴하여 제공됩니다.<br />
        - 비용/계산서관련 문의는 아이코드 연락하시기 바랍니다.<br /><br />
        
        <b>사용법</b><br />
        1. 신청하기 버튼을 눌러 신청서를 작성, SMS를 충전 합니다.<br />
        2. 신청한 ID와 암호를 sms아이디, sms비밀번호에 입력한 후 저장합니다.<br />
        3. 1번,2번을 다 마치시면 SMS 발송이 가능합니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->
      
      
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td align="center">
         <iframe src="http://www.icodekorea.com/res/userinfo.php?userid=<?=$oper_info->sms_id?>&userpw=<?=$oper_info->sms_pw?>&r_url=http://<?=$HTTP_HOST?>/admin/shop/shop_smsinfo.php" frameborder="0" width="100%" height="400"></iframe>
       </tr>
      </table>

			<!-- sms신청하기 -->
      <form name="smsjoin" method="post" target="popJoin" action="http://www.icodekorea.com/res/join_company_fix_a.php">
			<input type="hidden" name="sellid" value="anywiz">	
			</form>

			<!-- sms충전하기 -->
			<form name="smsfill" method="post" target="popFill" action="http://www.icodekorea.com/company/credit_card_input.php">
			<input type="hidden" name="icode_id" value="<?=$oper_info->sms_id?>">
			<input type="hidden" name="icode_passwd" value="<?=$oper_info->sms_pw?>">
			</form>

			<!-- sms발송내역 -->
			<form name="smslog" method="post" target="popLog" action="http://www.icodekorea.com/icodemsg/web/sms_sendmail_box.php">
			<input type="hidden" name="icode_id" value="<?=$oper_info->sms_id?>">
			<input type="hidden" name="icode_passwd" value="<?=$oper_info->sms_pw?>">	
			</form>

<? include "../footer.php"; ?>