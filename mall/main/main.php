<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>




<div class="manage-M-order">
	<div class="tit">최근 주문현황</div>
    <a href="../order/order_list.php" class="more"></a>
    <div class="cont">
    	<div class="latest">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<thead>
                	<tr>
                    	<td width="16%">주문날짜</td>
                    	<td>주문번호</td>
                    	<td width="16%">주문자명</td>
                    	<td width="16%">결제방법</td>
                    	<td width="16%">결제금액</td>
                    	<td width="16%">주문상태</td>
                    </tr>
                </thead>
                <tbody>
					<?
					  $sql = "select wo.* from wiz_order as wo inner join wiz_basket as wb on wo.orderid = wb.orderid where wo.status != '' and wb.mallid = '".$wiz_mall['id']."' order by wo.orderid desc limit 10";
					  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					  $total = mysqli_num_rows($result);
						  while($row = mysqli_fetch_array($result)){

							unset($mall_list);

									// 각 입점업체별 상품 수 구하기
									$b_sql = "select count(idx) as cnt, sum(prdprice) as total, mallid from wiz_basket where orderid='".$row['orderid']."' and mallid = '".$wiz_mall['id']."' group by mallid";
									$b_result = mysqli_query($connect, $b_sql) or error(mysqli_error($connect));
									while($b_row = mysqli_fetch_array($b_result)) {

										$mall_list[$b_row[mallid]]['cnt'] = $b_row['cnt'];
										$mall_list[$b_row[mallid]][total] = $b_row[total];

									}

									$b_sql = "select wb.*, wm.com_name, wm.com_tel, wm.del_method, wm.del_fixprice, wm.del_staprice, wm.del_staprice2, wm.del_staprice3, wm.del_prd, wm.del_prd2
													from wiz_basket as wb inner join wiz_mall as wm on wb.mallid = wm.id
													where wb.orderid='".$row['orderid']."' and wb.mallid = '".$wiz_mall['id']."' order by wb.mallid";
									$b_result = mysqli_query($connect, $b_sql) or error(mysqli_error($connect));
									while($b_row = mysqli_fetch_object($b_result)){

										$mall_no[$b_row->mallid]++;

										if(!strcmp($mall_no[$b_row->mallid], $mall_list[$b_row->mallid]['cnt'])) {

											if(!empty($b_row->del_method)) {

												$tmp_oper_info->mallid 				= $b_row->mallid;

												$tmp_oper_info->del_method 		= $b_row->del_method;
												$tmp_oper_info->del_fixprice 	= $b_row->del_fixprice;
												$tmp_oper_info->del_staprice 	= $b_row->del_staprice;
												$tmp_oper_info->del_staprice2 = $b_row->del_staprice2;
												$tmp_oper_info->del_staprice3 = $b_row->del_staprice3;

												$tmp_oper_info->del_prd	 = $b_row->del_prd;
												$tmp_oper_info->del_prd2 = $b_row->del_prd2;

											} else {
												$tmp_oper_info = $oper_info;
											}

										}

										// 배송비
										$deliver_price_mall[$b_row->mallid] = deliver_price($mall_list[$b_row->mallid][total], $tmp_oper_info, $b_row->mallid);

										$deliver_price += $deliver_price_mall[$b_row->mallid];

										if($b_row->del_price_mall > $deliver_price_mall[$b_row->mallid]){
											$deliver_msg .= " , 배송비 할증";
										}

										// 쿠폰사용
										if($b_row->coupon_use > 0){
											$coupon_msg = " - 쿠폰 사용(<b>".number_format($b_row->coupon_use)."</b>)";
										}

									$mall_price = $mall_list[$b_row->mallid][total] + $deliver_price_mall[$b_row->mallid] - $b_row->coupon_use;

									}

									$row['total_price'] = $mall_price;

					?>
					<tr>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=substr($row[order_date],0,10)?></a></td>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=$row['orderid']?></a></td>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=$row['send_name']?></a> </td>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=number_format($row['total_price'])?>원</a></td>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=pay_method($row['pay_method'])?></a></td>
						<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=order_status($row['status'])?></td>
					</tr>
					<? } if($total <= 0){ ?>
					<tr><td colspan="6" class="no-cont">주문내역이 없습니다.</td></tr>
					<? } ?>
                </tbody>
            </table>
        </div><!-- .latest-->
        <div class="total">
        	<dl>
            	<dt>오늘 매출액</dt>
                <dd><b>0</b>원</dd>
            </dl>
        	<dl>
            	<dt>올해 매출액</dt>
                <dd><b>0</b>원</dd>
            </dl>
        	<dl>
            	<dt>이달 매출액</dt>
                <dd><b>0</b>원</dd>
            </dl>
        	<dl>
            	<dt>총 매출액</dt>
                <dd><b>56,500</b>원</dd>
            </dl>
        </div><!-- .total -->
    </div><!-- .cont -->
</div><!-- .manage-M-order -->



<div class="manage-M-bbs">
	<div class="tit"><span>최근</span> 게시물</div>
    <a href="#" class="more"></a>
    <div class="cont">
    	<ul class="latest">
			<?
			$limit = 8;
			$sql = "select *, date_format(from_unixtime(wdate), '%Y/%m/%d') as wdate, wdate as wtime from wiz_bbs where code != 'memout' and (code like 'mall_%' or code = 'qna') and mallid = '".$wiz_mall['id']."' order by idx desc limit $limit";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);
			while($row = mysqli_fetch_object($result)){
				$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$wtime = $row->wtime;
							if(($ttime-$wtime)/86400 <= 1) $new_icon = "<img src='../image/bbs/icon_new.gif' border='0' align='absmiddle'>";	// new
							else $new_icon = "";
			?>
        	<li><a href="../bbs/view.php?code=<?=$row->code?>&idx=<?=$row->idx?>"><?=cut_str($row->subject,20)?></a> <?=$new_icon?><span><?=$row->wdate?></span></li>
			<? } if($total <= 0){ ?>
			<li class="no-cont">등록된 게시물이 없습니다.</li>
			<? } ?>
        </ul><!-- .latest-->
    </div><!-- .cont -->
</div><!-- .manage-M-bbs -->






<div class="manage-M-review">
	<div class="tit"><span>최근</span> 상품평</div>
    <a href="#" class="more"></a>
    <div class="cont">
    	<ul class="latest">
			<?
			$sql = "select wb.idx, wb.name, wb.prdcode, wb.subject, date_format(from_unixtime(wb.wdate), '%Y/%m/%d') as wdate, wb.wdate as wtime
							from wiz_bbs as wb inner join wiz_product as wp on wb.prdcode = wp.prdcode
							where wb.code = 'review' and wb.prdcode != '' and wp.mallid = '".$wiz_mall['id']."'
							order by wb.idx desc limit 8";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);
			while($row = mysqli_fetch_object($result)){
				$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$wtime = $row->wtime;
							if(($ttime-$wtime)/86400 <= 1) $new_icon = "<img src='../image/bbs/icon_new.gif' border='0' align='absmiddle'>";	// new
							else $new_icon = "";
			?>
        	<li><<a href="../product/prd_estimate.php"><?=cut_str($row->subject,20)?></a> <?=$new_icon?><span><?=str_replace("-","/",$row->wdate)?></span></li>
			<? } if($total <= 0){ ?>
			<li class="no-cont">등록된 상품평이 없습니다.</li>
			<? } ?>
        </ul><!-- .latest-->
    </div><!-- .cont -->
</div><!-- .manage-M-reply -->





<? include "../footer.php"; ?>