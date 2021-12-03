<script language="JavaScript">
<!--
function bbsCheck(frm){

  if(frm.name.value == ""){
    alert("작성자를 입력하세요.");
    frm.name.focus();
    return false;
  }
  if(frm.passwd != null && frm.passwd.value == ""){
    alert("비밀번호를 입력하세요.");
    frm.passwd.focus();
    return false;
  }
  if(frm.subject.value == ""){
    alert("제목을 입력하세요.");
    frm.subject.focus();
    return false;
  }
  if(frm.star != undefined) {
	   if(
	      frm.star[0].checked == false &&
	      frm.star[1].checked == false &&
	      frm.star[2].checked == false &&
	      frm.star[3].checked == false &&
	      frm.star[4].checked == false
	   ){
	      alert("평점을 선택하세요");
	      return false;
	   }
  }
  try{ content.outputBodyHTML(); } catch(e){ }
  if(frm.content.value == ""){
		alert("내용을 입력하세요.");
		return false;
  }
  if (frm.vcode != undefined && (hex_md5(frm.vcode.value) != md5_norobot_key)) {
  	alert("자동등록방지코드를 정확히 입력해주세요.");
    frm.vcode.focus();
    return false;
	}

}
-->
</script>
<div style="text-align:right; margin:0 0 10px;"><b>*</b> 표시는 필수입력 사항으로 글 작성시 반드시 기재해야 하는 항목입니다.</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AWbbs_input_table">
<form name="bbsFrm" action="<?=$PHP_SELF?>" method="post" enctype="multipart/form-data" onSubmit="return bbsCheck(this)">
<input type="hidden" name="ptype" value="save">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
    <tr>
        <th width="15%">작성자 *</th>
        <td width="35%"><input name="name" value="<?=$name?>" type="text" class="input w150" /></td>
        <th width="15%">이메일</th>
        <td width="35%"><input name="email" value="<?=$email?>" type="text" class="input w200" /></td>
    </tr>
    
    <?=$hide_admin_start?>
    <tr>
        <th height="30">작성일</th>
        <td><input name="wdate" value="<?=$wdate?>" type="text" class="input w150" /></td>
        <th>조회수</th>
        <td><input name="count" value="<?=$count?>" type="text" class="input w150" /></td>
    </tr>
    <?=$hide_admin_end?>
    
    <?=$hide_passwd_start?>
    <tr>
        <th>비밀번호 *</th>
        <td colspan="3"><input name="passwd" value="<?=$passwd?>" type="password" class="input w150" /> * 글 수정 삭제시 필요하시 꼭 기재해 주시기 바랍니다.</td>
    </tr>
    <?=$hide_passwd_end?>
    
    <tr>
        <th>제목 *</th>
        <td colspan="3"><?=$catlist?><input name="subject" value="<?=$subject?>" type="text" class="input" style="width:70%;" /></td>
    </tr>
    
    <?=$hide_star_start?>
    <tr>
        <th>평점 *</th>
        <td colspan="3">
            <input name="star" type="radio" value="5" style="border:0px; background-color:transparent;" <?=$star5_checked?>><img src="/adm/images/icon_star_5.gif">&nbsp;&nbsp;&nbsp;
            <input name="star" type="radio" value="4" style="border:0px; background-color:transparent;" <?=$star4_checked?>><img src="/adm/images/icon_star_4.gif">&nbsp;&nbsp;&nbsp;
            <input name="star" type="radio" value="3" style="border:0px; background-color:transparent;" <?=$star3_checked?>><img src="/adm/images/icon_star_3.gif">&nbsp;&nbsp;&nbsp;
            <input name="star" type="radio" value="2" style="border:0px; background-color:transparent;" <?=$star2_checked?>><img src="/adm/images/icon_star_2.gif">&nbsp;&nbsp;&nbsp;
            <input name="star" type="radio" value="1" style="border:0px; background-color:transparent;" <?=$star1_checked?>><img src="/adm/images/icon_star_1.gif">
        </td>
    </tr>
    <?=$hide_star_end?>
    
    <tr>
    	<td colspan="4" align="center" style="padding:10px 0;">
        	<div class="AW_bbs_input_checkbox">
            	<input type="checkbox" name="ctype" value="H" <?=$ctype_checked?> id="AWhtml" /> <label for="AWhtml">HTML사용</label>
                <input type="checkbox" name="privacy" value="Y" <?=$privacy_checked?> id="AWsecret" /> <label for="AWsecret">비밀글</label>
                <?=$hide_notice_start?>
                <input type="checkbox" name="notice" value="Y" <?=$notice_checked?> id="AWnotice" /> <label for="AWnotice">공지글</label>
                <?=$hide_notice_end?>
            </div>
           	<div>
            	<?
				if($bbs_info[editor] == "Y"){
					$edit_content = $content;
					include WIZHOME_PATH."/webedit/WIZEditor.html";
				}else{
				?>
				<textarea name="content" cols="85" rows="13" class="input" style="width:98%; word-break:break-all;"><?=$content?></textarea>
				<?
				}
				?>
            </div>
        </td>
    </tr>
    
    <?
    for($ii=1;$ii<=5;$ii++){
    echo ${"hide_upfile".$ii."_start"};
    ?>
    <tr>
        <th>첨부파일<?=$ii?></th>
        <td colspan="3"><input type="file" name="upfile<?=$ii?>" class="input w200" /> <?=${"upfile".$ii}?></td>
    </tr>
    <?
    echo ${"hide_upfile".$ii."_end"};
    }
    ?>
    
    <?
    for($ii=1;$ii<=3;$ii++){
    echo ${"hide_movie".$ii."_start"};
    if($ii == 1) $input_type = "file";
    else $input_type = "text";
    ?>
    <tr>
        <th>동영상<?=$ii?></th>
        <td colspan="3"><input type="<?=$input_type?>" name="movie<?=$ii?>" class="input w200" /> <?=${"movie".$ii}?></td>
    </tr>
    <?
    echo ${"hide_movie".$ii."_end"};
    }
    ?>
    
    <?=$hide_spam_check_start?>
    <tr>
        <th>자동등록방지</th>
        <td colspan="3"><?=$spam_check?></td>
    </tr>
    <?=$hide_spam_check_end?>
</table>


<div style="margin:10px 0 0;">
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
    <td align="left"><?=$list_btn?></td>
    <td align="right"><?=$confirm_btn?>&nbsp;<?=$cancel_btn?></td>
</tr></table>
</div>
