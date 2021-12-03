<?
$config_img = "_2";
$shop_img = "_2";
$page_img = "_2";
$product_img = "_2";
$order_img = "_2";
$member_img = "_2";
$marketing_img = "_2";
$bbs_img = "_2";
$sch_img = "_2";
$poll_img = "_2";
if(strpos($PHP_SELF,"/config/") !== false) $config_img = "";
else if(strpos($PHP_SELF,"/shop/") !== false) $shop_img = "";
else if(strpos($PHP_SELF,"/page/") !== false) $page_img = "";
else if(strpos($PHP_SELF,"/product/") !== false) $product_img = "";
else if(strpos($PHP_SELF,"/order/") !== false) $order_img = "";
else if(strpos($PHP_SELF,"/member/") !== false) $member_img = "";
else if(strpos($PHP_SELF,"/marketing/") !== false) $marketing_img = "";
else if(strpos($PHP_SELF,"/bbs/") !== false) $bbs_img = "";
else if(strpos($PHP_SELF,"/schedule/") !== false) $sch_img = "";
else if(strpos($PHP_SELF,"/poll/") !== false) $poll_img = "";

$tmp_path = explode("/", $PHP_SELF);
if(!strcmp($tmp_path[2], "config") && strcmp($wiz_admin['designer'], "Y")) {

	include "../../inc/shop_info.inc";
	if($shop_info->start_page == "") $start_page = "./main/main.php";
	else $start_page = $shop_info->start_page;

	Header("Location: $start_page");

}
?>
<?
if(strpos($_SERVER['PHP_SELF'],"/main/")!==false){
$pageNum=0; $pageTxt="메인";

} else if(strpos($_SERVER['PHP_SELF'],"/shop/")!==false){
$pageNum=2; $pageTxt="상점관리";

} else if(strpos($_SERVER['PHP_SELF'],"/product/")!==false){
$pageNum=4; $pageTxt="상품관리";

} else if(strpos($_SERVER['PHP_SELF'],"/order/")!==false){
$pageNum=5; $pageTxt="주문관리";

} else if(strpos($_SERVER['PHP_SELF'],"/marketing/")!==false){
$pageNum=7; $pageTxt="마케팅 분석";

} else if(strpos($_SERVER['PHP_SELF'],"/bbs/")!==false){
$pageNum=8; $pageTxt="게시판 관리";

} else if(strpos($_SERVER['PHP_SELF'],"/mall/")!==false){
$pageNum=12; $pageTxt="정산 관리";
}

${"navi".$pageNum}="active";
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>::::: 입점업체 관리자 :::::</title>
<link href="/mall/style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/js/lib.js"></script>
<script language="JavaScript" src="/js/calendar.js"></script>
<script language="JavaScript" src="/admin/js/jquery-1.10.2.js"></script>
<script>
$(document).ready(function(){
	if($.cookie("left_quick") == "close"){
		$('.AW-manage-wrap').addClass('left_close'); 
	} else {
		$('.AW-manage-wrap').removeClass('left_close'); 
	}
});
function leftBtn() {
$('.AW-manage-wrap').toggleClass('left_close');   
	if ($('.AW-manage-wrap').hasClass('left_close')) {
		$.cookie('left_quick', 'close', { expires: 1, path: '/', domain: 'wizmall.anywiz.co.kr', secure: false });
	} else {
		$.cookie('left_quick', 'open', { expires: 1, path: '/', domain: 'wizmall.anywiz.co.kr', secure: false });			
	}
}
</script>
</head>

<body>
<div class="AW-manage-head">
    <ul class="topmenu">
    	<li><a href="http://<?=$HTTP_HOST?>" target="_blank">내 쇼핑몰</a><i></i></li>
    	<li><a href="../main/main.php">관리자 메인</a><i></i></li>
    	<li><a href="../admin_logout.php">로그아웃</a><i></i></li>
    </ul><!-- .topmenu -->
</div><!-- .AW-manage-head -->





<div class="AW-manage-navi">
	<h1><a href="../main/main.php">ADMIN<span>ISTRATOR</span></a></h1>
	<ul>
        <li class="<?=$navi2?>"><a href="../shop/shop_info.php">상점관리</a></li>
        <li class="<?=$navi4?>"><a href="../product/prd_list.php">상품관리</a></li>
        <li class="<?=$navi5?>"><a href="../order/order_list.php">주문관리</a></li>
        <li class="<?=$navi7?>"><a href="../marketing/analy_prd.php">마케팅 분석</a></li>
        <li class="<?=$navi8?>"><a href="../bbs/list.php?code=qna">게시판 관리</a></li>
        <li class="<?=$navi12?>"><a href="../mall/account_list.php">정산관리</a></li>
    </ul>
</div><!-- .AW-manage-navi -->

<div class="AW-manage-wrap">
	<div class="nav_handle_left"><a onclick="leftBtn();"></a></div>
	<div class="lnb-wrap">
    	<? if(strpos($_SERVER['PHP_SELF'],"main/main.php")===false){ //main 제외하고 노출 ?>
        	<h2><div class="table"><div class="table-cell"><span><?=$pageTxt?></span></div></div></h2>
        <? } ?>
        <? include "./left_menu.php"; // 좌측메뉴?>		
    </div><!-- .lnb-wrap -->
    
	<div class="content-wrap">
    	<? if(strpos($_SERVER['PHP_SELF'],"main/main.php")===false){ //main 제외하고 노출 ?>
        <div class="path">HOME > <?=$pageTxt?></div>
        <? } ?>
		<div class="AW-manage-body">