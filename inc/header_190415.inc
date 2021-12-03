<?
/*
### 링크url ###
로그인						/member/login.php
로그아웃					/member/logout.php
회원가입					/member/join.php
마이페이지				/member/myinfo.php
장바구니					/shop/prd_basket.php
주문/배송조회			/shop/order_list.php
고객센터					/center/center.php
신상품						/shop/prd_list.php?grp=new
베스트상품				/shop/prd_list.php?grp=best
인기상품					/shop/prd_list.php?grp=popular
추천상품					/shop/prd_list.php?grp=recom
회사소개					/center/company.php
이용안내					/center/guide.php
개인정보취급방침	/center/privacy.php
사이트맵					/center/sitemap.php

상품목록					/shop/prd_list.php?catcode=000000

공지사항					/bbs/list.php?code=notice
고객문의					/bbs/list.php?code=qna
고객후기					/bbs/list.php?code=review
자주하는질문			/bbs/list.php?code=faq

### 각종변수 ###
회원명 						: $wiz_session[name]
회원아이디 				: $wiz_session['id']
현재위치 텍스트		: $now_position
현재위치 이미지		: $subimg
사이트명					: $design_info->site_title
사이트키워드			: $design_info->site_keyword
사이트소개				: $design_info->site_intro

### 로그인처리 ###
<? if($wiz_session['id'] == ""){ ?>
	로그인전
<? }else{ ?>
	로그인후
<? } ?>

### 상품검색 ###
<form action="/shop/prd_search.php">
<input name="prdname" type="text" class="prd_search" size="15">
<input type="image" src="/images/newimg/main/btn_search.gif" width="41" height="28" border="0">
</form>
*/

include "$DOCUMENT_ROOT/inc/design_info.inc"; 	// 디자인 정보
include "$DOCUMENT_ROOT/inc/shop_info.inc"; 	// 상점정보 정보
?>
<html>
<head>
<title><?=$design_info->site_title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="keywords" content="<?=$design_info->site_keyword?>">
<meta name="description" content="<?=$design_info->site_intro?>">
<? if($shop_info->naver_key!=""){ ?>
<meta name="naver-site-verification" content="<?=$shop_info->naver_key?>"/>
<? } ?>
<link href="/css/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript" src="/js/flash.js"></script>
<script language="JavaScript" type="text/JavaScript" src="/js/lib.js"></script>
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

//-->
</script>
</head>

<body style="background:url('/images/mainimg/top_bg.gif') top left repeat-x;">

<!-- 탑부분 -->
<table width="100%" height="153" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  	<td align="center" valign="top">
	  	<!--상단메뉴-->
			<table width="100%" height="26" cellpadding="0" cellspacing="0" border="0">
       			<tr>
					<td height="25" align="center" bgcolor="#FBFBFB">
						<table width="980" height="25" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="81"><a href="javascript:StartPage('http://<?=$_SERVER[HTTP_HOST]?>/')"><img src="/images/top_btn_set.gif" width="81" height="25"></a></td>
								<td width="81"><a href="javascript:bookMark('http://<?=$_SERVER[HTTP_HOST]?>/','<?=$design_info->site_title?>')"><img src="/images/top_btn_favorite.gif" width="81" height="25"></a></td>
								<td align="right">
									<table width="265" height="25" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<? if($wiz_session['id'] == ""){ ?>
											<td width="45"><a href="/member/login.php"><img src="/images/top_btn_login.gif" width="49" height="25"></a></td>
											<td width="53"><a href="/member/join.php"><img src="/images/top_btn_join.gif" width="55" height="25"></a></td>
											<? }else{ ?>
											<td width="45"><a href="/member/logout.php"><img src="/images/top_btn_logout.gif" width="49" height="25"></a></td>
											<td width="53"><a href="/member/my_info.php"><img src="/images/top_btn_mypage.gif" width="55" height="25"></a></td>
											<? } ?>
											<td width="74"><a href="/shop/order_list.php"><img src="/images/top_btn_order.gif" width="54" height="25"></a></td>
											<td width="54"><a href="/shop/prd_basket.php"><img src="/images/top_btn_cart.gif" width="55" height="25"></a></td>
											<td width="46"><a href="/center/center.php"><img src="/images/top_btn_customer.gif" width="52" height="25"></a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="1" bgcolor="#DFDFDF"></td>
				</tr>
			</table>
			<!--로고/검색/탑메뉴-->
			<table width="100%" height="115" cellpadding="0" cellspacing="0" border="0" background="/images/top_line_bg.gif" >
				<tr>
					<td align="center">
						<table width="980" height="115" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="302" height="71" align="left" style="vertical-align:bottom"><a href="#"><img src="/images/top_bn01.gif"></a></td>
								<td width="376" rowspan="2" background="/images/top_sch_bg.gif">
									<table cellpadding="0" cellspacing="0" border="0" width="376" height="115">
										<tr>
											<td height="55" align="center"><a href="/"><img src="/images/logo.gif"></a></td>
										</tr>
										<tr>
										  	<td height="60" align="center">
										  		<table cellpadding="0" cellspacing="0" border="0" width="308" height="22">
										  		<form action="/shop/prd_search.php">
													<tr>
														<td width="69"><img src="/images/top_txt_sch.gif"></td>
														<td width="185" align="left" bgcolor="#FFFFFF"><input name="prdname" class="inp01" style="width:182px"></td>
														<td width="5"></td>
														<td width="49"><input type="image" src="/images/top_btn_sch.gif" width="49"></td>
													</tr>
												</form>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td width="302" height="71" style="vertical-align:bottom" align="right"><a href="#"><img src="/images/top_bn02.gif"></a></td>
							</tr>
							<tr>
							  	<td height="44" align="left">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><a href="#"><img src="/images/top_menu01.gif"></a></td>
											<td width="2"><img src="/images/top_menu_line.gif"></td>
											<td><a href="/shop/prd_list.php?grp=best"><img src="/images/top_menu02.gif"></a></td>
											<td width="2"><img src="/images/top_menu_line.gif"></td>
											<td><a href="/shop/prd_list.php?grp=recom"><img src="/images/top_menu03.gif"></a></td>
										</tr>
									</table>
								</td>
						  	    <td height="44" align="right">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><a href="#"><img src="/images/top_menu04.gif"></a></td>
											<td width="2"><img src="/images/top_menu_line.gif"></td>
											<td><a href="#"><img src="/images/top_menu05.gif"></a></td>
											<td width="2"><img src="/images/top_menu_line.gif"></td>
											<td><a href="/center/center.php"><img src="/images/top_menu06.gif"></a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">

		<!-- 가운데 영역 -->
		<table width="980" border="0" cellpadding="0" cellspacing="0">
      <tr valign="top">
     		<? if(strpos($PHP_SELF,"/member/")!==false || strpos($PHP_SELF,"/center/")!==false || strpos($PHP_SELF,"/bbs/")!==false){ // index가 아닌경우 현재위치 나옴 ?>
        <td width="180" align="right">
        	<!-- 좌측 메뉴 및 배너 -->
        	<? include "$DOCUMENT_ROOT/inc/left.inc"; ?>
      	</td>
      	<td width="20"></td>
      	<? } ?>
        <td>

					<!-- 현재위치 -->
					<? if(strpos($PHP_SELF,"/index")===false && strpos($PHP_SELF,"/minishop")===false && strpos($PHP_SELF,"/minibbs")===false){ // index가 아닌경우 현재위치 나옴 ?>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
					    <td height="25" align="right" class="here"><?=$now_position?></td>
					  </tr>
					  <tr>
					    <td class="title"><?=$subimg?></td>
					  </tr>
					</table>
					<? } ?>