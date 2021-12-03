<?
$sub_tit="상품보기";
?>
<? include "../inc/header.php" ?>
<body>
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>
<? include "../inc/sub_title.php" ?>

<div class="prd_view">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="75" valign="top"><img src="../img/sub/item_01.jpg" width="75" height="75" /></td>
        <td style="padding-left:15px;" valign="top">
        	<div class="prd_tit">3M 983D-326 차량안전용 고휘도 반사(5cm폭) 롤 판매 백/적색 (15cm간격)</div>
            <div style="padding-top:8px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_normal">
              <tr>
                <th>상품가격</th>
                <td><font class="prd_price">145,000원</font></td>
              </tr>
              <tr>
                <th>적립금</th>
                <td>1,450원</td>
              </tr>
            </table>
            </div>
            <div style="padding-top:5px;"><input type="button" value="Photo Gallery ▶" class="btn_gray_small" style="width:80px;" /></div>
        </td>
      </tr>
    </table>
</div>
<div class="prd_view_tab">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><a href="prdview_1.php">상품정보</a></td>
    <td><a href="prdview_2.php">상품 상세보기</a></td>
    <th><a href="prdview_3.php">상품리뷰<small>(1)</small></a></th>
  </tr>
</table>
</div>

<div class="review">
상품 리뷰 <small>(총 1건)</small>
<hr style="margin-top:5px; margin-bottom:10px;" />
<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-bottom:1px solid #e6e6e6;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td class="review_info">0414hanna / 2013.06.30 / ★★★★★</td></tr>
<tr><td class="review_title">밤에 반짝반짝 빛나요. 상품 완전 만족합니다. 별 다섯...</td></tr>
<tr><td class="review_con">밤에 반짝반짝 빛나요. 상품 완전 만족합니다. 별 다섯개 드립니다. 번창하세요^^!</td></tr>
</table>
</td></tr>
<tr><td style="border-bottom:1px solid #e6e6e6;">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td class="review_info">0414hanna / 2013.06.30 / ★★★★★</td></tr>
<tr><td class="review_title">밤에 반짝반짝 빛나요. 상품 완전 만족합니다. 별 다섯...</td></tr>
</table>
</td></tr>
</table>
</div>


<div class="btn">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left"><table width="100%" border="0" cellpadding="0" cellspacing="0" align="left"><tr><td width="5"><img src="../img/sub/btn_gray_left.gif" /></td><td><input type="button" class="btn_order" value="구매하기" onClick="location.href='payment.php'" /></td><td width="5"><img src="../img/sub/btn_gray_right.gif" /></td></tr></table></td>
    <td align="center" style="padding-left:5px; padding-right:5px;"><table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td width="5"><img src="../img/sub/btn_white_left.gif" /></td><td><input type="button" class="btn_etc" value="장바구니" onClick="location.href='cart.php'" /></td><td width="5"><img src="../img/sub/btn_white_right.gif" /></td></tr></table></td>
    <td align="right"><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="5"><img src="../img/sub/btn_white_left.gif" /></td><td><input type="button" class="btn_etc" value="관심상품" onClick="location.href='wishlist.php'" /></td><td width="5"><img src="../img/sub/btn_white_right.gif" /></td></tr></table></td>
  </tr>
</table>
</div>
<? include "../inc/footer.php" ?>
</body>
</html>