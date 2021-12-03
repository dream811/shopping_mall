<?

include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/design_info.inc"; 	// 디자인 정보
include "../inc/oper_info.inc"; 	// 운영 정보

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이쇼핑";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/now_position.inc";	// 현재위치
include "../inc/mem_info.inc"; 		// 회원 정보

?>
<script language="JavaScript">
<!--
// 주문상세내역 보기
function orderView(orderid){
	var url = "/shop/order_view.php?orderid=" + orderid;
	window.open(url, "orderView", "height=640, width=736, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, left=0, top=0");
}
//-->
</script>

<table border=0 cellpadding=0 cellspacing=0 width="98%" align=center>
  <tr>
  	<td align=center>

    <? include "my_menu.php"; ?>

		</td>
	</tr>

	<!-- 구매내역 -->
	<tr><td height="15"></td></tr>
	<tr>
		<td align=center>
			
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr>
		  	<td colspan=12><img src="/images/myshop_m01_01.gif"></td>
		  	<td align="right"><a href="/member/my_order.php"><img src="/images/but_more.gif" border="0"></a></td>
		  </tr>
			<tr><td colspan=13 bgcolor=#939393 height=3></td></tr>
			<tr height=33>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">주문일자</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">주문번호</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">결제금액</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">결제방법</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">배송상태</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">운송장번호</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">상세보기</td>
			</tr>
			<tr><td colspan=13 bgcolor=#f7f7f7 height=3></td></tr>
			<?
			$sql = "select * from wiz_order where send_id = '".$wiz_session['id']."' and status != '' order by order_date desc limit 5";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);
      
      $rows = 12;
      $lists = 5;
      $page_count = ceil($total/$rows);
      if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
      $start = ($page-1)*$rows;
      if($start>1) mysql_data_seek($result,$start);
      
      while(($row = mysqli_fetch_object($result)) && $rows){
      ?>
			<tr align=center height=28>
				<td><?=substr($row->order_date,0,10)?></td>
				<td></td>
				<td><?=$row->orderid?></td>
				<td></td>
				<td><?=number_format($row->total_price)?>원</td>
				<td></td>
				<td><?=pay_method($row->pay_method)?></td>
				<td></td>
				<td><?=order_status($row->status)?></td>
				<td></td>
				<td><a href="<?=$oper_info->del_trace?><?=$row->deliver_num?>" target="_blank"><?=$row->deliver_num?></a></td>
				<td></td>
				<td><a href="javascript:orderView('<?=$row->orderid?>');"><img src="/images/but_view.gif" border="0"></a></td>
			</tr>
			<tr><td colspan=13 bgcolor=#dddddd height=1></td></tr>
			<?
				$rows--;
			}
			
			if($total <= 0){
			?>
			<tr><td colspan=13 align=center height=50><img src="/images/no_icon.gif" align=absmiddle> 현재 구매내역이 없습니다.</td></tr>
			<tr><td colspan=13 bgcolor=#dddddd height=1></td></tr>
			<?
			}
			?>
			<tr><td colspan=13 bgcolor=#f7f7f7 height=3></td></tr>
			</table>
		</td>
	</tr>


	<!--관심상품리스트-->
	<tr><td height="15"></td></tr>
	<tr>
	  <td align=center>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			  <tr>
			  	<td colspan=4><img src="/images/myshop_m01_02.gif"></td>
			  	<td align="right"><a href="/member/my_wish.php"><img src="/images/but_more.gif" border="0"></a></td>
			  </tr>
			  <tr><td colspan=5 bgcolor=#939393 height=3></td></tr>
			  <tr height=33>
				  <td width="80%" background="/images/shop_nomal_bar.gif" align=center class="gray">상품</td>
				  <td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				  <td width="10%" background="/images/shop_nomal_bar.gif" align=center class="gray">가격</td>
				  <td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				  <td width="10%" background="/images/shop_nomal_bar.gif" align=center class="gray" width="10%">삭제</td>
			  </tr>
			  <tr><td colspan=5 bgcolor=#f7f7f7 height=3></td></tr>
				<?
				$sql = "select ww.idx, wp.prdcode, wp.prdname, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.shortage, wp.stock, wp.conprice from wiz_wishlist ww, wiz_product wp where ww.memid = '".$wiz_session['id']."' and ww.prdcode = wp.prdcode order by ww.wdate desc limit 5";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$total = mysqli_num_rows($result);
				
				$no = 0;
				$rows = 10;
				$lists = 5;
				$page_count = ceil($total/$rows);
				if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				if($start>1) mysql_data_seek($result,$start);
				
				while(($row = mysqli_fetch_object($result)) && $rows){

					$sp_img = "";
					if($row->popular == "Y") $sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
					if($row->recom == "Y") $sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
					if($row->new == "Y") $sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
					if($row->sale == "Y"){ $sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;"; $sellprice = "<s>".number_format($row->conprice)." 원</s> → "; }
					if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";
					
					if(!empty($row->strprice)) $row->sellprice = $row->strprice;
					else $row->sellprice = number_format($row->sellprice)."원";
					
					// 상품 이미지
					if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
					else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;
				?>
				<tr>
					<td>
						<table width="100%">
						<tr>
							<td width="50"><a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&page=<?=$page?>"><img src="<?=$row->prdimg_R?>" border="0" width="50" height="50"></a></td>
							<td><a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&page=<?=$page?>"><?=$row->prdname?></a> <?=$sp_img?></td>
						</tr>
					  </table>
					</td>
					<td></td>
					<td align=center><?=$row->sellprice?></td>
					<td></td>
					<td align=center><a href="my_save.php?mode=my_wishdel&idx=<?=$row->idx?>"><img src="/images/but_delete.gif" border="0"></a></td>
				</tr>
				<tr><td colspan=5 bgcolor=#dddddd height=1></td></tr>
				<?
					$no++;
					$rows--;
				}
				if($total <= 0){
				?>
				<tr><td colspan=5 align=center height=50><img src="/images/no_icon.gif" align=absmiddle> 관심상품 리스트가 비어있습니다.</td></tr>
				<tr><td colspan=5 bgcolor=#dddddd height=1></td></tr>
				<?
				}
				?>
				<tr><td colspan=5 bgcolor=#f7f7f7 height=3></td></tr>
			</table>

		</td>
	</tr>
	

	<!--쿠폰목록-->
  <? if($oper_info->coupon_use == "Y"){ ?>
	<tr><td height="15"></td></tr>
	<tr>
		<td align=center>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr><td colspan=13><img src="/images/myshop_m01_011.gif"></td></tr>
			<tr><td colspan=13 bgcolor=#939393 height=3></td></tr>
			<tr height=33>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray" width="10%">번호</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">쿠폰명</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray">기간</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray" width="10%">할인액</td>
				<td background="/images/shop_nomal_bar.gif" align=center width=2><img src="/images/form_line.gif"></td>
				<td background="/images/shop_nomal_bar.gif" align=center class="gray" width="10%">사용여부</td>
			</tr>
			<tr><td colspan=13 bgcolor=#f7f7f7 height=3></td></tr>
			<?
			$sql = "select * from wiz_mycoupon where memid='".$wiz_session['id']."' and coupon_sdate <= curdate() and coupon_edate >= curdate() order by idx desc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);
      
      $rows = 12;
      $lists = 5;
      $page_count = ceil($total/$rows);
      if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
      $start = ($page-1)*$rows;
      if($start>1) mysql_data_seek($result,$start);
      $no = $total - $start;
      while(($row = mysqli_fetch_object($result)) && $rows){
      	if($row->coupon_use == "Y") $row->coupon_use = "사용함";
      	else  $row->coupon_use = "미사용";
      ?>
			<tr align=center height=28>
				<td><?=$no?></td>
				<td></td>
				<td><?=$row->coupon_name?></td>
				<td></td>
				<td><?=$row->coupon_sdate?> ~ <?=$row->coupon_edate?></td>
				<td></td>
				<td><?=$row->coupon_dis?><?=$row->coupon_type?></td>
				<td></td>
				<td><?=$row->coupon_use?></td>
			</tr>
			<tr><td colspan=13 bgcolor=#dddddd height=1></td></tr>
			<?
				$no--;
				$rows--;
			}
			
			if($total <= 0){
			?>
			<tr><td colspan=13 align=center height=50><img src="/images/no_icon.gif" align=absmiddle> 쿠폰이 없습니다.</td></tr>
			<tr><td colspan=13 bgcolor=#dddddd height=1></td></tr>
			<?
			}
			?>
			<tr><td colspan=13 bgcolor=#f7f7f7 height=3></td></tr>
			</table>
		</td>
	</tr>
	<? } ?>
	
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>