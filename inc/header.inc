<?

include "$DOCUMENT_ROOT/inc/design_info.inc"; 	// 디자인 정보
include "$DOCUMENT_ROOT/inc/shop_info.inc"; 	// 상점정보 정보

?>
<!DOCTYPE HTML>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$design_info->site_title?></title>
<meta name="keywords" content="<?=$design_info->site_keyword?>">
<meta name="description" content="<?=$design_info->site_intro?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<? if(isset($shop_info->naver_key) && $shop_info->naver_key!=""){ ?>
<meta name="naver-site-verification" content="<?=$shop_info->naver_key?>"/>
<? } ?>
<meta property="og:type" content="website">
<meta property="og:title" content="WIZ MALL">
<meta property="og:description" content="WIZ MALL">
<meta property="og:url" content="http://wizmall.anywiz.co.kr/">
<link rel="icon" href="/favicon.ico?1" type="image/x-icon">
<link rel="shortcut icon" href="#" type="image/x-icon">
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="/css/reset.css">
<link rel="stylesheet" type="text/css" href="/css/common.css">
<link rel="stylesheet" type="text/css" href="/css/style.css">
<!-- JS -->
<!--[if lt IE 9]>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/js/selectivizr-min.js"></script>
<![endif]-->
<!--[if gte IE 9]>
<script type="text/javascript" src="/js/jquery-2.2.4.min.js"></script>
<![endif]-->
<!--[if !IE]> -->
<script type="text/javascript" src="/js/jquery-2.2.4.min.js"></script>
<!-- <![endif]-->
<script type="text/javascript" src="/js/jquery.cycle2.js"></script>
<script type="text/javascript" src="/js/jquery.cycle2.carousel.js"></script>
<script type="text/javascript" src="/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/lib.js"></script>
<script type="text/javascript" src="/js/main.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
	if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
	else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}

MM_reloadPage(true);

function displayLay(getno){
	if(document.all.displayer != null){
		for(i=0; i<document.all.displayer.length; i++){
			document.all.displayer[i].style.display='none';
		}
		if(document.all.displayer.length==null) document.all.displayer.style.display='block';
		else document.all.displayer[getno].style.display='block';
	}
}

function disableLay(getno){
	if(document.all.displayer != null){
		if(document.all.displayer.length==null) document.all.displayer.style.display='none';
		else document.all.displayer[getno].style.display='none';
	}
}

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
//-->
</script>
</head>
<body>
<div class="wrap">
	<div class="header">
		<div class="container">
			<div class="head_top clearfix">
				<ul class="top_menu clearfix">
					<? if(!isset($wiz_session['id']) || $wiz_session['id'] == ""){ ?>
					<li><a href="/member/login.php">로그인</a></li>
					<li><a href="/member/join.php">회원가입</a></li>
					<? }else{ ?>
					<li><a href="/member/logout.php">로그아웃</a></li>
					<li><a href="/member/my_info.php">마이페이지</a></li>
					<? } ?>
					<li><a href="/member/my_order.php">주문배송조회</a></li>
				</ul>
			</div><!-- //head_top -->
			<div class="logo_area clearfix">
				<h1 class="main_logo"><a href="/">위즈몰 바로가기</a></h1>

				<div class="sch_bar">
				<form action="/shop/prd_search.php">
					<input type="text" name="prdname" value="" placeholder="검색어를 입력하세요" />
					<button type="submit">검색버튼</button>
				</form>
				</div><!-- //sch_bar -->

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
				<ul class="shop_menu clearfix">
					<li><a href="/shop/prd_basket.php">장바구니 <span class="count"><?=number_format($basket_cnt)?></span></a></li>
					<li><a href="/member/my_wish.php">관심상품 <span class="count"><?=number_format($wish_cnt)?></span></a></li>
					<li><a href="/member/my_info.php">마이쇼핑</a></li>
				</ul>
			</div><!-- //logo_area -->
		</div>
		<div class="gnb_area">
			<div class="container clearfix">
				<div class="all_cate <?=(basename($_SERVER['PHP_SELF'])!="index.php"?"sub":"")?>">
					<button type="button" class="all_btn">전체 카테고리</button>
					<? include $_SERVER['DOCUMENT_ROOT'].'/inc/category.inc'; ?>
				</div><!-- //all_cate -->
				<ul class="gnb clearfix">
					<li><a href="/shop/prd_list.php?grp=new">신상품</a></li>
					<li><a href="/shop/prd_list.php?grp=best">베스트100</a></li>
					<li><a href="/shop/prd_list.php?grp=sale">세일상품</a></li>
					<li><a href="/shop/prd_list.php?grp=recom">MD추천상품</a></li>
				</ul><!-- //gnb -->
				<ul class="brand clearfix">
					<li><a href="/shop/prd_list.php?brand=0">브랜드샵</a></li>
					<li><a href="/shop/prd_list.php?event=0">이벤트존</a></li>
					<li><a href="/bbs/list.php?code=notice">고객센터</a></li>
				</ul><!-- //brand -->
			</div>
		</div><!-- //gnb_area -->
	</div><!-- //header -->
	<? include $_SERVER['DOCUMENT_ROOT'].'/inc/quick.inc'; ?>
	<? if(strpos($_SERVER['PHP_SELF'],"/index.php")===false){ //index 아닐때만 노출 ?>
		<? if(basename($_SERVER['PHP_SELF']) == "prd_list.php" || basename($_SERVER['PHP_SELF']) == "prd_view.php" ) { ?>
			<? include $_SERVER["DOCUMENT_ROOT"]."/shop/cate_bar.php"; // 상품 리스트 카테고리 바 ?>

			<? if( basename($_SERVER['PHP_SELF']) == "prd_list.php" ) { ?>
				<h2 class="cat_ttl"><?=$catname?></h2>
			<? } ?>
		<? } ?>
		<div class="container">
	<? } ?>