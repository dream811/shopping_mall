<?
include "../inc/header.php";

include "../../inc/cat_info.inc"; 			// 카테고리정보
include "../../inc/brd_info.inc"; 			// 브랜드정보
include "../../inc/oper_info.inc"; 			// 운영정보

$param = "catcode=$catcode&grp=$grp&orderby=$orderby&brand=$brand&prdname=$prdname";

// 모바일 버전 크기 재설정
$cat_info->prd_width = "130";
$cat_info->prd_height = "130";

// 이동하지 말것(추천상품 상품이미지 사이트때문)
if($cat_info->prd_width == "") 	$cat_info->prd_width = "75";
if($cat_info->prd_height == "") $cat_info->prd_height = "75";
if($cat_info->prd_num == "" || $cat_info->prd_num <= 0) $cat_info->prd_num = 10;

// 정렬순서
if($orderby == "") $order_sql = "order by wp.prior desc, prdcode desc";
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
if($grp != "") $grp_sql = " wp.$grp = 'Y' and ";

// 브랜드별 찾기
if($brand != "") $brand_sql = " wp.brand = '$brand' and ";

// 상품검색
$search_sql = "";
if($prdname != "") $search_sql .= " wp.prdname like '%".$prdname."%' and ";

$sql = "select distinct wp.prdcode from wiz_cprelation wc, wiz_product wp, wiz_category wy where $catcode_sql $grp_sql $brand_sql $search_sql wy.catuse != 'N' and wc.catcode = wy.catcode and wc.prdcode = wp.prdcode and wp.showset != 'N' $order_sql";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

$sub_tit = ($catname != "") ? $catname : "전체상품보기";
$sub_tit .= " <small>(".number_format($total).")</small>";
?>

<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<div class="select_style clearfix">
<form action="<?=$PHP_SELF?>" method="get">
<input type="hidden" name="catcode" value="<?=$catcode?>">
<input type="hidden" name="grp" value="<?=$grp?>">
<input type="hidden" name="brand" value="<?=$brand?>">
	<span class="prd_count">총 <span><?=number_format($total)?></span> 개 상품</span>
	<select name="orderby" onChange="this.form.submit();">
	<!--
	<option value="viewcnt desc" <? if($orderby == "viewcnt desc") echo "selected"; ?>>조회수 순</option>
	<option value="prdcode desc" <? if($orderby == "prdcode desc") echo "selected"; ?>>최근등록순 순</option>
	<option value="sellprice asc" <? if($orderby == "sellprice asc") echo "selected"; ?>>최저가격 순</option>
	<option value="sellprice desc" <? if($orderby == "sellprice desc") echo "selected"; ?>>최고가격 순</option>
	-->
  <option value="prdcode desc" <? if($orderby == "prdcode desc") echo "selected"; ?>>최신순</option>
  <option value="viewcnt desc" <? if($orderby == "viewcnt desc") echo "selected"; ?>>인기상품순</option>
</select>
</form>
</div>

<div class="container">
<div class="prd_list_wrap">
	<ul class="prd_list">
	<?
	$no = 0;
	$prdname_len	= 45;					// 상품명 길이
	$line = 5;										// 라인당 상품수
	$rows = $cat_info->prd_num;		// 상품수
	$lists = 5;										// 페이징 갯수
	$page_count = ceil($total/$rows);
	if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
	$start = ($page-1)*$rows;

	$sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
				wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
			from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
				inner join wiz_category wy on wc.catcode = wy.catcode
				left join wiz_mall as wm on wp.mallid = wm.id
				left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
			where $catcode_sql $grp_sql $brand_sql $search_sql wy.catuse != 'N'  and wp.showset != 'N'
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

	$sellprice = "".number_format($row->sellprice)."";
	$reserve = number_format($row->reserve)."";

	if(!empty($row->strprice)) {
		$conprice = "";
		$sellprice = "".$row->strprice."";;
		$reserve = "";
	}

	if($no%$line == 0 && $line > 0){
		echo "";
	}

	// 상품 이미지
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
	else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

	$purl = "prdview.php?prdcode=".$row->prdcode."&page=".$page."&".$param;

	$prdname = cut_str($row->prdname,$prdname_len);

	?>
		<li>
			<button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button>
			<a href="<?=$purl?>">
				<div class="img_box">
					<img src="<?=$row->prdimg_R?>" width="<?=$cat_info->prd_width?>" height="<?=$cat_info->prd_height?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
				</div>
				<dl>
					<dt><?=$prdname?></dt><!-- 글자 수 최대 45자까지 / 초과 시 말줄임 -->
					<dd>
						<span class="price">
							<strong><?=$sellprice?></strong>원
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

	if($total <= 0) echo "<li style='text-align:center; height:80px;'>등록된 상품이 없습니다.</li>";
	?>
	</ul>
</div><!-- //prd_list_wrap -->
</div>
<div class="page_no">
	<? print_pagelist($page, $lists, $page_count, "&".$param); ?>
</div>
<? include "../inc/footer.php" ?>
