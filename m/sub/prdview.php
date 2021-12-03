<?
include "../inc/header.php";

$param = "catcode=$catcode&grp=$grp&orderby=$orderby&brand=$brand&page=$page";

// 상품정보 가져오기 (이동하지 말것)
$sql = "select * from wiz_product wp, wiz_cprelation wc where wp.prdcode='$prdcode' and wc.prdcode = wp.prdcode";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
$prd_info = mysqli_fetch_object($result);
if($prdcode == "" || $total <= 0) error("존재하지 않는 상품입니다.");
if($catcode == "") $catcode = $prd_info->catcode;

// 관심상품 여부 확인
$sql = "select idx from wiz_wishlist where prdcode='$prdcode' and memid='".$wiz_session['id']."'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
$wish_idx = $row['idx'];

include "../../inc/cat_info.inc"; 		// 카테고리정보
include "../../inc/oper_info.inc";		// 운영정보

// 상품아이콘
if($prd_info->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
if($prd_info->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
if($prd_info->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
if($prd_info->best == "Y") 			$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
if($prd_info->sale == "Y")			$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";

if(!empty($prd_info->strprice)) $sellprice = $prd_info->strprice;
else $sellprice = number_format($prd_info->sellprice)."";

if($prd_info->shortage == "Y" || (!strcmp($prd_info->shortage, "S") && $prd_info->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

$prdicon_list = explode("/",$prd_info->prdicon);
for($ii=0; $ii<count($prdicon_list)-1; $ii++){
  $sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
}

// 상품조회수 증가
$sql = "update wiz_product set viewcnt = viewcnt + 1 where prdcode='$prdcode'";
mysqli_query($connect, $sql) or die(mysqli_error($connect));


// 다음이전 상품
$catcode01 = str_replace("00","",substr($catcode,0,2));
$catcode02 = str_replace("00","",substr($catcode,2,2));
$catcode03 = str_replace("00","",substr($catcode,4,2));
$tmp_catcode = $catcode01.$catcode02.$catcode03;
$sql = "select wp.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prior > '$prd_info->prior' order by wp.prior asc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if($row = mysqli_fetch_object($result)) $prev_prdcode = "prd_view.php?prdcode=$row->prdcode&catcode=$catcode&brand=$brand";
else $prev_prdcode = "#";

$sql = "select wc.prdcode from wiz_cprelation wc, wiz_product wp where wc.catcode like '$tmp_catcode%' and wc.prdcode = wp.prdcode and wp.showset != 'N' and wp.prior < '$prd_info->prior' order by wp.prior desc limit 1";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
if($row = mysqli_fetch_object($result)) $next_prdcode = "prd_view.php?prdcode=$row->prdcode&catcode=$catcode&brand=$brand";
else $next_prdcode = "#";


// 오늘본 상품목록에 추가
$view_exist = false;
$view_idx = 0;
for($ii=0;$ii<100;$ii++){
	if($_SESSION["view_list"][$ii]['prdcode']) $view_idx++;
}
for($ii = 0; $ii < $view_idx; $ii++){
	if($_SESSION["view_list"][$ii]['prdcode'] == $prdcode){ $view_exist = true; break; }
}
if(!$view_exist){
	$_SESSION["view_list"][$view_idx]['prdcode'] = $prdcode;
	$_SESSION["view_list"][$view_idx]['prdimg'] = $prd_info->prdimg_R;
}

// 상품 이미지
/*for($ii = 1; $ii <= 5; $ii++) {
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$prd_info->{"prdimg_M".$ii})) $prd_info->{"prdimg_M".$ii} = "/images/noimg_M.gif";
	else $prd_info->{"prdimg_M".$ii} = "/data/prdimg/".$prd_info->{"prdimg_M".$ii};
}*/

// 상품리뷰
$sql = "select count(*) as cnt from wiz_bbs where code = 'review' and prdcode = '$prdcode'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$review_total = number_format($row['cnt']);

$sub_tit="상품보기";
?>

<? include "../inc/sub_title.php" ?>

<script language="javascript">
<!--

// 상품이미지 팝업
function prdZoom(){

   var url = "/shop/prd_zoom.php?prdcode=<?=$prdcode?>";
   window.open(url,"prdZoom","width=800,height=540,scrollbars=yes");
}

//-->
</script>

<div class="prd_view_wrap">
	<div class="img_box swiper-container">
		<div class="swiper-wrapper">
			<? for($i=1; $i<=5; $i++){ if(is_file($_SERVER['DOCUMENT_ROOT'].'/data/prdimg/'.$prd_info->{'prdimg_L'.$i})){ ?>
			<div class="swiper-slide">
				<img src="/data/prdimg/<?=$prd_info->{'prdimg_L'.$i}?>" alt="상품 사진" />
			</div>
			<? }} ?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
   <script>
		var mySwiper = new Swiper ('.img_box', {
		  direction: 'horizontal',
		  loop: true,
		  speed: 600,
		  autoplay : {
			  delay : 3500,
		  },
		  pagination: {
			  el: '.img_box .swiper-pagination',
			},
		  });
	</script>
    <div class="prd_title">
    	<h3 class="ttl"><?=$prd_info->prdname?></h3>
    </div><!-- //prd_title -->
</div><!-- //prd_view_wrap -->
<? include "./prdview_info.php" // 상품 정보 ?>
<? include "./prdview_detail.php" // 상품 상세정보 ?>

<? include "../inc/footer.php" ?>