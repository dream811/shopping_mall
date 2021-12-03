<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function delCoupon(idx){
   if(confirm('해당쿠폰을 삭제하시겠습니까?')){
      document.location = "shop_save.php?mode=shop_coupon&sub_mode=delete&idx=" + idx;
   }
}
//-->
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
      <form name="frm" action="shop_save.php" method="post">
      <input type="hidden" name="tmp">
      <input type="hidden" name="mode" value="coupon_use">
        <tr>
          <td width="15%" class="t_name">쿠폰사용여부</td>
          <td width="85%" class="t_value">
            <input type="radio" name="coupon_use" value="Y" <? if($oper_info->coupon_use == "Y") echo "checked"; ?>>사용함
            <input type="radio" name="coupon_use" value="N" <? if($oper_info->coupon_use == "N") echo "checked"; ?>>사용안함 &nbsp;
            <button type="submit">확인</button>
          </td>
        </tr>
      </form>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 쿠폰목록</td>
			    <td align="right"><a onClick="document.location='shop_coupon_input.php?sub_mode=insert';" class="AW-btn">쿠폰 등록</a></td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
			<th width="8%">번호</th>
			<th width="*">쿠폰명</th>
			<th width="20%">기간</th>
			<th width="15%">할인</th>
			<th width="15%">수량</th>
			<th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      $sql = "select * from wiz_coupon order by idx desc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);
		  $no = $total;
	      while($row = mysqli_fetch_array($result)){
	      	if($row['coupon_limit'] == "N") $row['coupon_amount'] = "수량제한없음";
	      ?>
        <tr class="t_tr">
          <td height="30" align="center"><?=$no?></td>
          <td><?=$row['coupon_name']?></td>
          <td align="center"><?=$row['coupon_sdate']?> ~ <?=$row['coupon_edate']?></td>
          <td align="center"><?=$row['coupon_dis']?><?=$row['coupon_type']?></td>
          <td align="center"><?=$row['coupon_amount']?></td>
          <td align="center">
          	<a onclick="document.location='shop_coupon_input.php?sub_mode=update&idx=<?=$row['idx']?>'" class="AW-btn-s modify">수정</a>
            <a onclick="delCoupon('<?=$row['idx']?>');" class="AW-btn-s del">삭제</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      		$no--;
	      }
	      if($total <= 0){
	      ?>
	        <tr align="center" class="t_tr"><td height="30" colspan="10" align="center">등록된 쿠폰이 없습니다.</td></tr>
	        <tr><td colspan="20" class="t_line"></td></tr>
	      <?
	      }
	      ?>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품별쿠폰</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
			<th width="8%">번호</th>
			<th width="*">쿠폰명</th>
			<th width="20%">기간</th>
			<th width="15%">할인</th>
			<th width="15%">수량</th>
			<th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
      <?
      $sql = "select prdcode from wiz_product where coupon_use='Y' order by prior desc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);

      $rows = 12;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;

      $sql = "select prdcode,prdname,coupon_dis,coupon_type,coupon_sdate,coupon_edate,coupon_amount,coupon_limit from wiz_product where coupon_use='Y' order by prior desc limit $start, $rows";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

      while(($row = mysqli_fetch_array($result)) && $rows){
      	if($row['coupon_limit'] == "N") $row['coupon_amount'] = "수량제한없음";
      ?>
        <tr class="t_tr">
          <td height="30" align="center"><?=$no?></td>
          <td>[상품쿠폰]<?=$row['prdname']?></td>
          <td align="center"><?=$row['coupon_sdate']?> ~ <?=$row['coupon_edate']?></td>
          <td align="center"><?=$row['coupon_dis']?><?=$row['coupon_type']?></td>
          <td align="center"><?=$row['coupon_amount']?></td>
          <td align="center">
          	<a onclick="document.location='shop_coupon_input.php?sub_mode=update&prdcode=<?=$row['prdcode']?>'" class="AW-btn-s modify">수정</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      		$no--;
					$rows--;
      }
      if($total <= 0){
      ?>
        <tr align="center" class="t_tr">
          <td height="30" colspan="10" align="center">등록된 쿠폰적용 상품이 없습니다.</td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      <?
      }
      ?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>





<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
        <b>- 쿠폰사용시 : 이벤트쿠폰,상품별쿠폰 발행이 가능합니다.</b><br />
        관리자 > 상품관리 > 상품등록시 상품별 쿠폰발행기능 생성<br />
        쇼핑몰 > 상품상세페이지 에서 상품별 할인쿠폰 다운로드 기능<br />
        쇼핑몰 > 주문정보입력 페이지에서 쿠폰조회 및 사용기능<br />
        쇼핑몰 > 마아페이지에서 쿠폰 사용내역 조회기능<br /><br />

        <b>- 이벤트쿠폰 : 기간과 할인액등을 설정하여 쿠폰을 생성합니다. 다운받은 쿠폰은 모든 상품구입시 사용 가능합니다.</b><br />
        특정상품 구입시만 또는 특정상품을 제외하고 사용하는 기능은 제공하지 않습니다.<br /><br />

        <b>- 상품별쿠폰 : 특정상품에 대해서만 쿠폰을 발행하며 다운받은 쿠폰은 해당 상품 구입시에만 사용 가능합니다.</b>
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->

<? include "../footer.php"; ?>