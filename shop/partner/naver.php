<?

include "../../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../../inc/util.inc"; 		   		// 유틸 라이브러리
include "../../inc/design_info.inc"; 	// 디자인 정보
include "../../inc/oper_info.inc"; 		// 운영 정보

$naver_path = $_SERVER["DOCUMENT_ROOT"]."/shop/partner";
               
// 상품그룹별 찾기 (신상품,추천상품,세일상품,인기상품)
if($grp != "") $grp_sql = " wp.$grp = 'Y' and ";


$sql = "select distinct wp.prdcode, wp.prdname, wp.stortexp, wp.prdcom, wp.reserve, wp.sellprice, wp.prdimg_R, wp.prdimg_M1, wp.popular, wp.recom, wp.new, wp.best, wp.sale, wp.shortage, wp.prdicon, wp.stock, 
							wp.conprice, wp.coupon_use, wp.coupon_type, wp.coupon_dis, wp.coupon_amount, wp.coupon_limit, wp.coupon_edate, wy.catcode, wy.catname, wy.depthno, wp.origin, wp.brand, wp.mdate  
							from wiz_cprelation wc, wiz_product wp, wiz_category wy where $catcode_sql $grp_sql wy.catuse != 'N' and wc.catcode = wy.catcode and wc.prdcode = wp.prdcode and wp.showset != 'N' ";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);
					

$xmlContent="";
$xmlContent2="";

		while(($row = mysqli_fetch_object($result))){
		
		$firCat = substr($row->catcode,0,2)."0000";
		$secCat = substr($row->catcode,0,4)."00";
		
		$sql="select catname from wiz_category where catcode = '$firCat'";
		$frow = mysqli_fetch_object(mysqli_query($connect, $sql));
		
		$sql="select catname from wiz_category where catcode = '$secCat'";
		$srow = mysqli_fetch_object(mysqli_query($connect, $sql));
		
		if($row->depthno == 1){
			$cate1 = $row->catname;
			$caid1 = $row->catcode;	
		}else if($row->depthno == 2){
			$cate1 = $frow->catname;
			$caid1 = $frow->catcode;	
			$cate2 = $row->catname;
			$caid2 = $row->catcode;	
		}else if($row->depthno == 3){
			$cate1 = $frow->catname;
			$caid1 = $frow->catcode;	
			$cate2 = $srow->catname;
			$caid2 = $srow->catcode;	
			$cate3 = $row->catname;
			$caid3 = $row->catcode;	
		}
		
		$pdate = substr($row->mdate,0,7);
		
		$deliver_price = deliver_price($prd_price, $oper_info);
		
		if(
		$row->coupon_use == "Y" && 
		$row->coupon_edate >= date('Y-m-d') && 
		($row->coupon_limit == "N" || ($row->coupon_limit == "" && $row->coupon_amount > 0))
		){			
			$cupon=number_format($row->coupon_dis).$row->coupon_type;
		}
		
		$sql = "select brdname from wiz_brand where brduse != 'N' and idx = '".$row->brand."'";
        $brow = mysqli_fetch_object(mysqli_query($connect, $sql));

$xmlContent .="<<<begin>>>\n";
$xmlContent .="<<<mapid>>>".$row->prdcode."\n";
$xmlContent .="<<<pname>>>".$row->prdname."\n";
$xmlContent .="<<<price>>>".$row->sellprice."\n";
$xmlContent .="<<<pgurl>>>http://".$HTTP_HOST."/shop/prd_view.php?prdcode=".$row->prdcode."\n";
$xmlContent .="<<<igurl>>>http://".$HTTP_HOST."/data/prdimg/".$row->prdimg_M1."\n";
$xmlContent .="<<<cate1>>>".$cate1."\n";
$xmlContent .="<<<cate2>>>".$cate2."\n";
$xmlContent .="<<<cate3>>>".$cate3."\n";
$xmlContent .="<<<cate4>>>\n";
$xmlContent .="<<<caid1>>>".$caid1."\n";
$xmlContent .="<<<caid2>>>".$caid2."\n";
$xmlContent .="<<<caid3>>>".$caid3."\n";
$xmlContent .="<<<caid4>>>\n";
$xmlContent .="<<<model>>>\n";
$xmlContent .="<<<brand>>>".$brow->brdname."\n";
$xmlContent .="<<<maker>>>\n";
$xmlContent .="<<<origi>>>".$row->origin."\n";
$xmlContent .="<<<pdate>>>".$pdate."\n";
$xmlContent .="<<<deliv>>>".$deliver_price."\n";
$xmlContent .="<<<event>>>\n";
$xmlContent .="<<<coupo>>>".$cupon."\n";
$xmlContent .="<<<pcard>>>\n";
$xmlContent .="<<<point>>>".$row->reserve."\n";
$xmlContent .="<<<modig>>>\n";
$xmlContent .="<<<ftend>>>\n";

$xmlContent2 .="<<<begin>>>\n";
$xmlContent2 .="<<<mapid>>>".$row->prdcode."\n";
$xmlContent2 .="<<<pname>>>".$row->prdname."\n";
$xmlContent2 .="<<<price>>>".$row->sellprice."\n";
$xmlContent2 .="<<<ftend>>>\n";
		
		}

/*
if(!file_exists($naver_path."/naver_total.txt")){
	touch($naver_path."/naver_total.txt");
	chmod($naver_path."/naver_total.txt",0707);
}

if(!file_exists($naver_path."/naver_stor.txt")){
	touch($naver_path."/naver_stor.txt");
	chmod($naver_path."/naver_stor.txt",0707);
}

$fp = fopen($naver_path."/naver_total.txt","w");
fwrite($fp,$xmlContent);
fclose($fp);

$fp = fopen($naver_path."/naver_stor.txt","w");
fwrite($fp,$xmlContent2);
fclose($fp);
*/

if($mode == "summary") echo $xmlContent2;
else echo $xmlContent;
	?>
