<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../../inc/bbs_info.inc"; ?>
<? include "../header.php"; ?>
<?
// 검색 파라미터
if(!isset($page)) $page ="";
$param = "code=$code&page=$page&category=$category&searchopt=$searchopt&searchkey=$searchkey";

// 게시물 정보
$sql = "select wb.*,from_unixtime(wb.wdate, '%Y-%m-%d %H:%i:%s') as wdate, wc.catname as category
				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
				where wb.idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$bbs_row = mysqli_fetch_array($result);
if($bbs_row['category'] != "") $bbs_row['category'] = "[".$bbs_row['category']."]";
if($bbs_row['ctype'] != "H")  $bbs_row['content'] = str_replace("\n", "<br>", $bbs_row['content']);

// 조회수 증가
$count_id = "bbs_view_".$code."_".$idx;
if(!isset($_SESSION[$count_id])) {
	$sql = "update wiz_bbs set count = count+1 where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$_SESSION[$count_id] = true;
	$bbs_row['count']++;
}

$_ResizeCheck = false;
for($ii = 1; $ii <= $upfile_max; $ii++) {
	if(img_type($DOCUMENT_ROOT."/data/bbs/$code/M".$bbs_row['upfile'.$ii])) {
		${'upimg'.$ii} = "<div align='".$bbs_info['img_align']."'><a href=javascript:openImg('".$bbs_row['upfile'.$ii]."');><img src='/data/bbs/$code/M".$bbs_row['upfile'.$ii]."' border='0'></a></div>";
		$_ResizeCheck = true;
	}
}

// 이미지 리사이즈를 위해서 처리하는 부분
if(strpos(strtolower($bbs_row['content']), "<img") !== false || $_ResizeCheck == true) {
	$bbs_row['content'] = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=wiz_target_resize style=\"cursor:pointer\" onclick=window.open(this.src) \\2 \\3", $bbs_row['content']);
	$bbs_row['content'] = "<table border=0 cellspacing=0 cellpadding=0 style='width:".$bbs_info['mimgsize']."px;height:0px;' height=1 id='wiz_get_table_width'>
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

for($ii = 1; $ii <= $upfile_max; $ii++) {
	if($bbs_row['upfile'.$ii] != "") ${'upfile'.$ii}  = "<a href='bbs_down.php?code=$code&idx=$idx&no=".$ii."'>".$bbs_row['upfile'.$ii.'_name']."</a>";
}

if($bbs_row['movie1'] != "") $movie1 = "<embed src='/data/bbs/".$code."/".$bbs_row['movie1']."' autostart=false></embed><br>";
if($bbs_row['movie2'] != "") $movie2 = "<embed src='".$bbs_row['movie2']."' autostart=false></embed><br>";
if($bbs_row['movie3'] != "") $movie3 = "<embed src='".$bbs_row['movie3']."' autostart=false></embed><br>";

// 선호도
$star = "<img src='/images/icon_star_".$bbs_row['star'].".gif'>";
if(!isset($upimg1)) $upimg1 = "";
if(!isset($upimg2)) $upimg2 = "";
if(!isset($upimg3)) $upimg3 = "";
if(!isset($upimg4)) $upimg4 = "";
if(!isset($upimg5)) $upimg5 = "";
if(!isset($upimg6)) $upimg6 = "";
if(!isset($upimg7)) $upimg7 = "";
if(!isset($upimg8)) $upimg8 = "";
if(!isset($upimg9)) $upimg9 = "";
if(!isset($upimg10)) $upimg10 = "";
if(!isset($upimg11)) $upimg11 = "";
if(!isset($upimg12)) $upimg12 = "";
if(!isset($upfile1)) $upfile1 = "";
if(!isset($upfile2)) $upfile2 = "";
if(!isset($upfile3)) $upfile3 = "";
if(!isset($upfile4)) $upfile4 = "";
if(!isset($upfile5)) $upfile5 = "";
if(!isset($upfile6)) $upfile6 = "";
if(!isset($upfile7)) $upfile7 = "";
if(!isset($upfile8)) $upfile8 = "";
if(!isset($upfile9)) $upfile9 = "";
if(!isset($upfile10)) $upfile10 = "";
if(!isset($upfile11)) $upfile11 = "";
if(!isset($upfile12)) $upfile12 = "";
if(!isset($movie1)) $movie1 = "";
if(!isset($movie2)) $movie2 = "";
if(!isset($movie3)) $movie3 = "";
?>

<script language="JavaScript" type="text/javascript">
<!--
function openImg(img){
   var url = "bbs_openimg.php?code=<?=$code?>&img=" + img;
   window.open(url,"openImg","width=300,height=300,scrollbars=yes");
}
function deleteConfirm(idx){
   if(confirm('선택한 글을 삭제하시겠습니까?')){
      document.location = "bbs_save.php?code=<?=$code?>&mode=delete&idx=" + idx + "&<?=$param?>";
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
		document.location = "bbs_save.php?mode=delco&code=<?=$code?>&cidx=<?=$idx?>&idx=" + idx;
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
						  		<td align="right"><a href="/shop/prd_view.php?prdcode=<?=$prd_info->prdcode?>" target="_blank"><strong>[상품보기]</strong></a></td>
						  	</tr>
						  </table>
						</td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td height="10"></td>
					</tr>
				</table>
				<?
				}
				?>

        <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style" style="layout:fixed;">
          <tr>
            <td width="15%" class="t_name">이름</td>
            <td width="35%" class="t_value"><?=$bbs_row['name']?></td>
            <td width="15%" class="t_name">이메일</td>
            <td width="35%" class="t_value"><?=$bbs_row['email']?></td>
          </tr>
          <tr>
            <td class="t_name">작성일</td>
            <td class="t_value"><?=$bbs_row['wdate']?></td>
            <td class="t_name">파일첨부</td>
            <td class="t_value"><?=$upfile1?> <?=$upfile2?> <?=$upfile3?> <?=$upfile4?> <?=$upfile5?> <?=$upfile6?> <?=$upfile7?> <?=$upfile8?> <?=$upfile9?> <?=$upfile10?> <?=$upfile11?> <?=$upfile12?></td>
          </tr>
          <? if(!strcmp($code, "review")) { ?>
          <tr>
            <td class="t_name">선호도</td>
            <td class="t_value" colspan="3" style="word-break:break-all;"><?=$star?></td>
          </tr>
        	<? } ?>
          <tr>
            <td class="t_name">제목</td>
            <td class="t_value" colspan="3" style="word-break:break-all;"><?=$bbs_row['category']?><?=$bbs_row['subject']?></td>
          </tr>
          <tr>
            <td class="t_name">내용</td>
            <td class="t_value" colspan="3" style="word-break:break-all;">
              <?=$upimg1?><?=$upimg2?><?=$upimg3?><?=$upimg4?><?=$upimg5?><?=$upimg6?>
              <?=$upimg7?><?=$upimg8?><?=$upimg9?><?=$upimg10?><?=$upimg11?><?=$upimg12?>
    					<?=$movie1?><?=$movie2?><?=$movie3?>
              <?=$bbs_row['content']?>
            </td>
          </tr>
        </table>
	      </td>
	    </tr>
	  </table>

    <? if($bbs_info['comment'] == "Y"){ ?>
		<br>
		<table width="100%" border="0" cellspacing="1" cellpadding="0" class="t_style">
    <?
		$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = mysqli_num_rows($result);

		if(!isset($rows) || $rows == "") $rows = "10";
		if(!isset($lists) || $lists == "") $lists = "10";

		$page_count = ceil($total/$rows);
		if(!isset($cpage) || !$cpage || $cpage > $page_count) $cpage = 1;
		$start = ($cpage-1)*$rows;
		$no = $total-$start;

		$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc limit $start, $rows";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while($com_row = mysqli_fetch_array($result)){
			$com_row['content'] = str_replace("\n", "<br>", $com_row['content']);
		?>
		<tr>
		  <td class="t_value" width="15%"><b><?=$com_row['name']?></b> (<?=$com_row['id']?>)</td>
		  <td class="t_value"><?=$com_row['content']?> <a href="javascript:delComment('<?=$com_row['idx']?>');"><font color="red" style="cursor:hand">x</font></a></td>
		  <td class="t_value" width="15%" style="padding-left:5px"><?=$com_row['wip']?><br><?=$com_row['wdate']?></td>
		</tr>
		<?
		}
		if($idx != "") $param .= "&idx=$idx";
		?>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="33%" height="35"></td>
		    <td width="33%"><? print_pagelist($cpage, $lists, $page_count, $param, "C"); ?></td>
		    <td width="33%"></td>
		  </tr>
		</table>
    <table width="100%" border="0" cellspacing="1" cellpadding="6" class="t_style">
    <form name="comment" action="bbs_save.php" method="post" onSubmit="return commentCheck(this);">
    <input type="hidden" name="mode" value="comment">
    <input type="hidden" name="code" value="<?=$code?>">
    <input type="hidden" name="memid" value="<?=$wiz_admin['id']?>">
    <input type="hidden" name="cidx" value="<?=$idx?>">
      <tr>
        <td align="center" height=35 class="t_name">
	      <table width="98%" border=0 cellpadding=0 cellspacing=0>
	      <tr>
	        <td>&nbsp;댓글쓰기</td>
		      <td width=140 align=right>이름 <input type="text" name="name" value="<?=$wiz_admin['name']?>" size=10 class="input"></td>
		      <td width=140 align=right>비밀번호 <input type="text" name="passwd" value="<?=date('is')?>" size=10 class="input"></td>
		      <td width=70 align=right><table cellpadding="0" cellspacing="0"><tr><td></td></tr></table><input type="image" src="../image/btn_confirm_s.gif"></td></tr>
	      </table>
        </td>
      </tr>
      <tr><td align=center class="t_name"><textarea name="content" cols="50" rows="3" style="width:98%;height:50px;" class="input"></textarea></td></tr>
    </form>
    </table>
		<? } ?>

    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="../image/btn_list_l.gif" style="cursor:hand" onClick="document.location='bbs_list.php?code=<?=$code?>&page=<?=$page?>';"></td>
        <td align="right">
        	<img src="../image/btn_edit_l.gif" style="cursor:hand" onClick="document.location='bbs_input.php?mode=update&code=<?=$code?>&idx=<?=$idx?>&page=<?=$page?>';">&nbsp;
        	<img src="../image/btn_reply_l.gif" style="cursor:hand" onClick="document.location='bbs_input.php?mode=reply&code=<?=$code?>&idx=<?=$idx?>&page=<?=$page?>';">&nbsp;
        	<img src="../image/btn_delete_l.gif" style="cursor:hand" onClick="javascript:deleteConfirm('<?=$idx?>');">
        </td>
      </tr>
    </table>

<? view_img_resize(); ?>

<? include "../footer.php"; ?>