<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<?
$param = "code=$code&title=$title&page=$page";

if(empty($mode)) $mode = "catinsert";

if(!strcmp($mode, "catupdate")) {
	$sql = "select * from wiz_bbscat where code = '$code' and idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$cat_info = mysqli_fetch_array($result);
}
?>
<html>
<head>
<title>:: 카테고리관리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
<!--
function inputCheck(frm){

	if(frm.catname.value == ""){
		alert("분류명을 입력하세요.");
		frm.catname.focus();
		return false;
	}
}
//-->
</script>
</head>
<body>
<table width="100%" border="0" cellpadding=10 cellspacing=0>
<tr>
<td>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> 카테고리관리 : <?=$title?></td>
  </tr>
</table>
<form name="frm" action="bbs_pro_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
<input type="hidden" name="mode" value="<?=$mode?>">
<input type="hidden" name="code" value="<?=$code?>">
<input type="hidden" name="title" value="<?=$title?>">
<input type="hidden" name="idx" value="<?=$idx?>">
<table width="100%" border="0" cellpadding=2 cellspacing=1 class="t_style">
  <tr>
    <td height="30" width="20%" align=center class="t_name">분류</td>
    <td class="t_value">
    	<input type="checkbox" name="gubun" value="A" <? if(!strcmp($cat_info['gubun'], "A")) { ?> checked <? } ?>> 전체분류
			<br>※전체분류는 게시판의 특정 분류값이 아닌 "전체" 게시물을 보여주는 값입니다.
    </td>
  </tr>
  <tr>
    <td align="center" class="t_name">우선순위</td>
    <td class="t_value">
    	<select name="prior">
    		<? for($ii = 1; $ii < 21; $ii++) { ?>
    		<option value="<?=$ii?>" <? if(!strcmp($ii, $cat_info['prior'])) echo "selected"; ?>><?=$ii?></option>
    		<? } ?>
    	</select> (작을수록 순위가 높음, "전체분류"는 우선순위에 상관없이 가장 순위가 높습니다.)<br>
    </td>
  </tr>
  <tr>
    <td height="30" width="20%" align=center class="t_name">분류명</td>
    <td class="t_value">
    	<input type="text" name="catname" value="<?=$cat_info['catname']?>" class="input">
    </td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">분류이미지</td>
    <td class="t_value">
    	<input type="file" name="catimg" class="input">

<? if(!empty($cat_info['catimg'])) { ?>
			<br> <img src="/data/category/<?=$code?>/<?=$cat_info['catimg']?>">
			<input type="checkbox" name="delfile[]" value="catimg"> 삭제
<? } ?>

    </td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">롤오버이미지</td>
    <td class="t_value">
    	<input type="file" name="catimg_over" class="input">

<? if(!empty($cat_info['catimg_over'])) { ?>
			<br> <img src="/data/category/<?=$code?>/<?=$cat_info['catimg_over']?>">
			<input type="checkbox" name="delfile[]" value="catimg_over"> 삭제
<? } ?>
    </td>
  </tr>
  <tr>
    <td height="30" align=center class="t_name">분류아이콘</td>
    <td class="t_value">
    	<input type="file" name="caticon" class="input">

<? if(!empty($cat_info['caticon'])) { ?>
			<br> <img src="/data/category/<?=$code?>/<?=$cat_info['caticon']?>">
			<input type="checkbox" name="delfile[]" value="caticon"> 삭제
<? } ?>

    </td>
  </tr>

<? if(!strcmp($mode, "catupdate")) { ?>
  <tr>
    <td width="15%" height="30" align=center class="t_name">링크값</td>
    <td class="t_value">
    	파일명?category=<?=$cat_info['idx']?>
    </td>
  </tr>
<? } ?>

</table>

<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onClick="document.location='category.php?<?=$param?>'">목록</a>
</div><!-- .AW-btn-wrap -->

</form>

</td>
</tr>
</table>
</body>
</html>