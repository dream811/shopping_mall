<?
include_once $_SERVER['DOCUMENT_ROOT']."/inc/common.inc"; 			// DB컨넥션, 접속자 파악
include_once $_SERVER['DOCUMENT_ROOT']."/inc/util.inc"; 	      // 라이브러리 함수
include_once $_SERVER['DOCUMENT_ROOT']."/inc/oper_info.inc"; 		// 운영 정보
include_once $_SERVER['DOCUMENT_ROOT']."/inc/shop_info.inc"; 		// 상점 정보
?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>WIZMALL Mobile</title>
<meta name="keywords" content="WIZMALL Mobile">
<meta name="description" content="WIZMALL Mobile">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<? if($shop_info->naver_key!=""){ ?>
<meta name="naver-site-verification" content="<?=$shop_info->naver_key?>"/>
<? } ?>
<meta property="og:type" content="website">
<meta property="og:title" content="WIZMALL Mobile">
<meta property="og:description" content="WIZMALL Mobile">
<meta property="og:url" content="http://wizmall.anywiz.co.kr/m/">
<link rel="icon" href="/favicon.ico?1" type="image/x-icon">
<link rel="shortcut icon" href="#" type="image/x-icon">
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="/m/css/reset.css?ver=1">
<link rel="stylesheet" type="text/css" href="/m/css/swiper.min.css">
<link rel="stylesheet" type="text/css" href="/m/css/common.css?ver=1">
<link rel="stylesheet" type="text/css" href="/m/css/style.css?ver=1">
<link rel="stylesheet" type="text/css" href="/m/css/respon.css?ver=1">
<!-- jQuery -->
<script type="text/javascript" src="/m/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/m/js/swiper.min.js"></script>
<script type="text/javascript" src="/js/lib.js"></script>
<script type="text/javascript" src="/m/js/main.js"></script>
<script>
function addWish(prdcode){
	<? if(!$wiz_session['id']){ ?>
	alert('로그인 후 이용 가능합니다.');
	<? }else{ ?>
	$.ajax({
		url		: '/member/my_save.php',
		data	: {
			'mode'		: 'my_wish_ajax',
			'prdcode'	: prdcode
		},
		type	: 'POST',
		dataType: 'text',
		success	: function(response){
			if(response=='delete'){
				alert("관심상품에서 삭제하였습니다.");
				$(".wish_btn"+prdcode).removeClass('on');
			}
			else{
				alert("관심상품 추가는 상세페이지에서 가능합니다.");
				//alert("관심상품에 추가하였습니다.");
				//$(".wish_btn"+prdcode).addClass('on');
			}
		}
	});
	<? } ?>
}
</script>
</head>
<body>
<div class="wrap">
	<div class="header">
		<div class="logo_area">
			<button type="button" class="snb_open">메뉴 열기</button>
			<h1 class="main_logo"><a href="/m/">위즈몰 모바일 바로가기</a></h1>
			<ul class="top_menu clearfix">
				<li><button type="button" class="sch_open">검색버튼</button></li>
				<li><a href="/m/sub/cart.php">장바구니</a></li>
			</ul>
		</div><!-- //logo_area -->
		<ul class="gnb_bar">
			<li><a href="/m/sub/prdlist.php?grp=new">신상품</a></li>
			<li><a href="/m/sub/prdlist.php?grp=best">베스트100</a></li>
			<li><a href="/m/sub/prdlist.php?grp=sale">세일상품</a></li>
			<li><a href="/m/sub/prdlist.php?grp=recom">MD추천상품</a></li>
		</ul><!-- //gnb_bar -->
		<div class="shadow_bg"></div>
		<div class="side_wrap">
			<button type="button" class="snb_close">메뉴 닫기</button>
			<div class="side_head">
				<h3><strong>우리 쇼핑몰</strong>에<br />오신 것을 환영합니다.</h3>
				<ul class="side_lnk">
					<? if($wiz_session['id'] == ""){ ?>
					<li><a href="/m/sub/login.php">로그인</a></li>
					<li><a href="/m/sub/join_agree.php">회원가입</a></li>
					<? }else{ ?>
					<li><a href="/member/logout.php">로그아웃</a></li>
					<li><a href="/m/sub/myinfo.php">마이페이지</a></li>
					<? } ?>
				</ul><!-- //side_lnk -->
			</div><!-- //side_head -->

			<?
			// 장바구니 상품수
			$sql	= "select count(idx) as cnt from wiz_basket_tmp wb
							inner join wiz_product wp on wb.prdcode = wp.prdcode
						where uniq_id='".$_COOKIE['uniq_id']."'";
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row	= mysqli_fetch_assoc($result);
			$basket_cnt = $row['cnt'];

			// 관심상품 상품수
			$sql	= "select count(idx) as cnt from wiz_wishlist ww
							inner join wiz_product wp on ww.prdcode = wp.prdcode
						where memid = '".$wiz_session['id']."'";
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$row	= mysqli_fetch_assoc($result);
			$wish_cnt = $row['cnt'];
			?>
			<div class="side_menu">
				<a href="/m/sub/orderlist.php">주문배송</a>
				<a href="/m/sub/cart.php">장바구니<span class="count"><?=number_format($basket_cnt)?></span></a>
				<a href="/m/sub/wishlist.php">관심상품<span class="count"><?=number_format($wish_cnt)?></span></a>
				<a href="/m/sub/myinfo.php">마이쇼핑</a>
			</div><!-- //side_menu -->
			<ul class="snb_area">
				<?
				$sql	= "select catcode, catname, catimg, catimg_over from wiz_category where depthno=1 and catuse != 'N' order by priorno01 asc";
				$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($row = mysqli_fetch_object($result)){

					if($row->catimg == ""){
						$cat_url ="<a href='/shop/prd_list.php?catcode=".$row->catcode."'>".$row->catname."</a>";
					}else{
						if($row->catimg_over == "") $row->catimg_over = $row->catimg;
						$cat_url = "<a href='/shop/prd_list.php?catcode=".$row->catcode."' onMouseOver=\"displayLay('".($no-1)."');MM_swapImage('c_".$no."','','/data/catimg/".$row->catimg_over."',1)\" onMouseOut=\"MM_swapImgRestore();disableLay('".($no-1)."')\"><a href='/shop/prd_list.php?catcode=".$row->catcode."'><img src='/data/catimg/".$row->catimg."' name='c_".$no."' border=0 id='c_".$no."'></a>";
					}

					unset($arrCat);
					$c_sql	= "select catcode, catname, catimg, catimg_over from wiz_category where catcode like '".substr($row->catcode, 0, 2)."%' and depthno=2 and catuse != 'N' order by priorno02 asc";
					$c_result	= mysqli_query($connect, $c_sql) or error(mysqli_error($connect));
					while($c_row = mysqli_fetch_object($c_result)) $arrCat[] = $c_row;
				?>
				<li>
					<span><?=$row->catname?></span>
					<ul class="depth_menu">
						<li class="all"><a href="/m/sub/prdlist.php?catcode=<?=$row->catcode?>">전체보기</a></li>
						<?
						for($i=0; $i<sizeof($arrCat); $i++){
							$c_row = $arrCat[$i];
							if($c_row->catimg == ""){
								$cat_url ="<a href='/shop/prd_list.php?catcode=".$c_row->catcode."'>".$c_row->catname."</a>";
							}else{
								if($c_row->catimg_over == "") $c_row->catimg_over = $c_row->catimg;
								$cat_url = "<a href='/shop/prd_list.php?catcode=".$c_row->catcode."' onMouseOver=\"displayLay('".($no-1)."');MM_swapImage('c_".$no."','','/data/catimg/".$c_row->catimg_over."',1)\" onMouseOut=\"MM_swapImgRestore();disableLay('".($no-1)."')\"><a href='/shop/prd_list.php?catcode=".$c_row->catcode."'><img src='/data/catimg/".$c_row->catimg."' name='c_".$no."' border=0 id='c_".$no."'></a>";
							}
						?>
						<li><a href="/m/sub/prdlist.php?catcode=<?=$c_row->catcode?>"><?=$c_row->catname?></a></li>
						<? } ?>
					</ul>
				</li>
				<? } ?>
			</ul><!-- //snb_area -->
		</div><!-- //side_wrap -->
		<div class="sch_wrap">
			<div class="sch_head clearfix">
				<div class="sch_box">
				<form name="searchFrm" action="/m/sub/prdlist.php" method="get">
					<input type="text" name="prdname" value="" placeholder="어떤 상품을 찾으세요?">
					<button type="submit">검색</button>
				</form>
				</div>
				<button type="button" class="sch_close">검색닫기</button>
			</div><!-- //sch_head -->
			<div class="popular_cont">
				<h3>인기 검색어</h3>
				<ul>
					<?
					$keyword = array('3단 자동 우산'
						,'추석 선물세트'
						,'블루투스 스피커'
						,'선풍기'
						,'달력'
						,'USB'
						,'보조배터리'
					);
					for($i=0; $i<sizeof($keyword); $i++){
					?>
					<li><a href="/m/sub/prdlist.php?prdname=<?=urlencode($keyword[$i])?>"><?=$keyword[$i]?></a></li>
					<? } ?>
				</ul>
				<button type="button" class="popular_cls">닫기</button>
			</div>
		</div><!--//sch_wrap -->
	</div><!-- //header -->