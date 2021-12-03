<? include $_SERVER['DOCUMENT_ROOT'].'/m/inc/header.php'; ?>
	<?
	// 배너(메인롤링)
	$code	= 'm_main_roll';
	$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
	$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
	if($ban_info = mysqli_fetch_assoc($result)){
	?>
	<div class="M_visual swiper-container">
		<div class="swiper-wrapper">
			<?
			$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($ban_row = mysqli_fetch_assoc($result)){
			?>
			<div class="swiper-slide">
				<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>><img src="/data/banner/<?=$ban_row['de_img']?>" alt="위즈몰 메인 슬라이드" /></a><!--/m/images/main_vis1.jpg-->
			</div>
			<? } ?>
		</div>
		<div class="swiper-pagination"></div>
	</div><!-- //M_visual -->
	<script>
		var mySwiper = new Swiper ('.M_visual', {
		  direction: 'horizontal',
		  loop: true,
		  speed: 600,
		  autoplay : {
			  delay : 3500,
		  },
		  pagination: {
			  el: '.M_visual .swiper-pagination',
			},
		  });
	</script>
	<div class="gry_bar"></div>
	<? } ?>
	<div class="M_contents">
		<div class="prd_list_wrap">
			<div class="prd_ttl clearfix">
				<h2><strong>BEST</strong> item</h2>
				<a href="/m/sub/prdlist.php?grp=best">더 보기</a>
			</div>
			<ul class="prd_list">

                <!--베스트-->
                <? $type="MO_MAIN"; $type_value='best'; include $_SERVER['DOCUMENT_ROOT']."/inc/main_product.php"; ?>
				
				
			</ul>
		</div><!-- //prd_list_wrap -->
		<?
		// 배너(메인중단)
		$code	= 'm_main_mid';
		$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
		$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
		if($ban_info = mysqli_fetch_assoc($result)){
		?>
		<div class="full_ban">
			<?
			$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($ban_row = mysqli_fetch_assoc($result)){
			?>
			<div class="ban">
				<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>><img src="/data/banner/<?=$ban_row['de_img']?>" alt="메인 풀배너" /></a>
			</div>
			<? } ?>
		</div><!-- //full_ban -->
		<div class="gry_bar"></div>
		<? } ?>
		<div class="prd_list_wrap">
			<div class="prd_ttl clearfix">
				<h2><strong>NEW</strong> item</h2>
				<a href="/m/sub/prdlist.php?grp=new">더 보기</a>
			</div>
			<ul class="prd_list">

                <!--신상품-->
                <? $type="MO_MAIN"; $type_value='new'; include $_SERVER['DOCUMENT_ROOT']."/inc/main_product.php"; ?>
				
			</ul>
		</div><!-- //prd_list_wrap -->
		<?
		// 배너(메인하단)
		$code	= 'm_main_bottom';
		$sql	= "select types_num from wiz_bannerinfo where name = '$code' and isuse != 'N'";
		$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
		if($ban_info = mysqli_fetch_assoc($result)){
		?>
		<div class="half_ban clearfix">
			<?
			$sql	= "select * from wiz_banner where name = '$code' and isuse != 'N' and de_type = 'IMG' order by prior, idx limit ".$ban_info['types_num'];
			$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
			while($ban_row = mysqli_fetch_assoc($result)){
			?>
			<div class="ban">
				<a href="<?=$ban_row['link_url']?>"<?=($ban_row['link_target']=='_BLANK' ? ' target="_blank"' : '')?>><img src="/data/banner/<?=$ban_row['de_img']?>" alt="메인 풀배너" /></a>
			</div>
			<? } ?>
		</div><!-- //half_ban -->
		<div class="gry_bar"></div>
		<? } ?>
		<div class="gry_bar"></div>
		<div class="M_center">
			<div class="container">
				<div class="ttl clearfix">
					<h2>고객문의</h2>
					<a href="/m/bbs/list.php?code=qna">더 보기</a>
				</div><!-- //ttl -->
				<ul>
					<?
					$code	= 'qna';
					$sql	= "select *, from_unixtime(wdate, '%Y.%m.%d') wtime from wiz_bbs where code = '$code' order by prino desc limit 2";
					$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
					while($bbs_row = mysqli_fetch_assoc($result)){
					?>
					<li>
						<a href="/m/bbs/view.php?idx=<?=$bbs_row['idx']?>&code=<?=$bbs_row['code']?>"><?=$bbs_row['subject']?></a>
						<span class="date"><?=$bbs_row['wtime']?></span>
					</li>
					<? } ?>
				</ul>
			</div>
		</div><!-- //M_center -->
		<div class="M_notice clearfix">
			<h3>NOTICE</h3>
			<div class="notice_slide swiper-container">
				<div class="swiper-wrapper">
					<?
					$code	= 'notice';
					$sql	= "select *, from_unixtime(wdate, '%Y.%m.%d') wtime from wiz_bbs where code = '$code' order by prino desc limit 2";
					$result	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
					while($bbs_row = mysqli_fetch_assoc($result)){
					?>
					<div class="swiper-slide">
						<a href="/m/bbs/view.php?idx=<?=$bbs_row['idx']?>&code=<?=$bbs_row['code']?>"><?=$bbs_row['subject']?></a>
						<span class="date"><?=$bbs_row['wtime']?></span>
					</div>
					<? } ?>
				</div>
			</div>
		</div><!-- //M_notice -->
		<script>
            var swiper = new Swiper('.notice_slide', {
                direction: 'vertical',
                loop: true,
                speed: 800,
                autoplay : {
                  delay : 4000,
                }
            });
        </script>
		<div class="M_counsel">
			<div class="container">
				<h2>고객상담센터 <strong>02-1212-8989</strong></h2>
				<span class="notice">오전 09:00 - 오후 06:00 / 토&#183;일&#183;공휴일 휴무</span>
				<span>주말 및 고휴일은 1:1문의 게시판을 이용해주세요.</span>
				<ul class="lnk_area clearfix">
					<li><a href="/m/center/qna.php">문의 게시판</a></li>
					<li><a href="/m/center/faq.php">자주묻는 질문</a></li>
				</ul>
			</div>
		</div><!-- //M_counsel -->
	</div><!-- //M_contents -->
<? include $_SERVER['DOCUMENT_ROOT'].'/m/inc/footer.php'; ?>