<? if(strpos($_SERVER['PHP_SELF'],"/member/")!==false){ // 회원메뉴 ?> 
	<ul class="AW_lnk_list clearfix">
		<li class="<? if(strpos($_SERVER['PHP_SELF'], "/member/my_info.php") !== false || strpos($_SERVER['PHP_SELF'],"/member/my_out.php")!==false){ echo 'on';} ?>"><a href="/member/my_info.php">마이페이지</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="my_order.php"?"on":"")?>"><a href="/member/my_order.php">주문 &#183; 배송조회</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="my_wish.php"?"on":"")?>"><a href="/member/my_wish.php">관심상품</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="my_reserve.php"?"on":"")?>"><a href="/member/my_reserve.php">내 적립금</a></li>
	</ul>
	
<? } else if(strpos($_SERVER['PHP_SELF'],"/bbs/")!==false){ // 게시판 ?> 
	<ul class="AW_lnk_list clearfix">
		<li class="<?=strstr($_SERVER['REQUEST_URI'], "notice") ? "on" : "" ?>"><a href="/bbs/list.php?code=notice">공지사항</a></li>
		<li class="<?=strstr($_SERVER['REQUEST_URI'], "faq") ? "on" : "" ?>"><a href="/bbs/list.php?code=faq">자주하는 질문</a></li>
		<li class="<?=strstr($_SERVER['REQUEST_URI'], "qna") ? "on" : "" ?>"><a href="/bbs/list.php?code=qna">고객문의</a></li>
		<li class="<?=strstr($_SERVER['REQUEST_URI'], "review") ? "on" : "" ?>"><a href="/bbs/list.php?code=review">고객후기</a></li>
	</ul>
	
<? } else if(strpos($_SERVER['PHP_SELF'],"/center/")!==false){ // 고객센터 ?> 
	<ul class="AW_lnk_list clearfix">
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="company.php"?"on":"")?>"><a href="/center/company.php">회사소개</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="guide.php"?"on":"")?>"><a href="/center/guide.php">이용안내</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="privacy.php"?"on":"")?>"><a href="/center/privacy.php">개인정보처리방침</a></li>
		<li class="<?=(basename($_SERVER['PHP_SELF'])=="mall_info.php"?"on":"")?>"><a href="/member/mall_info.php">입점문의</a></li>
	</ul>
	
<? } ?>