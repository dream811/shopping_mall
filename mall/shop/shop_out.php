<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select count(idx) as cnt, content from wiz_bbs where code = 'mallout' and memid = '$wiz_mall['id']' group by idx";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);

$content = $row['content'];
?>

<script language="JavaScript" type="text/javascript">
<!--

function outCheck(frm) {
<? if($row['cnt'] > 0) { ?>
	alert("이미 탈퇴 신청을 하셨습니다.");
	return false;
<? } else { ?>
	if(!frm.content.value) {
		alert("탈퇴사유를 입력하세요.");
		frm.content.focus();
		return false;
	}

	if(!confirm("정말 탈퇴하시겠습니까?")) {
		return false;
	}
<? } ?>
}

//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">탈퇴하기</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">몰인몰에서 탈퇴합니다.</td>
			  </tr>
			</table>


<div class="AW-manage-checkinfo" style="margin-bottom:20px;">
	<div class="tit">체크사항</div>
    <div class="cont">
        - 탈퇴 신청 시 모든 상품이 비승인 상태가 되며 접근이 불가능해집니다.<br />
		- 탈퇴 신청 후 관리자의 승인 후 실제 탈퇴가 이루어집니다.<br />
		- 탈퇴 시 등록 상품은 모두 삭제되며 복구가 불가능합니다.<br />
		- 탈퇴 시 입점업체 관리자에 로그인이 불가능하므로 주문내역등은 미리 백업해두시기바랍니다.<br />
		- 탈퇴 시 고객님의 정보는 상품 반품 및 A/S를 위해 전자상거래 등에서의 소비자 보호에 관한 법률에 의거한 고객정보 보호정책에따라 관리 됩니다.
    </div><!-- .cont -->
</div>



<form name="frm" action="shop_save.php" method="post" onSubmit="return outCheck(this);">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="out">

	  <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
        <tr>
          <td width="15%" class="t_name">탈퇴사유</td>
          <td width="85%" class="t_value">
          	<textarea name="content" rows="5" class="textarea" style="width:100%;"><?=$content?></textarea>
          </td>
        </tr>
      </table>

	<div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on">
        <a href="javascript:history.go(-1);">취소</a>
    </div>

</form>
<? include "../footer.php"; ?>