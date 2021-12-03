<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/bbs_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
// 검색 파라미터
$param = "code=$code&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey";

$upfile_max = $bbs_info[upfile];
$movie_max = $bbs_info[movie];

if($mode == "insert" || $mode == ""){

	$mode = "insert";
	$bbs_row[name] = $wiz_admin[name];
	$bbs_row[email] = $wiz_admin[email];
	if($wdate != "") $bbs_row['wdate'] = $wdate;
	else $bbs_row['wdate'] = date('Y-m-d');
	$bbs_row[passwd] = date('is');
	$bbs_row[count] = 0;

}else if($mode == "update"){

	$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where code = '$code' and idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);
	if(!get_magic_quotes_gpc()) $bbs_row['content'] = stripslashes($bbs_row['content']);

}else if($mode == "reply"){

	$sql = "select subject, content, privacy, passwd from wiz_bbs where code = '$code' and idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	$bbs_row[name] 	= $wiz_admin[name];
  $bbs_row[email] = $wiz_admin[email];
  $bbs_row['wdate'] = date('Y-m-d');
  $bbs_row[count] = 0;
  if(!get_magic_quotes_gpc()) $bbs_row['content'] = stripslashes($bbs_row['content']);
	$bbs_row['content'] = $bbs_row['content']."\n\n==================== 답 변 ====================\n\n";

}

?>

      <script language="JavaScript" type="text/javascript">
      <!--
        function inputCheck(frm){

        if(frm.name.value == ""){
          alert("이름을 입력하세요.");
          frm.name.focus();
          return false;
        }
        if(frm.subject.value == ""){
          alert("제목을 입력하세요.");
          frm.subject.focus();
          return false;
        }
        try{ content.outputBodyHTML(); } catch(e){ }
			  if(frm.content.value == ""){
					alert("내용을 입력하세요.");
					return false;
			  }
        if(frm.passwd.value == ""){
          alert("비밀번호를 입력하세요.");
          frm.passwd.focus();
          return false;
        }

      }
      //-->
      </script>

	  <table border="0" cellspacing="0" cellpadding="2">
		<tr>
		  <td><img src="../image/ic_tit.gif"></td>
		  <td valign="bottom" class="tit">일정보기</td>
		  <td width="2"></td>
		  <td valign="bottom" class="tit_alt">일정을 관리합니다.</td>
		</tr>
	  </table>
	  <br>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form name="frm" action="save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="ctype" value="<?=$ctype ?>">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">작성자</td>
                <td width="35%" class="t_value"><input name="name" type="text" value="<?=$bbs_row[name]?>" class="input"></td>
                <td width="15%" class="t_name">이메일</td>
                <td width="35%" class="t_value"><input name="email" type="text" value="<?=$bbs_row[email]?>" size="30" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">일자</td>
                <td class="t_value">
                	<input name="wdate" type="text" value="<?=$bbs_row['wdate']?>" class="input">
                	<img src="../image/ic_cal.gif" style="cursor:hand" align="center" onClick="Calendar1('document.frm','wdate');">
                </td>
                <td class="t_name">조회수</td>
                <td class="t_value"><input name="count" type="text" value="<?=$bbs_row[count]?>" class="input"></td>
              </tr>
              <tr>
                <td class="t_name">제목</td>
                <td class="t_value" colspan="3">
								<?
								if($bbs_info[category] != ""){
									$catlist = explode(",",$bbs_info[category]);
                  echo "<select name='category'>";
                  echo "<option value=''>분류</option>";
									for($ii=0;$ii<count($catlist);$ii++){
										if($bbs_row[category] == $catlist[$ii]) $selected = "selected";
										else $selected = "";
                		echo "<option value='".$catlist[$ii]."' ".$selected.">".$catlist[$ii]."</option>";
									}
                	echo "</select>";
								}
								?>
                <input type="text" name="subject"  value="<?=$bbs_row['subject']?>" size="60" class="input">
                <!--<input type="checkbox" name="notice" value="Y" <? if($bbs_row[notice] == "Y") echo "checked"; ?>>공지글-->
                <input type="checkbox" name="privacy" value="Y" <? if($bbs_row[privacy] == "Y" || ($mode != "update" && $bbs_info[privacy] == "Y")) echo "checked"; ?>>비밀글
                <input type="checkbox" name="ctype" value="H" <? if($bbs_row[ctype] == "H") echo "checked"; ?>>HTML사용
                </td>
              </tr>
              <tr>
                <td class="t_name">내용</td>
                <td class="t_value" colspan="3">
                <?
					 			if($bbs_info[editor] == "Y"){
									$edit_content = $bbs_row['content'];
									include "../webedit/WIZEditor.html";
								}else{
								?>
                  <textarea name="content" rows="16" cols="80" class="textarea" style="width:100%"><?=$bbs_row['content']?></textarea>
								<?
								}
								?>
				    </td>
              </tr>
              <tr>
                <td class="t_name">비밀번호</td>
                <td width="275" class="t_value" colspan="3"><input name="passwd" type="text" value="<?=$bbs_row[passwd]?>" class="input"></td>
              </tr>
							<?php
							for($ii = 1; $ii <= $upfile_max; $ii++) {
								$upfile = "upfile".$ii;
								$upfile_name = "upfile".$ii."_name";
							?>
              <tr>
                <td class="t_name">파일첨부<?=$ii?></td>
                <td class="t_value" colspan="3"><input name="upfile<?=$ii?>" type="file" value="" class="input">
                <? if($bbs_row[$upfile] != ""){ ?>
	                <a href="save.php?mode=delfile&code=<?=$code?>&idx=<?=$idx?>&file=<?=$upfile?>">[삭제]</a>&nbsp;
	                <a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$upfile]?>' target='_blank'><?=$bbs_row[$upfile_name]?></a>
                <? } ?>
                </td>
              </tr>
							<?php
							}

							for($ii = 1; $ii <= $movie_max; $ii++) {
								$movie = "movie".$ii;
								if($ii == 1) {
							?>
              <tr>
                <td class="t_name">동영상<?=$ii?></td>
                <td class="t_value" colspan="3">
                <input name="<?=$movie?>" type="file" value="" class="input">
                <? if($bbs_row[$movie] != ""){ ?>
	                <a href="save.php?mode=delfile&code=<?=$code?>&idx=<?=$idx?>&file=<?=$movie?>">[삭제]</a>&nbsp;
                	<a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$movie]?>' target='_blank'><?=$bbs_row[$movie]?></a>
                <? } ?>
                </td>
              </tr>
              <?php
              	} else {
              ?>
              <tr>
                <td class="t_name">동영상<?=$ii?></td>
                <td width="275" class="t_value" colspan="3"><input name="<?=$movie?>" size="50" type="text" value="<?=$bbs_row[$movie]?>" class="input"></td>
              </tr>
              <?php
              	}
              }
              ?>
            </table>
          </td>
        </tr>
      </table>

      <br>
      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          	<input type="image" src="../image/btn_confirm_l.gif"> &nbsp;
          	<img src="../image/btn_list_l.gif" style="cursor:hand" onClick="document.location='list.php?<?=$param?>';">
          </td>
        </tr>
      </form>
      </table>


<? include "../footer.php"; ?>