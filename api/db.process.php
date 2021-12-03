<?php
function registProduct($data)
{
    global $connect;
    // 상품넘버 만들기
	$sql = "select max(prdcode) as prdcode from wiz_product";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	if($row = mysqli_fetch_object($result)){

		$datenum = substr($row->prdcode,0,6);
		$prdnum = substr($row->prdcode,6,4);
		$prdnum = substr("000".(++$prdnum),-4);

		if($datenum == date('ymd')) $prdcode = $datenum.$prdnum;
		else $prdcode = date('ymd')."0001";

	}else{
		$prdcode = date('ymd')."0001";
	}
	// // 상품아이콘
	// for($ii=0; $ii<count($prdicon); $ii++){
	// 	$prdicon_list .= $prdicon[$ii]."/";
	// }
    $prdicon_list = "";
	// 상품이미지 저장
	include "./prd_imgin.php";

	$prdname = str_replace("'","′",$data['name']);
	$prior = date('ymdHis');

	// 상품 옵션 1
    $optcode = "";
    $opttitle="";
    if(count($data['options']) >0){
        $opttitle = $data['options'][0];
        for($ii = 0; $ii < count($data['optionValues'][0]); $ii++) {
            if(!isset($optionValues[0][$ii])) $optcode .= $data['optionValues'][0][$ii]."^";
        }
    }
	// 상품 옵션 2
    $optcode2 = "";
    $opttitle2="";
    if(count($data['options']) > 1){
        $opttitle2 = $data['options'][1];
        for($ii = 0; $ii < count($data['optionValues'][1]); $ii++) {
            
            if(!isset($optionValues[1][$ii])) $optcode2 .= $data['optionValues'][1][$ii]."^";
        }
    }
	// 상품 옵션 - 가격/적립금/재고
    $tmp_opt = array();
    $tmp_opt['sellprice'] = array();
    $tmp_opt['reserve'] = array();
    $tmp_opt['stock'] = array();
    $optvalue = "";
    if(!isset($data)) exit(); 
	for($ii = 0; $ii < count($data['items']); $ii++) {

		if(empty($data['items'][$ii]['nSubItemOptionPrice'])) $tmp_opt['sellprice'][$ii] = 0;
        else $tmp_opt['sellprice'][$ii] = $data['items'][$ii]['nSubItemOptionPrice'];
		
        if(!isset($data['items'][$ii]['reserve'])) $tmp_opt['reserve'][$ii] = 0;
		
        if(empty($data['items'][$ii]['nOriginQuantity'])) $tmp_opt['stock'][$ii] = 0;
        else $tmp_opt['stock'][$ii] = $data['items'][$ii]['nOriginQuantity'];
		$optvalue .= $tmp_opt['sellprice'][$ii]."^".$tmp_opt['reserve'][$ii]."^".$tmp_opt['stock'][$ii]."^^";
	}

	// // 추가 옵션 1
	// for($ii = 0; $ii < count($optcode3_opt); $ii++) {
	// 	if(strcmp($optcode3_opt[$ii]."^".$optcode3_pri[$ii]."^".$optcode3_res[$ii]."^^", "^^^^")) {

	// 		if(empty($optcode3_pri[$ii])) $optcode3_pri[$ii] = 0;
	// 		if(empty($optcode3_res[$ii])) $optcode3_res[$ii] = 0;

	// 		$optcode3 .= $optcode3_opt[$ii]."^".$optcode3_pri[$ii]."^".$optcode3_res[$ii]."^^";
	// 	}
	// }

	// // 추가 옵션 2
	// for($ii = 0; $ii < count($optcode4_opt); $ii++) {
	// 	if(strcmp($optcode4_opt[$ii]."^".$optcode4_pri[$ii]."^".$optcode4_res[$ii]."^^", "^^^^")) {

	// 		if(empty($optcode4_pri[$ii])) $optcode4_pri[$ii] = 0;
	// 		if(empty($optcode4_res[$ii])) $optcode4_res[$ii] = 0;

	// 		$optcode4 .= $optcode4_opt[$ii]."^".$optcode4_pri[$ii]."^".$optcode4_res[$ii]."^^";
	// 	}
	// }

	$content= addslashes($data['desc']);

	// 입점업체 아이디
	$mallid = $data['strUserID'];
	$mdid = $data['userid'];
    $prior = date('ymdHis');
    $scrapId = $data['scrapId'];
	// 상품승인
	$status = "N";
    $stock = $data['stock'];
    $sellprice = $supprice = $data["price"];
    $conprice = $data["price"] + $data["discount"];
    $reserve = round($sellprice/100*5);
    if($data["discount"] > 0) $sale = "Y"; else $sale = "N";
    $prdicon_list="";
	// 상품정보 저장
    
	$sql = "insert into wiz_product
					(prdcode,scrapId,prdname,prdcom,origin,showset,stock,savestock,prior,viewcnt,deimgcnt,basketcnt,ordercnt,cancelcnt,
					comcnt,sellprice,conprice,supprice,reserve,strprice,cms_type,cms_type2,cms_rate,new,best,popular,recom,sale,shortage,coupon_use,coupon_dis,coupon_type,
					coupon_amount,coupon_limit,coupon_sdate,coupon_edate,del_type,del_price,prdicon,prefer,brand,info_use,info_name1,info_value1,
					info_name2,info_value2,info_name3,info_value3,info_name4,info_value4,info_name5,info_value5,
					info_name6,info_value6,opt_use,opttitle,optcode,opttitle2,optcode2,opttitle3,optcode3,
					opttitle4,optcode4,opttitle5,optcode5,opttitle6,optcode6,opttitle7,optcode7,optvalue,
					prdimg_R,prdimg_L1,prdimg_M1,prdimg_S1,prdimg_L2,prdimg_M2,prdimg_S2,prdimg_L3,prdimg_M3,prdimg_S3,
					prdimg_L4,prdimg_M4,prdimg_S4,prdimg_L5,prdimg_M5,prdimg_S5,searchkey,stortexp,content,wdate,mdate,mallid,mdid,status)
					values('$prdcode','$scrapId','$prdname','','중국','Y','$stock','0','$prior','0',
					'$deimgcnt','0','0','0','0','$sellprice','$conprice','$supprice','$reserve','','C','P','0',
					'Y','N','N','N','$sale','S','','0','%','0',
					'','0000-00-00','0000-00-00','DA','0','$prdicon_list','0','0','N',
					'','','','','','',
					'','','','','','',
					'Y','$opttitle','$optcode','$opttitle2','$optcode2','','','','',
					'','','','','','','$optvalue',
					'$prdimg_R_name','$prdimg_L1_name','$prdimg_M1_name','$prdimg_S1_name',
					'$prdimg_L2_name','$prdimg_M2_name','$prdimg_S2_name','$prdimg_L3_name','$prdimg_M3_name','$prdimg_S3_name',
					'$prdimg_L4_name','$prdimg_M4_name','$prdimg_S4_name','$prdimg_L5_name','$prdimg_M5_name','$prdimg_S5_name',
					'','','$content',now(),now(),'$mallid','$mdid','$status')";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));


	// 카테고리정보 저장
    $catcode = "19000000";
    $arr_catcode = explode(' : ', $data['category']);
    $tmpcatcode = "19000000";
    if(count($arr_catcode) > 1) $tmpcatcode = $arr_catcode[0];
	// if(!empty($class04)){
	// 	$catcode = $class04;
	// }else{
	// 		if(!empty($class03)) $catcode = $class03."00";
	// 	else {
	// 		if(!empty($class02)) $catcode = $class02."0000";
	// 		else {
	// 			if(empty($class01)) $class01 = "00";
	// 			$catcode = $class01."000000";
	// 		}
	// 	}
	// }
	$sql = "insert into wiz_cprelation(idx,prdcode,catcode,tmpcatcode) values('', '$prdcode', '$catcode', '$tmpcatcode')";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

    //return $rows;
}

?>
		