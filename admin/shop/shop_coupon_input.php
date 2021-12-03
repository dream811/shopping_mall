<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if (!isset($prdcode)) $prdcode = "";
if (!isset($idx)) $idx = "";
if ($sub_mode == "update" && $idx) {
  $sql = "select * from wiz_coupon where idx = '$idx'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $coupon_info = mysqli_fetch_array($result);
  $coupon_link = '&lt;a href="/shop/coupon_down.php?eventidx=' . $idx . '"&gt;링크명&lt;/a&gt;';
  $add_sql = " and wc.eventidx='$idx'";
} else if ($sub_mode == "update" && $prdcode) {
  $sql = "select prdcode, concat('[상품쿠폰]', prdname) as coupon_name, coupon_sdate, coupon_edate, coupon_dis, coupon_type, coupon_amount, coupon_limit from wiz_product where prdcode = '$prdcode'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $coupon_info = mysqli_fetch_array($result);
  $coupon_link = '&lt;a href="/shop/coupon_down.php?prdcode=' . $prdcode . '"&gt;링크명&lt;/a&gt;';
  $add_sql = " and wc.prdcode='$prdcode'";
}
?>
<script language="JavaScript" type="text/javascript">
  <!--
  function inputCheck(frm) {

    if (frm.coupon_name.value == "") {
      alert("쿠폰명을 입력하세요");
      frm.coupon_name.focus();
      return false;
    }
    if (frm.coupon_sdate.value == "") {
      alert("기간을 입력하세요");
      frm.coupon_sdate.focus();
      return false;
    }
    if (frm.coupon_edate.value == "") {
      alert("기간을 입력하세요");
      frm.coupon_edate.focus();
      return false;
    }
    if (frm.coupon_dis.value == "") {
      alert("할인율을 입력하세요");
      frm.coupon_dis.focus();
      return false;
    }
  }

  function coupon_input(idx, prdcode) {

    var url = "shop_coupon_pop.php?cidx=" + idx + "&prdcode=" + prdcode;
    window.open(url, "coupon_input", "height=600, width=600, menubar=no, scrollbars=yes, resizable=yes, toolbar=no, status=no, left=150, top=100");
  }

  function delmycoupon(cidx, idx, prdcode) {
    if (confirm("발급쿠폰을 삭제하시겠습니까?")) {
      var url = "shop_save.php?mode=delmycoupon&cidx=" + cidx + "&idx=" + idx + "&prdcode=" + prdcode;
      location.href = url;
    }
  }

  function chmycoupon(cidx, idx, prdcode) {
    if (confirm("사용상태를 변경하시겠습니까?")) {
      var url = "shop_save.php?mode=chmycoupon&cidx=" + cidx + "&idx=" + idx + "&prdcode=" + prdcode;
      location.href = url;
    }
  }
  //
  -->
</script>

<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">쿠폰관리</td>
    <td width="2"></td>
    <td valign="bottom" class="tit_alt">쿠폰을 등록,수정합니다.</td>
  </tr>
</table>

<br>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
  <form name="frm" action="shop_save.php" method="post" onSubmit="return inputCheck(this);" enctype="multipart/form-data">
    <input type="hidden" name="tmp">
    <input type="hidden" name="mode" value="shop_coupon">
    <input type="hidden" name="sub_mode" value="<?= $sub_mode ?>">
    <input type="hidden" name="idx" value="<?= $idx ?>">
    <input type="hidden" name="prdcode" value="<?= $prdcode ?>">
    <tr>
      <td width="15%" class="t_name">쿠폰명</td>
      <td width="85%" class="t_value">
        <? if ($prdcode) { ?>
          <?= $coupon_info['coupon_name'] ?> <a href="../product/prd_input.php?mode=update&prdcode=<?= $prdcode ?>" target="_blank"><button type="button">상품정보에서 수정</button></a>
        <? } else { ?>
          <input name="coupon_name" value="<?= $coupon_info['coupon_name'] ?>" type="text" size="60" class="input">
        <? } ?>
      </td>
    </tr>
    <tr>
      <td class="t_name">기간</td>
      <td class="t_value">
        <input name="coupon_sdate" value="<?= $coupon_info['coupon_sdate'] ?>" type="text" size="12" class="input" onClick="Calendar1('document.frm','coupon_sdate');"> ~
        <input name="coupon_edate" value="<?= $coupon_info['coupon_edate'] ?>" type="text" size="12" class="input" onClick="Calendar1('document.frm','coupon_edate');">
      </td>
    </tr>
    <tr>
      <td class="t_name">쿠폰금액/할인율</td>
      <td class="t_value">
        <input name="coupon_dis" value="<?= $coupon_info['coupon_dis'] ?>" type="text" class="input">&nbsp;
        <input type="radio" name="coupon_type" value="%" <? if ($coupon_info['coupon_type'] == "" || $coupon_info['coupon_type'] == "%") echo "checked"; ?>>% 퍼센트
        <input type="radio" name="coupon_type" value="원" <? if ($coupon_info['coupon_type'] == "원") echo "checked"; ?>>원
      </td>
    </tr>
    <tr>
      <td class="t_name">쿠폰수량</td>
      <td class="t_value">
        <input name="coupon_amount" value="<?= $coupon_info['coupon_amount'] ?>" type="text" class="input">&nbsp;
        <input type="checkbox" name="coupon_limit" value="N" <? if ($coupon_info['coupon_limit'] == "N") echo "checked"; ?> onClick="if(this.checked==true) this.form.coupon_amount.disabled = true; else this.form.coupon_amount.disabled = false;">수량제한없음
      </td>
    </tr>
    <!--tr>
          <td class="t_name">쿠폰이미지</td>
          <td class="t_value">
          	<?
            $couponimg_path = "../../images/coupon";
            if (@file($couponimg_path . "/" . $coupon_info['coupon_img'])) {
            ?>
          	<img src="/images/coupon/<?= $coupon_info['coupon_img'] ?>"><br>
          	<?
            }
            ?>
            <input name="coupon_img" value="<?= $coupon_info['coupon_img'] ?>" type="file" class="input">&nbsp;

          </td>
        </tr-->
    <? if ($sub_mode == "update") { ?>
      <tr>
        <td height="25" class="t_name">쿠폰링크</td>
        <td class="t_value"><?= $coupon_link ?></td>
      </tr>
      <tr>
        <td height="25" class="t_name">쿠폰발급회원</td>
        <td class="t_value">
          <button type="button" onclick="coupon_input('<?= $idx ?>', '<?= $prdcode ?>')">쿠폰발급</button><br /><br />
          <table width="100%" border="0" cellspacing="2" cellpadding="1">
            <tr>
              <td height="20" align="center" class="t_name">번호</td>
              <td align="center" class="t_name">회원이름</td>
              <td align="center" class="t_name">회원아이디</td>
              <td align="center" class="t_name">발급시간</td>
              <td align="center" class="t_name">사용여부</td>
              <th align="center" class="t_name" width="10%">삭제</th>
            </tr>
            <?
            $sql = "select wc.idx, wc.wdate, wc.coupon_use, wm.id, wm.name from wiz_mycoupon wc, wiz_member wm where wc.eventidx='$idx' and wc.memid = wm.id";
            $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
            $total = mysqli_num_rows($result);
            while ($row = mysqli_fetch_object($result)) {
            ?>
              <tr>
                <td align="center"><?= $total ?></td>
                <td align="center"><?= $row->name ?></td>
                <td align="center"><?= $row->id ?></td>
                <td align="center"><?= $row->wdate ?></td>
                <td align="center"><?= $row->coupon_use ?> &nbsp;<button type="button" onclick="chmycoupon('<?= $row->idx ?>', '<?= $idx ?>', '<?= $prdcode ?>')">변경</button></td>
                <td align="center"><button type="button" onclick="delmycoupon('<?= $row->idx ?>', '<?= $idx ?>', '<?= $prdcode ?>')">삭제</button></td>
              </tr>
            <?
              $total--;
            }
            ?>
          </table>
        </td>
      </tr>
    <? } ?>
</table>

<div class="AW-btn-wrap">
  <button type="submit" class="on">확인</button>
  <a onClick="document.location='shop_coupon.php';">목록</a>
</div><!-- .AW-btn-wrap -->

</form>





<div class="AW-manage-checkinfo">
  <div class="tit">체크사항</div>
  <div class="cont">
    <b>쿠폰다운로드 페이지 생성방법</b><br />
    쿠폰을 생성후 다운로드 받을 페이지를 디자인하여 생성합니다.<br />
    생성한 페이지의 적당한 위치에 "쿠폰링크" 테그를 이용하여 쿠폰다운로드를 링크를 생성합니다.<br />
    링크를 클릭하면 쿠폰이 다운로드 됩니다.<br /><br />

    "쿠폰링크" 테그로 링크를 걸면 쇼핑몰 어느위치에서든 쿠폰다운로드 기능을 만들수 있습니다.
  </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->

<? include "../footer.php"; ?>