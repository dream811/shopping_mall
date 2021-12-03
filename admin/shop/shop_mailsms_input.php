<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
if($sub_mode == "update"){
   $sql = "select * from wiz_mailsms where code = '$code'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   $row = mysqli_fetch_object($result);
}
?>
<script language="javascript">
<!--
function inputCheck(frm){

	if(frm.code.value == ""){
		alert("코드명을 입력하세요");
		frm.code.focus();
		return false;
	}
	if(frm.subject.value == ""){
		alert("분류명을 입력하세요");
		frm.subject.focus();
		return false;
	}
	content.outputBodyHTML();
	
}

function calByte(type, aquery){
	
	var tmpStr;
	var temp = 0;
	var onechar;
	var tcount = 0;;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(k=0; k<temp; k++) {
		onechar = tmpStr.charAt(k);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}
		
		if(type == "cust") frm.sms_custbyte.value = tcount+"/80 bytes";
		else  frm.sms_operbyte.value = tcount+"/80 bytes";
		
		if(tcount > 80) {
			alert("메시지내용은 80 바이트 이상 전송할 수 없습니다.");
			
			if(type == "cust") cutText(type, frm.sms_msg.value);
			else cutText(type, frm.sms_opermsg.value);
			
			
			return;
		}
	}
	if ( temp == 0 ) { 
		
		if(type == "cust") frm.sms_custbyte.value = "0/80 bytes";
		else  frm.sms_operbyte.value = "0/80 bytes";
		
	}
}

function cutText(type, aquery) {
	
	var tmpStr;
	var temp=0;
	var onechar;
	var tcount = 0;

	tmpStr = new String(aquery);
	temp = tmpStr.length;
	for(t=0; t<temp; t++){
		onechar = tmpStr.charAt(t);
		if(escape(onechar).length > 4) {
			tcount += 2;
		} else if(onechar != '\n' || onechar != '\r') {
			tcount++;
		}
		if(tcount > 80) {
			tmpStr = tmpStr.substring(0, t);
			break;
		}
	}
	
	if(type == "cust") document.frm.sms_msg.value = tmpStr;
	else  document.frm.sms_opermsg.value = tmpStr;
	
	calByte(type, tmpStr);        
}

function checkSmsmsg(type){
	
	var tmpStr;
	if(type == "cust" && document.frm.sms_msg != null){
		tmpStr = document.frm.sms_msg.value;
		calByte(type, tmpStr);
	}
}

-->
</script>


      <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">메일<? if(!strcmp($shop_info->sms_use, "Y")) { ?>/SMS<? } ?>설정</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">각 상황별 메일내용을 설정합니다.</td>
        </tr>
      </table>

			<br>
<form name="frm" action="shop_save.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="mailsms">
<input type="hidden" name="sub_mode" value="<?=$sub_mode?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="t_style">
      
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td width="15%" class="t_name">코드 <font color=red>*</font></td>
                <td width="85%" class="t_value">
                <input type="text" name="code" value="<?=$row->code?>" size="20" class="input" <? if($sub_mode == "update") echo "readonly"; ?>>
                </td>
              </tr>
              <tr> 
                <td class="t_name">분류명 <font color=red>*</font></td>
                <td class="t_value">
                <input type="text" name="subject" value="<?=$row->subject?>" size="60" class="input">
                </td>
              </tr>
              <?
              if($code != "mem_notice" && !strcmp($shop_info->sms_use, "Y")){
              ?>
              <tr> 
                <td class="t_name">SMS수신</td>
                <td class="t_value">
                  <input type="checkbox" name="sms_cust" value="Y" <? if($row->sms_cust == 'Y') echo "checked"; ?>>고객수신 &nbsp; &nbsp; 
                  <input type="checkbox" name="sms_oper" value="Y" <? if($row->sms_oper == 'Y') echo "checked"; ?>>관리자수신<br>
                  <textarea cols="35" rows="5" name="sms_msg" onKeyDown="checkSmsmsg('cust');" class="textarea"><?=$row->sms_msg?></textarea>
                  <input type="text" name="sms_custbyte" size="11" style="height:14px; border: 1px solid #91FBFF; ; font-size:8pt; font-family:돋움; background-color:#91FBFF" value="0/80 bytes" onfocus="this.blur()">
                </td>
              </tr>
              <?
              }
              ?>
              <?
              if($code != "mem_notice"){
              ?>
              <tr> 
                <td class="t_name">메일수신</td>
                <td class="t_value">
                <input type="checkbox" name="email_cust" value="Y" <? if($row->email_cust == 'Y') echo "checked"; ?>>고객수신 &nbsp; &nbsp; 
                <input type="checkbox" name="email_oper" value="Y" <? if($row->email_oper == 'Y') echo "checked"; ?>>관리자수신
                </td>
              </tr>
              <tr> 
                <td class="t_name">메일제목</td>
                <td class="t_value">
                <input type="text" name="email_subj" value="<?=$row->email_subj?>" size="80" class="input">
                </td>
              </tr>
              <?
              }
              ?>
              <tr> 
                <td class="t_name">메일내용</td>
                <td class="t_value">
                
                <?
                $edit_content = $row->email_msg;
                if(!get_magic_quotes_gpc()) $edit_content = stripslashes($edit_content);
                $edit_content = str_replace("{SHOP_URL}", "http://".$HTTP_HOST, $edit_content);
                include "../webedit/WIZEditor.html";
                ?>
                
                 <table width="98%" border="0" cellpadding="5" cellspacing="3" align="center" class="e_style">
                    <tr>
                      <td bgcolor="#FFFFFF">
                      <table>
                      <tr>
                      <td><b>{DATE}</b> 오늘날자 &nbsp;</td>
                      <td><b>{MEM_ID}</b> 회원아이디 &nbsp;</td>
                      <td><b>{MEM_PW}</b> 회원비밀번호 &nbsp;</td>
                      </tr>
                      <tr>
                      <td><b>{MEM_NAME}</b> 회원이름</td>
                      <td><b>{SHOP_NAME}</b> 사이트명 &nbsp;</td>
                      <td><b>{SHOP_EMAIL}</b> 사이트 이메일</td>
                      </tr>
                      <tr>
                      <td><b>{SHOP_TEL}</b> 사이트 전화번호 &nbsp;</td>
                      <td><b>{SHOP_URL}</b> 사이트 주소로 변경되어 발송됩니다.</td>
                      <td></td>
                      </tr>
                      <tr>
                      <td><b>{ORDER_INFO}</b> 주문정보 &nbsp;</td>
                      <td></td>
                      <td></td>
                      </tr>
                      </table>
                    </tr>
                  </table>
                  
                </td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>

    <div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on" />
        <a onclick="document.location='shop_mailsms.php';">목록</a>
    </div><!-- .AW-btn-wrap -->


</form>

      <script language="javascript">
      <!--
      checkSmsmsg('cust');
      //checkSmsmsg('oper');
      -->
      </script>

<? include "../footer.php"; ?>