	<? if(strpos($_SERVER['PHP_SELF'],"/index.php")===false){ //index 아닐때만 노출 ?>
		</div>
	<? } ?>
	<div class="footer">
		<div class="container">
			<ul class="foot_lnk clearfix">
				<li><a href="/center/company.php">회사소개</a></li>
				<li><a href="/center/guide.php">이용안내</a></li>
				<li><a href="/center/privacy.php">개인정보처리방침</a></li>
				<li><a href="/member/mall_info.php">입점문의</a></li>
			</ul><!-- //foot_lnk -->
			<div class="foot_info clearfix">
				<ul>
					<li>
						<span>위즈몰</span>
						<span>대표자 : 홍길동</span>
						<span>주소 : 서울시 가나구 다라동 1234-56번지</span>
						<span>사업자등록번호 : 123-4567-89</span>
					</li>
					<li>
						<span>고객센터 : 02-1212-8989</span>
						<span>팩스 : 02-1212-8989</span>
						<span>이메일 : help@test.com</span>
					</li>
					<li class="copy">Copyright (C) by wizmall. All rights reserved.</li>
				</ul>
				<img src="../images/foot_logo.png" alt="푸터로고" />
			</div>
		</div>
	</div><!-- //footer -->
</div><!-- //wrap -->
</body>
</html>