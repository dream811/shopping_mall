<?
$sub_tit="상점정보";
?>
<? include "../inc/header.php" ?>
<body>
<? include "../inc/gnb.php" ?>
<? include "../inc/search.php" ?>
<? include "../inc/sub_title.php" ?>
<style>
.mallinfo_table th, .mallinfo_table td{border-bottom:1px solid #e6e6e6; padding:7px; font-size:12px;}
.mallinfo_table th{color:#787878; background:url(../img/sub/point.gif) 13px 12px no-repeat; padding-left:20px; width:40%; font-weight:normal; text-align:left}
</style>
<div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mallinfo_table">
  <tr>
    <th>상점명</th>
    <td>쇼핑몰</td>
  </tr>
  <tr>
    <th>상호명</th>
    <td>쇼핑몰</td>
  </tr>
  <tr>
    <th>대표</th>
    <td>홍길동</td>
  </tr>
  <tr>
    <th>개인정보관리책임자</th>
    <td>홍길동</td>
  </tr>
  <tr>
    <th>사업자등록번호</th>
    <td>000-00-00000</td>
  </tr>
  <tr>
    <th>통신판매업신고번호</th>
    <td>제0000-서울서초-0000호</td>
  </tr>
  <tr>
    <th>사업장소재지</th>
    <td>OO시 OO구 OOO로 1층<br />(구 OO구 OO동 OO번지)</td>
  </tr>
  <tr>
    <th>전화</th>
    <td>00-0000-0000 <a href="tel:0000000000"><img src="../img/sub/icon_call.gif" align="absmiddle" /></a></td>
  </tr>
  <tr>
    <th>팩스</th>
    <td>00-0000-0000</td>
  </tr>
  <tr>
    <th>메일</th>
    <td>test@test.com <a href="mailto:test@test.com"><img src="../img/sub/icon_email.gif" align="absmiddle" /></a></td>
  </tr>
  <tr>
    <th>상담시간</th>
    <td>평일 10시~17시, 토요일 10시~12시</td>
  </tr>
</table>
</div>
<div style="text-align:center; padding-top:10px;">
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><input type="button" class="btn_guide" value="이용약관" onClick="goURL('agreement.php')" /></td>
    <td style="padding-left:5px;"><input type="button" class="btn_privacy" value="개인정보취급방침" onClick="goURL('privacy.php')" /></td>
  </tr>
</table>
</div>
<? include "../inc/footer.php" ?>
</body>
</html>