<?
include "../inc/common.inc";		// DB컨넥션, 접속자 파악
include "../inc/util.inc";	// 운영정보
include "../inc/shop_info.inc";	// 운영정보
include "../inc/oper_info.inc";	// 운영정보

// 재고량 체크
function checkAmount($prdcode, $amount, $optcode){

	global $prd_info;

	global $optcode3;
	global $optcode4;
	global $connect;
	$sql = "select prdname, prdimg_R as prdimg, opttitle, optcode, opttitle2, optcode2, opttitle3, optcode3, opttitle4, optcode4, optvalue, stock, sellprice, reserve, shortage, opt_use, mallid, supprice, cms_rate from wiz_product where prdcode = '$prdcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$prd_info = mysqli_fetch_object($result);

	if(!empty($prd_info->optcode3)) {

		$opt3_arr = explode("^^", $prd_info->optcode3);

		for($ii = 0; $ii < count($opt3_arr); $ii++) {

			list($opt, $price, $reserve) = explode("^", $opt3_arr[$ii]);

			if(!strcmp($opt, $optcode3)) {

				$prd_info->sellprice = $prd_info->sellprice + $price;
				$prd_info->reserve = $prd_info->reserve + $reserve;

				$prd_info->supprice = $prd_info->supprice + ($price * (100 - $prd_info->cms_rate)/100);

			}
		}
	}
	if(!empty($prd_info->optcode4)) {

		$opt4_arr = explode("^^", $prd_info->optcode4);

		for($ii = 0; $ii < count($opt4_arr); $ii++) {

			list($opt, $price, $reserve) = explode("^", $opt4_arr[$ii]);

			if(!strcmp($opt, $optcode4)) {

				$prd_info->sellprice = $prd_info->sellprice + $price;
				$prd_info->reserve = $prd_info->reserve + $reserve;

				$prd_info->supprice = $prd_info->supprice + ($price * (100 - $prd_info->cms_rate)/100);

			}
		}
	}

	if(!strcmp($prd_info->opt_use, "Y")){

		$opt1_arr = explode("^", $prd_info->optcode);
		$opt2_arr = explode("^", $prd_info->optcode2);
		$opt_tmp = explode("^^", $prd_info->optvalue);

		list($optcode1, $optcode2) = explode("/", $optcode);

		$no = 0;
		for($ii = 0; $ii < count($opt1_arr) - 1; $ii++) {
			for($jj = 0; $jj < count($opt2_arr) - 1; $jj++) {
				list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

				if(!strcmp($optcode1, $opt1_arr[$ii]) && !strcmp($optcode2, $opt2_arr[$jj])) {
					$prd_info->sellprice = $prd_info->sellprice + $price;
					$prd_info->reserve = $prd_info->reserve + $reserve;

					$prd_info->supprice = $prd_info->supprice + ($price * (100 - $prd_info->cms_rate)/100);

					if($stock < $amount){
						error("주문수량이 재고량(".$stock."개)보다 많습니다.");
					}
				}

				$no++;
			}
		}

		/*
		$tmp_short = 0;
		$opt_tmp = explode("^^",$prd_info->optcode);
		for($ii=0; $ii<count($opt_tmp)-1; $ii++){
			$opt_sub_tmp = explode("^",$opt_tmp[$ii]);
			if($opt_sub_tmp[0] == $optcode){
				$prd_info->sellprice = $opt_sub_tmp[1];
				if($opt_sub_tmp[2] < $amount){
					error("주문수량이 재고량(".$opt_sub_tmp[2]."개)보다 많습니다.");
				}
			}
		}
		*/

	}else{

		if(!strcmp($prd_info->shortage, "S")) {

			if($amount > $prd_info->stock){
				error("주문수량이 재고량(".$prd_info->stock."개)보다 많습니다.");
			}

		} else if(!strcmp($prd_info->shortage, "Y")) {

			error("품절된 상품입니다.");
		}
	}
}

// 상품장바구니에 저장
if($mode == "insert"){

	if(empty($idx) && empty($selprd)) {

		$optlist = explode("^",$optcode);
		$optcode = $optlist[0];

		$optlist = explode("^",$optcode2);
		$optcode2 = $optlist[0];

		$optlist = explode("^",$optcode3);
		$optcode3 = $optlist[0];

		$optlist = explode("^",$optcode4);
		$optcode4 = $optlist[0];

		$optlist = explode("^",$optcode5);
		$optcode5 = $optlist[0];

		$optlist = explode("^",$optcode6);
		$optcode6 = $optlist[0];

		$optlist = explode("^",$optcode7);
		$optcode7 = $optlist[0];

		// 같은상품에 같은 옵션을 선택했는지
		$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'";
		$bresult = mysqli_query($connect, $bsql) or error(mysqli_error($connect));
		while($result = mysqli_fetch_array($bresult)){
			if($result['prdcode'] == $prdcode &&
				$result['optcode'] == $optcode &&
				$result['optcode2'] == $optcode2 &&
				$result['optcode3'] == $optcode3 &&
				$result['optcode4'] == $optcode4 &&
				$result['optcode5'] == $optcode5 &&
				$result['optcode6'] == $optcode6 &&
				$result['optcode7'] == $optcode7){
				$result['amount'] = $amount;
				$basket_exist = true;
				$basket_idx = $result['idx'];
				break;
			}
		}

		// 재고 체크
		checkAmount($prdcode, $amount, $optcode);

		// 적립금 사용여부
		if($oper_info->reserve_use != "Y") $prd_info->reserve = 0;

		// 중복된 상품에 옵션이 없다면 신규생성
		if(!$basket_exist){

			$sellprice = $tmp_sellprice + $opt_price1 + $opt_price2 + $opt_price3;
			$reserve = $tmp_reserve + $opt_reserve1 + $opt_reserve2 + $opt_reserve3;

			$insert_sql = "INSERT INTO wiz_basket_tmp (
			idx,uniq_id,prdcode,prdname,prdimg,prdprice,prdreserve,supprice,mallid,opttitle,optcode,opttitle2,optcode2,
			opttitle3,optcode3,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,amount,wdate
			)VALUES(
			'','".$_COOKIE["uniq_id"]."','$prdcode','$prd_info->prdname','$prd_info->prdimg','$sellprice','$reserve','$prd_info->supprice','$prd_info->mallid','$opttitle','$optcode','$opttitle2','$optcode2',
			'$opttitle3','$optcode3','$opttitle4','$optcode4','$opttitle5','$optcode5','$opttitle6','$optcode6','$opttitle7','$optcode7','$amount',now())";

			mysqli_query($connect, $insert_sql) or error(mysqli_error($connect));
			$basket_idx = mysqli_insert_id($connect);

			// 장바구니수 증가
			$sql = "update wiz_product set basketcnt = basketcnt + 1 where prdcode='$prdcode'";
			@mysqli_query($connect, $sql);

		}
		// 동일한 상품이 있으면 일부 정보 업데이트
		else{
			
			$sellprice = $tmp_sellprice + $opt_price1 + $opt_price2 + $opt_price3;
			$reserve = $tmp_reserve + $opt_reserve1 + $opt_reserve2 + $opt_reserve3;

			$update_sql = "UPDATE wiz_basket_tmp SET
								prdprice		= '$sellprice',
								prdreserve		= '$reserve',
								amount			= '$amount'
							WHERE uniq_id='".$_COOKIE["uniq_id"]."' AND idx='".$basket_idx."'";

			mysqli_query($connect, $update_sql) or error(mysqli_error($connect));
		}

	} else {
		//위시리스트에서 선택후 장바구니 담길때
		if(!empty($idx)) {
			$selprd = $idx;
		}

		$tmp_prd = explode("|", $selprd);
		foreach($tmp_prd as $pkey => $pvalue){
			if(!empty($pvalue)) $tmpq .= " OR idx='$pvalue'";
		}
		$tmpq = substr($tmpq,3);

		$sql = "SELECT * FROM wiz_wishlist WHERE memid = '".$wiz_session['id']."' AND ".$tmpq;
		$results = mysqli_query($connect, $sql);
		while($row = mysqli_fetch_array($results)){

			$prdcode = $row['prdcode'];
			$opttitle = $row['opttitle'];
			$optcode = $row['optcode'];
			$opttitle2 = $row['opttitle2'];
			$optcode2 = $row['optcode2'];
			$opttitle3 = $row['opttitle3'];
			$optcode3 = $row['optcode3'];

			$opttitle4 = $row['opttitle4'];
			$optcode4 = $row['optcode4'];
			$opttitle5 = $row['opttitle5'];
			$optcode5 = $row['optcode5'];
			$opttitle6 = $row['opttitle6'];
			$optcode6 = $row['optcode6'];
			$opttitle7 = $row['opttitle7'];
			$optcode7 = $row['optcode7'];

			$amount = $row['amount'];

			$optlist = explode("^",$optcode);
			$optcode = $optlist[0];
			$optlist = explode("^",$optcode2);
			$optcode2 = $optlist[0];
			$optlist = explode("^",$optcode3);
			$optcode3 = $optlist[0];
			$optlist = explode("^",$optcode4);
			$optcode4 = $optlist[0];
			$optlist = explode("^",$optcode5);
			$optcode5 = $optlist[0];
			$optlist = explode("^",$optcode6);
			$optcode6 = $optlist[0];
			$optlist = explode("^",$optcode7);
			$optcode7 = $optlist[0];

			// 같은상품에 같은 옵션을 선택했는지
    		$bsql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'";
    		$bresult = mysqli_query($connect, $bsql) or error(mysqli_error($connect));
    		while($result = mysqli_fetch_array($bresult)){
    			if($result['prdcode'] == $prdcode &&
    				$result['optcode'] == $optcode &&
    				$result['optcode2'] == $optcode2 &&
    				$result['optcode3'] == $optcode3 &&
    				$result['optcode4'] == $optcode4 &&
    				$result['optcode5'] == $optcode5 &&
    				$result['optcode6'] == $optcode6 &&
    				$result['optcode7'] == $optcode7){
    				$result['amount'] = $amount;
    				$basket_exist = true;
					$basket_idx = $result['idx'];
    				break;
    			}
    		}

			// 재고 체크
			checkAmount($prdcode, $amount, $optcode);

			// 적립금 사용여부
			if($oper_info->reserve_use != "Y") $prd_info->reserve = 0;

    		// 중복된 상품에 옵션이 없다면 신규생성
    		if(!$basket_exist){
				$insert_sql = "INSERT INTO wiz_basket_tmp (
				idx,uniq_id,prdcode,prdname,prdimg,prdprice,prdreserve,supprice,mallid,opttitle,optcode,opttitle2,optcode2,
				opttitle3,optcode3,opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,amount,wdate
				)VALUES(
				'','".$_COOKIE["uniq_id"]."','$prdcode','$prd_info->prdname','$prd_info->prdimg','$prd_info->sellprice','$prd_info->reserve','$prd_info->supprice','$prd_info->mallid','$opttitle','$optcode','$opttitle2','$optcode2',
				'$opttitle3','$optcode3','$opttitle4','$optcode4','$opttitle5','$optcode5','$opttitle6','$optcode6','$opttitle7','$optcode7','$amount',now())";
				mysqli_query($connect, $insert_sql) or error(mysqli_error($connect));

 				// 장바구니수 증가
 				$sql = "UPDATE wiz_product SET basketcnt = basketcnt + 1 WHERE prdcode='$prdcode'";
 				@mysqli_query($connect, $sql);
			}
		}
	}

	if(mobile_check() == true) {
		if($direct == "basket" || empty($direct)) $go_url = "/".$mobile_path."/sub/cart.php";
		else if($direct == "buy") $go_url = "/".$mobile_path."/sub/order_form.php?selidx=$basket_idx|";
	} else {
		if($direct == "basket" || empty($direct)) $go_url = "prd_basket.php";
		else if($direct == "buy") $go_url = "order_form.php?selidx=$basket_idx|";
	}

	header("Location: $go_url");

// 장바구니 수정
}else if($mode == "update"){

	$idx = $_POST['idx'];
	$amount = $_POST['amount'];
	$bkinfo= mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."' AND idx='".$idx."'"));

	// 재고 체크
	checkAmount($bkinfo['prdcode'], $amount, $bkinfo['optcode']);

	@mysqli_query($connect, "UPDATE wiz_basket_tmp SET amount = '$amount' WHERE uniq_id='".$_COOKIE["uniq_id"]."' AND idx='".$idx."'");

	if(mobile_check() == true) {
		$go_url = "/".$mobile_path."/sub/cart.php";
	} else {
		$go_url = "prd_basket.php";
	}

	header("Location: $go_url");

// 장바구니 삭제
}else if($mode == "delete"){

	$idx = $_GET['idx'];
	$selected = $_GET['selected'];

	// 개별삭제
	if($idx != "") {
		@mysqli_query($connect, "DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."' AND idx='".$idx."'");
	}

	// 선택삭제
	if($selected != "") {


		$array_selected = explode("|",$selected);
		$i=0;
		while($array_selected[$i]){

			$tmp_idx = $array_selected[$i];
			@mysqli_query($connect, "DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."' AND idx='".$tmp_idx."'");

			$i++;
		}

	}

	if(mobile_check() == true) {
		$go_url = "/".$mobile_path."/sub/cart.php";
	} else {
		$go_url = "prd_basket.php";
	}

	header("Location: $go_url");

// 장바구니 전체삭제
}else if($mode == "delall"){
	@mysqli_query($connect, "DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'");

	if(mobile_check() == true) {
		$go_url = "/".$mobile_path."/sub/cart.php";
	} else {
		$go_url = "prd_basket.php";
	}

	header("Location: $go_url");

// 상품평 작성
}else if($mode == "review"){

	if($oper_info->review_level == "M" && empty($wiz_session['id'])){

		error("상품평 작성은 회원만 가능합니다.");

	}else{

		$ctype = "PRD";

		$sql = "insert into wiz_comment(idx,ctype,cidx,prdcode,star,id,name,content,passwd,wdate,wip)
								values('', '$ctype', '', '$prdcode', '$star', '".$wiz_session['id']."', '$name', '$content', '$passwd', now(), '".$_SERVER['REMOTE_ADDR']."')";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		if(mobile_check() == true) {
			$go_url = "/".$mobile_path."/sub/prdview_3.php";
		} else {
			$go_url = "/shop/prd_view.php";
		}

		comalert("상품평을 작성하였습니다.", $go_url."?prdcode=$prdcode");
	}

// 상품평 삭제
}else if($mode == "del_review"){

	$sql = "select idx from wiz_comment where idx='$idx' and passwd = '$passwd'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if(mysqli_num_rows($result) > 0){

		$sql = "delete from wiz_comment where idx='$idx' and passwd = '$passwd'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		if(mobile_check() == true) {
			$go_url = "/".$mobile_path."/sub/prdview_3.php";
		} else {
			$go_url = "/shop/prd_view.php";
		}

		comalert("상품평을 삭제하였습니다.", $go_url."?prdcode=$prdcode");

	}else{

		error("비밀번호가 맞지 않습니다.");
	}

// 상품Q&A 비밀번호 체크
} else if(!strcmp($mode, "prdqna")) {

	$sql = "select passwd from wiz_bbs where code = 'prdqna' and idx = '$idx' and prdcode = '$prdcode' and passwd = '$passwd'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	if(!empty($row['passwd'])) {

		if(mobile_check() == true) {
			$go_url = "/".$mobile_path."/sub/prdview_3.php";
		} else {
			$go_url = "/shop/prd_view.php";
		}

		echo "<script>document.location='".$go_url."?prdcode=".$prdcode."';</script>";
	} else {
		error("비밀번호가 일치하지 않습니다.", "");
	}

// 단골샵 등록
} else if($mode == "mall_wish_add") {

	$sql = "select count(idx) as cnt from wiz_mall_wish where memid = '".$wiz_session['id']."' and mallid = '".$mallid."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	if($row['cnt'] > 0) {
		error("이미 등록한 업체입니다.");
		exit;
	}

	$sql = "select com_name from wiz_mall where id = '".$mallid."'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$row = mysqli_fetch_array($result);

	$sql = "insert into wiz_mall_wish (idx,memid,mallid,mallname,wdate) values ('', '".$wiz_session['id']."', '".$mallid."', '".$row['com_name']."', now())";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	comalert("단골미니샵으로 등록하였습니다.", "/shop/minishop.php?mallid=$mallid");
}
?>