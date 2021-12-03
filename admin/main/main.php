<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?
// 총게시판수
$sql = "select count(code) as cnt from wiz_bbsinfo";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$bbs_num = $row['cnt'];

// 총게시물수
$sql = "select count(idx) as cnt from wiz_bbs";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$bbs_total = $row['cnt'];

// 오늘게시물수
$today = date("Ymd");
$sql = "select count(idx) as cnt from wiz_bbs where DATE_FORMAT(FROM_UNIXTIME(wdate), '%Y%m%d') = '".$today."'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$bbs_today = $row['cnt'];

// 오늘댓글
$sql = "select count(idx) as cnt from wiz_comment where DATE_FORMAT(wdate, '%Y%m%d') = '".$today."'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$comment_today = $row['cnt'];
?>
<div class="manage-M-bbs">
	<div class="tit">최근 게시물</div>
    <a href="../bbs/bbs_pro_list.php" class="more"></a>
    <div class="cont">
    	<ul class="latest">
    		<?
        $sql = "select wb.idx,wb.code,wb.subject,date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate,wi.title, wi.type from wiz_bbs wb, wiz_bbsinfo wi where wb.code = wi.code order by wb.idx desc limit 6";
        //$sql = "select *, date_format(from_unixtime(wdate), '%Y-%m-%d') as wdate from wiz_bbs where code != 'memout' order by idx desc limit $limit";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $total = mysqli_num_rows($result);
        while($row = mysqli_fetch_array($result)){
        ?>
        	<li><a href="../bbs/bbs_view.php?idx=<?=$row['idx']?>&code=<?=$row['code']?>">[<?=$row['title']?>] <?=cut_str($row['subject'],20)?></a><span><?=$row['wdate']?></span></li>
				<?
				}
				?>
        </ul><!-- .latest-->
        <div class="total">
        	<dl>
            	<dt>총 게시판 수</dt>
                <dd><b><?=$bbs_num?></b>개</dd>
            </dl>
        	<dl>
            	<dt>총 게시물</dt>
                <dd><b><?=$bbs_total?></b>개</dd>
            </dl>
        	<dl>
            	<dt>오늘 게시물</dt>
                <dd><b><?=$bbs_today?></b>개</dd>
            </dl>
        	<dl>
            	<dt>오늘 댓글</dt>
                <dd><b><?=$comment_today?></b>개</dd>
            </dl>
        </div><!-- .total -->
    </div><!-- .cont -->
</div><!-- .manage-M-bbs -->


<div class="manage-M-order">
	<div class="tit">최근 주문현황</div>
    <a href="../order/order_list.php" class="more"></a>
    <div class="cont">
    	<div class="latest">
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
            	<thead>
                	<tr>
                    	<td>주문번호</td>
                    	<td width="17%">주문자명</td>
                    	<td width="17%">주문방법</td>
                    	<td width="17%">주문금액</td>
                    	<td width="17%">주문상태</td>
                    </tr>
                </thead>
                <tbody>
                <?
                $sql = "select * from wiz_order where status != '' order by orderid desc limit 4";
                $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
                $total = mysqli_num_rows($result);
					      while($row = mysqli_fetch_array($result)){
					      ?>
                	<tr>
                    	<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=$row['orderid']?></a></td>
                    	<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=$row['send_name']?></a></td>
                    	<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=pay_method($row['pay_method'])?></a></td>
                    	<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=number_format($row['total_price'])?>원</a></td>
                    	<td><a href="../order/order_info.php?orderid=<?=$row['orderid']?>"><?=order_status($row['status'])?></a></td>
                    </tr>
								<?
              	}
              	?>
                </tbody>
            </table>
        </div><!-- .latest-->
        <?
        // 오늘매출액
        $sdate = date('Y-m-d')." 00:00:00";
        $edate = date('Y-m-d')." 23:59:59";
        $sql = "select sum(total_price) as today_price from wiz_order where order_date >= '$sdate' and order_date <= '$edate' and (status='OY' or status='DR' or status='DI' or status='DC')";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $row = mysqli_fetch_object($result);
        $today_price = $row->today_price;

        // 이달매출액
        $sdate = date('Y-m')."-01 00:00:00";
        $edate = date('Y-m')."-31 23:59:59";
        $sql = "select sum(total_price) as month_price from wiz_order where order_date >= '$sdate' and order_date <= '$edate' and (status='OY' or status='DR' or status='DI' or status='DC')";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $row = mysqli_fetch_object($result);
        $month_price = $row->month_price;

        // 올해매출액
        $sdate = date('Y')."-01-01 00:00:00";
        $edate = date('Y')."-12-31 23:59:59";
        $sql = "select sum(total_price) as year_price from wiz_order where order_date >= '$sdate' and order_date <= '$edate' and (status='OY' or status='DR' or status='DI' or status='DC')";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $row = mysqli_fetch_object($result);
        $year_price = $row->year_price;

        // 총매출액
        $sql = "select sum(total_price) as total_price from wiz_order where (status='OY' or status='DR' or status='DI' or status='DC')";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $row = mysqli_fetch_object($result);
        $total_price = $row->total_price;
        ?>
        <div class="total">
        	<dl>
            	<dt>오늘 매출액</dt>
                <dd><b><?=number_format($today_price)?></b>원</dd>
            </dl>
        	<dl>
            	<dt>올해 매출액</dt>
                <dd><b><?=number_format($year_price)?></b>원</dd>
            </dl>
        	<dl>
            	<dt>이달 매출액</dt>
                <dd><b><?=number_format($month_price)?></b>원</dd>
            </dl>
        	<dl>
            	<dt>총 매출액</dt>
                <dd><b><?=number_format($total_price)?></b>원</dd>
            </dl>
        </div><!-- .total -->
    </div><!-- .cont -->
</div><!-- .manage-M-order -->


<div class="manage-M-reply">
	<div class="tit"><span>최근</span>댓글</div>
    <a href="../bbs/bbs_pro_list.php" class="more"></a>
    <div class="cont">
    	<ul class="latest">
    		<?
					$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		
					$sql = "select wb.idx,wb.code,wb.subject,date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate,wc.content, wc.cidx, wbi.code, wbi.title, wbi.type, wc.wdate as cdate
					from wiz_bbs wb, wiz_comment wc, wiz_bbsinfo wbi
					where wb.idx = wc.cidx and wb.code = wbi.code
					order by wc.idx desc limit 8";
		
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$total = mysqli_num_rows($result);
					while($row = mysqli_fetch_array($result)){
						$new = "";
						$wtime = mktime(0,0,0,substr($row['cdate'],5,2),substr($row['cdate'],8,2),substr($row['cdate'],0,4));
						if(($ttime-$wtime)/86400 <= 2) $new = "<img src='../image/main/n.gif' border='0' align='absmiddle'>";	// new
						$row['wdate'] = str_replace("-","-",$row['wdate']);
		
						if(!strcmp($row['type'], "SCH")) $purl = "../schedule/list.php";
						else $purl = "../bbs/list.php";
					?>
        	<li><a href="../bbs/bbs_view.php?idx=<?=$row['idx']?>&code=<?=$row['code']?>">[<?=$row['title']?>] <?=$row['content']?></a><span>2017-06-07</span></li>
					<?
					}
					?>
        </ul><!-- .latest-->
    </div><!-- .cont -->
</div><!-- .manage-M-reply -->


<div class="manage-M-mem">
	<div class="tit"><span>회원</span>현황</div>
    <a href="../member/member_list.php" class="more"></a>
    <div class="cont">
    	<div class="latest">
    			<?
					// 전체회원수
					$sql = "select id from wiz_member";
					$result = mysqli_query($connect, $sql);
					$all_total = mysqli_num_rows($result);
		
					// 오늘가입자수
					$sql = "select id from wiz_member where wdate between '".date('Y-m-d')." 00:00:00' and '".date('Y-m-d')." 23:59:59'";
					$result = mysqli_query($connect, $sql);
					$today_total = mysqli_num_rows($result);
		
					// 오늘탈퇴회원수
					$sql = "select idx from wiz_bbs where code = '[memout]' and FROM_UNIXTIME(wdate, '%Y-%m-%d') = '".date('Y-m-d')."'";
					$result = mysqli_query($connect, $sql);
					$today_out = mysqli_num_rows($result);
		
					// 탈퇴회원수
					$sql = "select idx from wiz_bbs where code = '[memout]'";
					$result = mysqli_query($connect, $sql);
					$mem_out = mysqli_num_rows($result);
		
					?>
        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        		<?
						// 최근 가입한 회원4명
						$sql = "select id,wm.name as mname,wm.level as mlevel,wdate,wl.level as llevel,wl.name as lname
										from wiz_member as wm left join wiz_level as wl on wm.level = wl.idx order by wdate desc limit 4";
						$result = mysqli_query($connect, $sql);
						while($row = mysqli_fetch_array($result)){
						?>
            	<tr>
                	<td><a href="../member/member_info.php?mode=update&id=<?=$row['id']?>"><?=$row['id']?></a></td>
                	<td><a href="../member/member_info.php?mode=update&id=<?=$row['id']?>"><?=$row['mname']?></a></td>
                	<td><a href="../member/member_info.php?mode=update&id=<?=$row['id']?>"><?=$row['lname']?></a></td>
                	<td><a href="../member/member_info.php?mode=update&id=<?=$row['id']?>"><?=$row['wdate']?></a></td>
                </tr>
						<? } ?>            	
            </table>
        </div><!-- .latest -->
        <div class="total">
        	<dl>
            	<dt>오늘 가입회원</dt>
                <dd><b><?=$today_total?></b>명</dd>
            </dl>
        	<dl>
            	<dt>전체 회원수</dt>
                <dd><b><?=$all_total?></b>명</dd>
            </dl>
        	<dl>
            	<dt>오늘 탈퇴회원</dt>
                <dd><b><?=$today_out?></b>명</dd>
            </dl>
        	<dl>
            	<dt>총 탈퇴회원</dt>
                <dd><b><?=$mem_out?></b>명</dd>
            </dl>
        </div><!-- .total -->
    </div><!-- .cont -->
</div><!-- .manage-M-mem -->  


<div class="manage-M-graph_bbs">
	<div class="tit"><span>게시판</span> 통계</div>
    <a href="../bbs/bbs_pro_list.php" class="more"></a>
    <div class="cont">
        
        <?
        
        $chart_mode="border";
        include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
        ?>
         
    
    </div><!-- .cont -->
</div><!-- .manage-M-graph_bbs -->


<div class="manage-M-graph_user">
	<div class="tit"><span>접속</span> 통계</div>
    <a href="../marketing/connect_list.php" class="more"></a>
    <div class="cont">
    
		<?
        $chart_mode="common";
        $chart_param	= "common|OD|".date('Y-m-')."01"."|".date('Y-m-').date('t')."";
        
        include "$_SERVER[DOCUMENT_ROOT]/admin/chart.php"; 
        ?>
            
    
    
    </div><!-- .cont -->
</div><!-- .manage-M-graph_user -->
              
              
<? include "../footer.php"; ?>