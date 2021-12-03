<?
$total = 0;
for($ii=0;$ii<100;$ii++){
	if(isset($_SESSION['view_list'][$ii]['prdcode']) && $_SESSION['view_list'][$ii]['prdcode']) $total++;
}
$v_idx = $total-1;
$scroll_amount = 67;				// 한번에 스크롤되는 값
if($total > 0) $div_height = 193;		// 오늘본상품 역역 높이
if(!isset($site_align)) $site_align="";
?>
<div class="quick_wrap<?=(isset($_COOKIE['show_quick']) && $_COOKIE['show_quick']=='hidden' ? ' close' : '')?>">
	<button type="button" class="quick_btn">퀵바접기 버튼</button>
	<div class="inner">
		<ul class="quick_lnk">
			<li><a href="/shop/prd_basket.php">장바구니 <span class="count"><?=number_format($basket_cnt)?></span></a></li>
			<li><a href="/member/my_order.php">주문배송</a></li>
			<li><a href="/bbs/list.php?code=qna">고객문의</a></li>
		</ul><!-- //quick_lnk -->
		<div class="today">
			<span>TODAY</span>
			<ul id="gdscroll" style="height:<?=$div_height?>px;overflow:hidden">
	    	<?
	    		while(0 <= $v_idx){
				// 상품 이미지
				if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$_SESSION["view_list"][$v_idx]['prdimg'])) $view_prdimg = "/images/noimg_R.gif";
				else $view_prdimg = "/data/prdimg/".$_SESSION["view_list"][$v_idx]['prdimg'];
			?>
				<li>
					<a href="/shop/prd_view.php?prdcode=<?=$_SESSION["view_list"][$v_idx]['prdcode']?>" onFocus="this.blur();">
						<img src="<?=$view_prdimg?>" width="61" height="61" />
					</a>
				</li>
			<?
				$v_idx--;
				}
			?>
	    	</ul>
	    	<div class="controls_btn clearfix">
	    		<button type="button" class="prev" onclick="gdscroll('-<?=$scroll_amount?>');">이전보기</button>
	    		<button type="button" class="next" onclick="gdscroll('<?=$scroll_amount?>')">다음보기</button>
	    	</div>
		</div>
	</div>
</div><!-- //quick_wrap -->
<script>
function gdscroll(gap){
	var gdscroll = document.getElementById('gdscroll');
	gdscroll.scrollTop += gap;
}

self.onError=null;
currentX = 0;
currentY = 0;
whichIt = null;
lastScrollX = 0; lastScrollY = 0; lastClientWhidth = 0;
NS = (document.layers) ? 1 : 0;
IE = (document.all) ? 1: 0;

IE = (NS == 0 && IE == 0) ? 1 : IE;

function heartBeat() {
	if(document.all.quick != null){
		if(IE) {
			//diffY = document.body.scrollTop;
			//diffY = document.documentElement.scrollTop;
			if(document.body.scrollTop > document.documentElement.scrollTop) diffY = document.body.scrollTop;
			else diffY = document.documentElement.scrollTop;
			diffX = 0;
		}
		if(NS) { diffY = self.pageYOffset; diffX = self.pageXOffset; }
		if(diffY != lastScrollY) {
			percent = .05 * (diffY - lastScrollY);
			if(percent > 0) percent = Math.ceil(percent);
			else percent = Math.floor(percent);
			if(IE) document.all.quick.style.pixelTop += percent;
			if(NS) document.quick.top += percent;
			lastScrollY = lastScrollY + percent;
		}
		if(diffX != lastScrollX) {
			percent = .05 * (diffX - lastScrollX);
			if(percent > 0) percent = Math.ceil(percent);
			else percent = Math.floor(percent);
			if(IE) document.all.quick.style.pixelLeft += percent;
			if(NS) document.quick.left += percent;
			lastScrollX = lastScrollX + percent;
		}

		<? if($site_align == "CENTER"){ ?>
/*
		// 브라우저 창이 늘어나면 위치 수정
		siteWidth = <?=$site_width * 2?>;
		clientWhidth = document.body.clientWidth;
		if(clientWhidth < siteWidth) clientWhidth = siteWidth;	// 브라우저 창이 사이트 가로크기보다 작으면 사이트 가로크기를 기준으로

		if(clientWhidth != lastClientWhidth) {
			document.getElementById("quick").style.left = eval((clientWhidth/2) + <?=$site_width?>) + "px";
		}
*/
		<? } ?>


	}
}
if(NS || IE) action = window.setInterval("heartBeat()",1);
</script>