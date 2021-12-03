<?


//PC 베스트 , 신상품
if($type=='PC_MAIN'){

        if($type_value=='best'){
            $grp_sql = " wp.best = 'Y' and ";
        }else if($type_value=='new'){
            $grp_sql = " wp.new = 'Y' and ";
        }
    	if(!isset($catcode_sql)){
            $catcode_sql = "";
        }
        if(!isset($brand_sql)){
            $brand_sql = "";
        }
    $cc=1;
    $sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
                        wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
    from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
        inner join wiz_category wy on wc.catcode = wy.catcode
        left join wiz_mall as wm on wp.mallid = wm.id
        left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
    where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' 
    order by wp.prior desc, prdcode desc limit 8";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

    while($row = mysqli_fetch_object($result)){
        
        // 상품아이콘
		$sp_img = "";
		if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
		if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
		if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
		if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
		if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
		if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

		$prdicon_list = explode("/",$row->prdicon);
		for($ii=0; $ii<count($prdicon_list)-1; $ii++){
			$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
		}

		// 쿠폰아이콘
		$coupon_img = "";
		if(
		$row->coupon_use == "Y" &&
		$row->coupon_edate >= date('Y-m-d') &&
		($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
		){

			$coupon_img = "<font class=coupon>".number_format($row->coupon_dis).$row->coupon_type."</font> <img src='/images/icon_coupon.gif'>&nbsp;";
		}

		// 정상가(판매가보다 높을경우 할인표시)
		$conprice = "";
		if($row->conprice > $row->sellprice){
			$conprice = "<strong>".number_format($row->conprice)."</strong>원";
		}

		$sellprice = "<strong>".number_format($row->sellprice)."</strong>원";

		if(!empty($row->strprice)) {
			$conprice = "";
			$sellprice = "<font class=price>".$row->strprice."</font>";
		}

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
		else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;
        
        if(!isset($brand)) $brand="";
        if(!isset($catcode)) $catcode="";
        if(!isset($page)) $page="";
    ?>


    <li>
        <button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button><!-- 클릭하면 관심상품 등록 얼럿과 함께 on 클래스 추가 / 등록된 상태에서 다시 클릭하면 관심상품 해제 얼럿과 함께 on 클래스 삭제 -->
        <a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>">
            <span class="best"><?=$cc?></span>
            <div class="img_box">
                <img src="<?=$row->prdimg_R?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
            </div>
            <dl>
                <dt><?=cut_str($row->prdname,'45')?></dt><!-- 글자 수 최대 45자까지 / 초과 시 말줄임 -->
                <dd>
                    <span class="price">
                        <?=$sellprice?>
                    </span>
                    <span class="cost"><?=$conprice?></span>
                </dd>
            </dl>
        </a>
    </li>

    <?
    $cc++;
    }


//모바일 베스트 , 신상품
}else if($type=='MO_MAIN'){

  if($type_value=='best'){
            $grp_sql = " wp.best = 'Y' and ";
        }else if($type_value=='new'){
            $grp_sql = " wp.new = 'Y' and ";
        }
    	
    $cc=1;
    $sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
                        wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
    from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
        inner join wiz_category wy on wc.catcode = wy.catcode
        left join wiz_mall as wm on wp.mallid = wm.id
        left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
    where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' 
    order by wp.prior desc, prdcode desc limit 8";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    

     while($row = mysqli_fetch_object($result)){
        
        // 상품아이콘
		$sp_img = "";
		if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
		if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
		if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
		if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
		if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
		if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

		$prdicon_list = explode("/",$row->prdicon);
		for($ii=0; $ii<count($prdicon_list)-1; $ii++){
			$sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
		}

		// 쿠폰아이콘
		$coupon_img = "";
		if(
		$row->coupon_use == "Y" &&
		$row->coupon_edate >= date('Y-m-d') &&
		($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
		){

			$coupon_img = "<font class=coupon>".number_format($row->coupon_dis).$row->coupon_type."</font> <img src='/images/icon_coupon.gif'>&nbsp;";
		}

		// 정상가(판매가보다 높을경우 할인표시)
		$conprice = "";
		if($row->conprice > $row->sellprice){
			$conprice = "<strong>".number_format($row->conprice)."</strong>원";
		}

		$sellprice = "<strong>".number_format($row->sellprice)."</strong>원";

		if(!empty($row->strprice)) {
			$conprice = "";
			$sellprice = "<font class=price>".$row->strprice."</font>";
		}

		// 상품 이미지
		if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
		else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

?>

        <li>
            <button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button><!-- 관심상품에 등록되어있으면 on 클래스 추가 / 없으면 on 클래스 삭제 / 모바일에선 클릭이벤트 없이 관심상품 등록유무 상태만 확인 -->
            <a href="/m/sub/prdview.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>">
                <span class="best"><?=$cc?></span>
                <div class="img_box">
                    <img src="<?=$row->prdimg_R?>" alt="제품이미지" /><!-- 이미지 비율 1:1 -->
                </div>
                <dl>
                    <dt><?=cut_str($row->prdname,'45')?></dt><!-- 글자 수 최대 45자까지 / 초과 시 말줄임 -->
                    <dd>
                        <span class="price">
                            <?=$sellprice?>
                        </span>
                        <span class="cost"><?=$conprice?></span>
                    </dd>
                </dl>
            </a>
        </li>


<?
    $cc++;
    }

}else if($type=='PC_SALE'){

    $grp_sql = " wp.sale = 'Y' and ";
    $cc=1;
    $sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R,wp.prdimg_M1, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
                        wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
    from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
        inner join wiz_category wy on wc.catcode = wy.catcode
        left join wiz_mall as wm on wp.mallid = wm.id
        left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
    where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' 
    order by wp.prior desc, prdcode desc limit 1";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

    $row = mysqli_fetch_object($result);
    if(!isset($row)){
        $row = new stdClass();
        $row->popular = "";
        $row->recom = "";
        $row->new = "";
        $row->sale = "";
        $row->best = "";
        $row->shortage = "";
        $row->prdicon = "";
        $row->coupon_use = "";
        $row->conprice = "";
        $row->sellprice = 0;
        $row->prdname = "";
    } 
    
    // 상품아이콘
    $sp_img = "";
    if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
    if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
    if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
    if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
    if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
    if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

    $prdicon_list = explode("/",$row->prdicon);
    for($ii=0; $ii<count($prdicon_list)-1; $ii++){
        $sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
    }

    // 쿠폰아이콘
    $coupon_img = "";
    if(
    $row->coupon_use == "Y" &&
    $row->coupon_edate >= date('Y-m-d') &&
    ($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
    ){

        $coupon_img = "<font class=coupon>".number_format($row->coupon_dis).$row->coupon_type."</font> <img src='/images/icon_coupon.gif'>&nbsp;";
    }

    // 정상가(판매가보다 높을경우 할인표시)
    $conprice = "";
    if($row->conprice > $row->sellprice){
        $conprice = "<strong>".number_format($row->conprice)."</strong>원";
    }

    $sellprice = "<strong>".number_format($row->sellprice)."</strong>원";

    if(!empty($row->strprice)) {
        $conprice = "";
        $sellprice = "<font class=price>".$row->strprice."</font>";
    }

    // 상품 이미지
    if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
    else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;
?>


            <div class="sale_big">
                <a href="#">
                    <div class="img_box">
                        <img src="<?=$row->prdimg_R?>" alt="세일 상품" width="100%" height="100%"/>
                    </div>
                    <dl>
                        <dt><?=cut_str($row->prdname,'45')?></dt>
                        <dd>
                            <span class="price">
                                <?=$sellprice?>
                            </span>
                            <span class="cost"><?=$conprice?></span>
                        </dd>
                    </dl>
                </a>
            </div><!-- //sale_big -->



<?

    $cc=1;
    $sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.strprice, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock,
                        wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, ww.prdcode as wish
    from wiz_cprelation wc inner join wiz_product wp on wc.prdcode = wp.prdcode
        inner join wiz_category wy on wc.catcode = wy.catcode
        left join wiz_mall as wm on wp.mallid = wm.id
        left join wiz_wishlist as ww on wp.prdcode = ww.prdcode and ww.memid = '".$wiz_session['id']."'
    where $catcode_sql $grp_sql $brand_sql wy.catuse != 'N' and wp.showset != 'N' and wp.status = 'Y' 
    order by wp.prior desc, prdcode desc limit 1,3";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

    
    ?>


        <div class="sale_cont">
            <div class="sale_ttl clearfix">
                <h2><strong>SALE</strong> item <span class="red">UP TO <strong>40&#37;</strong></span></h2>

                <p>핫한 아이템을 저렴한 가격에 만나볼 수 있는 기회!</p>
                <a href="/shop/prd_list.php?grp=sale">더보기</a>
            </div><!-- //sale_ttl -->
            <ul class="sale_list prd_list clearfix">

            
            <?
            while($row = mysqli_fetch_object($result)){

            // 상품아이콘
            $sp_img = "";
            if($row->popular == "Y") 	$sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
            if($row->recom == "Y") 		$sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
            if($row->new == "Y") 			$sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
            if($row->sale == "Y") 		$sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;";
            if($row->best == "Y") 		$sp_img .= "<img src='/images/icon_best.gif'>&nbsp;";
            if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

            $prdicon_list = explode("/",$row->prdicon);
            for($ii=0; $ii<count($prdicon_list)-1; $ii++){
                $sp_img .= "<img src='/data/prdicon/".$prdicon_list[$ii]."'> ";
            }

            // 쿠폰아이콘
            $coupon_img = "";
            if(
            $row->coupon_use == "Y" &&
            $row->coupon_edate >= date('Y-m-d') &&
            ($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
            ){

                $coupon_img = "<font class=coupon>".number_format($row->coupon_dis).$row->coupon_type."</font> <img src='/images/icon_coupon.gif'>&nbsp;";
            }

            // 정상가(판매가보다 높을경우 할인표시)
            $conprice = "";
            if($row->conprice > $row->sellprice){
                $conprice = "<strong>".number_format($row->conprice)."</strong>원";
            }

            $sellprice = "<strong>".number_format($row->sellprice)."</strong>원";

            if(!empty($row->strprice)) {
                $conprice = "";
                $sellprice = "<font class=price>".$row->strprice."</font>";
            }

            // 상품 이미지
            if(!@file($_SERVER['DOCUMENT_ROOT']."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimg_R.gif";
            else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

            ?>
                <li>
                    <button type="button" class="wish_btn wish_btn<?=$row->prdcode?><?=($row->wish ? ' on' : '')?>" onclick="addWish('<?=$row->prdcode?>');">관심상품 등록</button>
                    <a href="/shop/prd_view.php?prdcode=<?=$row->prdcode?>&catcode=<?=$catcode?>&brand=<?=$brand?>&page=<?=$page?>">
                        <div class="img_box">
                            <img src="<?=$row->prdimg_R?>" alt="제품이미지" />
                        </div>
                        <dl>
                            <dt>[단독 2+1]AL 헥사곤 실리콘 컵받침</dt>
                            <dd>
                                <span class="price">
                                    <?=$sellprice?>
                                </span>
                                <span class="cost"><?=$conprice?></span>
                            </dd>
                        </dl>
                    </a>
                </li>
               <?
               $cc++;
               }
               ?>

            </ul><!-- //sale_list -->
        </div><!-- //sale_cont -->

<?

}
?>