<? include "../inc/header.php" ?>

<body>
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>

<div class="main_title">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" style="padding-left:10px; font-weight:bold">BEST ITEM</td>
    <td align="right"><img src="../img/main/allow_top.gif" width="30" height="30" /></td>
  </tr>
</table>
</div>
<div class="main_item">
<?
$prd_type = "best";				// 상품구분(베스트상품)
$prd_num = "6";						// 상품수
$prd_row = "0";						// 줄바꿈 상품수
$prd_len = "12";					// 상품명글자수
$prd_width = "0";					// 상품이미지 너비
$prd_height = "0";				// 상품이미지 높이
include "../../inc/prdmain_mobile.inc";
?>
</div>

<div class="main_title">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" style="padding-left:10px; font-weight:bold">NEW ITEM</td>
    <td align="right"><img src="../img/main/allow_top.gif" width="30" height="30" /></td>
  </tr>
</table>
</div>
<div class="main_item">
<?
$prd_type = "new";				// 상품구분(신상품)
$prd_num = "6";						// 상품수
$prd_row = "0";						// 줄바꿈 상품수
$prd_len = "12";					// 상품명글자수
$prd_width = "0";					// 상품이미지 너비
$prd_height = "0";				// 상품이미지 높이
include "../../inc/prdmain_mobile.inc";
?>
</ul>
</div>


<? include "../inc/footer.php" ?>

</body>
</html>