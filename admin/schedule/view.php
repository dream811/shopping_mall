<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include_once "../../inc/bbs_info.inc"; ?>
<? include "../header.php"; ?>

<?
// 검색 파라미터
$param = "code=$code&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey";

// 게시물 정보
$sql = "select *, from_unixtime(wdate, '%Y-%m-%d') as wdate from wiz_bbs where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$bbs_row = mysqli_fetch_array($result);
if($bbs_row[category] != "") $bbs_row[category] = "[".$bbs_row[category]."]";
if($bbs_row[ctype] != "H")  $bbs_row['content'] = str_replace("\n", "<br>", $bbs_row['content']);

// 조회수증가
$sql = "update wiz_bbs set count = count + 1 where idx = '$idx'";
mysqli_query($connect, $sql) or die(mysqli_error($connect));

for($ii = 1; $ii <= 12; $ii++) {
	if(img_type(WIZSHOP_PATH."/data/bbs/$code/M".$bbs_row[upfile.$ii])) {
		${upimg.$ii} = "<div align='".$sch_info[img_align]."'><a href=javascript:viewImg('".$bbs_row[upfile.$ii]."');><img src='/data/bbs/$code/M".$bbs_row[upfile.$ii]."' border='0' name='wiz_target_resize'></a></div>";
		$_ResizeCheck = true;
	}
}

// 이미지 리사이즈를 위해서 처리하는 부분
if(strpos(strtolower($bbs_row['content']), "<img") !== false || $_ResizeCheck == true) {
	$bbs_row['content'] = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $bbs_row['content']);
	$bbs_row['content'] = "<table border=0 cellspacing=0 cellpadding=0 style='width:".$bbs_info[mimgsize]."px;height:0px;' height=1 id='wiz_get_table_width'>
								<col width=100%></col>
								<tr>
									<td><img src='' border='0' name='wiz_target_resize' width='0' height='0'></td>
								</tr>
							</table>
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
								<col width=100%></col>
								<tr><td valign=top>".$bbs_row['content']."</td></tr>
							</table>";
	$_ResizeCheck = true;
}

// 첨부파일
for($ii = 1; $ii <= 12; $ii++) {
	if($bbs_row[upfile.$ii] != "") ${upfile.$ii}  = "<a href='../bbs/bbs_down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row[upfile.$ii._name]."</a>";
}

if($bbs_row[movie1] != "") $movie1 = "<embed src='/data/bbs/$code/".$bbs_row[movie1]."' autostart=false></embed><br>";
if($bbs_row[movie2] != "") $movie2 = "<embed src='".$bbs_row[movie2]."' autostart=false></embed><br>";
if($bbs_row[movie3] != "") $movie3 = "<embed src='".$bbs_row[movie3]."' autostart=false></embed><br>";
?>

      <script language="JavaScript" type="text/javascript">
      <!--
      function viewImg(img){
        var url = "view_img.php?code=<?=$code?>&img=" + img;
        window.open(url,"viewImg","width=300,height=300,scrollbars=yes");
      }
      function delConfirm(idx){
         if(confirm('선택한 글을 삭제하시겠습니까?')){
           document.location = "save.php?code=<?=$code?>&mode=delete&idx=" + idx + "&<?=$param?>";
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
      		document.location = "save.php?mode=delcomment&code=<?=$code?>&cidx=<?=$idx?>&idx=" + idx;
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
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
              <tr>
                <td width="15%" class="t_name">이름</td>
                <td width="35%" class="t_value"><?=$bbs_row[name]?></td>
                <td width="15%" class="t_name">이메일</td>
                <td width="35%" class="t_value"><?=$bbs_row[email]?></td>
              </tr>
              <tr>
                <td class="t_name">작성일</td>
                <td width="220" class="t_value"><?=$bbs_row['wdate']?></td>
                <td class="t_name">파일첨부</td>
                <td width="220" class="t_value"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?></td>
              </tr>
              <tr>
                <td class="t_name">제목</td>
                <td class="t_value" colspan="3"><?=$bbs_row[category]?><?=$bbs_row['subject']?></td>
              </tr>
              <tr>
                <td height="80" class="t_name">내용</td>
                <td class="t_value" colspan="3" valign="top">
                <?=$upimg1?><?=$upimg2?><?=$upimg3?>
      					<?=$movie1?><?=$movie2?><?=$movie3?>
                <?=$bbs_row['content']?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

      <? if($bbs_info[comment] == "Y"){ ?>
      <br>
      <table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
      <?
			$sql = "select * from wiz_comment where ctype='SCH' and cidx='$idx' order by idx desc";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			if($rows == "") $rows = "10";
			if($lists == "") $lists = "10";

			$page_count = ceil($total/$rows);
			if(!$cpage || $cpage > $page_count) $cpage = 1;
			$start = ($cpage-1)*$rows;
			$no = $total-$start;

			$sql = "select * from wiz_comment where ctype='SCH' and cidx='$idx' order by idx desc limit $start, $rows";
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
			?>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="25%" height="35">&nbsp;</td>
			    <td width="50%" align="center">

			    	<? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?>

			    </td>
			    <td width="25%" align="right"></td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
      <form name="comment" action="save.php" method="post" onSubmit="return commentCheck(this);">
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
        <tr><td align=center class="t_name"><textarea name="content" cols="50" rows="3" style="width:98%" class="textarea"></textarea></td></tr>
      </form>
      </table>
			<? } ?>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="300"><img src="../image/btn_list_l.gif" style="cursor:hand" onClick="document.location='list.php?<?=$param?>';"></td>
          <td width="300"></td>
          <td width="60"><img src="../image/btn_edit_l.gif" style="cursor:hand"  onClick="document.location='input.php?mode=update&idx=<?=$idx?>&<?=$param?>';"></td>
          <td width="60"><img src="../image/btn_reply_l.gif" style="cursor:hand"  onClick="document.location='input.php?mode=reply&idx=<?=$idx?>&<?=$param?>';"></td>
          <td width="60"><img src="../image/btn_delete_l.gif" style="cursor:hand"  onClick="javascript:delConfirm('<?=$idx?>');"></td>
        </tr>
      </table>


<? include "../footer.php"; ?>

<? view_img_resize() ?>