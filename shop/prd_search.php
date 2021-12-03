<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

$now_position = "<a href=/>Home</a> &gt; 상품검색";
$page_type = "prdsearch";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

if(!isset($catcode)) $catcode = "";
if(!isset($sellprice)) $sellprice = "";
if(!isset($sellprice2)) $sellprice2 = "";
if(!isset($prdcom)) $prdcom = "";
if(!isset($reserve)) $reserve = "";
if(!isset($reserve2)) $reserve2 = "";
if(!isset($group)) $group = "";
if(!isset($orderby)) $orderby = "";
if(!isset($grp)) $grp = "";
if(!isset($brand)) $brand = "";
if(!isset($page)) $page = "";
if(!isset($limit)) $limit = "";
if(!isset($search_keyword)) $search_keyword = "";
if(!isset($str_price)) $str_price = "";
if(!isset($end_price)) $end_price = "";

$param = "&catcode=$catcode&prdname=$prdname&sellprice=$sellprice&sellprice2=$sellprice2";
$param .= "&prdcom=$prdcom&reserve=$reserve&reserve2=$reserve2&group=$group&orderby=$orderby";

?>

<div class="prd_list_wrap">
	<h2 class="search_dt">상세 검색</h2>
	<form action="<?=$PHP_SELF?>" method="get">
	<div class="prd_search_area">

		<table width="100%" border="0" cellpadding="2" cellspacing="0" class="prd_search_table">
			<tr>
			  <th>상품분류</th>
			  <td colspan="3">
				<select name="catcode">
				<option value="">:::::::::::: 상품 분류를 선택하세요 ::::::::::::</option>
				<?
					$sql = "select catcode, catname from wiz_category where depthno = 1 and catuse != 'N' order by priorno01 asc";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					while($row = mysqli_fetch_object($result)){
				?>
					<option value="<?=$row->catcode?>" <? if($row->catcode == $catcode) echo "selected"; ?>>
					<?=$row->catname?>
					</option>
				<?
					}
				?>
				</select>
			  </td>
			  <td width="80" rowspan="3">
				<button type="submit" class="submit_btn">검색하기</button>
			  </td>
			</tr>
			<tr>
			  <th>상품명</th>
			  <td><input type="text" name="prdname" value="<?=$prdname?>" placeholder="상품명을 입력하세요." class="input" /></td>
			  <th>가격</th>
			  <td><input type="text" name="sellprice" value="<?=$sellprice?>" class="input" />
				~
					<input type="text" name="sellprice2" value="<?=$sellprice2?>" class="input"  />                                                				</td>
			</tr>
			<tr>
			  <th>브랜드검색</th>
			  <td><input type="text" name="prdcom" value="<?=$prdcom?>" placeholder="브랜드명을 입력하세요." class="input"  /></td>
			  <th>포인트</th>
			  <td><input type="text" name="reserve" value="<?=$reserve?>" class="input" />
				~
				<input type="text" name="reserve2" value="<?=$reserve2?>" class="input" /></td>
			</tr>
		  </table><!--//prd_search_table -->
	  </div><!--//prd_search_area -->
      <?
			$code01 = substr($catcode,0,2);
			if(empty($code01)) $catcode_sql = "";
			else $catcode_sql = " wc.catcode like '$code01%' and ";

			if(empty($production)) $production_sql = "";
			else $production_sql = " wp.production like '%$production%' and ";

			if(empty($prdname)) $prdname_sql = "";
			else $prdname_sql = " wp.prdname like '%$prdname%' and ";

			if(empty($sellprice)) $sellprice_sql = "";
			else $sellprice_sql = " wp.sellprice >= $sellprice  and ";
			if(empty($sellprice2)) $sellprice_sql2 = "";
			else $sellprice_sql2 = " wp.sellprice <= $sellprice2  and ";

			if(empty($reserve)) $reserve_sql = "";
			else $reserve_sql = " wp.reserve >= '$reserve'  and ";
			if(empty($reserve2)) $reserve_sql2 = "";
			else $reserve_sql2 = " wp.reserve <= '$reserve2'  and ";

			if(empty($prdcom)) $prdcom_sql = "";
			else $prdcom_sql = " wp.prdcom like '%$prdcom%' and ";

			if(empty($orderby)) $order_by = "order by wp.prior desc";
			else $order_by = "order by $orderby";

			$sql = "select distinct wp.prdcode from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
							inner join wiz_category wy on wc.catcode = wy.catcode
							left join wiz_mall as wm on wp.mallid = wm.id
							where $catcode_sql $production_sql $prdname_sql $sellprice_sql $sellprice_sql2 $reserve_sql $reserve_sql2 $prdcom_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' and (wm.status = 'Y' or ifnull(wm.status, 1) = 1) $order_by";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$total = mysqli_num_rows($result);

			$no = 0;
			$prdimg_width = 270;		// 상품 이미지 너비
			$prdimg_height = 270;		// 상품 이미지 높이
			$prdname_len	= 50;		// 상품명 길이

			$line = 4;							// 라인당 상품수
			$rows = 20;							// 상품수
			$lists = 5;							// 페이징 갯수
			$page_count = ceil($total/$rows);
			if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
			$start = ($page-1)*$rows;
			?>
			<div class="prd_search clearfix">
				<span class="count">총 <span class="11red_01"><strong><?=$total?></strong></span>개의 상품이 검색되었습니다.</span>

				<select name="orderby" onChange="this.form.submit();">
					<option value="">상품정렬방식</option>
					<option value="viewcnt desc" <? if($orderby == "viewcnt desc") echo "selected"; ?>>조회수 순</option>
					<option value="prdcode desc" <? if($orderby == "prdcode desc") echo "selected"; ?>>최근등록순 순</option>
					<option value="sellprice asc" <? if($orderby == "sellprice asc") echo "selected"; ?>>최저가격 순</option>
					<option value="sellprice desc" <? if($orderby == "sellprice desc") echo "selected"; ?>>최고가격 순</option>
				</select>
			</div><!-- //prd_search -->
		</form>

	    <ul class="prd_list clearfix">
			<?
			$sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.shortage, wp.prdicon, wp.stock, wp.conprice,
					wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
					from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
						inner join wiz_category wy on wc.catcode = wy.catcode
						left join wiz_mall as wm on wp.mallid = wm.id
						left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
					where $catcode_sql $production_sql $prdname_sql $sellprice_sql $sellprice_sql2 $reserve_sql $reserve_sql2 $prdcom_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' and (wm.status = 'Y' or ifnull(wm.status, 1) = 1)
					$order_by limit $start, $rows";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while(($row = mysqli_fetch_object($result)) && $rows){
				$sp_img = "";
				if(isset($row->popular) && $row->popular == "Y") $sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
				if(isset($row->recom) && $row->recom == "Y") $sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
				if(isset($row->new) && $row->new == "Y") $sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
				if(isset($row->sale) && $row->sale == "Y") $sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
				if(isset($row->best) && $row->best == "Y") $sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
				if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

				$prdicon_list = explode("/",$row->prdicon);
				for($ii=0; $ii<count($prdicon_list)-1; $ii++){
				$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
				}

				// 쿠폰아이콘
				$coupon_img = "";
				if(
				$row->coupon_use == "Y" &&
				$row->coupon_sdate <= date('Y-m-d') &&
				$row->coupon_edate >= date('Y-m-d') &&
				($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
				){

					$coupon_img = "<font class=coupon>".$row->coupon_dis.$row->coupon_type."</font> <img src='/images/icon_coupon.gif' align='absmiddle'>";
				}

				// 정상가(판매가보다 높을경우 할인표시)
				$conprice = "";
				if($row->conprice > $row->sellprice){
				$conprice = "<strong>".number_format($row->conprice)."</strong>원";
				}

				$sellprice = "<strong>".number_format($row->sellprice)."</strong>원";

				if(!empty($row->strprice)) {
					$conprice = "";
					$sellprice = "<font class=price>".$row->strprice."</font>";;
				}

				if($no%$line==0){
					if($no == 0) echo "";
					else echo "";
				}

				// 상품 이미지
				if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
				else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

			?>
			<li>
				<button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button>
				<a href="prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&page=<?=$page?>">
					<div class="img_box">
						<img src="<?=$row->prdimg_R?>" width="<?=$prd_width?>" height="<?=$prd_height?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
					</div>
					<dl>
						<dt><?=cut_str($row->prdname,$prdname_len)?></dt>
						<dd class="icon"><?=$coupon_img?><?=$sp_img?></dd>
						<dd>
							<span class="price">
								<?=$sellprice?>
							</span>
							<span class="cost"><?=$conprice?></span>
						</dd>
					</dl>
				</a>
			</li>
	   	<?
	   		$no++;
	   		$rows--;
			}

			if($total <= 0){
				echo "<p class='no_prd'>검색된 상품이 없습니다.</p>";
			}
			?>
	</ul><!-- //prd_list -->


	<? //print_pagelist($page, $lists, $page_count, $param); ?>
	

	<div class="page-btn">
		<? print_pagelist2($page, $lists, $page_count, "&catcode=".$catcode."&grp=".$grp."&orderby=".$orderby."&brand=".$brand."&limit=".$limit."&search_keyword=".$search_keyword."&str_price=".$str_price."&end_price=".$end_price); ?>
		<!-- 페이저 최대 4개 출력, prev/next 클릭하면 1개씩 이동 -->
	</div><!-- // page-btn -->


</div><!-- //prd_list_wrap -->

<?

include "../inc/footer.inc"; 		// 하단디자인

?>