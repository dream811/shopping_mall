<div class="left-quick">
	<div class="tit"><span>퀵</span> 링크</div>
	<a href="#" class="modify">메뉴관리</a><!-- .modify -->
	<div class="cont">
		<ul>
			<li><a href="#">주문관리</a></li>
			<li><a href="#">상품목록</a></li>
			<li><a href="#">상품평관리</a></li>
		</ul>
	</div><!-- .cont -->
</div><!-- .left-quick -->

<div class="left-quick-modify" style="display:none;">
	<div class="inner-box">
		<div class="tit">퀵링크 메뉴관리</div>
		<a href="#" class="close"></a>
		<div class="cont">
			<a href="#" class="AW-btn-s modify">추가</a>
			<a href="#" class="AW-btn-s delete">삭제</a>
			<ul>
				<li>
					<span>링크명<input type="text" value="주문관리" /></span>
					<span>URL<input type="text" value="/adm/manage/product/order_list.php" /></span>
					<span><label>사용여부<input type="checkbox" /></label></span>
				</li>
				<li>
					<span>링크명<input type="text" /></span>
					<span>URL<input type="text" /></span>
					<span><label>사용여부<input type="checkbox" /></label></span>
				</li>
				<li>
					<span>링크명<input type="text" /></span>
					<span>URL<input type="text" /></span>
					<span><label>사용여부<input type="checkbox" /></label></span>
				</li>
			</ul>
		</div><!-- .cont -->
		<div class="AW-btn-wrap">
            <button class="on" type="submit">저장</button>
            <a onclick="history.go(-1);">취소</a>
        </div><!-- .AW-btn-wrap -->
	</div><!-- .inner-box -->
</div><!-- .left-quick-modify -->