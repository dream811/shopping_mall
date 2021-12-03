<?php
include_once "../inc/common.inc"; 		// DB컨넥션, 접속자 파악
include_once "../inc/util.inc"; 			// 라이브러리 함수

//로그인 (없는 경우 가입 진행)
function userLogin($userId, $prdcode="")
{
	global $connect;
	global $_http_host;
	$http_port = $_SERVER['SERVER_PORT'] == "" ? "": ":".$_SERVER['SERVER_PORT'];

	$sql = "select id,passwd,name,email,tphone,hphone,level from wiz_member where id='$userId'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$wiz_session = array();
	// 일반회원 로그인
	if($row = mysqli_fetch_object($result)){

		//방문회수 증가
		$sql = "update wiz_member set visit = visit+1 , visit_time = now() where id='$userId'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		global $wiz_session;
		$level_info = level_info();
		$level_value = $level_info[$row->level]['level'];

		$wiz_session['id'] = $_SESSION['wiz_session']['id']			= $row->id;
		$wiz_session['passwd'] = $_SESSION['wiz_session']['passwd']		= $row->passwd;
		$wiz_session['name'] = $_SESSION['wiz_session']['name']		= $row->name;
		$wiz_session['tphone'] = $_SESSION['wiz_session']['tphone']		= $row->tphone;
		$wiz_session['hphone'] = $_SESSION['wiz_session']['hphone']		= $row->hphone;
		$wiz_session['email'] = $_SESSION['wiz_session']['email']		= $row->email;
		$wiz_session['level'] = $_SESSION['wiz_session']['level']		= $row->level;
		$wiz_session['level_value'] = $_SESSION['wiz_session']['level_value']	= $level_value;
		$prev="";
		if(empty($prdcode)) $prev = "https://".$_http_host;
		else $prev = "https://".$_http_host."/shop/prd_view.php?prdcode=".$prdcode;
		Header("Location: $prev");

	// 관리자 로그인
	}else{
		// 가입레벨(가장낮은 레벨)
		$sql = "select idx from wiz_level order by level desc limit 1";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_object($result);
		$level = $row->idx;

		$passwd = md5($userId);

		// 입력정보 저장
		$sql = "insert into wiz_member(id,passwd,name,resno,email,tphone,hphone,fax,post,address,address2,reemail,resms,birthday,bgubun,marriage,memorial,
											scholarship,job,income,car,consph,conprd,level,recom,visit,visit_time,comment,com_num,com_name,com_owner,com_post,com_address,com_kind,com_class,wdate)
											values('$userId', '$passwd', '$userId', '', '${userId}@mail.com', '000-000-0000', '000-000-0000', '000-000-0000', '', '', '', '', '',
											'', '', '', '', '', '', '', '', '', '',
											'$level', '', '', '', '','','','','','','','', now())";

		mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$prev="";
		if(empty($prdcode)) $prev = "https://".$_http_host;
		else $prev = "https://".$_http_host."/shop/prd_view.php?prdcode=".$prdcode;
		Header("Location: $prev");
	}
    
}

//상품리스트자료
function getProducts($lastIndex = 0, $pageSize = 100)
{
    global $connect;
    // 상품넘버 만들기
	$sql = "select wiz_product.prdcode, prdname, prdcom, origin, stock, sellprice, conprice, supprice, reserve, new, best, popular, recom, sale, mallid, prdimg_R as main_image, prdimg_L1 as large_image, prdimg_M1 as medium_image, prdimg_S1 as small_image, wiz_category.catcode as category_code, wiz_category.catname as category_name from wiz_product left join wiz_cprelation on wiz_product.prdcode = wiz_cprelation.prdcode left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode where status='Y' limit $pageSize offset $lastIndex";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
			$row['main_image'] = $row['main_image'] != "" ? "https://$_http_host/data/prdimg/".$row['main_image'] : "";
			$row['large_image'] = $row['large_image'] != "" ? "https://$_http_host/data/prdimg/".$row['large_image'] : "";
			$row['medium_image'] = $row['medium_image'] != "" ? "https://$_http_host/data/prdimg/".$row['medium_image'] : "";
			$row['small_image'] = $row['small_image'] != "" ? "https://$_http_host/data/prdimg/".$row['small_image'] : "";
            array_push($rows, $row);
        }
    }
	return $rows;
}
//상품코드리스트 자료
function getProductCodes_($lastIndex = 0, $pageSize = 100)
{
    global $connect;
	$prdcode = date('ymd', time() - 60 * 60 * 24)."0000";
	$prdcode = "0000000000";
    // 상품넘버 만들기
	$sql = "select prdcode from wiz_product where status='Y' and prdcode > $prdcode limit $pageSize offset $lastIndex";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
    }
	return $rows;
}
//상품코드리스트 자료
function getProductCodes($lastIndex = 0, $pageSize = 500)
{
    global $connect;
	$prdcode = date('ymd', time() - 60 * 60 * 24)."0000";
	$prdcode = "0000000000";
    // 상품넘버 만들기
	$sql = "select prdcode from wiz_product where status='Y' and prdcode > '$prdcode' ";//limit $pageSize offset $lastIndex
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
    }
	$sql = "select strId as prdcode from tb_success_products where nProductWorkProcess=1 and strRegPageId != '' and nUserId = 15";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
    }
	return $rows;
}
//상품상세 자료
function getProductInfo_($prdcode)
{
	global $connect;
	//$sql = "select wiz_product.prdcode, prdname, prdcom, origin, stock, sellprice, conprice, supprice, reserve, new, best, popular, recom, sale, mallid, prdimg_R as main_image, prdimg_L1 as large_image, prdimg_M1 as medium_image, prdimg_S1 as small_image, wiz_category.catcode as category_code, wiz_category.catname as category_name from wiz_product left join wiz_cprelation on wiz_product.prdcode = wiz_cprelation.prdcode left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode where status='Y' and wiz_product.prdcode=$prdcode";
	$sql = "select wiz_product.prdcode, prdname, prdcom, origin, stock, new, best, sellprice, conprice as discprice,  prdimg_R as main_image, wiz_category.catcode as category_code, wiz_category.catname as category_name 
	from wiz_product left join wiz_cprelation on wiz_product.prdcode = wiz_cprelation.prdcode 
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode where status='Y' and wiz_product.prdcode=$prdcode";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	if($row = mysqli_fetch_assoc($result)){
		$row['main_image'] = $row['main_image'] != "" ? "https://xn--9n3bo0el5b.com/data/prdimg/".$row['main_image'] : "";
		
		//conprice가 0인경우 할인가 없다고 본다.
		// if($row['discprice'] == 0 ){
		// 	$row['discprice'] = $row['sellprice'];
		// 	$row['sellprice'] = 0;
		// }
		$row['strShopId'] = "simbongsa";
		$row['strShopName'] = "심봉사";
		$row['strShopPrdLink'] = 'https://xn--9n3bo0el5b.com/shop/prd_view.php?prdcode='.$row['prdcode'];
		$row['new'] = ($row['new'] == "Y") ? "1" : "0";
		$row['best'] = ($row['best'] == "Y") ? "1" : "0";
		$row['oversea'] = 1;
		$row['nProfit'] = number_format($row['sellprice'] * 0.15, 0, '.', '');
	}
	
	return $row;
}

//상품상세 자료
function getProductInfo($prdcode)
{
	global $connect;
	//$sql = "select wiz_product.prdcode, prdname, prdcom, origin, stock, sellprice, conprice, supprice, reserve, new, best, popular, recom, sale, mallid, prdimg_R as main_image, prdimg_L1 as large_image, prdimg_M1 as medium_image, prdimg_S1 as small_image, wiz_category.catcode as category_code, wiz_category.catname as category_name from wiz_product left join wiz_cprelation on wiz_product.prdcode = wiz_cprelation.prdcode left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode where status='Y' and wiz_product.prdcode=$prdcode";
	$sql = "select wiz_product.prdcode, prdname, prdcom, origin, stock, new, best, sellprice, conprice as discprice,  prdimg_R as main_image, wiz_category.catcode as category_code, wiz_category.catname as category_name 
	from wiz_product left join wiz_cprelation on wiz_product.prdcode = wiz_cprelation.prdcode 
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode where status='Y' and wiz_product.prdcode='$prdcode'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	if($row = mysqli_fetch_assoc($result)){
		$row['main_image'] = $row['main_image'] != "" ? "https://xn--9n3bo0el5b.com/data/prdimg/".$row['main_image'] : "";
		
		//conprice가 0인경우 할인가 없다고 본다.
		// if($row['discprice'] == 0 ){
		// 	$row['discprice'] = $row['sellprice'];
		// 	$row['sellprice'] = 0;
		// }
		$row['strShopId'] = "simbongsa";
		$row['strShopName'] = "심봉사";
		$row['strShopPrdLink'] = 'https://xn--9n3bo0el5b.com/shop/prd_view.php?prdcode='.$row['prdcode'];
		$row['new'] = ($row['new'] == "Y") ? "1" : "0";
		$row['best'] = ($row['best'] == "Y") ? "1" : "0";
		$row['oversea'] = 1;
		$row['nProfit'] = number_format($row['sellprice'] * 0.15, 0, '.', '');
	}else{
		//외부
		
		$sql = "select nIdx, strId as prdcode, strRegPageId, strKrMainName as prdname, nExpectedRevenue as nProfit, nProductPrice as sellprice,
		bReg11thhouse, bRegAuction, bRegCoupang, bRegGmarket, bRegInterpark, bRegLotteon, bRegSmartstore, bRegTmon, bRegWemakePrice, strCategoryCode0

		from tb_success_products where tb_success_products.strId='$prdcode'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		if($row = mysqli_fetch_assoc($result)){
			$sql1 = "select strURL from tb_success_product_images where nProductIdx=".$row['nIdx']." limit 1";
			$result1 = mysqli_query($connect, $sql1) or die(mysqli_error($connect));
			$row_img = mysqli_fetch_assoc($result1);

			$row['main_image'] = $row_img['strURL'];
			
			//conprice가 0인경우 할인가 없다고 본다.
			// if($row['discprice'] == 0 ){
			// 	$row['discprice'] = $row['sellprice'];
			// 	$row['sellprice'] = 0;
			// }
			$arrCategoryCode = mb_split(" : ", $row['strCategoryCode0']);
			$shopCode = mb_split(":", $row['prdcode'])[0];
			if($shopCode == '1G' || $shopCode == '11' ){
				$row['strShopId'] = "11thhouse";
				$row['strShopName'] = "11번가";
				$row['strShopPrdLink'] = "https://www.11st.co.kr/products/".$row['strRegPageId'];
			}else if($shopCode == 'A'){
				$row['strShopId'] = "auction";
				$row['strShopName'] = "옥션";
				$row['strShopPrdLink'] = "http://itempage3.auction.co.kr/DetailView.aspx?ItemNo=".$row['strRegPageId'];
			}else if($shopCode == 'C'){
				$row['strShopId'] = "coupang";
				$row['strShopName'] = "쿠팡";
				$row['strShopPrdLink'] = "https://www.coupang.com/vp/products/".$row['strRegPageId'];
			}else if($shopCode == 'G'){
				$row['strShopId'] = "gmarket";
				$row['strShopName'] = "지마켓";
				$row['strShopPrdLink'] = "http://item.gmarket.co.kr/Item?goodscode=".$row['strRegPageId'];
			}else if($shopCode == 'S'){
				$row['strShopId'] = "smartstore";
				$row['strShopName'] = "스마트스토어";
				$row['strShopPrdLink'] = "https://smartstore.naver.com/talinmall/".$row['strRegPageId'];
			}
			unset($row['bReg11thhouse']);
			unset($row['bRegAuction']);
			unset($row['bRegCoupang']);
			unset($row['bRegGmarket']);
			unset($row['bRegSmartstore']);
			unset($row['bRegInterpark']);
			unset($row['bRegLotteon']);
			unset($row['bRegTmon']);
			unset($row['bRegWemakePrice']);
			unset($row['strRegPageId']);
			unset($row['strCategoryCode0']);
			unset($row['nIdx']);

			$row['category_code'] = trim($arrCategoryCode[0]);
			$row['category_name'] = trim($arrCategoryCode[1]);
			$row['new'] = "0";
			$row['best'] = "0";
			$row['oversea'] = 1;
			$row['origin'] = "중국";
			$row['prdcom'] ="미확정";
			$row['nProfit'] = number_format($row['nProfit'],0, '.', '');
			$row['discprice'] = 0;
		}


	}
	return $row;
}
//주문자료
function getOrders($lastIndex = 0, $pageSize = 100)
{
    global $connect;
    // 주문자료 만들기
	$sql = "select wiz_order.orderid, send_id, send_name, send_tphone, send_hphone, send_email, send_post, send_address, rece_name, rece_tphone, rece_hphone, rece_address, pay_method, reserve_price, deliver_method, deliver_price, wiz_order.deliver_date, prd_price, total_price, order_date, pay_date, send_date, 
	wiz_basket.prdcode as prdcode, wiz_basket.prdname as prdname, wiz_basket.mallid as mallid, wiz_basket.amount as stock, wiz_category.catcode as catcode 
	
	from wiz_order
	left join wiz_basket
	on wiz_order.orderid = wiz_basket.orderid
	left join wiz_cprelation on wiz_basket.prdcode = wiz_cprelation.prdcode 
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode
	limit $pageSize offset $lastIndex";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
			$row['profit_price'] = number_format($row['total_price'] * 0.3, 0, '.', '');
            array_push($rows, $row);
        }
    }
	return $rows;
}
// 카테고리
function getCategories($lastIndex = 0, $pageSize = 100)
{
    global $connect;
    // 카테고리 만들기
	$sql = "select catcode, catname, cms_rate from wiz_category limit $pageSize offset $lastIndex";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, $row);
        }
    }
	return $rows;
}

//주문자료
function getSellData($sell_date = "", $shop_agent = "")
{
	$sql_agent = "";

	if($shop_agent != ""){
		$sql_agent = "and wiz_basket.mallid = '$shop_agent'";
	}
    global $connect;
    // 주문자료 만들기
	$sql = "select  
	order_date as strTime, total_price,
	wiz_basket.prdcode as strProductID, wiz_basket.prdname as strProductName, wiz_basket.mallid as mallid, wiz_basket.amount as nProductCnt, 
	IFNULL(wiz_cprelation.catcode, '00000000') as strCategoryID, IFNULL(wiz_category.catname, '') as strCategoryName 
	
	from wiz_order
	left join wiz_basket on wiz_order.orderid = wiz_basket.orderid
	left join wiz_cprelation on wiz_basket.prdcode = wiz_cprelation.prdcode
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode
	where order_date > '$sell_date' $sql_agent order by order_date";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
			$row['nProfit'] = number_format($row['total_price'] * 0.15, 0, '.', '');
			unset($row['total_price']);
            array_push($rows, $row);
        }
    }
	return $rows;
}

//판매리력
function getSellHistory($sell_date = "", $prd_code = "")
{
	$sql_agent = "";

	if($prd_code != ""){
		$sql_agent = "and wiz_basket.prdcode = '$prd_code'";
	}
    global $connect;
    // 주문자료 만들기
	$sql = "select  
	order_date as strTime, total_price,
	wiz_basket.prdcode as strProductID, wiz_basket.prdname as strProductName, wiz_basket.mallid as mallid, wiz_basket.amount as nProductCnt, 
	IFNULL(wiz_cprelation.catcode, '00000000') as strCategoryID, IFNULL(wiz_category.catname, '') as strCategoryName 
	
	from wiz_order
	left join wiz_basket on wiz_order.orderid = wiz_basket.orderid
	left join wiz_cprelation on wiz_basket.prdcode = wiz_cprelation.prdcode
	left join wiz_category on wiz_cprelation.catcode = wiz_category.catcode
	where order_date > '$sell_date' $sql_agent order by order_date";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$rows=[];
    if (mysqli_num_rows($result)) {
        while($row = mysqli_fetch_assoc($result)){
			$row['nProfit'] = number_format($row['total_price'] * 0.15, 0, '.', '');
			unset($row['total_price']);
            array_push($rows, $row);
        }
    }
	return $rows;
}
