<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/bbs_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?

$upfile_max = $bbs_info['upfile'];
$movie_max = $bbs_info['movie'];

if($mode == "insert" || $mode == ""){

	$mode = "insert";
	$bbs_row['name'] = $wiz_admin['name'];
	$bbs_row['email'] = $wiz_admin['email'];
	$bbs_row['wdate'] = date('Y-m-d H:i:s');
	$bbs_row['passwd'] = date('is');
	$bbs_row['count'] = 0;

}else if($mode == "update"){

	$sql = "select *,from_unixtime(wdate, '%Y-%m-%d %H:%i:%s') as wdate from wiz_bbs where code = '$code' and idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);
	if(!get_magic_quotes_gpc()) $bbs_row['content'] = stripslashes($bbs_row['content']);

	for($ii = 1; $ii <= 5; $ii++) {
		if(!strcmp($ii, $bbs_row['star'])) ${"star".$ii."_checked"} = "checked";
	}

}else if($mode == "reply"){

	$sql = "select subject, content, privacy, passwd from wiz_bbs where code = '$code' and idx='$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$bbs_row = mysqli_fetch_array($result);

	$bbs_row['name'] 	= $wiz_admin['name'];
  $bbs_row['email'] = $wiz_admin['email'];
  $bbs_row['wdate'] = date('Y-m-d');
  $bbs_row['count'] = 0;
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
	      <td valign="bottom" class="tit">게시물관리</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">게시물을 관리합니다.</td>
	    </tr>
	  </table>
	  <br>

    <table width="100%" border="0" cellspacing="0" cellpadding="2">
  	<form action="bbs_list.php" method="post">
		  <tr>
		    <td class="tit_sub"><img src="../image/ics_tit.gif"> 선택게시판 : <?=$bbs_info['title']?></td>
		  </tr>
		</form>
		</table>
    <form name="frm" action="bbs_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this)">
    <input type="hidden" name="code" value="<?=$code?>">
    <input type="hidden" name="mode" value="<?=$mode?>">
    <input type="hidden" name="idx" value="<?=$idx?>">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="ctype" value="<?=$ctype ?>">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
        	<?
					if($bbs_row['prdcode'] != ""){
						$prd_sql = "select prdcode,prdname,sellprice,strprice,prdimg_R from wiz_product where prdcode='$bbs_row[prdcode]'";
						$prd_result = mysqli_query($connect, $prd_sql);
						$prd_info = mysqli_fetch_object($prd_result);

						if(!empty($prd_info->strprice)) $prd_info->sellprice = $prd_info->strprice;
						else $prd_info->sellprice = number_format($prd_info->sellprice)."원";
					?>
					<table width="100%" border="0" cellspacing="2" cellpadding="6" class="t_style">
						<tr>
							<td class="t_value">
							  <table width="100%" border="0">
							  	<tr>
							  		<td width="100"><img src="/data/prdimg/<?=$prd_info->prdimg_R?>" width="100" height="100"></td>
							  		<td width="20"></td>
							  		<td><?=$prd_info->prdname?><br><font class="price"><?=$prd_info->sellprice?></font></td>
							  		<td align="right" style="padding-right:10px"><a href="/shop/prd_view.php?prdcode=<?=$prd_info->prdcode?>" target="_blank"><strong>[상품보기]</strong></a></td>
							  	</tr>
							  </table>
							</td>
						</tr>
					</table>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td height="5"></td>
						</tr>
					</table>
					<?
					}
					?>
          <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
            <tr>
              <td width="15%" class="t_name">작성자</td>
              <td width="35%" class="t_value"><input name="name" type="text" value="<?=$bbs_row['name']?>" class="input"></td>
              <td width="15%" class="t_name">이메일</td>
              <td width="35%" class="t_value"><input name="email" type="text" value="<?=$bbs_row['email']?>" size="30" class="input"></td>
            </tr>
            <tr>
              <td class="t_name">작성일</td>
              <td class="t_value"><input name="wdate" type="text" value="<?=$bbs_row['wdate']?>" class="input"></td>
              <td class="t_name">조회수</td>
              <td class="t_value"><input name="count" type="text" value="<?=$bbs_row['count']?>" class="input"></td>
            </tr>
            <? if(!strcmp($code, "review")) { ?>
            <tr>
              <td class="t_name">선호도</td>
              <td class="t_value" colspan="3">
								<input name="star" type="radio" value="5" style="border:0px; background-color:transparent;" <?=$star5_checked?>><img src="/images/icon_star_5.gif">&nbsp;&nbsp;&nbsp;
								<input name="star" type="radio" value="4" style="border:0px; background-color:transparent;" <?=$star4_checked?>><img src="/images/icon_star_4.gif">&nbsp;&nbsp;&nbsp;
								<input name="star" type="radio" value="3" style="border:0px; background-color:transparent;" <?=$star3_checked?>><img src="/images/icon_star_3.gif">&nbsp;&nbsp;&nbsp;
								<input name="star" type="radio" value="2" style="border:0px; background-color:transparent;" <?=$star2_checked?>><img src="/images/icon_star_2.gif">&nbsp;&nbsp;&nbsp;
								<input name="star" type="radio" value="1" style="border:0px; background-color:transparent;" <?=$star1_checked?>><img src="/images/icon_star_1.gif">
            	</td>
            </tr>
          	<? } ?>
            <tr>
              <td class="t_name">제목</td>
              <td class="t_value" colspan="3">
								<?
			          $sql = "select idx, catname from wiz_bbscat where code = '$code' and gubun != 'A' order by prior asc, idx asc";
			          $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			          $total = mysqli_num_rows($result);
			          if($total > 0) {
                  echo "<select name='category'>";
                  echo "<option value=''>분류</option>";
									while($row = mysqli_fetch_array($result)) {
										if($bbs_row['category'] == $row['idx']) $selected = "selected";
										else $selected = "";
			          		echo "<option value='".$row['idx']."'".$selected.">".$row['catname']."</option>";
									}
                	echo "</select>";
								}
								?>

                <input type="checkbox" name="notice" value="Y" <? if($bbs_row['notice'] == "Y") echo "checked"; ?>> 공지글
                <input type="checkbox" name="privacy" value="Y" <? if($bbs_row['privacy'] == "Y" || ($mode != "update" && $bbs_info['privacy'] == "Y")) echo "checked"; ?>> 비밀글
                <input type="checkbox" name="ctype" value="H" <? if($bbs_row['ctype'] == "H") echo "checked"; ?>> HTML사용<br>
                <input name="subject" type="text" value="<?=$bbs_row['subject']?>" size="70" class="input" style="word-break:break-all;">
              </td>
            </tr>
            <tr>
              <td class="t_name">내용</td>
              <td class="t_value" colspan="3">
              <?
				 			if($bbs_info['editor'] == "Y"){
                $edit_content = $bbs_row['content'];
                include "../webedit/WIZEditor.html";
			    		}else{
			    		?>
			      		<textarea name="content" rows="16" cols="80" class="textarea" style="width:100%;word-break:break-all;"><?=$bbs_row['content']?></textarea>
					    <?
					    }
					    ?>
			    </td>
            </tr>
            <tr>
              <td class="t_name">비밀번호</td>
              <td width="275" class="t_value" colspan="3"><input name="passwd" type="text" value="<?=$bbs_row['passwd']?>" class="input"></td>
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
              	<input type="checkbox" name="delupfile[]" value="upfile<?=$ii?>"> 삭제
              	<!--a href="save.php?mode=delfile&code=<?=$code?>&idx=<?=$idx?>&file=upfile3">[삭제]</a//-->
              	&nbsp;<a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$upfile]?>' target='_blank'><?=$bbs_row[$upfile_name]?></a>
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
              	<input type="checkbox" name="delupfile[]" value="<?=$movie?>"> 삭제
              	<!--a href="save.php?mode=delfile&code=<?=$code?>&idx=<?=$idx?>&file=<?=$movie?>">[삭제]</a//-->
              	&nbsp;<a href='../../data/bbs/<?=$code?>/<?=$bbs_row[$movie]?>' target='_blank'><?=$bbs_row[$movie]?></a>
              <? } ?>

              	<!--<input name="<?=$movie?>" size="50" type="text" value="<?=$bbs_row[$movie]?>" class="input">//-->
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
    
<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="document.location='bbs_list.php?code=<?=$code?>';" href="javascript:history.go(-1);">목록</a>
</div><!-- .AW-btn-wrap -->


    </form>

<? include "../footer.php"; ?>