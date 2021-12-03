<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if($mode == "") $mode = "insert";

if($mode == "insert"){

	$sch_info[simgsize] = "120";
	$sch_info[mimgsize] = "500";

	$sch_info[permsg] = "권한이 없습니다.";

}else if($mode == "update"){
	$sql = "select * from wiz_bbsinfo where code = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$sch_info = mysqli_fetch_array($result);
}
?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(frm){

   if(frm.code != undefined) {
	   if(frm.code.value == ""){
	      alert('일정 영문명(db명)을 입력하세요.');
	      frm.code.focus();
	      return false;
	   } else if(!Check_Char(frm.code.value)) {
	   		alert('일정 영문명(db명)은 특수문자를 사용할 수 없습니다.');
	      frm.code.focus();
	   		return false;
	   }
	 }

   if(frm.title.value == ""){
      alert('일정 한글명 입력하세요.');
      frm.title.focus();
      return false;
   }
}
//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">일정설정</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">일정 기능을 상세설정 합니다.</td>
	    </tr>
	  </table>
	  <br>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form name="frm" action="sch_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="page" value="<?=$page?>">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td class="t_name">영문명(db명) <font color=red>*</font></td>
                <td class="t_value" colspan="3">
                <? if(!strcmp($mode, "update")) { ?>
	                <?=$sch_info['code']?>
	                <input name="code" type="hidden" value="<?=$sch_info['code']?>">
                <? } else { ?>
                	<input name="code" type="text" size="30" value="<?=$sch_info['code']?>" <? if($mode == "update") echo "readonly"; ?> class="input">
                <? } ?>
                </td>
              </tr>
              <tr>
                <td class="t_name">한글명 <font color=red>*</font></td>
                <td class="t_value" colspan="3"><input name="title" type="text" size="30" value="<?=$sch_info['title']?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">타이들이미지</td>
                <td class="t_value" colspan="3">
                <?
                if($sch_info[titleimg] != "") echo "<img src=/data/bbs/$code/$sch_info[titleimg] width=500><a href=sch_save.php?mode=del_titleimg&code=$code><font color=red>[삭제]</font></a><br>";
                ?>
                <input name="titleimg" type="file" size="30" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_name">게시판주소</td>
                <td class="t_value" colspan="3">
                http://<?=$HTTP_HOST?>/schedule/list.php?code=<?=$sch_info['code']?> &nbsp;
                <a href="http://<?=$HTTP_HOST?>/schedule/list.php?code=<?=$sch_info['code']?>" target="_blank"><img src="../image/btn_overview.gif" border="0" align="absmiddle"></a></td>
              </tr>
              <!-- 상단/하단파일
              <tr>
                <td class="t_name">상단파일</td>
                <td class="t_value" colspan="3"><input name="header" type="text" size="30" value="<?=$sch_info[header]?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">하단파일</td>
                <td class="t_value" colspan="3"><input name="footer" type="text" size="30" value="<?=$sch_info[footer]?>" class="input"></td>
              </tr>
              상단/하단파일 //-->
              <tr>
                <td class="t_name">일정관리자</td>
                <td class="t_value" colspan="3">아이디를 쉼표로 분리 예)admin,admin1,admin3<br><input name="bbsadmin" type="text" size="60" value="<?=$sch_info[bbsadmin]?>" class="input"></td>
              </tr>
              <tr>
                <td width="15%" class="t_name">자동 비밀글</td>
                <td width="35%" class="t_value">
                  <input type="checkbox" name="privacy" value="Y" <? if($sch_info[privacy] == "Y") echo "checked"; ?>>작성자와 운영자만 연람가능
                </td>
                <td width="15%" class="t_name">일정스킨</td>
                <td width="35%" class="t_value">
                <select name="skin">
                <?
                $dh = opendir("../../schedule/skin");
                while(($file = readdir($dh)) !== false){
                	if($file != "." && $file != ".."){
                		$file_list[] = $file;
                	}
                }
                sort ($file_list); reset ($file_list);
                for($ii=0;$ii<count($file_list);$ii++){
                ?>
                <option value="<?=$file_list[$ii]?>"><?=$file_list[$ii]?></option>
                <?
                }
                ?>
                </select>
                </td>
              </tr>
              <tr>
                <td class="t_name">권한</td>
                <td class="t_value" colspan="3">
                  <table width="98%" border="0" cellpadding="0" cellspacing="0">
                    <tr class="t_name">
                      <td width="20%" align="center" height="25">목록보기</td>
                      <td width="20%" align="center">내용보기</td>
                      <td width="20%" align="center">글쓰기</td>
                      <td width="20%" align="center">답글쓰기</td>
                      <td width="20%" align="center">코멘트쓰기</td>
                    </tr>
                    <tr>
                      <td align="center" height="25">
                        <select name="lpermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="rpermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="wpermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="apermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                      <td align="center">
                        <select name="cpermi">
                        <option value="">전체</option>
                        <?=level_list();?>
                        <option value="0">관리자</option>
                        </select>
                      </td>
                    </tr>
                  </table>
                  <script language="javascript">
                    <!--
                      skin = document.frm.skin;
                      for(ii=0; ii<skin.length; ii++){
                         if(skin.options[ii].value == "<?=$sch_info[skin]?>")
                            skin.options[ii].selected = true;
                      }
                      lpermi = document.frm.lpermi;
                      for(ii=0; ii<lpermi.length; ii++){
                         if(lpermi.options[ii].value == "<?=$sch_info[lpermi]?>")
                            lpermi.options[ii].selected = true;
                      }
                      rpermi = document.frm.rpermi;
                      for(ii=0; ii<rpermi.length; ii++){
                         if(rpermi.options[ii].value == "<?=$sch_info[rpermi]?>")
                            rpermi.options[ii].selected = true;
                      }
                      wpermi = document.frm.wpermi;
                      for(ii=0; ii<wpermi.length; ii++){
                         if(wpermi.options[ii].value == "<?=$sch_info[wpermi]?>")
                            wpermi.options[ii].selected = true;
                      }
                      apermi = document.frm.apermi;
                      for(ii=0; ii<apermi.length; ii++){
                         if(apermi.options[ii].value == "<?=$sch_info[apermi]?>")
                            apermi.options[ii].selected = true;
                      }
                      cpermi = document.frm.cpermi;
                      for(ii=0; ii<cpermi.length; ii++){
                         if(cpermi.options[ii].value == "<?=$sch_info[cpermi]?>")
                            cpermi.options[ii].selected = true;
                      }
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td rowspan="2" class="t_name">권한이 없을경우</td>
                <td class="t_value" colspan="3">
                	경고메세지 : <input name="permsg" type="text" size="30" value="<?=$sch_info[permsg]?>" class="input">&nbsp;
                	경고후 이동페이지 : <input name="perurl" type="text" size="30" value="<?=$sch_info[perurl]?>" class="input">
                </td>
              </tr>
              <tr>
                <td class="t_value" colspan="3">
                	<input type="radio" name="btn_view" value="N" <? if($sch_info[btn_view] == "N" || $sch_info[btn_view] == "") echo "checked"; ?>> 글쓰기 버튼이 보이지 않음
                	<input type="radio" name="btn_view" value="Y" <? if($sch_info[btn_view] == "Y") echo "checked"; ?>> 글쓰기 버튼이 보이고 클릭 시 경고창
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지크기</td>
                <td class="t_value" colspan="3">
                	목록페이지  : <input name="simgsize" type="text" size="9" value="<?=$sch_info[simgsize]?>" class="input">픽셀 &nbsp;
                	보기페이지  : <input name="mimgsize" type="text" size="9" value="<?=$sch_info[mimgsize]?>" class="input">픽셀
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지파일</td>
                <td class="t_value" colspan="3">
                	<input type="checkbox" name="imgview" value="N" <? if($sch_info[imgview] == "N") echo "checked"; ?>>첨부파일이 이미지인 경우 보기 페이지에서 이미지 감추기
                </td>
              </tr>
              <tr>
                <td class="t_name">이미지 첨부파일 정렬</td>
                <td class="t_value">
                	<input type="radio" name="img_align" value="LEFT" <? if($sch_info[img_align] == "LEFT" || $sch_info[img_align] == "") echo "checked"; ?>> 좌측정렬
                	<input type="radio" name="img_align" value="CENTER" <? if($sch_info[img_align] == "CENTER") echo "checked"; ?>> 중앙정렬
                	<input type="radio" name="img_align" value="RIGHT" <? if($sch_info[img_align] == "RIGHT") echo "checked"; ?>> 우측정렬
                </td>
                <td class="t_name">날짜형식(보기페이지)</td>
                <td class="t_value">
                	<select name=datetype_view>
                		<option value="">:: 보기페이지 :: </option>
                		<option value="%y.%m.%d"><?= date('y.m.d') ?></option>
                		<option value="%y/%m/%d"><?= date('y/m/d') ?></option>
                		<option value="%y-%m-%d"><?= date('y-m-d') ?></option>
                		<option value="%Y.%m.%d"><?= date('Y.m.d') ?></option>
                		<option value="%Y/%m/%d"><?= date('Y/m/d') ?></option>
                		<option value="%Y-%m-%d"><?= date('Y-m-d') ?></option>
                		<option value="%Y년 %m월 %d일"><?= date('Y년 m월 d일') ?></option>
                		<option value="%Y-%m-%d %H:%i"><?= date('Y-m-d H:i') ?></option>
                		<option value="%Y-%m-%d %H:%i %p"><?= date('Y-m-d h:i A') ?></option>
                	</select>
                  <script language="javascript">
                    <!--
                      datetype_view = document.frm.datetype_view;
                      for(ii=0; ii<datetype_view.length; ii++){
                         if(datetype_view.options[ii].value == "<?=$sch_info[datetype_view]?>")
                            datetype_view.options[ii].selected = true;
                      }
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td height="30" class="t_name">웹에디터</td>
                <td class="t_value">
                	<input type="radio" name="editor" value="Y" <? if($sch_info[editor] == "Y") echo "checked"; ?>>사용함
                  <input type="radio" name="editor" value="N" <? if($sch_info[editor] == "N" || $sch_info[editor] == "") echo "checked"; ?>>사용안함
                </td>
                <td height="30" class="t_name">코멘트 허용</td>
                <td class="t_value">
                  <input type="radio" name="comment" value="Y" <? if($sch_info[comment] == "Y") echo "checked"; ?>>허용함
                  <input type="radio" name="comment" value="N" <? if($sch_info[comment] == "N" || empty($sch_info[comment])) echo "checked"; ?>>허용안함
                </td>
              </tr>
              <tr>
                <td height="30" class="t_name">파일업로드</td>
                <td class="t_value">
                	<select name="upfile">
                		<option value="0">사용안함</option>
                		<option value="1">1개</option>
                		<option value="2">2개</option>
                		<option value="3">3개</option>
                		<option value="4">4개</option>
                		<option value="5">5개</option>
                	</select>
                  <script language="javascript">
                    <!--
                      upfile = document.frm.upfile;
                      for(ii=0; ii<upfile.length; ii++){
                         if(upfile.options[ii].value == "<?=$sch_info[upfile]?>")
                            upfile.options[ii].selected = true;
                      }
                    -->
                  </script>
                </td>
                <td height="30" class="t_name">동영상</td>
                <td class="t_value">
                	<select name="movie">
                		<option value="0">사용안함</option>
                		<option value="1">1개</option>
                		<option value="2">2개</option>
                		<option value="3">3개</option>
                	</select>
                  <script language="javascript">
                    <!--
                      movie = document.frm.movie;
                      for(ii=0; ii<movie.length; ii++){
                         if(movie.options[ii].value == "<?=$sch_info[movie]?>")
                            movie.options[ii].selected = true;
                      }
                    -->
                  </script>
                </td>
              </tr>
              <tr>
                <td class="t_name">스팸글체크기능</td>
                <td class="t_value">
                	<input type="radio" name="spam_check" value="Y" <? if($sch_info[spam_check] == "Y") echo "checked"; ?>>사용함
                	<input type="radio" name="spam_check" value="N" <? if($sch_info[spam_check] == "N" || $sch_info[spam_check] == "") echo "checked"; ?>>사용안함
                </td>
                <td height="30" class="t_name">추천기능</td>
                <td class="t_value">
                	<input type="radio" name="recom" value="Y" <? if($sch_info[recom] == "Y") echo "checked"; ?>>사용함
                  <input type="radio" name="recom" value="N" <? if($sch_info[recom] == "N" || empty($sch_info[recom])) echo "checked"; ?>>사용안함
                </td>
              </tr>
              <tr>
                <td class="t_name">욕설,비방글<br>필터링</td>
                <td class="t_value" colspan="3">
                  <input type="checkbox" name="abuse" value="Y" <? if($sch_info[abuse] == "Y") echo "checked"; ?>>사용함 &nbsp; (공백없이 단어를 입력하시고, 단어와 단어사이에는 콤마(,)로 구분하세요.)<br>
                  <textarea name="abtxt" rows="3" cols="80" class="textarea"><?=$sch_info[abtxt]?></textarea></td>
              </tr>
            </table></td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="9d9d9d">
        <tr>
          <td width="5" height="5"><img src="../image/check_left_top.gif"></td>
          <td width="100%"></td>
          <td width="5" height="5"><img src="../image/check_right_top.gif"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="6">
            <tr>
              <td><img src="../image/check_tit.gif" width="75" height="19" /></td>
            </tr>
            <tr>
              <td class="chk_alt">
            	- 영문명은 반드시 영문으로 작성하고 변경이 불가합니다.<br>
            	- 권한설정은 각 상황별 회원분류에따라 접근권한을 설정합니다.<br>
            	- 욕설,비방글 설정을 통하여 글 작성시 욕설 비방글을 방지할 수 있습니다.
              </td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="5" height="5"><img src="../image/check_left_bottom.gif" width="5" height="5" /></td>
          <td></td>
          <td width="5" height="5"><img src="../image/check_right_bottom.gif" width="5" height="5" /></td>
        </tr>
      </table>

      <br>
      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input type="image" src="../image/btn_confirm_l.gif"></td>
        </tr>
      </form>
      </table>

<? include "../footer.php"; ?>