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
<form name="frm" action="/bbs/save.php" method="post" enctype="multipart/form-data" onSubmit="return bbsCheck(this)">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="searchopt" value="<?=$searchopt?>">
<input type="hidden" name="searchkey" value="<?=$searchkey?>">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
<input type="hidden" name="tmp_vcode" value="<?=md5($norobot_key)?>">
<input type="hidden" name="mallid" value="<?=$mallid?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="AW_common_table view write">

  <tr>
    <th><strong>작성자 *</strong></th>
    <td width="35%" align="left" style="padding-left:10px; border-right:1px solid #d7d7d7;"><input name="name" value="<?=$name?>" type="text" size="20" class="input" /></td>
    <th><strong>이메일</strong></th>
    <td width="35%" align="left" style="padding-left:10px;"><input name="email" value="<?=$email?>" type="text" size="30" class="input" /></td>
  </tr>
  <?=$hide_passwd_start?>
  <tr>
    <th><strong>비밀번호 *</strong></th>
  	<td align="left" colspan="3" style="padding-left:10px;"><input name="passwd" value="<?=$passwd?>" type="password" size="20" class="input" /> * 글 수정 삭제시 필요하시 꼭 기재해 주시기 바랍니다.</td>
  </tr>
  <?=$hide_passwd_end?>
  <tr>
    <th><strong>제목 *</strong></th>
    <td align="left" colspan="3" style="padding-left:10px;"><?=$catlist?><input name="subject" value="<?=$subject?>" type="text" size="60" class="input" /></td>
  </tr>
  <?=$hide_star_start?>
  <tr>
    <th><strong>평점 *</strong></th>
    <td align="left" colspan="3" style="padding-left:10px;">
		<input name="star" value="5" type="radio" <?=$star5_checked?> id="star1"><label for="star1"><img src="/images/icon_star_5.gif" alt="평점5"></label>
    	<input name="star" value="4" type="radio" <?=$star4_checked?> id="star2"><label for="star2"><img src="/images/icon_star_4.gif" alt="평점4"></label>
    	<input name="star" value="3" type="radio" <?=$star3_checked?> id="star3"><label for="star3"><img src="/images/icon_star_3.gif" alt="평점3"></label>
    	<input name="star" value="2" type="radio" <?=$star2_checked?> id="star4"><label for="star4"><img src="/images/icon_star_2.gif" alt="평점2"></label>
    	<input name="star" value="1" type="radio" <?=$star1_checked?> id="star5"><label for="star5"><img src="/images/icon_star_1.gif" alt="평점1"></label>
    </td>
  </tr>
	<?=$hide_star_end?>
  <tr>
    <td colspan="4" align="center">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td valign="top" align="left">
			<input type="checkbox" name="ctype" value="H" <?=$ctype_checked?> id="chk1"><label for="chk1">HTML사용</label>
			<input type="checkbox" name="privacy" value="Y" <?=$privacy_checked?> id="chk2"><label for="chk2">비밀글</label>
			<?=$hide_notice_start?>
			<input type="checkbox" name="notice" value="Y" <?=$notice_checked?> id="chk3"><label for="chk3">공지글</label>
			<?=$hide_notice_end?>
		</td>
		</tr>
		<tr>
		<td align="left" valign="top">
			<?
			if($bbs_info[editor] == "Y"){
				$edit_content = $content;
				include "../admin/webedit/WIZEditor.html";
			}else{
			?>
			<textarea name="content" cols="85" rows="20" class="input" style="width:100%;word-break:break-all;"><?=$content?></textarea>
			<?
			}
			?>
		</td>
		</tr>
		</table>
    </td>
  </tr>

  <?
  for($ii=1;$ii<=5;$ii++){
  	echo ${"hide_upfile".$ii."_start"};
  ?>
  <tr>
    <th><strong>첨부파일<?=$ii?></strong></th>
    <td align="left" colspan="3" style="padding-left:10px;"><input type="file" name="upfile<?=$ii?>" size="20" class="input" /> <?=${"upfile".$ii}?></td>
  </tr>
  <?
		echo ${"hide_upfile".$ii."_end"};
	}
	?>

  <?
  for($ii=1;$ii<=3;$ii++){
  	echo ${"hide_movie".$ii."_start"};
  ?>
  <tr>
    <th><strong>동영상<?=$ii?></strong></th>
    <td align="left" colspan="3" style="padding-left:10px;"><input type="file" name="movie<?=$ii?>" size="20" class="input" /> <?=${"movie".$ii}?></td>
  </tr>
  <?
		echo ${"hide_movie".$ii."_end"};
	}
	?>

	<?=$hide_spam_check_start?>
	<tr>
    <th><strong>자동등록방지</strong></th>
    <td align="left" colspan="3" style="padding-left:10px;"><?=$spam_check?></td>
  </tr>
	<?=$hide_spam_check_end?>

</table>
<div class="AW_board_btn clearfix">
	<div class="left">
		<?=$list_btn?>
	</div>
	<div class="right">
		<?=$confirm_btn?><?=$cancel_btn?>
	</div>
</div>
</form>
