<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/cat_info.inc"; 			// 카테고리정보
include "../inc/brd_info.inc"; 			// 브랜드정보

// 이동하지 말것(추천상품 상품이미지 사이트때문)
$prd_width = $cat_info->prd_width;
$prd_height = $cat_info->prd_height;
$prd_num = $cat_info->prd_num;

if($prd_width == "") 	$prd_width = "270";
if($prd_height == "") $prd_height = "270";
if($prd_num == "" || $prd_num <= 0) $prd_num = 20;

include "../inc/header.inc"; 				// 상단디자인
?>

<div class="prd_list_wrap">
	<? include "./prd_category.inc";	// 카테고리 추가 작업 필요_190415 ?>
	<? // include "./prd_recom.inc";					// 추천상품 ?>

	<?php
	// 정렬순서
	if(!isset($orderby) || $orderby == "") $order_sql = "order by wp.prior desc, prdcode desc";
	else $order_sql = "order by $orderby";

	// 카테고리별 찾기
	if(!empty($catcode)){
		$catcode01 = substr($catcode,0,2);
		$catcode02 = substr($catcode,2,2);
		$catcode03 = substr($catcode,4,2);
		if($catcode01 == "00") $catcode01 = "";
		if($catcode02 == "00") $catcode02 = "";
		if($catcode03 == "00") $catcode03 = "";
		$tmpcode = $catcode01.$catcode02.$catcode03;
		$catcode_sql = " wc.catcode like '$tmpcode%' and ";
	}
	// 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
	if(isset($grp) && $grp != "") $grp_sql = " wp.$grp = 'Y' and ";

	// 브랜드별 찾기
	if(isset($brand) && $brand != "") $brand_sql = " wp.brand = '$brand' and ";
	if(!isset($grp_sql)) $grp_sql = "";
	if(!isset($brand_sql)) $brand_sql = "";
	if(!isset($catcode_sql)) $catcode_sql = "";

	$sql = "select distinct wp.prdcode from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode inner join wiz_category wy on wc.catcode = wy.catcode left join wiz_mall as wm on wp.mallid = wm.id where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' and (wm.status = 'Y' or ifnull(wm.status, 1) = 1) $order_sql";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);
	?>
	<div class="prd_search clearfix">
	<form name="search_frm" action="<?=$PHP_SELF?>" method="get">
	<input type="hidden" name="catcode" value="<?=$catcode?>">
	<input type="hidden" name="grp" value="<?=$grp?>">
	<input type="hidden" name="brand" value="<?=$brand?>">

		<span class="count">카테고리에 <span class="11red_01"><strong><?=$total?></strong></span>개의 상품이 등록되어 있습니다.</span>

		<select name="orderby" onChange="this.form.submit();">
			<option value="">상품정렬방식</option>
			<option value="viewcnt desc" <? if($orderby == "viewcnt desc") echo "selected"; ?>>조회수 순</option>
			<option value="prdcode desc" <? if($orderby == "prdcode desc") echo "selected"; ?>>최근등록순 순</option>
			<option value="sellprice asc" <? if($orderby == "sellprice asc") echo "selected"; ?>>최저가격 순</option>
			<option value="sellprice desc" <? if($orderby == "sellprice desc") echo "selected"; ?>>최고가격 순</option>
		</select>
	</form>
	</div><!-- //prd_search -->

	<ul class="prd_list clearfix">
		<?

		$no = 0;
		$prdname_len	= 50;					// 상품명 길이
		$line = 4;										// 라인당 상품수
		$rows = $prd_num;		// 상품수
		$lists = 5;										// 페이징 갯수
		$page_count = ceil($total/$rows);
		if(!isset($page) || !$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;

		$sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
									wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
				from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
					inner join wiz_category wy on wc.catcode = wy.catcode
					left join wiz_mall as wm on wp.mallid = wm.id
					left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
				where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' and (wm.status = 'Y' or ifnull(wm.status, 1) = 1)
				$order_sql limit $start, $rows";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while(($row = mysqli_fetch_object($result)) && $rows){

		// 상품아이콘
		$sp_img = "";
		if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
		if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
		if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
		if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
		if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
		if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

		$prdicon_list = explode("/",$row->prdicon);
		for($ii=0; $ii<count($prdicon_list)-1; $ii++){
			$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
		}

		// 쿠폰아이콘
		$coupon_img = "";
		if(
		$row->coupon_use == "Y" &&
		$row->coupon_edate >= date('Y-m-d') &&
		($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
		){

			$coupon_img = "<font class=coupon>".number_format($row->coupon_dis).$row->coupon_type."</font> <img src='/images/icon_coupon.gif'>&nbsp;";
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

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
		else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

		if(!isset($catcode)) $catcode = 0;
		if(!isset($brand)) $brand = 0;
		?>
		<li>
			<button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button>
			<a href="prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>">
				<div class="img_box">
					<img src="<?=$row->prdimg_R?>" width="<?=$prd_width?>" height="<?=$prd_height?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
				</div>
				<dl>
					<dt><?= cut_str($row->prdname,$prdname_len)?></dt>
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
			$rows--;
			$no++;
		}

		if($total <= 0) echo "<p class='no_prd'>해당 카테고리에 등록된 상품이 없습니다.</p>";
		?>
	</ul><!-- //prd_list -->

	<div class="page-btn">
		<?php 
		if(!isset($grp)) $grp = "";
		if(!isset($orderby)) $orderby = "";
		if(!isset($brand)) $brand = "";
		if(!isset($limit)) $limit = "";
		if(!isset($search_keyword)) $search_keyword = "";
		if(!isset($str_price)) $str_price = "";
		if(!isset($end_price)) $end_price = "";
		?>
		<? print_pagelist2($page, $lists, $page_count, "&catcode=".$catcode."&grp=".$grp."&orderby=".$orderby."&brand=".$brand."&limit=".$limit."&search_keyword=".$search_keyword."&str_price=".$str_price."&end_price=".$end_price); ?>
		<!-- 페이저 최대 4개 출력, prev/next 클릭하면 1개씩 이동 -->
	</div><!-- // page-btn -->

</div><!-- //prd_list_wrap -->

<?
include "../inc/footer.inc"; 		// 하단디자인
?>