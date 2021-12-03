<?
include "./inc/common.inc"; 		// DB컨넥션, 접속자 파악
include "./inc/util.inc"; 			// 라이브러리 함수

// 모바일 체크
if(mobile_check() == true && $_GET['mod'] != "pc"){
	//2013-01-28 LOG 박가영 리다이렉션처리

	$Us = $mobile_path;
	$Qs = $_SERVER['QUERY_STRING'];
	$Ds = (strpos($Us,'?')) ? '&' : '?';
	$Qs = (strlen($Qs)>0) ? $Ds.$Qs : '';
	$Us = $Us.$Qs;
	header('Location: '.$Us);
	exit;
}

include "./inc/connect.inc";		// 로그분석
include "./inc/shop_info.inc"; 		// 운영정보
include "./inc/header.inc"; 		// 상단디자인
include "./inc/popup.inc";			// 팝업

?>
<script language="javascript">
<!--

function setBest(num) {
	for(ii = 1; ii <= 5; ii++) {
		document.getElementById("best_tab_"+ii).src = "/images/best_menu0"+ii+"_tab.gif";
		document.getElementById("best_prd_"+ii).style.display = "none";
	}
	document.getElementById("best_tab_"+num).src = "/images/best_menu0"+num+"_tab_ov.gif";
	document.getElementById("best_prd_"+num).style.display = "block";
}

function setBrand(num) {
	for(ii = 1; ii <= 4; ii++) {
		document.getElementById("brand_tab_"+ii).src = "/images/brand_menu0"+ii+"_tab.gif";
		document.getElementById("brand_prd_"+ii).style.display = "none";
	}
	document.getElementById("brand_tab_"+num).src = "/images/brand_menu0"+num+"_tab_ov.gif";
	document.getElementById("brand_prd_"+num).style.display = "block";
}

function setAuction(num) {
	for(ii = 1; ii <= 6; ii++) {
		document.getElementById("auction_tab_"+ii).src = "/images/auction_menu0"+ii+"_tab.gif";
		document.getElementById("auction_prd_"+ii).style.display = "none";
	}
	document.getElementById("auction_tab_"+num).src = "/images/auction_menu0"+num+"_tab_ov.gif";
	document.getElementById("auction_prd_"+num).style.display = "block";
}

function setLeftMenu(num) {
	for(ii = 1; ii <= 2; ii++) {
		document.getElementById("left_menu_tab_"+ii).src = "/images/left_menu_tab0"+ii+".gif";
		document.getElementById("left_menu_"+ii).style.display = "none";
	}
	document.getElementById("left_menu_tab_"+num).src = "/images/left_menu_tab0"+num+"_ov.gif";
	document.getElementById("left_menu_"+num).style.display = "block";
}
-->
</script>
	<?
	// 배너(메인롤링)
	$code	= 'main_roll';
	$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
	$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
	if($ban_info = mysqli_fetch_assoc($result)){
	?>
	<div class="M_visual cycle-slideshow"
	 data-cycle-fx="fade"
	 data-cycle-timeout="4000"
	 data-cycle-speed="650"d
	 data-cycle-slides=">.slide"
	 data-cycle-log="false">
		<?
		$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
		$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
		while($ban_row = mysqli_fetch_assoc($result)){
		?>
		<div class="slide" style="background-image:url(/data/banner/<?=$ban_row['de_img']?>);">
			<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>>메인제품 자세히보기</a>
		</div>
		<? } ?>
		<div class="cycle-pager"></div>
	</div><!-- //M_visual -->
	<? } ?>



	<div class="M_contents">
		<div class="container">
			<div class="prd_list_wrap">
				<div class="prd_ttl clearfix">
					<h2><strong>BEST</strong> item <span>가장 잘나가는 제품들을 만나보세요~</span></h2>
					<a href="/shop/prd_list.php?grp=best">더보기</a>
				</div><!-- //prd_ttl -->
				<ul class="prd_list clearfix">
                    <!--베스트-->
                    <? $type="PC_MAIN"; $type_value='best'; include $_SERVER['DOCUMENT_ROOT']."/inc/main_product.php"; ?>

				</ul><!-- //prd_list -->
			</div><!-- //prd_list_wrap -->
		</div>
		<div class="prd_sale_wrap prd_list_wrap">
			<div class="container clearfix">


                <? $type="PC_SALE"; $type_value='sale'; include $_SERVER['DOCUMENT_ROOT']."/inc/main_product.php"; ?>





			</div>


		</div><!-- //prd_sale_wrap -->
		<div class="container">
			<?
			// 배너(메인중단)
			$code	= 'main_mid';
			$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			if($ban_info = mysqli_fetch_assoc($result)){
			?>
			<div class="M_banner clearfix">
				<?
				$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
				$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($ban_row = mysqli_fetch_assoc($result)){
				?>
				<div class="half_ban">
					<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>><img src="/data/banner/<?=$ban_row['de_img']?>" alt="기획전 배너" /></a>
				</div>
				<? } ?>
			</div><!-- //M_banner -->
			<? } ?>
			<div class="prd_new_wrap prd_list_wrap">
				<div class="prd_ttl clearfix">
					<h2><strong>NEW</strong> item <span>따끈따끈한 신상 아이템!</span></h2>
					<a href="/shop/prd_list.php?grp=new">더보기</a>
				</div><!-- //prd_ttl -->
				<ul class="prd_list clearfix">

                    <!--신상품-->
					<? $type="PC_MAIN"; $type_value='new'; include $_SERVER['DOCUMENT_ROOT']."/inc/main_product.php"; ?>


				</ul><!-- //prd_list -->
			</div><!-- //prd_list_wrap -->
			<?
			// 배너(메인하단)
			$code	= 'main_bottom';
			$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			if($ban_info = mysqli_fetch_assoc($result)){
			?>
			<div class="M_banner clearfix">
				<?
				$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
				$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
				while($ban_row = mysqli_fetch_assoc($result)){
				?>
				<div class="half_ban">
					<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>><img src="/data/banner/<?=$ban_row['de_img']?>" alt="기획전 배너" /></a>
				</div>
				<? } ?>
			</div><!-- //M_banner -->
			<? } ?>
		</div>
		<div class="M_center">
			<div class="container clearfix">
				<div class="event_area">
					<div class="ttl clearfix">
						<h2>고객문의</h2>
						<a href="/bbs/list.php?code=qna">더보기</a>
					</div>
					<ul class="event_list">
						<?
						$code	= 'qna';
						$sql	= "select *, from_unixtime(wdate, '%Y.%m.%d') wtime from wiz_bbs where code = '$code' order by prino desc limit 4";
						$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
						while($bbs_row = mysqli_fetch_assoc($result)){
						?>
						<li>
							<a href="/bbs/view.php?idx=<?=$bbs_row['idx']?>&code=<?=$bbs_row['code']?>"><?=$bbs_row['subject']?></a>
							<span class="date"><?=$bbs_row['wtime']?></span>
						</li>
						<? } ?>
					</ul><!-- //event_list -->
				</div><!-- //event_area -->
				<div class="center_area clearfix">
					<div class="faq_box">
						<h2>FAQ</h2>
						<dl>
							<dt>우리 쇼핑몰<br />자주묻는 질문</dt>
							<dd>자주묻는 질문을 통해 궁금증을<br />빠르게 해결해 드립니다.</dd>
							<dd class="lnk"><a href="/bbs/list.php?code=faq">자주묻는 질문 <i class="lnk_arr"></i></a></dd>
						</dl>
					</div><!-- //faq_box -->
					<div class="center_box">
						<h2>고객 상담센터</h2>
						<dl>
							<dt>02-1212-8989</dt>
							<dd>
								오전 09:00 - 오후 06:00  /  토·일·공휴일 휴무
								<span class="notice">주말 및 공휴일은 1:1문의 게시판을 이용해주세요</span>
							</dd>
							<dd class="lnk"><a href="/bbs/list.php?code=qna">문의게시판 바로가기 <i class="lnk_arr"></i></a></dd>
						</dl>
					</div><!-- //center_box -->
				</div><!-- //center_area -->
			</div>
		</div><!-- //M_center -->
		<div class="notice_slide">
			<div class="container clearfix">
				<h2>NOTICE</h2>
				<div class="slide_area cycle-slideshow"
				 data-cycle-fx="carousel"
				 data-cycle-timeout="4000"
				 data-cycle-slides=">.slide"
				 data-cycle-carousel-visible="1"
				 data-cycle-carousel-vertical="true"
				 data-cycle-pause-on-hover="true"
				 data-cycle-log="false"
				 >
					<div class="slide clearfix">
						<a href="/bbs/list.php?code=notice">사이트 이용약관 변경 안내</a>
						<span class="date">2018.09.12</span>
					</div>
					<div class="slide clearfix">
						<a href="#">사이트 이용약관 변경 안내</a>
						<span class="date">2018.09.12</span>
					</div>
					<div class="slide clearfix">
						<a href="#">사이트 이용약관 변경 안내</a>
						<span class="date">2018.09.12</span>
					</div>
				</div><!-- //slide_area -->
			</div>
		</div><!-- //notice_slide -->
	</div><!-- //M_contents -->
<? include "./inc/footer.inc" ?>