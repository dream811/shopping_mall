<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/poll_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$param = "code=".$code."&page=".$page."&searchopt=".$searchopt."&searchkey=".$searchkey;

if($mode == "") $mode = "insert";

if($mode == "insert"){

	$poll_info[sdate] = date('Y-m-d');
	$poll_info[edate] = date('Y-m-d');

}else if($mode == "update"){

	$sql = "select * from wiz_poll where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$poll_row = mysqli_fetch_array($result);

}
?>
<script language="JavaScript" type="text/javascript">
<!--
function inputCheck(form){

   //if(form.code.value == ""){
   //   alert('설문코드를 입력하세요.');
   //   form.code.focus();
   //   return false;
   //}
   if(form.sdate.value == ""){
      alert('진행기간을 입력하세요.');
      form.sdate.focus();
      return false;
   }
   if(form.edate.value == ""){
      alert('진행기간을 입력하세요.');
      form.edate.focus();
      return false;
   }
   if(form.subject.value == ""){
      alert('제목을 입력하세요.');
      form.subject.focus();
      return false;
   }
   
   return true;

}

function insertQuestion(){

	<? if($mode == "insert"){ ?>

	alert("설문생성 후 등록할 수 있습니다.");

	<? }else{ ?>

	var url = "poll_question.php?pidx=<?=$idx?>";
	window.open(url,"insertQuestion","height=400, width=450, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");

	<? } ?>
}

function editQuestion(idx){

	var url = "poll_question.php?mode=question&smode=update&pidx=<?=$idx?>&idx=" + idx;
	window.open(url,"editQuestion","height=400, width=450, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");

}

function delQuestion(idx){

	if(confirm("삭제하시겠습니까?")){
		document.location = 'poll_save.php?mode=question&smode=delete&pidx=<?=$idx?>&idx=' + idx;
	}
}

function commentCheck(frm){
  if(frm.name.value == ""){
    alert("이름을 입력하세요");
    frm.name.focus();
    return false;
  }
  if(frm.passwd.value == ""){
    alert("비밀번호를 입력하세요");
    frm.passwd.focus();
    return false;
  }
  if(frm.content.value == ""){
    alert("내용을 입력하세요");
    frm.content.focus();
    return false;
  }
}

function delComment(idx){
	if(confirm("해당 댓글을 삭제하시겠습니까?")){
		document.location = "poll_save.php?mode=delco&code=<?=$code?>&cidx=<?=$idx?>&idx=" + idx;
	}
}

function frmSubmit() {
	var frm = document.frm;
	
	if(inputCheck(frm)) {
		frm.submit();
	}
}
//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">설문목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">설문을 등록/수정/삭제 합니다.</td>
	    </tr>
	  </table>			
	  <br>	  
      
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form name="frm" action="poll_save.php?<?=$param?>" method="post" onSubmit="return inputCheck(this);">
      <input type="hidden" name="mode" value="<?=$mode?>">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="idx" value="<?=$idx?>">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">진행여부</td>
                <td width="35%" class="t_value">
                  <input type="radio" name="polluse" value="Y" <? if($poll_row[polluse] == "Y" || empty($poll_row[polluse])) echo "checked"; ?>>진행중
                  <input type="radio" name="polluse" value="N" <? if($poll_row[polluse] == "N") echo "checked"; ?>>진행종료
                </td>
                <td width="15%" class="t_name">메인추출 여부</td>
                <td width="35%" class="t_value">
                  <input type="radio" name="pollmain" value="Y" <? if($poll_row[pollmain] == "Y" || empty($poll_row[pollmain])) echo "checked"; ?>>메인추출
                  <input type="radio" name="pollmain" value="N" <? if($poll_row[pollmain] == "N") echo "checked"; ?>>메인추출 안함
                </td>
              </tr>
              <tr>
                <td height="10" class="t_name">진행기간 <font color=red>*</font></td>
                <td colspan="3" class="t_value">
                  <input type="text" name="sdate" value="<?=$poll_row[sdate]?>" size="15" class="input">
                  <img src="../image/ic_cal.gif" style="cursor:hand" align="absmiddle" onClick="Calendar1('document.frm','sdate');"> ~
                  <input type="text" name="edate" value="<?=$poll_row[edate]?>" size="15" class="input">
                  <img src="../image/ic_cal.gif" style="cursor:hand" align="absmiddle" onClick="Calendar1('document.frm','edate');">&nbsp;
                  <? if($poll_row[edate] < date('Y-m-d')) echo "<font color=red>[종료일지남]</font>"; ?>
                </td>
              </tr>
              <!--tr>
                <td height="10" class="t_name">참여권한 <font color=red>*</font></td>
                <td class="t_value">
                  <input type="radio" name="apermi" value="N" <? if($poll_row[apermi] == "N" || $poll_row[apermi] == "") echo "checked"; ?>>전체
                  <input type="radio" name="apermi" value="M" <? if($poll_row[apermi] == "M") echo "checked"; ?>>회원
                </td>
                <td height="10" class="t_name">댓글권한 <font color=red>*</font></td>
                <td class="t_value">
                  <input type="radio" name="cpermi" value="N" <? if($poll_row[cpermi] == "N" || $poll_row[cpermi] == "") echo "checked"; ?>>전체
                  <input type="radio" name="cpermi" value="M" <? if($poll_row[cpermi] == "M") echo "checked"; ?>>회원
                </td>
              </tr//-->
              <tr>
                <td height="10" class="t_name">제목 <font color=red>*</font></td>
                <td colspan="3" class="t_value">
                  <input type="text" name="subject" value="<?=$poll_row['subject']?>" size="60" class="input">
                </td>
              </tr>
              <tr>
                <td height="10" class="t_name">내용</td>
                <td colspan="3" class="t_value">
                  <textarea type="text" rows="8" cols="60" name="content" style="width:98%" class="textarea"><?=$poll_row['content']?></textarea>
                </td>
              </tr>
              <tr>
                <td height="10" class="t_name">설문내용</td>
                <td colspan="3" class="t_value">

                <img src="../image/btn_polldadd.gif" style="cursor:hand" onClick="insertQuestion();"><br><br>

					      <table cellspacing=0 cellpadding=0 border=0 width=95%>
								<tr>
								<td>
								<?
								$no = 0;
					      $sql = "select * from wiz_polldata where pidx = '$idx' order by idx asc";
					      $result = mysqli_query($connect, $sql);
					      while($row = mysqli_fetch_array($result)){

					      	$total_count = $row[count01]+$row[count02]+$row[count03]+$row[count04]+$row[count05]+$row[count06]+$row[count07]+$row[count08]+$row[count09]+$row[count10];
					      	if($total_count == 0) $total_count = 1;

					      	$answer_list[0][0] = $row[answer01];
					      	$answer_list[0][1] = $row[count01];
					      	$answer_list[0][2] = "count01";
									$answer_list[0][3] = round(($row[count01]/$total_count)*100,1);

									$answer_list[1][0] = $row[answer02];
					      	$answer_list[1][1] = $row[count02];
					      	$answer_list[1][2] = "count02";
					      	$answer_list[1][3] = round(($row[count02]/$total_count)*100,1);

					      	$answer_list[2][0] = $row[answer03];
					      	$answer_list[2][1] = $row[count03];
					      	$answer_list[2][2] = "count03";
					      	$answer_list[2][3] = round(($row[count03]/$total_count)*100,1);

					      	$answer_list[3][0] = $row[answer04];
					      	$answer_list[3][1] = $row[count04];
					      	$answer_list[3][2] = "count04";
					      	$answer_list[3][3] = round(($row[count04]/$total_count)*100,1);

					      	$answer_list[4][0] = $row[answer05];
					      	$answer_list[4][1] = $row[count05];
					      	$answer_list[4][2] = "count05";
					      	$answer_list[4][3] = round(($row[count05]/$total_count)*100,1);

					      	$answer_list[5][0] = $row[answer06];
					      	$answer_list[5][1] = $row[count06];
					      	$answer_list[5][2] = "count06";
					      	$answer_list[5][3] = round(($row[count06]/$total_count)*100,1);

					      	$answer_list[6][0] = $row[answer07];
					      	$answer_list[6][1] = $row[count07];
					      	$answer_list[6][2] = "count07";
					      	$answer_list[6][3] = round(($row[count07]/$total_count)*100,1);

					      	$answer_list[7][0] = $row[answer08];
					      	$answer_list[7][1] = $row[count08];
					      	$answer_list[7][2] = "count08";
					      	$answer_list[7][3] = round(($row[count08]/$total_count)*100,1);

					      	$answer_list[8][0] = $row[answer09];
					      	$answer_list[8][1] = $row[count09];
					      	$answer_list[8][2] = "count09";
					      	$answer_list[8][3] = round(($row[count09]/$total_count)*100,1);

					      	$answer_list[9][0] = $row[answer10];
					      	$answer_list[9][1] = $row[count10];
					      	$answer_list[9][2] = "count10";
					      	$answer_list[9][3] = round(($row[count10]/$total_count)*100,1);

					      ?>
					        <table cellspacing=0 cellpadding=0 border=0 width=94%>
					        <tr>
					        	<td height=30><b><?=$row[question]?></b></td>
					        	<td align="right">
					        		<a href="javascript:editQuestion('<?=$row['idx']?>');"><img src="../image/btn_edit_s.gif" border="0"></a>
					        		<a href="javascript:delQuestion('<?=$row['idx']?>');"><img src="../image/btn_delete_s.gif" border="0"></a>&nbsp;
					        	</td>
					        </tr>
					      <?
					      	for($ii=0;$ii<10;$ii++){
					      		if($answer_list[$ii][0] != ""){
					      ?>
					        <tr>
					          <td valign=top colspan=2>
					            <table width=100% cellspacing=0 cellpadding=0 border=0>
					              <tr>
					                <td valign=top height=20><?=$answer_list[$ii][0]?></td>
					                <td valign=top align=right>
					                  <table cellspacing=0 cellpadding=0 border=0>
					                    <tr>
					                      <td height=5></td>
					                    </tr>
					                    <tr>
					                      <td align=middle bgcolor=#cccccc height=12>
					                        <table height=10 cellspacing=0 cellpadding=0 width=150 border=0>
					                          <tr>
					                            <td width="<?=$answer_list[$ii][3]?>%" bgcolor=#FFB743 height=10></td>
					                            <td width=43 bgcolor=#CCCCCC height=10></td>
					                          </tr>
					                        </table>
					                      </td>
					                    </tr>
					                    <tr>
					                      <td height=5></td>
					                    </tr>
					                  </table>
					                </td>
					                <td valign=top width=78>
					                  <table cellspacing=0 cellpadding=0 width="100%" border=0>
					                    <tr valign=center>
					                      <td width=78 height=20>&nbsp; <?=$answer_list[$ii][1]?> 명/ <?=$answer_list[$ii][3]?> % </td>
					                    </tr>
					                  </table>
					                </td>
					              </tr>
					              </tbody>
					            </table>
					          </td>
					        </tr>
					        <?
					        		}
					        	}
									?>
									<tr>
					          <td height=10></td>
					        </tr>
					        </table>
									<?
										$no++;
					      	}
					        ?>
					        </td>
							    </tr>
						     </table>



                </td>
              </tr>
            </table></td>
        </tr>
      </form>
      </table>
      
      <? if($poll_info[comment] == "Y" && !strcmp($mode, "update")){ ?>
      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
      <?
			$sql = "select * from wiz_comment where ctype='POLL' and cidx='$idx' order by idx desc";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			if($rows == "") $rows = "12";
			if($lists == "") $lists = "10";

			$page_count = ceil($total/$rows);
			if(!$cpage || $cpage > $page_count) $cpage = 1;
			$start = ($cpage-1)*$rows;
			$no = $total-$start;

			$sql = "select * from wiz_comment where ctype='POLL' and cidx='$idx' order by idx desc limit $start, $rows";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while($com_row = mysqli_fetch_array($result)){
				$com_row['content'] = str_replace("\n", "<br>", $com_row['content']);
			?>
			<tr>
			  <td class="t_value" width="15%"><b><?=$com_row[name]?></b> (<?=$com_row['id']?>)</td>
			  <td class="t_value"><?=$com_row['content']?> <a href="javascript:delComment('<?=$com_row['idx']?>');"><font color="red" style="cursor:hand">x</font></a></td>
			  <td class="t_value" width="15%" style="padding-left:5px"><?=$com_row[wip]?><br><?=$com_row['wdate']?></td>
			</tr>
			<?
			}
			if($idx != "") $param .= "&idx=$idx";
			if($mode != "") $param .= "&mode=$mode";
			?>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="33%" height="35">&nbsp;</td>
			    <td width="33%" align="center">

			    	<? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?>

			    </td>
			    <td width="33%" align="right"></td>
			  </tr>
			</table>
			<table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
      <form name="comment" action="poll_save.php" method="post" onSubmit="return commentCheck(this);">
      <input type="hidden" name="mode" value="comment">
      <input type="hidden" name="code" value="<?=$code?>">
      <input type="hidden" name="memid" value="<?=$wiz_admin['id']?>">
      <input type="hidden" name="cidx" value="<?=$idx?>">
        <tr>
          <td align="center" height=35 class="t_name">
		      <table width="98%" border=0 cellpadding=0 cellspacing=0>
		      <tr>
		        <td>&nbsp;댓글쓰기</td>
			      <td width=130 align=right>이름 <input type="text" name="name" value="<?=$wiz_admin[name]?>" size=10 class="input"></td>
			      <td width=130 align=right>비밀번호 <input type="text" name="passwd" value="<?=date('is')?>" size=10 class="input"></td>
			      <td width=50 align=right><input type="image" src="../image/btn_confirm_s.gif"></td>
			    </tr>
		      </table>
          </td>
        </tr>
        <tr><td align=center class="t_name"><textarea name="content" cols="50" rows="3" style="width:98%;height:50px;" class="input"></textarea></td></tr>
      </form>
      </table>
			<? } ?>

      <br>
      <table align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
          	<img src="../image/btn_confirm_l.gif" style="cursor:hand" onClick="frmSubmit();"> &nbsp; 
          	<img src="../image/btn_list_l.gif" style="cursor:hand" onClick="document.location='poll_list.php?<?=$param?>';">
          </td>
        </tr>
      </table>
 

<? include "../footer.php"; ?>