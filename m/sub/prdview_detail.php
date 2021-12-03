<div class="prdview_tab" id="tab1">
	<ul class="prd_tabBtn clearfix">
		<li class="on"><a href="#tab1">상세정보</a></li>
		<li><a href="#tab2">구매가이드</a></li>
		<li><a href="#tab3">교환 및 반품</a></li>
		<li><a href="#tab4">상품후기</a></li>
	</ul><!-- //prd_tabBtn -->
	<div class="prd_tabCont">
		<div class="cont">
			<div class="prdview_img">
				<?=$prd_info->content?>
			</div>
			<div class="info_box">
				<h2>상품 정보 제공 고시</h2>
				<h3>전자상거래 등에서의 상품정보 제공 고시에 따라 작성 되었습니다.</h3>
				<ul class="inner">
					<li><span>&#42; 품명 및 모델명</span>상품상세설명 참조</li>
					<li><span>&#42; 제조국 또는 원산지</span>상품상세설명 참조</li>
					<li><span>&#42; 고객센터 </span><?=$shop_info->com_name?> <?=$shop_info->shop_tel?></li>
				</ul>
			</div>
		</div><!-- 상세정보 -->
	</div><!-- //prd_tabCont -->
</div><!-- //prdview_tab 상세정보 -->
<div class="prdview_tab" id="tab2">
	<ul class="prd_tabBtn clearfix">
		<li><a href="#tab1">상세정보</a></li>
		<li class="on"><a href="#tab2">구매가이드</a></li>
		<li><a href="#tab3">교환 및 반품</a></li>
		<li><a href="#tab4">상품후기</a></li>
	</ul><!-- //prd_tabBtn -->
	<div class="prd_tabCont">
		<div class="cont">
			<div class="txt-box">
				<h3>구매가이드</h3>
				<? $page_type = "prdview"; include "../inc/page_info.inc"; ?>
				<?=$page_info->content?>
				<!--     구매가이드         -->
				<?php
				if(!empty($prd_info->mallid)) {
					$sql = "select guide as content from wiz_mall where id = '$prd_info->mallid'";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$page_info = mysqli_fetch_object($result);
				} else {
					$page_type = "prdview"; include "../inc/page_info.inc";
				}
				?>

				<?=$page_info->content?>
			</div>
		</div><!-- 구매가이드 -->
	</div><!-- //prd_tabCont -->
</div><!-- //prdview_tab 구매가이드 -->
<div class="prdview_tab" id="tab3">
	<ul class="prd_tabBtn clearfix">
		<li><a href="#tab1">상세정보</a></li>
		<li><a href="#tab2">구매가이드</a></li>
		<li class="on"><a href="#tab3">교환 및 반품</a></li>
		<li><a href="#tab4">상품후기</a></li>
	</ul><!-- //prd_tabBtn -->
	<div class="prd_tabCont">
		<div class="cont">
			<div class="txt-box">
				<h3>교환 및 반품</h3>
				<p>- 본 제품의 특성상 단순변심, 개봉, 사용, 제품 또는 패기지를 훼손한 경우 교환 및 반품이 불가합니다.
				- 단, 배송중 파손되거나 초기불량제품 또는 제품이 오발송된 경우(수령 후 7일 이내) 즉시 택배비 및 모든부담을 무료로 처리해 드리며 제품수령 즉시 고객센터로 연락 부탁드립니다.
				- 주소불명 / 주문착오 / 연락두절 / 수치인부재 등 부주의에 의한 반품 등의 고객부주의로 반송되는 경우 구매자께서 왕복택배비 (6,000원)은 고객님 부담하셔야합니다. (도착지 영업소 기준 3일 이내)
				- 택배 수령일로부터 7일이내, 고객센터로 교환 및 반품 접수가 가능하며, 고객센터 접수 없이 임의로 제품을 보내실 경우 처리가 지연될 수 있으니, 꼭 고객센터 교환 및 반품접수를 해주시기 바랍니다.
				- OO택배 택배를 통해 반품가능하며, 타 택배사 이용시 추가운임은 고객님께서 지불하셔야 합니다.</p>
			</div>
		</div><!-- 배송/반품/교환안내 -->
	</div><!-- //prd_tabCont -->
</div><!-- //prdview_tab 배송/반품/교환 -->
<div class="prdview_tab" id="tab4">
	<ul class="prd_tabBtn clearfix">
		<li><a href="#tab1">상세정보</a></li>
		<li><a href="#tab2">구매가이드</a></li>
		<li><a href="#tab3">교환 및 반품</a></li>
		<li class="on"><a href="#tab4">상품후기</a></li>
	</ul><!-- //prd_tabBtn -->
	<div class="prd_tabCont">
		<div class="cont">
			<? include "./prdview_review.php" // 상품 후기 ?>
		</div><!-- 상품후기 -->
	</div><!-- //prd_tabCont -->
</div><!-- //prdview_tab 상품후기 -->