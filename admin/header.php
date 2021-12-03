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
if (strpos($PHP_SELF, "/config/") !== false) $config_img = "";
else if (strpos($PHP_SELF, "/shop/") !== false) $shop_img = "";
else if (strpos($PHP_SELF, "/page/") !== false) $page_img = "";
else if (strpos($PHP_SELF, "/product/") !== false) $product_img = "";
else if (strpos($PHP_SELF, "/order/") !== false) $order_img = "";
else if (strpos($PHP_SELF, "/member/") !== false) $member_img = "";
else if (strpos($PHP_SELF, "/marketing/") !== false) $marketing_img = "";
else if (strpos($PHP_SELF, "/bbs/") !== false) $bbs_img = "";
else if (strpos($PHP_SELF, "/schedule/") !== false) $sch_img = "";
else if (strpos($PHP_SELF, "/poll/") !== false) $poll_img = "";
?>
<?
if (strpos($_SERVER['PHP_SELF'], "/main/") !== false) {
	$pageNum = 0;
	$pageTxt = "메인";
} else if (strpos($_SERVER['PHP_SELF'], "/config/") !== false) {
	$pageNum = 1;
	$pageTxt = "환경설정";
} else if (strpos($_SERVER['PHP_SELF'], "/shop_admin") !== false) {
	$pageNum = 11;
	$pageTxt = "관리자설정";
} else if (strpos($_SERVER['PHP_SELF'], "/shop/") !== false) {
	$pageNum = 2;
	$pageTxt = "상점관리";
} else if (strpos($_SERVER['PHP_SELF'], "/page/") !== false) {
	$pageNum = 3;
	$pageTxt = "페이지 설정";
} else if (strpos($_SERVER['PHP_SELF'], "/product/") !== false) {
	$pageNum = 4;
	$pageTxt = "상품관리";
} else if (strpos($_SERVER['PHP_SELF'], "/order/") !== false) {
	$pageNum = 5;
	$pageTxt = "주문관리";
} else if (strpos($_SERVER['PHP_SELF'], "/member/") !== false) {
	$pageNum = 6;
	$pageTxt = "회원관리";
} else if (strpos($_SERVER['PHP_SELF'], "/md/") !== false) {
	$pageNum = 12;
	$pageTxt = "MD회원관리";
} else if (strpos($_SERVER['PHP_SELF'], "/marketing/") !== false) {
	$pageNum = 7;
	$pageTxt = "마케팅 분석";
} else if (strpos($_SERVER['PHP_SELF'], "/bbs/") !== false) {
	$pageNum = 8;
	$pageTxt = "게시판 관리";
} else if (strpos($_SERVER['PHP_SELF'], "/account_list.php") !== false) {
	$pageNum = 10;
	$pageTxt = "정산관리";
} else if (strpos($_SERVER['PHP_SELF'], "/mall/") !== false) {
	$pageNum = 9;
	$pageTxt = "입점업체 관리";
}
${"navi" . $pageNum} = "active";
?>
<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?= $shop_info->admin_title ?></title>
	<link href="/admin/style.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/admin/js/jquery-ui.css">
	<script src="/js/lib.js"></script>
	<script src="/js/calendar.js"></script>
	<script src="/admin/js/jquery-1.10.2.js"></script>
	<script src="/admin/js/jquery-ui.js"></script>
	<script src="/admin/js/jquery.highchartTable.js"></script>
	<script src="/admin/js/highcharts.js"></script>
	<script src="/admin/js/jquery.bpopup.min.js"></script>
	<script src="/admin/js/jquery.cookie.js"></script>
	<script>
		$(function() {
			$('.datepicker').datepicker({
				dateFormat: 'yy-mm-dd',
				//yearSuffix: '년',
				dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
				monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				showOn: "both",
				buttonImage: "/admin/image/cal.gif",
				buttonImageOnly: true,
				changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
				changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
				yearRange: 'c-150:c+0', // 년도 선택 셀렉트 박스를 현재년도에서 이전, 이후로 얼마의 범위를 표시할 것인가/
				showMonthAfterYear: true // 년월 셀렉트 박스 위치 변경
				//altField: "#date",			// 타겟 필드
				//minDate: '-0d',				// 오늘 이전 날짜는 선택 못함
			});
		});
	</script>

	<style>
		.ui-datepicker {
			width: 242px;
			font-size: 90%;
		}

		.ui-datepicker-calendar>tbody td:first-child a {
			COLOR: #f00;
		}

		.ui-datepicker-calendar>tbody td:last-child a {
			COLOR: blue;
		}
	</style>
	<script>
		$(document).ready(function() {
			if ($.cookie("left_quick") == "close") {
				$('.AW-manage-wrap').addClass('left_close');
			} else {
				$('.AW-manage-wrap').removeClass('left_close');
			}
		});

		$(function() {
			$("#datepicker1").datepicker({
				dateFormat: 'yy-mm-dd',
				//yearSuffix: '년',
				dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
				monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
				changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
				showMonthAfterYear: true // 년월 셀렉트 박스 위치 변경
				//altField: "#date", // 타겟 필드
				//minDate: '-0d', // 오늘 이전 날짜는 선택 못함

			});
		});
		$(function() {
			$("#datepicker2").datepicker({
				dateFormat: 'yy-mm-dd',
				//yearSuffix: '년',
				dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
				monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
				changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
				changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
				showMonthAfterYear: true // 년월 셀렉트 박스 위치 변경
				//altField: "#date", // 타겟 필드
				//minDate: '-0d', // 오늘 이전 날짜는 선택 못함

			});
		});

		$(document).ready(function() {

			if ($.cookie("left_quick") == "close") {
				$('#Container_wrap').addClass('left_close');
			} else {
				$('#Container_wrap').removeClass('left_close');

			}


		});

		function leftBtn() {
			$('.AW-manage-wrap').toggleClass('left_close');
			if ($('.AW-manage-wrap').hasClass('left_close')) {
				$.cookie('left_quick', 'close', {
					expires: 1,
					path: '/',
					domain: 'wizmall.anywiz.co.kr',
					secure: false
				});
			} else {
				$.cookie('left_quick', 'open', {
					expires: 1,
					path: '/',
					domain: 'wizmall.anywiz.co.kr',
					secure: false
				});
			}
		}
	</script>
</head>

<body>
	<div class="Topmenu-wrap clearfix">
		<ul class="gnb clearfix">
			<li><a href="http://<?= $HTTP_HOST ?>" target="_blank">내 쇼핑몰</a></li>
			<li><a href="/admin/main/main.php">관리자홈</a></li>
			<li><a href="../admin_logout.php">로그아웃</a></li>
		</ul><!-- .gnb -->
	</div><!-- .Topmenu-wrap -->


	<div class="Head-wrap clearfix">
		<h1><a href="/admin/main/main.php">ADMIN<span>ISTRATOR</span></a></h1>
		<ul class="Navi clearfix">
			<? if (isset($wiz_admin['designer']) && $wiz_admin['designer'] == "Y") { ?>
				<li class="adm-setting <?= $navi1 ?>"><a href="../config/basic_config.php">환경설정</a></li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "01-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi2 ?>">
					<a href="../shop/shop_info.php"><span>상점관리</span></a>
					<ul class="category">
						<li><a href="../shop/shop_info.php">기본정보설정</a></li>
						<li class="depth">
							<a href="../shop/shop_oper.php">운영정보설정</a>
							<ul class="category_2depth">
								<li><a href="../shop/shop_oper.php#pay">결제정보</a></li>
								<li><a href="../shop/shop_oper.php#del">배송정보</a></li>
								<li><a href="../shop/shop_oper.php#res">적립금정보</a></li>
							</ul>
						</li>
						<li><a href="../shop/shop_mailsms.php">메일 / SMS설정</a></li>
						<li><a href="../shop/shop_mailtest.php">메일발송테스트</a></li>
						<li><a href="../shop/shop_smsfill.php">SMS관리</a></li>
						<li><a href="../shop/shop_admin.php">관리자설정</a></li>
						<li><a href="../shop/shop_trade.php">거래처관리</a></li>
						<li><a href="../shop/shop_coupon.php">쿠폰관리</a></li>
					</ul>
				</li>
				<li class="<?= $navi11 ?>"><a href="../shop/shop_admin.php"><span>관리자설정</span></a></li>
			<? } ?>



			<? if (strpos($wiz_admin['permi'], "03-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi3 ?>">
					<a href="../page/popup_list.php"><span>페이지 설정</span></a>
					<ul class="category">
						<li><a href="../page/popup_list.php">팝업관리</a></li>
						<li><a href="../page/banner_list.php">배너관리</a></li>
						<li><a href="../page/page_company.php">회사소개</a></li>
						<li><a href="../page/page_join.php">회원가입</a></li>
						<li><a href="../page/page_prdview.php">상품상세</a></li>
						<li><a href="../page/page_basket.php">장바구니</a></li>
						<li><a href="../page/page_center.php">고객센터</a></li>
						<li><a href="../page/page_guide.php">이용안내</a></li>
						<li><a href="../page/page_privacy.php">개인정보보호정책</a></li>
						<li><a href="../page/page_mallinfo.php">입점안내</a></li>
						<li><a href="../page/page_malljoin.php">입점신청</a></li>
					</ul>
				</li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "08-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi8 ?>"><a href="../bbs/bbs_pro_list.php"><span>게시판 관리</span></a></li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "07-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi7 ?>">
					<a href="../marketing/connect_list.php"><span>마케팅 분석</span></a>
					<ul class="category">
						<li><a href="../marketing/connect_list.php">접속자분석</a></li>
						<li><a href="../marketing/connect_refer.php">접속경로분석</a></li>
						<li><a href="../marketing/connect_osbrowser.php">OS/브라우저</a></li>
						<li><a href="../marketing/analy_paymethod.php">매출통계분석</a></li>
						<li><a href="../marketing/analy_prd.php">상품통계분석</a></li>
					</ul>
				</li>
			<? } ?>
			<? if (strpos($wiz_admin['permi'], "06-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi6 ?>">
					<a href="../member/member_list.php"><span>회원관리</span></a>
					<ul class="category">
						<li><a href="../member/member_list.php">회원목록</a></li>
						<li><a href="../member/member_level.php">등급관리</a></li>
						<li><a href="../member/member_qna.php">1:1 상담관리</a></li>
						<li><a href="../member/member_out.php">탈퇴회원</a></li>
						<li><a href="../member/member_email.php">메일발송</a></li>
						<li><a href="../member/member_sms.php">SMS 발송</a></li>
						<li><a href="../member/member_analy.php">회원통계</a></li>
					</ul>
				</li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "04-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi4 ?>">
					<a href="../product/prd_list.php"><span>상품관리</span></a>
					<ul class="category">
						<li><a href="../product/prd_list.php">상품목록</a></li>
						<li><a href="../product/prd_input.php">상품등록</a></li>
						<li><a href="../product/prd_category.php?mode=insert">상품분류관리</a></li>
						<li><a href="../product/prd_brand.php">브랜드관리</a></li>
						<li><a href="../product/prd_option.php">옵션항목 관리</a></li>
						<li><a href="../product/prd_shortage.php">재고관리</a></li>
						<li><a href="../product/prd_estimate.php">상품평관리</a></li>
					</ul>
				</li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "05-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi5 ?>">
					<a href="../order/order_list.php"><span>주문관리</span></a>
					<ul class="category">
						<li><a href="../order/order_list.php">전체주문목록</a></li>
						<li><a href="../order/cancel_list.php">주문취소목록</a></li>
						<li><a href="../order/tax_list.php?tax_type=T">세금계산서</a></li>
						<li><a href="../order/tax_list.php?tax_type=C">현금영수증</a></li>
					</ul>
				</li>
			<? } ?>

			<? if (strpos($wiz_admin['permi'], "11-00") !== false || !strcmp($wiz_admin['designer'], "Y")) { ?>
				<li class="<?= $navi9 ?>">
					<a href="../mall/mall_list.php"><span>입점업체 관리</span></a>
					<ul class="category">
						<li><a href="../mall/mall_list.php">입점업체목록</a></li>
						<li><a href="../mall/mall_out.php">탈퇴업체목록</a></li>
						<li class="depth">
							<a href="../mall/account_list.php">정산목록</a>
							<ul class="category_2depth">
								<li><a href="../mall/account_list.php?s_status=AW">정산대기</a></li>
								<li><a href="../mall/account_list.php?s_status=AA">정산요청</a></li>
								<li><a href="../mall/account_list.php?s_status=AI">정산중</a></li>
								<li><a href="../mall/account_list.php?s_status=AD">정산보류</a></li>
								<li><a href="../mall/account_list.php?s_status=AC">정산완료</a></li>
							</ul>
						</li>
					</ul>
				</li>
			<? } ?>

			<li class="<?= $navi10 ?>">
				<a href="../mall/account_list.php"><span>정산관리</span></a>
				<ul class="category">
					<li><a href="../mall/account_list.php?s_status=AW">정산대기</a></li>
					<li><a href="../mall/account_list.php?s_status=AA">정산요청</a></li>
					<li><a href="../mall/account_list.php?s_status=AI">정산중</a></li>
					<li><a href="../mall/account_list.php?s_status=AD">정산보류</a></li>
					<li><a href="../mall/account_list.php?s_status=AC">정산완료</a></li>
				</ul>
			</li>
		</ul><!-- .Navi -->
	</div><!-- .Head-wrap -->


	<div class="AW-manage-wrap">
		<div class="nav_handle_left"><a onclick="leftBtn();"></a></div>
		<div class="lnb-wrap">
			<? if (strpos($_SERVER['PHP_SELF'], "main/main.php") === false) { //main 제외하고 노출 
			?>
				<h2>
					<div class="table">
						<div class="table-cell"><span><?= $pageTxt ?></span></div>
					</div>
				</h2>
			<? } ?>
			<? include "./left_menu.php"; // 좌측메뉴
			?>
		</div><!-- .lnb-wrap -->

		<div class="content-wrap">
			<? if (strpos($_SERVER['PHP_SELF'], "main/main.php") === false) { //main 제외하고 노출 
			?>
				<div class="path">HOME > <?= $pageTxt ?></div>
			<? } ?>
			<div class="AW-manage-body">