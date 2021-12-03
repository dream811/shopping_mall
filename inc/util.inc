<?
// prev, 스팸글 체크등 HTTP_HOST 체크 시 SSL 포트번호와 분리
list($_http_host, $_http_port) = explode(":", $_SERVER['HTTP_HOST']);

$mobile_path = "m";

function mobile_check() {

	global $mobile_path;

	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/'.$mobile_path))	return false;

	$mobile_array  = array("iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");

	$checkCount = 0;
	for($i=0; $i<sizeof($mobile_array); $i++){
		if(preg_match("/$mobile_array[$i]/", strtolower($_SERVER['HTTP_USER_AGENT']))){ $checkCount++; break; }
	}

	// PC 테스트 시
	if(isset($_SERVER['HTTP_REFERER'])){
		$referer = $_SERVER['HTTP_REFERER'];
	}else{
		$referer = "";
	}
	


	$parse_url = parse_url($referer);

	$parse_path = explode("/", $parse_url['path']);

	if(isset($parse_path[1]) && $parse_path[1] == $mobile_path) {
		$checkCount += 1;
	}

	return ($checkCount >= 1) ? true : false;
}

// 문자열 자르기 (이상의 길이일때는 ... 로 표시)
function cut_str($msg, $cut_size) {
	$suffix = "";
	if(mb_strlen($msg) > $cut_size) {
		$asuffix = "...";
	}
	return mb_substr($msg, 0, $cut_size, 'utf-8').$suffix;

	if($cut_size<=0) return $msg;
	if(preg_match('/[re]/i',$msg)) $cut_size=$cut_size+4;
	$han=0;$eng=0;
	for($i=0;$i<$cut_size;$i++) if(ord($msg[$i])>127) $han++; else $eng++;
	$cut_size=$cut_size+(int)$han*0.6;
	$point=1;
	$pointtmp="";
	for ($i=0;$i<strlen($msg);$i++) {
		if ($point>$cut_size) return $pointtmp."...";
		if (ord($msg[$i])<=127) {
			$pointtmp.= $msg[$i];
			if ($point%$cut_size==0) return $pointtmp."...";
		} else {
			if ($point%$cut_size==0) return $pointtmp."...";
			$pointtmp.=$msg[$i].$msg[++$i];
			$point++;
		}
		$point++;
	}

	return $pointtmp;

}

// 배송정책 이름
function deliver_name($deliver_method){
	if($deliver_method == "DA") return "전액무료";
	else if($deliver_method == "DB") return "수신자부담(착불)";
	else if($deliver_method == "DC") return "고정값";
	else if($deliver_method == "DD") return "구매가격별";
}

// 배송정책 이름 - 상품
function deliver_name_prd($deliver_method) {
	if($deliver_method == "DA") return "기본 배송정책";
	else if($deliver_method == "DB") return "무료배송";
	else if($deliver_method == "DC") return "상품별 배송비";
	else if($deliver_method == "DD") return "수신자부담(착불)";
}

function del_complete($com_msg, $go_url=""){
	if($go_url == "")
		echo "<script>window.setTimeout(\"history.go(-2)\",600);</script>";
	else
	echo "<script>window.setTimeout(\"document.location='$go_url';\",600);</script>";
	echo "<body><table width=100% height=100%><tr><td align=center><font size=2>$com_msg</font></td></tr></table></body>";
}


// 배송비
function deliver_price($prd_price, $oper_info, $mallid = "")
{
	global $connect;
	global $deliver_msg; $deliver_price = 0;
	if(!isset($oper_info->mallid)) $oper_info->mallid = "";
	if($oper_info->mallid == "" && $mallid != "") $oper_info->mallid = $mallid;

	// 장바구니에 담긴 상품의 배송정책 및 배송비

 	if(strpos($_SERVER['PHP_SELF'],"/admin/") !== false || strpos($_SERVER['PHP_SELF'],"/mall/") !== false || strpos($_SERVER['PHP_SELF'],"/order_ok.php") || strpos($_SERVER['PHP_SELF'],"/order_view.php") !== false || strpos($_SERVER['PHP_SELF'],"/order_detail.php")) {
 		global $orderid;
 		$sql = "select prdprice, del_type, del_price from wiz_basket where orderid = '$orderid' and mallid = '$oper_info->mallid'";
 	} else {
 		$sql = "SELECT wb.prdprice, wp.del_type, wp.del_price FROM wiz_basket_tmp as wb inner join wiz_product as wp on wb.prdcode = wp.prdcode WHERE wb.uniq_id='".$_COOKIE["uniq_id"]."' and wb.mallid = '$oper_info->mallid'";
 	}

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$basket_total = mysqli_num_rows($result);
	if(!isset($del_info)) $del_info = array();
	while($row = mysqli_fetch_array($result)) {

		if(empty($row['del_type'])) $row['del_type'] = "DA";

		if(!strcmp($row['del_type'], "DC")) $del_price += $row[del_price];			// 상품별 배송비 합산
		if(!isset($del_info[$row['del_type']]['cnt'])) $del_info[$row['del_type']]['cnt'] = 0;
		$del_info[$row['del_type']]['cnt'] = $del_info[$row['del_type']]['cnt'] + 1;	// 각 배송방법별 갯수

		if($row['del_type'] != "DA") $prd_price -= $row[prdprice];							// 기본 배송정책이 아닌 경우 해당 상품의 금액을 제외한 상품 금액으로 배송비 책정

		$del_type = $row['del_type'];
	}

	/**************************************************************************************
		기본 배송정책
	**************************************************************************************/

	// 기본 배송정책에 따른 배송방법 및 배송비
	if($oper_info->del_method == "DA"){ // 배송비 전액무료

 		$deliver_price = 0; $deliver_msg = "배송비 전액무료";

  }else if($oper_info->del_method == "DB"){ // 수신자부담 (착불)

 		$deliver_price = 0; $deliver_msg = "수신자부담 (착불)";

  }else if($oper_info->del_method == "DC"){ // 고정값

 		$deliver_price = $oper_info->del_fixprice; $deliver_msg = "고정 ".number_format($oper_info->del_fixprice)."원";

  }else if($oper_info->del_method == "DD"){ // 구매가격별

  	if($oper_info->del_staprice <= $prd_price) $deliver_price = $oper_info->del_staprice2;
  	else $deliver_price = $oper_info->del_staprice3;

		$deliver_msg = number_format($oper_info->del_staprice)."원 이상구매시 ";

		if($oper_info->del_staprice2 == 0) $deliver_msg .= "무료";
		else $oper_info->del_staprice2 = $deliver_msg .= number_format($oper_info->del_staprice2)."원";

 	}

	/**************************************************************************************
		상품별 배송정책
	**************************************************************************************/
	if(!isset($del_info["DA"]['cnt'])) $del_info["DA"]['cnt'] = 0;
	if(!isset($del_info["DB"]['cnt'])) $del_info["DB"]['cnt'] = 0;
	if(!isset($del_info["DC"]['cnt'])) $del_info["DC"]['cnt'] = 0;
	if(!isset($del_info["DD"]['cnt'])) $del_info["DD"]['cnt'] = 0;
	// 무료배송 상품과 함께 주문할 경우, 전체 배송비를 무료
	if(!strcmp($oper_info->del_prd, "DA") && $del_info["DB"]['cnt'] > 0) {
		$deliver_price = 0; $del_price = 0; $deliver_msg = "배송비 전액무료";
	}

	// 장바구니 상품 갯수와 배송비가 없는 상품의 갯수가 같으면 배송비 0
	if($basket_total == $del_info["DB"]['cnt'] + $del_info["DD"]['cnt']) {
		$deliver_price = 0; $del_price = 0; $deliver_msg = "상품별 배송비";
	}

	// 장바구니에 상품별 배송비가 있는 경우 배송비 문구에 "상품별 배송비 제외" 추가
	if($del_info["DC"]['cnt'] > 0) $deliver_msg .= "<br>&nbsp;(상품별 배송비는 부과)";

	// 상품을 2개 이상 주문할 경우
	if($basket_total > 1) {

		// 상품별 배송비 + 기본 배송비
		if(!strcmp($oper_info->del_prd2, "DA")) {

			// 장바구니 모든 상품이 상품별 배송인 경우 기본 배송료를 더하지 않음
			if($basket_total == $del_info["DC"]['cnt']) $deliver_price = $del_price;
			else $deliver_price = $deliver_price + $del_price;

		// 상품별 배송비, 기본 배송비 중 큰 값
		} else if(!strcmp($oper_info->del_prd2, "DB")) {
			if($deliver_price < $del_price) $deliver_price = $del_price;
		}

	// 장바구니 상품이 1개이고 기본 배송료가 아닌경우
	} else if($basket_total > 0 && strcmp($del_type, "DA")) {
		$deliver_price = $del_price;	$deliver_msg = deliver_name_prd($del_type);
	}

 	return $deliver_price;

}

// 회원등급 리스트
function level_list()
{
	global $connect;
	$sql = "select idx,level,name from wiz_level order by level asc, idx asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)){
		echo "<option value='$row->idx'>$row->name";
	}
}

// MD회원등급 리스트
function md_level_list()
{
	global $connect;
	$sql = "select idx,level,name from wiz_mdlevel order by level asc, idx asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)){
		echo "<option value='$row->idx'>$row->name";
	}
}

// 스크레핑회원등급 리스트
function scrap_level_list()
{
	global $connect;
	$sql = "select idx,level,name from wiz_scraplevel order by level asc, idx asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($result)){
		echo "<option value='$row->idx'>$row->name";
	}

}

// 등급정보
function level_info()
{
	global $connect;
	$level_info[""]['level'] = 11;
  $level_info[""]['name'] = "전체";
	$level_info["-1"]['level'] = -1;
  $level_info["-1"]['name'] = "구매회원";
	$level_info["0"]['level'] = 0;
  $level_info["0"]['name'] = "관리자";

	$sql = "select * from wiz_level";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_object($result)){
		$level_info[$row->idx]['level'] = $row->level;
		$level_info[$row->idx]['name'] = $row->name;
	}

	return $level_info;

}

// MD등급정보
function md_level_info()
{
	global $connect;
	$level_info[""]['level'] = 11;
  $level_info[""]['name'] = "전체";
	$level_info["-1"]['level'] = -1;
  $level_info["-1"]['name'] = "구매회원";
	$level_info["0"]['level'] = 0;
  $level_info["0"]['name'] = "관리자";

	$sql = "select * from wiz_mdlevel";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_object($result)){
		$level_info[$row->idx]['level'] = $row->level;
		$level_info[$row->idx]['name'] = $row->name;
	}

	return $level_info;

}

// 스크래핑등급정보
function scrap_level_info()
{
	global $connect;
	$level_info[""]['level'] = 11;
  $level_info[""]['name'] = "전체";
	$level_info["-1"]['level'] = -1;
  $level_info["-1"]['name'] = "구매회원";
	$level_info["0"]['level'] = 0;
  $level_info["0"]['name'] = "관리자";

	$sql = "select * from wiz_scraplevel";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($row = mysqli_fetch_object($result)){
		$level_info[$row->idx]['level'] = $row->level;
		$level_info[$row->idx]['name'] = $row->name;
	}
	return $level_info;
}


// 등급할인액
function level_discount($idx,$prd_price)
{
	global $connect;
	global $discount_msg;
	$discount_price = 0;

	if($idx != "" && $prd_price > 0)
	{
		$sql = "select * from wiz_level where idx = '$idx'";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_object($result);

		if($row->discount > 0)
		{
		   if($row->distype == "W")
		   {
		   	$discount_price = $row->discount;
		   }else
		   {
		      //$discount_price = floor(($prd_price*($row->discount/100))/100)*100;
			    $discount_price = floor($prd_price*($row->discount/100));
			}
		}
	}

	if($discount_price > 0) $discount_msg = " - 회원할인(<b>".number_format($discount_price)."원</b>)";

	return $discount_price;

}

// 결제방법
function pay_method($pay_method)
{

   if($pay_method == "PB") $pay_method = "무통장입금";
   else if($pay_method == "PC") $pay_method = "신용카드";
   else if($pay_method == "PN") $pay_method = "계좌이체";
   else if($pay_method == "PV") $pay_method = "가상계좌";
   else if($pay_method == "PH") $pay_method = "휴대폰";

   return $pay_method;
}

// 배송상태
function order_status($status)
{

   if($status == "OR") $status = "주문접수";
   else if($status == "OY") $status = "결제완료";
   else if($status == "DR") $status = "배송준비중";
   else if($status == "DI") $status = "배송처리";
   else if($status == "DC") $status = "배송완료";
   else if($status == "OC") $status = "주문취소";
   else if($status == "RD") $status = "취소요청";
   else if($status == "RC") $status = "취소완료";
   else if($status == "CD") $status = "교환요청";
   else if($status == "CC") $status = "교환완료";

   return $status;
}

// 비방글, 욕설체크
function check_abuse($str)
{

	global $bbs_info;
	global $sch_info;
	global $poll_info;

	if(!empty($sch_info)) $bbs_info = $sch_info;
	if(!empty($poll_info)) $bbs_info = $poll_info;

	if($bbs_info['abuse'] == "Y") {
		$abuse_list = explode(",",$bbs_info[abtxt]);
		for($ii=0; $ii < count($abuse_list); $ii++){
			$abuse_list[$ii] = trim($abuse_list[$ii]);
			if(!empty($abuse_list[$ii])){
				if( strpos($str, $abuse_list[$ii]) !== false){
					error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
				}
			}
		}
	}

}

// 이미지 리사이즈
function img_resize($srcimg, $dstimg, $imgpath, $rewidth, $reheight)
{

	$src_info = getimagesize("$imgpath/$srcimg");

	if($rewidth < $src_info[0] || $reheight < $src_info[1] ){
		if(($src_info[0]-$rewidth) > ($src_info[1]-$reheight)){
			$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);
		}else{
			$rewidth = round(($src_info[0]*$reheight)/$src_info[1]);
		}
	}else{
		copy($imgpath."/".$srcimg, $imgpath."/".$dstimg);
		chmod($imgpath."/".$dstimg, 0606);
		return;
	}

	$dst = imageCreatetrueColor($rewidth,$reheight);

	if($src_info[2] == 1){

		$src = ImageCreateFromGIF("$imgpath/$srcimg");
		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/$dstimg",100);
		chmod($imgpath."/".$dstimg, 0606);

	}else if($src_info[2] == 2){

		$src = ImageCreateFromJPEG("$imgpath/$srcimg");
		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagejpeg($dst,"$imgpath/$dstimg",100);
		chmod($imgpath."/".$dstimg, 0606);

	}else if($src_info[2] == 3){

		$src = ImageCreateFromPNG("$imgpath/$srcimg");
		imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
		Imagepng($dst,"$imgpath/$dstimg");
		chmod($imgpath."/".$dstimg, 0606);

	}

	imageDestroy($src);
	imageDestroy($dst);

}

// 파일이 이미지인지
function img_type($srcimg)
{

	if(is_file($srcimg)){

		$image_info = getimagesize($srcimg);
		switch ($image_info['mime']) {
			case 'image/gif': return true; break;
			case 'image/jpeg': return true; break;
			case 'image/png': return true; break;
			case 'image/bmp': return true; break;
			default : return false; break;
		}
	}else{
		return false;
	}

}

// 페이지 리스트 출력
function print_pagelist($page, $list_amount, $page_count, $param, $page_type = "")
{
	$PHP_SELF = $_SERVER['PHP_SELF'];
   	global $code, $catcode, $orderby;

	if(($page%$list_amount) == 0) $tmp = $page-1;
	else $tmp = $page;

	if($tmp <= 0) $tmp = 1;
	if($list_amount <= 0) $list_amount = 1;

	$spage = floor($tmp/$list_amount)*$list_amount+1;
	if($spage <= 1) $ppage = 1;
	else $ppage = $spage - $list_amount;

	$epage = $spage+$list_amount-1;
	if($epage >= $page_count){
		$epage = $page_count;
		$npage = $page_count;
	}else{
		$npage = $epage + 1;
	}

	if($page_type){
		$page_name = $page_type."page";
	}
	if(!isset($page_name)) $page_name = "page";
	if(mobile_check() == true) $skin_dir = "../img/sub/";
	else $skin_dir = "/images/";

	if($epage > 0) {

		echo "    <div class='AW-pagenum'>";
		echo "          <strong><a href='$PHP_SELF?$page_name=1&$param'><img src=\"/images/ico/page-first.jpg\" /></a></strong>";
		echo "          <strong><a href='$PHP_SELF?$page_name=$ppage&$param'><img src=\"/images/ico/page-prev.jpg\" /></a></strong>";
		echo "          <b>";

		for($spage; $spage <= $epage; $spage++){
			if($page == $spage) echo "<em>$spage</em>";
			else echo "<a href='$PHP_SELF?$page_name=$spage&$param'>$spage</a> ";
		}

		echo "          </b>";
		echo "          <strong><a href='$PHP_SELF?$page_name=$npage&$param'><img src=\"/images/ico/page-next.jpg\" /></a></strong>";
		echo "          <strong><a href='$PHP_SELF?$page_name=$page_count&$param'><img src=\"/images/ico/page-last.jpg\" /></a></strong>";
		echo "    </div>";
	}
}

// 페이지 리스트 출력
function print_pagelist2($page, $list_amount, $page_count, $param, $page_type = "")
{
	$PHP_SELF = $_SERVER['PHP_SELF'];
    global $code, $catcode, $orderby;

    if(($page%$list_amount) == 0) $tmp = $page-1;
    else $tmp = $page;

    if($tmp <= 0) $tmp = 1;
    if($list_amount <= 0) $list_amount = 1;

    $spage = floor($tmp/$list_amount)*$list_amount+1;
    if($spage <= 1) $ppage = 1;
    else $ppage = $spage - $list_amount;

    $epage = $spage+$list_amount-1;
    if($epage >= $page_count){
        $epage = $page_count;
        $npage = $page_count;
    }else{
        $npage = $epage + 1;
    }

    if($page_type){
		$page_name = $page_type."page";
	}else{
		$page_name = "page";
	}

    if(mobile_check() == true) $skin_dir = "../img/sub/";
    else $skin_dir = "/images/";

    if($epage > 0) {

        echo "<button onclick=\"location.href='$PHP_SELF?$page_name=1&$param'\"><img src=\"/images/ico/page-first.jpg\" /></button>";
        echo "<button onclick=\"location.href='$PHP_SELF?$page_name=$ppage&$param'\"><img src=\"/images/ico/page-prev.jpg\" /></button>";
        echo "<ul>";
        for($spage; $spage <= $epage; $spage++){
            if($page == $spage) echo "<li class=\"active\"><a href='$PHP_SELF?$page_name=$spage&$param'>$spage</a></li>";
            else echo "<li><a href='$PHP_SELF?$page_name=$spage&$param'>$spage</a></li>";
        }
        echo "</ul>";
        echo "<button onclick=\"location.href='$PHP_SELF?$page_name=$npage&$param'\"><img src=\"/images/ico/page-next.jpg\" /></button>";
        echo "<button onclick=\"location.href='$PHP_SELF?$page_name=$page_count&$param'\"><img src=\"/images/ico/page-last.jpg\" /></button>";
    }
}

// 이메일 발송
function send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content, $cc="", $bcc="")
{
	$charset  = "EUC-KR";

	$se_name   = "=?$charset?B?" . base64_encode($se_name) . "?=";
	$subject = "=?$charset?B?" . base64_encode($subject) . "?=";

	$header  = "Return-Path: <$se_email>\n";
	$header .= "From: $se_name <$se_email>\n";
	$header .= "Reply-To: <$se_email>\n";
	if ($cc)  $header .= "Cc: $cc\n";
	if ($bcc) $header .= "Bcc: $bcc\n";
	$header .= "MIME-Version: 1.0\n";

	$header .= "Content-Type: TEXT/HTML; charset=$charset\n";
	$content = stripslashes($content);

	$header .= "Content-Transfer-Encoding: BASE64\n\n";
	$header .= chunk_split(base64_encode($content)) . "\n";

	$result = @mail($re_email, $subject, "", $header);

	return $result;
}


function send_sms($se_num, $re_num, $message, $se_name="")
{

	global $DOCUMENT_ROOT, $sms_con, $oper_info;

	/**************************************************************************************
		SMS 클래스 사용 예제입니다.
	**************************************************************************************/
	include_once $DOCUMENT_ROOT."/inc/class.sms.php";

	$sms_server	= "211.172.232.124";	## SMS 서버
	$sms_id		= $oper_info->sms_id;				## icode 아이디
	$sms_pw		= $oper_info->sms_pw;				## icode 패스워드
	//$portcode	= 1;				## 정액제 : 2, 충전식 : 1
	if($oper_info->sms_type == "" || $oper_info->sms_type == "C") $portcode = 1;
	else if($oper_info->sms_type == "J") $portcode = 2;

	$SMS	= new SMS;
	$SMS->SMS_con($sms_server,$sms_id,$sms_pw,$portcode);

	/**************************************************************************************
	1단계: 보낼 메시지를 저장합니다. 쇼핑몰에서 장바구니에 물건을 담는다고 생각하면 됩니다.

		일반 메시지를 보낼 경우 SMS->Add() 를 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. 보내는 사람 전화 (회신번호)
			3. 보내는 사람 이름
			4. 보내는 메시지 (80자 이내)
			5. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		URL을 보낼 경우 SMS->AddURL() 을 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. URL (50자 이내)
			3. 보내는 메시지 (80자 이내)
			4. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		잘못된 값이 들어갔을 경우 에러메시지가 리턴됩니다.

		※ .URL 콜백의 경우 건당 50원의 요금이 부과 됩니다.
		※ .SKT(011,017) 번호로 발송하실 경우 사용자 동의를 받지 않아 전송 실패일 경우에도
		    정상적으로 요금이 청구 됩니다.
		※ .KTF(016,018) 번호로 발송하실 경우 회신번호를 반드시 입력하셔야 정상적으로 송신이 됩니다.
	**************************************************************************************/

	$tran_phone	= str_replace("-", "", $re_num);		# 수신번호
	$tran_callback	= str_replace("-", "", $se_num);			# 회신번호
	$tran_msg		= $message;	# 발송 메세지
	$tran_date	= "";				#발송시간
	#즉시 전송일 경우 $tran_date	= "" ;
	#예약 전송일 경우 $tran_date	= "200412312359";	# 2004년 12월 31일 23시 59분

	$result = $SMS->Add($tran_phone,"$tran_callback","$sms_id","$tran_msg","$tran_date");
	//if ($result) echo $result; else echo "일반메시지 입력 성공<BR>";

	//$result = $SMS->AddURL($tran_phone,"$tran_callback","w.yahoo.co.kr","테스트입니다","");
	//if ($result) echo $result; else echo "URL 입력 성공<BR>";
	//echo "<HR>";

	/**************************************************************************************
	2단계: 저장해둔 메시지를 전송합니다. 쇼핑몰에서 결제를 한다고 생각하면 됩니다.

		SMS->Send() 를 실행하면 모아둔 메시지를 모두 발송합니다.
		이때 SMS->Send()가 리턴하는 값은 true, false 입니다.
		이것은 서버와의 접속 상태를 나타냅니다.

		SMS->Send() 를 실행하고 난 후에는 메시지 발송 결과를 조회할 수 있습니다.
		메시지 발송 결과는 SMS->Result 배열에 저장되어 있습니다.
		데이타 형식은 "핸드폰 번호 : 메시지 고유번호" 입니다. 예) 0115511474:13622798
		전송이 제대로 되지 않은 건에 대해서는 에러 표시가 납니다. 예) 0195200107:Error

		만약 같은 클래스를 재사용할 경우, SMS->Init() 명령으로 메시지 발송 결과를 없애주십시오.
	**************************************************************************************/

	$result = $SMS->Send();
	if ($result) {
		//echo "SMS 서버에 접속했습니다.<br>";
		$success = $fail = 0;
		foreach($SMS->Result as $result) {
			list($phone,$code)=explode(":",$result);
			if ($code=="Error") {
				//echo $phone.'로 발송하는데 에러가 발생했습니다.<br>';
				$fail++;
			} else {
				//echo $phone."로 전송했습니다. (메시지번호:".$code.")<br>";
				$success++;
			}
		}
		//echo $success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br>";
		$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
	} else {
		//echo "에러: SMS 서버와 통신이 불안정합니다.<br>";
	}

	//echo "<table width='100%'><tr><td align='center'><span onClick='self.close()' style='cursor:pointer'>[닫기]</span></td></tr></table>";

}

function info_replace($shop_info, $re_info, $order_info, $msg)
{

	global $_http_host;

	$date = date('Y')."년 ".date('m')."월 ".date('d')."일";

	if(!isset($re_info['id'])) $re_info['id'] = "";
	if(!isset($re_info['pw'])) $re_info['pw'] = "";
	if(!isset($re_info['name'])) $re_info['name'] = "";

	if(!isset($shop_info->shop_name)) $shop_info->shop_name = "";
	if(!isset($shop_info->shop_email)) $shop_info->shop_email = "";
	if(!isset($shop_info->shop_tel)) $shop_info->shop_tel = "";

	$msg = str_replace("{DATE}", $date, $msg);
	$msg = str_replace("{MEM_ID}", $re_info['id'], $msg);
	$msg = str_replace("{MEM_PW}", $re_info['pw'], $msg);
	$msg = str_replace("{MEM_NAME}", $re_info['name'], $msg);
	$msg = str_replace("{SHOP_NAME}", $shop_info->shop_name, $msg);
	$msg = str_replace("{SHOP_EMAIL}", $shop_info->shop_email, $msg);
	$msg = str_replace("{SHOP_TEL}", $shop_info->shop_tel, $msg);
	$msg = str_replace("{SHOP_URL}", "http://".$_http_host, $msg);
	$msg = str_replace("{ORDER_INFO}", $order_info, $msg);

	return $msg;

}

// 메일내용 생성
function send_mailsms($type, $re_info, $order_info="")
{

	global $DOCUMENT_ROOT,$shop_info,$connect;

	// 관리자 정보 가져오기
	include_once "$DOCUMENT_ROOT/inc/shop_info.inc";

	if(!isset($re_info['name'])) $re_info['name'] = "";
	if(!isset($re_info['email'])) $re_info['email'] = "";
	if(!isset($re_info['hphone'])) $re_info['hphone'] = "";
	
	if(!isset($shop_info)) $shop_info = new stdClass();
	if(!isset($shop_info->shop_name)) $shop_info->shop_name = "";
	if(!isset($shop_info->shop_email)) $shop_info->shop_email = "";
	if(!isset($shop_info->shop_tel)) $shop_info->shop_tel = "";
	if(!isset($shop_info->shop_hand)) $shop_info->shop_hand = "";

	$se_name = $shop_info->shop_name;
	$se_email = $shop_info->shop_email;
	$se_tel = $shop_info->shop_tel;
	$se_hand = $shop_info->shop_hand;

	// 메일/sms 발송내용 가져오기
	$sql = "select * from wiz_mailsms where code = '$type'";
	$result = mysqli_query($connect, $sql) or error(mysqli_error($connect));
	$row = mysqli_fetch_object($result);


	$row->email_subj = info_replace($shop_info, $re_info, $order_info, $row->email_subj);
	$row->email_msg = info_replace($shop_info, $re_info, $order_info, $row->email_msg);
	$row->sms_msg = info_replace($shop_info, $re_info, $order_info, $row->sms_msg);

	if($row->sms_cust == "Y"){
		send_sms($se_tel, $re_info['hphone'], $row->sms_msg, $se_name);
	}
	if($row->sms_oper == "Y"){
		send_sms($se_tel, $se_hand, $row->sms_msg, $se_name);
	}
	if($row->email_cust == "Y"){
		send_mail($se_name, $se_email, $re_info['name'], $re_info['email'], $row->email_subj, $row->email_msg);
	}
	if($row->email_oper == "Y"){
		send_mail($se_name, $se_email, $se_name, $se_email, $row->email_subj, $row->email_msg);
	}

}

// 파일 확장자 체크
function file_check($filename, $file_str = "php|htm|html|inc|htm|shtm|ztx|dot|cgi|pl|phtm|ph|exe"){

	$fnames = explode(".", $filename);
	$fext = $fnames[count($fnames)-1];
	$fext = strtolower($fext);
	$file_str = strtolower($file_str);

	//업로드 금지 확장자 체크
	if(preg_replace('/'.$file_str.'/i', $fext)) {
		error("해당 파일은 업로드할 수 없는 형식입니다.");
		exit;
	}

}

/******************************************************************
*			 WizShop basket : SESSION -> DATABASE 변경
******************************************************************/

//쿠키 생성
function makeCookie( $name, $valuename, $exptime = "" ) {
	if(empty($exptime) || !$exptime){
		setcookie("$name","$valuename","0","/");
	}else{
		setcookie("$name","$valuename","$exptime","/");
	}
}

//장바구니를 위한 유니크 쿠키생성
function makeBasketCookie(){
	//쿠키가 생성되어있지 않으면 고유값 생성해서 만들기
	if(empty($_COOKIE["uniq_id"])){
		if(!isset($REMOTE_ADDR)) $REMOTE_ADDR = "";//"http://scrap.xn--9n3bo0el5b.com";
		$basketid = md5($REMOTE_ADDR.time());
		makeCookie("uniq_id",$basketid);
		return $basketid;
	}
}

makebasketCookie();

/******************************************************************
*			 결제연동에 필요한 함수들
******************************************************************/
//////////////////////////////////////////////////////////////////////
//order_ok 에 들어가는 함수
//(선행 $orderid ,  $resmsg = pg (order_update.php)에서 넘어온값들
//////////////////////////////////////////////////////////////////////

function Pay_result($pgname,$rescode){
		// rescode = 0000 이 성공메세지
		global $orderid,$resmsg;
		switch($pgname){
			case "DACOM":
				if($rescode == "0000" || $rescode == "C000"){
					$rescode = "0000";
				}else{
					$rescode = $rescode;
				}
				break;
			case "KCP":
				if($rescode == "0000"){
					$rescode = "0000";
				}else{
					$rescode = $rescode;
				}
				break;
			case "ALLTHEGATE":
				if($rescode == "y"){
					$rescode = "0000";
				}else{
					$rescode = $rescode;
				}
				break;
			case "INICIS":
				if($rescode == "00"){
					$rescode = "0000";
				}else{
					$rescode = $rescode;
				}
				break;
		}
		$presult['orderid'] = $orderid;
		$presult[resmsg] = $resmsg;
		$presult[rescode] = $rescode;

		return $presult;

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   /shop/order_pay.php 에 사용되는것 , 결제방법,결제사를 파일 인쿠르드 ($oper_info , $pay_method , $order_info 선행)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Inc_payment($pay_method,$pay_agent){
	// 무통장 입금
		if($pay_method == "PB"){
		  return "pay_account.inc";
	// 신용카드결제,계좌이체,휴대폰..
		}else{
		  if($pay_agent == "DACOM"){
		    return "./dacom/pay_form.php";
		  }elseif($pay_agent == "KCP"){
  			 return "./kcp/pay_form.php";
		  }elseif($pay_agent == "ALLTHEGATE"){
		    return "./allthegate/pay_form.php";
		  }elseif($pay_agent == "INICIS"){
		    return "./INICIS/pay_form.php";
		  }
	  }

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//주문후 자신의 임시 장바구니 삭제
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_delbasket(){
	global $connect;
	@mysqli_query($connect, "DELETE FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'");
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//적립금 처리 (선행 인클루드 $order_info 주문정보배열)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_reserve(){
	global $connect;
	Global $order_info;
	if($order_info->reserve_use > 0){
				$reserve_msg = "상품구입시 사용함";
				$sql = "INSERT INTO wiz_reserve(idx,memid,reservemsg,reserve,orderid,wdate) VALUES('', '$order_info->send_id', '$reserve_msg', -$order_info->reserve_use, '$order_info->orderid', now())";
				mysqli_query($connect, $sql) or die(mysqli_error($connect));

	}
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//재고관리 (선행 $order_info 주문정보배열)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_stock(){
	global $connect;
	Global $order_info;

	$j_sql = "SELECT wb.optcode, wb.prdcode, wb.amount, wp.optcode as p_optcode, wp.optcode2 as p_optcode2, wp.optvalue, wp.opt_use, wp.shortage
						FROM wiz_basket wb, wiz_product wp
						WHERE wb.orderid = '$order_info->orderid' AND wb.prdcode = wp.prdcode";
	$j_result = mysqli_query($connect, $j_sql) or die(mysqli_error($connect));
	while($row = mysqli_fetch_object($j_result)){
		// 옵션별 재고관리 없는 제품이라면 전체재고 감소
		if(strcmp($row->opt_use, "Y")){

			if(!strcmp($row->shortage, "S")) {
				$sql = "UPDATE wiz_product SET stock = stock - $row->amount , ordercnt = ordercnt + $row->amount WHERE prdcode = '$row->prdcode'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			} else {
				$sql = "UPDATE wiz_product SET ordercnt = ordercnt + $row->amount WHERE prdcode = '$row->prdcode'";
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			}

			// 옵션별 재고관리 상품
		}else{

			$opt_list_app = "";

			if(!empty($tmp_prdcode[$row->prdcode])) $row->optvalue = $tmp_prdcode[$row->prdcode];

			$opt1_arr = explode("^", $row->p_optcode);
			$opt2_arr = explode("^", $row->p_optcode2);
			$opt_tmp = explode("^^", $row->optvalue);

			$opt1_cnt = count($opt1_arr) - 1;
			$opt2_cnt = count($opt2_arr) - 1;

			if($opt1_cnt < 1) $opt1_cnt = 1;
			if($opt2_cnt < 1) $opt2_cnt = 1;

			$no = 0;
			for($ii = 0; $ii < $opt1_cnt; $ii++) {
				for($jj = 0; $jj < $opt2_cnt; $jj++) {

					list($price, $reserve, $stock) = explode("^", $opt_tmp[$no]);

					$optcode = $opt1_arr[$ii];
					if(!empty($opt1_arr[$ii]) && !empty($opt2_arr[$jj])) $optcode .= "/";
					$optcode .= $opt2_arr[$jj];

					if(!strcmp($row->optcode, $optcode)) {
						$stock = $stock - $row->amount;
						$opt_list_app .= $price."^".$reserve."^".$stock."^^";
					} else {
						$opt_list_app .= $opt_tmp[$no]."^^";
					}

					$no++;

				}
			}

			$sql = "UPDATE wiz_product SET optvalue = '$opt_list_app', ordercnt = ordercnt + $row->amount WHERE prdcode = '$row->prdcode'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$tmp_prdcode[$row->prdcode] = $opt_list_app;

		}
	}

}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//결제처리(상태변경,주문 업데이트)
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Exe_payment($payment){

	global $connect;
	global $oper_info;

	/*변수 초기화 시작*/
	$status				=	"";
	$orderid			=	"";
	$financecode	=	"";
	$accountnumber	=	"";
	$tno					=	"";
	$esw_check		=	"";
	$esw_stats		=	"";
	$totalprice		=	"";
	$paymentkind	=	"";
	$pay_method		=	"";
	$other_update	=	"";
	$accountname	=	"";
	$cancelmsg 		= "";
	/*변수 초기화 끝*/
	$status			=	$payment['status'];		// 주문상태
	$orderid			=	$payment['orderid'];	// 주문번호
	$financecode	=	$payment[bankkind];	// 은행코드
	$accountnumber	=	$payment[accountno];	// 계좌번호
	$accountname	=	$payment[accountname];	// 예금주
	$tno				=	$payment[ttno];		//	승인번호,TID번호,처리번호
	$es_check		=	$payment[es_check];	//에스크로 사용여부
	$es_stats		=	$payment[es_stats];	//에스크로 상태
	$tprice			=	$payment[tprice];		//결제금액
	$paymentkind	=	$payment[pgname];		//pg사 종류
	$pay_method		=	$payment[paymethod];	//wizshop 결제방법
	$other_update	=	$payment[otherupdate];	//기타 wiz_order 을 update 하는방법
	$cancelmsg		= $payment[cancelmsg];	// 주문취소 사유

	@make_log("dacom_log.txt","--START".date("Y/m/d H:i:s",time())."---------\r".$status."\r".$orderid."\r".$financecode."\r".$accountnumber."\r".$tno."\r".$esw_check."\r".$esw_stats."\r".$totalprice."\r".$paymentkind."\r".$pay_method."\r");

	// 주문정보
	$sql = "SELECT * FROM wiz_order WHERE orderid='$orderid'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$order_info = mysqli_fetch_object($result);

	if($pay_method == "PV"||$pay_method=="PN"||$pay_method=="PB"){
		//$status = "OR";// 가상계좌인경우 입금이 안되었으므로 주문접수

		if($paymentkind=='allthegate'){//올더게이트 은행 코드
			switch($financecode){
				case "03":$financecode = "기업은행";break;
				case "04":$financecode = "국민은행";break;
				case "05":$financecode = "외환은행";break;
				case "06":$financecode = "국민은행(구 주택은행)";break;
				case "07":$financecode = "수협중앙회";break;
				case "11":$financecode = "농협중앙회";break;
				case "12":$financecode = "단위농협";break;
				case "20":$financecode = "우리은행";break;
				case "21":$financecode = "조흥은행";break;
				case "23":$financecode = "제일은행";break;
				case "32":$financecode = "부산은행";break;
				case "71":$financecode = "우체국";break;
				case "81":$financecode = "하나은행";break;
				case "88":$financecode = "신한은행";break;
				default:$financecode = $financecode;
			}
		}else if($paymentkind=='dacom'){//데이콤 은행 코드
			switch($financecode){
				case "02":$financecode = "산업은행";break;
				case "03":$financecode = "기업은행";break;
				case "05":$financecode = "외환은행";break;
				case "06":$financecode = "국민은행";break;
				case "07":$financecode = "수협";break;
				case "11":$financecode = "농협";break;
				case "20":$financecode = "우리은행";break;
				case "23":$financecode = "제일은행";break;
				case "26":$financecode = "신한은행";break;
				case "27":$financecode = "씨티은행";break;
				case "31":$financecode = "대구은행";break;
				case "32":$financecode = "부산은행";break;
				case "34":$financecode = "광주은행";break;
				case "35":$financecode = "제주은행";break;
				case "37":$financecode = "전북은행";break;
				case "39":$financecode = "경남은행";break;
				case "45":$financecode = "새마을금고";break;
				case "48":$financecode = "신협";break;
				case "71":$financecode = "우체국";break;
				case "81":$financecode = "하나은행";break;
				case "88":$financecode = "신한은행";break;
				default:$financecode = $financecode;
			}
		}else if($paymentkind=='inicis'){//이니시스 은행코드
			switch($financecode){
					case "03":$financecode = "기업은행";break;
					case "04":$financecode = "국민은행";break;
					case "05":$financecode = "외환은행";break;
					case "06":$financecode = "국민은행(구 주택은행)";break;
					case "07":$financecode = "수협중앙회";break;
					case "11":$financecode = "농협중앙회";break;
					case "12":$financecode = "단위농협";break;
					case "20":$financecode = "우리은행";break;
					case "21":$financecode = "조흥은행";break;
					case "23":$financecode = "제일은행";break;
					case "32":$financecode = "부산은행";break;
					case "71":$financecode = "우체국";break;
					case "81":$financecode = "하나은행";break;
					case "88":$financecode = "신한은행";break;
				}
		}else{
			if($financecode == "05") $financecode = "외환은행";
			else if($financecode == "06") $financecode = "국민은행";
			else if($financecode == "11") $financecode = "농협은행";
			else if($financecode == "26") $financecode = "신한은행";
			else if($financecode == "81") $financecode = "하나은행";
			else if($financecode == "20") $financecode = "우리은행";
			else $financecode = $financecode;
		}
		if($accountname){$accountname = " , account_name='$accountname'";}
		$pv_account = ", account='".$financecode." ".$accountnumber."'".$accountname;


	}
	if(trim($tno)){//승인번호,TID값
			$tno=" , tno='$tno' ";
	}else{
			$tno="";
	}
	if($status == "OY") {
		$oper_time = ", pay_date = now()";
		// 세금계산서 업데이트
		if(!strcmp($oper_info->tax_use, "Y")) {
			$sql = "update wiz_tax set tax_date = now() where orderid = '$orderid'";
			mysqli_query($connect, $sql) or die(mysqli_error($connect));
		}
	}
	// 에스크로 체크한경우 에스크로 상태 업데이트
	//if($tprice>=100000){
	if($oper_info->pay_escrow == "Y") {
		if($pay_method == "PV" || $pay_method == "PN" || $pay_method == "PC"){$escrow_=",escrow_check='$es_check',escrow_stats='$es_stats'";}
	}

	if(!empty($cancelmsg)) $cancel_sql = ", cancelmsg = '$cancelmsg' ";

	// 쿠폰
	if($order_info->coupon_idx != "") {

		$today = date('Y-m-d');
		$coupon_list = explode("|", $order_info->coupon_idx);
		if(is_array($coupon_list)) {
			foreach($coupon_list as $c_idx => $cidx) {

				$c_sql = "select * from wiz_mycoupon where idx='".$cidx."'";
				$c_result = mysqli_query($connect, $c_sql) or error(mysqli_error($connect));
				$c_row = mysqli_fetch_array($c_result);

				if($c_row[coupon_use] == "N" && $c_row[coupon_sdate] <= $today && $c_row[coupon_edate] >= $today) {
					$sql = "update wiz_mycoupon set coupon_use = 'Y' where idx = '".$cidx."'";
					mysqli_query($connect, $sql) or die(mysqli_error($connect));
				} else {
					// 미사용, 기간내의 쿠폰이 아닌 경우 주문 내역 업데이트
				}
			}
		}

	}

	// 주문상태 변경
	$sql = "UPDATE wiz_order SET status='$status' $tno $pv_account $oper_time $escrow_ $other_update $cancel_sql WHERE orderid='$orderid'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	@make_log("dacom_log.txt","\r".$sql."\r---------------------------End----------------------------------\r");
}


//임시 로그파일 생성하기
function make_log($file, $noti) {
		$fp = fopen($file, "a+");
		ob_start();
		print_r($noti);
		$msg = ob_get_contents();
		ob_end_clean();
		fwrite($fp, $msg);
		fclose($fp);
}

// 영수증 출력
function receipt_link($oper_info, $ord_info) {

	$card_icon = "카드영수증";
	$cash_icon = "현금영수증";

	// 데이콤
	if(!strcmp($oper_info->pay_agent, "DACOM")) {

		if(!strcmp($oper_info->pay_test, "Y")) {//테스트
			$oper_info->pay_id = "tanywiz";
			$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
			$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}else{//실거래
			$platform	= "service";
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}


		/////////////////
		//결제방법 출력//
		/////////////////
		switch($ord_info->pay_method){
			case "PC"://신용카드
				$pay_method = "SC0010";break;
			case "PN"://계좌이체
				$pay_method = "SC0030";break;
			case "PV"://가상계좌
				$pay_method = "SC0040";break;
			case "PH";//휴대폰
				$pay_method = "SC0060";break;
		}

		echo "<script language='JavaScript' src='/shop/dacom/receipt_link.js'></script>";

		// 신용카드
		if(!strcmp($ord_info->pay_method, "PC")) {

			$authdata = md5($mid.$ord_info->tno.$pay_key);
			echo "<a href=\"javascript:showReceiptByTID('".$mid."', '".$ord_info->tno."', '".$authdata."','".$platform."')\">".$card_icon."</a>";

		// 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		} else if(!strcmp($ord_info->pay_method, "PN") || !strcmp($ord_info->pay_method, "PV") || !strcmp($ord_info->pay_method, "PB")) {

			switch($ord_info->pay_method) {
				case "PN" : $pay_method = "BANK"; break;
				case "PV" : $pay_method = "CAS"; break;
				case "PB" : $pay_method = "CR"; break;
			}

			echo "<a href=\"javascript:showCashReceipts('".$mid."','".$ord_info->orderid."','','".$pay_method."','".$platform."')\">".$cash_icon."</a>";

		// 무통장입금
		} else {

			$authdata = md5($mid.$ord_info->orderid.$pay_method.$pay_key);

			echo "<a href=\"javascript:showReceiptByOID('".$mid."','".$ord_info->orderid."','".$pay_method."','".$authdata."','".$platform."')\">aaa</a>";

		}

	// KCP
	} else if(!strcmp($oper_info->pay_agent, "KCP")) {

		if(!strcmp($oper_info->pay_test, "Y")) {//테스트
			$oper_info->pay_id = "tanywiz";
			$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
			$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}else{//실거래
			$platform	= "service";
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
		}

		// 신용카드
		if(!strcmp($ord_info->pay_method, "PC")) {
?>
			<script language="JavaScript">
			<!--
			function receipt() {
				<? if(empty($ord_info->tno)) { ?>
					alert("KCP거래번호가 없습니다.");
				<? } else { ?>
					var url = "http://admin.kcp.co.kr/Modules/Sale/Card/ADSA_CARD_BILL_Receipt.jsp?c_trade_no="+tno;
					window.open(url, "Receipt", "height=670, width=420, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
				<? } ?>
			}
			-->
			</script>
			<a href="javascript:receipt('<?=$ord_info->tno?>')"><?=$card_icon?></a>
<?php
		// 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		} else if(!strcmp($ord_info->pay_method, "PN") || !strcmp($ord_info->pay_method, "PV") || !strcmp($ord_info->pay_method, "PB")) {
			$site_cd = "	PGNW".$oper_info->pay_id;
?>
			<script language="JavaScript">
			<!--
			function receiptCash() {
				var url = "https://admin.kcp.co.kr/Modules/Service/Cash/Cash_Bill_Common_View.jsp?term_id=<?=$site_cd?>&orderid=<?=$ord_info->orderid?>&id_info=<?=$ord_info->id_info?>&bill_yn=<?=$ord_info->bill_yn?>&authno=<?=$ord_info->authno?>";
				window.open(url, "Receipt", "height=670, width=420, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
			}
			-->
			</script>
			<a href="javascript:receiptCash()"><?=$cash_icon?></a>
<?php
		// 무통장입금
		} else {

		}

	// ALL THE GATE
	} else if(!strcmp($oper_info->pay_agent, "ALLTHEGATE")) {

		if(!strcmp($oper_info->pay_test, "Y")) {
			$oper_info->pay_id = "aegis";
			$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
		}

		// 신용카드
		if(!strcmp($ord_info->pay_method, "PC")) {
?>
<script language=javascript>
<!--
function show_receipt(approve, send_no, send_dt)
{
	if("<?=$ord_info->status?>"!= "" && "<?=$ord_info->pay_method?>"=="PC")
	{
		if(parseInt(send_dt) < parseInt("<?=date('Ymd')?>")) {

			window.open("http://www.allthegate.com/support/card_search.html", "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=yes,resizable=no,width=630,height=510,top=0,left=150");

		} else {
			var sRetailer_id = "<?=$oper_info->pay_id?>";

			url="http://www.allthegate.com/customer/receiptLast3.jsp"
			url=url+"?sRetailer_id="+sRetailer_id;	// 상점아이디
			url=url+"&approve="+approve;						// 승인번호
			url=url+"&send_no="+send_no;						// 거래고유번호
			url=url+"&send_dt="+send_dt.substring(0,8);		// 승인시각

			window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=420,height=700,top=0,left=150");
		}
	}
	else
	{
		alert("해당하는 결제내역이 없습니다");
	}
}
-->
</script>
			<a href="javascript:show_receipt('<?=$ord_info->tno?>', '<?=$ord_info->orderid?>', '<?=substr(str_replace("-", "", $ord_info->pay_date), 0, 8)?>')"><?=$card_icon?></a>
<?php
		// 계좌이체, 가상계좌, 무통장입금(상점관리 > 결제내역조회 > 현금영수증에서 직접 등록) 현금영수증
		} else if(!strcmp($ord_info->pay_method, "PN") || !strcmp($ord_info->pay_method, "PV") || !strcmp($ord_info->pay_method, "PB")) {

		// 무통장입금
		} else {

		}

	}

}

// 자동등록방지코드 생성
function get_spam_check() {

	global $is_norobot;
	global $norobot_img;
	global $norobot_msg;
	global $norobot_key;
	global $spam_check;
	
	$is_norobot = false;

	$tmp_str = substr(md5(rand()),0,12); // 임의의 md5 문자열을 생성

	list($usec, $sec) = explode(' ', microtime()); // 난수 발생기
	$seed =  (float)$sec + ((float)$usec * 100000);
	srand($seed);
	$keylen = strlen($tmp_str);
	$div = (int)($keylen / 2);
	if(!isset($arr)) $arr=array("");
	while (count($arr) < 6)
	{
	    unset($arr);
	    for ($i=0; $i<$keylen; $i++)
	    {
	        $rnd = rand(1, $keylen);
	        $arr[$rnd] = $rnd;
	        if ($rnd > $div) break;
	    }
	}

	sort($arr);	// 배열에 저장된 숫자를 차례대로 정렬

	$norobot_key = "";
	$norobot_str = "";
	$m = 0;

	for ($i=0; $i<count($arr); $i++)
	{
	    for ($k=$m; $k<$arr[$i]-1; $k++)
	        $norobot_str .= $tmp_str[$k];
	    $norobot_str .= "<font size=3 color=#FF0000><b>{$tmp_str[$k]}</b></font>";
	    $norobot_key .= $tmp_str[$k];
	    $m = $k + 1;

	}

	if ($m < $keylen) {
	    for ($k=$m; $k<$keylen; $k++)
	        $norobot_str .= $tmp_str[$k];
	}

	$norobot_str = "<font color=#999999>$norobot_str</font>";

	$ss_norobot_key = $norobot_key;
	$is_norobot = true;

	if (function_exists("imagecreate")) {	// 이미지 생성이 가능한 경우 자동등록체크코드를 이미지로 생성
	  $norobot_img = "<img src='/bbs/norobot_image.php?ss_norobot_key=$norobot_key' border='0' style='border: 1px solid #343d4f;' align='absmiddle'>";
	  $norobot_msg = "* 왼쪽의 자동등록방지 코드를 입력하세요.";
	}
	else {
	 $norobot_img = $norobot_str;
	 $norobot_msg = "* 왼쪽의 글자중 <FONT COLOR='red'>빨간글자</font>만 순서대로 입력하세요.";
	}
	$spam_check = $norobot_img." <input type='text' name='vcode' class='input' style='height:26px; vertical-align:middle;' /> ".$norobot_msg;
	?>
	<script Language="JavaScript" src="/js/md5.js"></script>
	<script language="javascript">
	<!--

	function hex_md5(s) {
		return binl2hex(core_md5(str2binl(s), s.length * chrsz));
	}
	var md5_norobot_key = "<?=md5($norobot_key)?>";

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, "");
	}

	//-->
	</script>
<?php
}

// 디렉토리 삭제
function nRmDir($path) {
	$oDir = openDir($path);
	while($entry = readDir($oDir)) {
		if($entry <> '.' && $entry <> '..') {
			if(Is_Dir($path.'/'.$entry)) {
				nRmDir ($path.'/'.$entry);
			} else {
				@UnLink ($path.'/'.$entry);
			}
		}
	}
	@closeDir($oDir);
	@RmDir($path);
}

// 배송정보 전송
function escrow_delivery($order_info, $oper_info, $delsno="", $deldate="") {

	if(!strcmp($order_info->escrow_check, "Y") && strcmp($order_info->escrow_stats, "DE")) {

		if(!strcmp($oper_info->pay_agent, "DACOM")) {

			$oid = $order_info->orderid;							// 주문번호
			$productid = $order_info->orderid;				// 상품ID
			$dlvtype = "03";													// 등록내용구분
			$dlvdate = $deldate;											// 발송일자

			// 배송회사코드
			switch($oper_info->del_com) {
				case "대한통운" : $dlvcompcode = "KE"; break;
				case "로젠택배" : $dlvcompcode = "LG"; break;
				case "아주택배" : $dlvcompcode = "AJ"; break;
				case "엘로우캡" : $dlvcompcode = "YC"; break;
				case "우체국택배" : $dlvcompcode = "PO"; break;
				case "이젠택배" : $dlvcompcode = "EZ"; break;
				case "트라넷" : $dlvcompcode = "TN"; break;
				case "한진택배" : $dlvcompcode = "HJ"; break;
				case "현대택배" : $dlvcompcode = "HD"; break;
				case "훼미리택배" : $dlvcompcode = "FE"; break;
				case "Bell Express" : $dlvcompcode = "BE"; break;
				case "CJ GLS" : $dlvcompcode = "CJ"; break;
				case "HTH" : $dlvcompcode = "SS"; break;
				case "KGB택배" : $dlvcompcode = "KB"; break;
				case "KT로지스택배" : $dlvcompcode = "KT"; break;
			}

			$dlvcomp = $oper_info->del_com;					// 배송회사명
			$dlvno = $delsno;												// 운송장번호

			if(!empty($dlvdate) && !empty($dlvno)) {
				echo "<script>window.open('/shop/dacom/escrow_delivery.php?oid=$oid&productid=$productid&dlvtype=$dlvtype&dlvdate=$dlvdate&dlvcompcode=$dlvcompcode&dlvcomp=$dlvcomp&dlvno=$dlvno', 'Delivery', 'width=250px,height=220px,scrollbars=yes');</script>";
			}

		} else if(!strcmp($oper_info->pay_agent, "KCP")) {

	    // 테스트 체크
			if(!strcmp($oper_info->pay_test, "Y")) {
				$oper_info->pay_id = "T0007";
				$oper_info->pay_key = "3CRB7XHFjUp6fjf1FLEM.g6__";
			}

			if(!strcmp($oper_info->pay_test, "Y")) {
				$payplus = "testpaygw.kcp.co.kr";
			} else {
				$payplus = "paygw.kcp.co.kr";
			}

	    /* ============================================================================== */
	    /* =   01. 지불 데이터 셋업 (업체에 맞게 수정)                                  = */
	    /* = -------------------------------------------------------------------------- = */
	    $g_conf_home_dir    = $_SERVER[DOCUMENT_ROOT]."/shop/kcp/payplus";   // BIN 절대경로 입력
	    $g_conf_log_level   = "3";                                  // 변경불가
	    $g_conf_pa_url  = $payplus;																	// real url : paygw.kcp.co.kr , test url : testpaygw.kcp.co.kr
	    $g_conf_pa_port = "8090";                                   // 포트번호 , 변경불가
	    $g_conf_mode    = 0;                                        // 변경불가

	    require $_SERVER[DOCUMENT_ROOT]."/shop/kcp/pp_ax_hub_lib.php";				// library [수정불가]
	    /* ============================================================================== */

	    /* ============================================================================== */
	    /* =   02. 지불 요청 정보 설정                                                  = */
	    /* = -------------------------------------------------------------------------- = */
	    $site_cd        = $oper_info->pay_id; // 사이트 코드
	    $site_key       = $oper_info->pay_key; // 사이트 키
	    $req_tx         = "mod_escrow"; // 요청 종류
	    $cust_ip        = getenv( "REMOTE_ADDR"    ); // 요청 IP
	    $ordr_idxx      = $order_info->orderid; // 쇼핑몰 주문번호
	    /* = -------------------------------------------------------------------------- = */
	    $tran_cd        = "00200000"; 							// 처리 종류
	    /* = -------------------------------------------------------------------------- = */
	    $res_cd         = "";                         // 응답코드
	    $res_msg        = "";                         // 응답메시지
	    $tno            = $order_info->tno; 					// KCP 거래 고유 번호
	    /* ============================================================================== */

	    $mod_type				= "STE1"; 										// 변경상태 : 배송시작

	    /* ============================================================================== */
	    /* =   03. 인스턴스 생성 및 초기화                                              = */
	    /* = -------------------------------------------------------------------------- = */
	    /* =       결제에 필요한 인스턴스를 생성하고 초기화 합니다.                     = */
	    /* = -------------------------------------------------------------------------- = */
	    $c_PayPlus = new C_PP_CLI;

	    $c_PayPlus->mf_clear();

      $c_PayPlus->mf_set_modx_data( "tno",        $order_info->tno	);          // KCP 원거래 거래번호
      $c_PayPlus->mf_set_modx_data( "mod_type",   $mod_type       	);          // 원거래 변경 요청 종류
      $c_PayPlus->mf_set_modx_data( "mod_ip",     $cust_ip        	);          // 변경 요청자 IP
      $c_PayPlus->mf_set_modx_data( "mod_desc",   $mod_desc       	);          // 변경 사유
      if ($mod_type == "STE1")                                                	// 상태변경 타입이 [배송요청]인 경우
      {
          $c_PayPlus->mf_set_modx_data( "deli_numb",   $delsno 						 );          // 운송장 번호
          $c_PayPlus->mf_set_modx_data( "deli_corp",   $oper_info->del_com );          // 택배 업체명
      }
      else if ($mod_type == "STE2" || $mod_type == "STE4")                    // 상태변경 타입이 [즉시취소] 또는 [취소]인 계좌이체, 가상계좌의 경우
      {
          if ($acnt_yn == "Y")
          {
              $c_PayPlus->mf_set_modx_data( "refund_account",   $_POST[ "refund_account" ] );      // 환불수취계좌번호
              $c_PayPlus->mf_set_modx_data( "refund_nm",        $_POST[ "refund_nm"      ] );      // 환불수취계좌주명
              $c_PayPlus->mf_set_modx_data( "bank_code",        $_POST[ "bank_code"      ] );      // 환불수취은행코드
          }
      }

	    /* = -------------------------------------------------------------------------- = */
	    /* =   04-4. 실행                                                               = */
	    /* = -------------------------------------------------------------------------- = */
	    if ( $tran_cd != "" )
	    {
	        $c_PayPlus->mf_do_tx( $trace_no, $g_conf_home_dir, $site_cd, $site_key, $tran_cd, "",
	                              $g_conf_pa_url, $g_conf_pa_port, "payplus_cli_slib", $ordr_idxx,
	                              $cust_ip, $g_conf_log_level, 0, $g_conf_mode );
	    }
	    else
	    {
	        $c_PayPlus->m_res_cd  = "9562";
	        $c_PayPlus->m_res_msg = "연동 오류 TRAN_CD[" . $tran_cd . "]";
	    }

	    $res_cd  = $c_PayPlus->m_res_cd;  // 결과 코드
	    $res_msg = $c_PayPlus->m_res_msg; // 결과 메시지
	    /* ============================================================================== */

		} else if(!strcmp($oper_info->pay_agent, "INICIS")) {

			if(!strcmp($oper_info->pay_test, "Y")) {
				$oper_info->pay_id = "tanywiz";
				$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
			}
			/**********************************************************************
			 *  FILE NAME : escrow.php                                            *
			 *  AUTHOR : ts@inicis.com                                            *
			 *  DATE : 2003/12 (Payment Team 대리 이종완)                         *
			 *                                                                    *
			 * 하나은행 에스크로용 배송 및 반품 요청 및 결과 페이지               *
			 **********************************************************************/

			/*########################
			            라이브러리 호출
			########################*/

			require($_SERVER['DOCUMENT_ROOT']."/shop/INICIS/EscrowLib.php");

			/*########################
			            인스턴스 생성
			########################*/

			$escrow = new Escrow;

			/*########################################
			                  배송/반품 공통 정보 설정
			########################################*/

			$escrow->inipayhome	= $_SERVER['DOCUMENT_ROOT']."/shop/INICIS/";	// 이니페이 지불시스템 설치 절대 경로(반드시 절대 경로로 입력하시기 바랍니다.)
			$escrow->mid				= $oper_info->pay_id;		// 상점 아이디
			$escrow->EscrowType	= "dr";									// 에스크로 타입
			$escrow->hanatid		= $order_info->tno;			// 하나은행 거래 아이디
			$escrow->invno 			= $delsno;							// 운송장 번호
			$escrow->adminID 		= $wiz_admin['id'];				// 등록자 ID
			$escrow->adminName 	= $wiz_admin['name'];			// 등록자 성명
			$escrow->regdate 		= date("Ymd");					// 등록요청 일자
			$escrow->regtime 		= date("His");					// 등록요청 시간

			/*#################################
			                 배송관련 정보 설정
			#################################*/

			switch($oper_info->del_com) {
				case "CJ GLS" 			: $compID = "CJGLSLOGIS"; break;
				case "현대택배" 		: $compID = "HYUNDAIEXP"; break;
				case "한진택배" 		: $compID = "HANJINLOGI"; break;
				case "대한통운" 		: $compID = "KOREXEXPR"; break;
				case "KGB" 					: $compID = "KGBLOGISTI"; break;
				case "로젠택배" 		: $compID = "LOGENEXPRE"; break;
				case "우체국택배" 	: $compID = "EPOSTEXPRE"; break;
				case "HTH" 					: $compID = "SMHTHEXPRE"; break;
				case "훼미리택배" 	: $compID = "FAMILEXPRE"; break;
				case "건영택배" 		: $compID = "KUNYONGEXP"; break;
				case "아주택배" 		: $compID = "AJULOGIEXP"; break;
				case "Bell Express" : $compID = "BELLEXPRES"; break;
				case "옐로우캡" 		: $compID = "YELLOWCAPE"; break;
				case "우리택배" 		: $compID = "WOORIGLEXP"; break;
				case "일양택배" 		: $compID = "ILYANGLOGS"; break;
				case "코덱스택배" 	: $compID = "EKODEXLOGS"; break;
				case "트라넷" 			: $compID = "ETRANETEXP"; break;
				case "ECLINE택배" 	: $compID = "ECLINELOGS"; break;
				case "KGB택배"			: $compID = "IKGBLOGEXP"; break;
				case "KT로지스택배"	: $compID = "KLSLOGISTS"; break;
				default 						: $compID = "OTHEREXPRX"; break;
			}

			$transtype = "0";

			$escrow->compName		= $oper_info->del_com;		// 배송회사명
			$escrow->compID = $compID;						// 배송회사
			$escrow->transtype = $transtype;			// 배송종류, 배송지시 일경우 - 운송 구분 (0 - 배송, 1 - 반송)
			$escrow->transport = $transport;			// 운송수단
			$escrow->transfee = $transfee;				// 배송비
			$escrow->paymeth = $paymeth;					// 배송비 지급방법
			$escrow->notice = $notice;						// 배송 주의 사항
			$escrow->transdate1 = $transdate1;		// 배송요청일자 (from)
			$escrow->transdate2 = $transdate2;		// 배송요청일자 (to)
			$escrow->cnt = "1";										// 배송요청 메세지 겟수(배송정보 등록시에만 필요함)

			$escrow->sendMsg = $escrow->mid . "\x0B" .
													$escrow->cnt . "\x0B" .    // (주의) 메세지의 겟수는 최대 10개를 넘지 않도록 합니다.
													$escrow->hanatid . "|" .
													$escrow->invno . "|" .
													$escrow->adminID . "|" .
													$escrow->adminName . "|" .
													$escrow->regdate . "|" .
													$escrow->regtime . "|" .
													$escrow->compName . "|" .
													$escrow->compID . "|" .
													$escrow->transtype . "|" .
													$escrow->transport . "|" .
													$escrow->transfee . "|" .
													$escrow->paymeth . "|" .
													$escrow->notice . "|" .
													$escrow->transdate1 . "|" .
													$escrow->transdate2;         // (주의) 1개이상의 메세지를 전달할때는 반드시 "\x0B"(ctrl+k)로 구분하도록 하시기 바랍니다.

				/*##############################
				        배송정보/반품정보 요청
				##############################*/

				$escrow->startAction();

				/*############################################
				                    배송정보/반품정보 결과
				##############################################
				# * 결과내용 : $escrow->resultMsg            #
				# * 결과코드 : $escrow->resultCode           #
				#              (0000 이면 성공)              #
				#                                            #
				############################################*/

/*
				echo $escrow->resultMsg."<br>";
				echo $escrow->resultCode."<br>";
				exit;
*/
		}

	}
}

// 주문상세 페이지 주문취소 버튼
function get_cancel_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $cancel_btn;

?>

	<script language="JavaScript">
	<!--

	// 주문취소
	function orderCancel(orderid)
	{
		<? if($order_info->status == "OR"){ ?>
		alert("결제완료된 주문만 취소요청이 가능합니다.");
		<? }else if($order_info->status == "RD"){ ?>
		alert("이미 취소요청한 상태입니다.");
		<? }else if($order_info->status == "RC" || $order_info->status == "OC"){ ?>
		alert("최소처리가 완료된 상태입니다.");
		<? }else{ ?>
		var url = "/shop/order_cancel.php?orderid=" + orderid;
	  window.open(url, "orderCancel", "height=360, width=500, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no, left=300, top=300");
		<? } ?>
	}

	// 주문취소 해제
	function orderRemoval(orderid)
	{
		if(confirm("주문취소를 해제하시겠습니까?")){
			document.location='order_status.php?orderid=' + orderid;
		}
	}

	//-->
	</script>

<?php

	$cancel_btn = "<a href=\"javascript:orderCancel('".$orderid."');\">주문 취소 요청</a>";
}

// 주문상세 페이지의 에스크로 버튼
function get_escrow_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $escrow_btn;

	if(!strcmp($oper_info->pay_test, "Y")) {//테스트

		if($oper_info->pay_agent == "DACOM") {//데이콤 버튼
			$oper_info->pay_id = "tanywiz";
			$oper_info->pay_key = "6f51f77a2b2222d642e20e445101a35f";
			$platform	= "test";             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
			$mid = $oper_info->pay_id;
			$pay_key = $oper_info->pay_key;
			$tport = ":7085";
			$_htt = "";
		} else if($oper_info->pay_agent == "KCP") {//KCP버튼
			$oper_info->pay_id = "T0007";
			$oper_info->pay_key = "3CRB7XHFjUp6fjf1FLEM.g6__";
		}

	}else{//실거래
		$platform	= "service";
		$mid = $oper_info->pay_id;
		$pay_key = $oper_info->pay_key;
		$tport = "";
		$_htt = "s";
	}

	if(!strcmp($oper_info->pay_agent, "DACOM")) {
?>
	<SCRIPT language=JavaScript src="http://pgweb.dacom.net<?=$tport?>/js/DACOMEscrow.js"></SCRIPT>
<?
	}else if($oper_info[pay_agent] == "KCP") {
?>
<?php
	}
?>
	<script language="JavaScript">
	<!--

	// 에스크로 수령확인 Dacom
	function linkESCROW(mid,oid)
	{
	   checkDacomESC ("<?=$mid?>", oid,'');
	   location.reload();
	}



	//-->
	</script>

<?php

	///////////////////////////////////////////
	//에스크로 사용일경우 수령확인 버튼 출력 //
	///////////////////////////////////////////
	if($oper_info->pay_escrow == "Y"){
		if($oper_info->pay_agent == "DACOM") {//데이콤 버튼
			if($order_info->escrow_stats=='IN' || $order_info->escrow_stats=='DE'){//에스크로 정보 : 수령확인 을안하였으면
				$escrow_btn = "<a href=\"javascript:linkESCROW('".$mid."', '".$orderid."');\" class='submit_btn'>수령하기</a>";
				$escrow_install_btn="<a href='http://pgdownload.uplus.co.kr/lgdacom/LGDacomXPayWizard.exe' taget='_blank'>수동설치</a>"; //수동설치 버튼 생성
			}
		} else if($oper_info->pay_agent == "KCP") {//KCP버튼
			//$escrow_btn = "<img src=\"/images/shop/but_receok.gif\" border=\"0\" onClick=\"linkESCROW_kcp('".$oper_info->pay_id."', '".$order_info->tno."', '".$orderid."')\" style=\"cursor:pointer\">";
		} else if($oper_info->pay_agent == "INICIS") {//INICIS버튼
			//$escrow_btn = "<img src=\"/images/shop/but_receok.gif\" border=\"0\" onClick=\"linkESCROW_inicis('".$order_info->tno."')\" style=\"cursor:pointer\">";
		}
	}
}

// 주문상세 페이지 세금계산서 버튼
function get_tax_btn() {

	global $order_info;
	global $oper_info;
	global $orderid;
	global $tax_btn;

?>
	<script language="JavaScript">
	<!--

	// 세금계산서 출력
	function printTax(orderid) {
	<?php
	$print_tax_check = false;
	$status = order_status($order_info->status);
	$tax_status = order_status($oper_info->tax_status);

	if(!strcmp($order_info->status, "OC") || !strcmp($order_info->status, "RD") || !strcmp($order_info->status, "RC")) {
	?>
		alert("주문취소로 세금계산서가 폐기되었습니다.");
	<?php
	} else {

		if(!strcmp($oper_info->tax_status, "OY")) {
			if(strcmp($order_info->status, "OR")) {
				$print_tax_check = true;
			}
		} else if(!strcmp($oper_info->tax_status, "DC")) {
			if(!strcmp($order_info->status, "DC") || !strcmp($order_info->status, "CC")) {
				$print_tax_check = true;
			}
		}

		if($print_tax_check) {
	?>
		var url = "/shop/print_tax.php?orderid=" + orderid;
		window.open(url, "taxPub", "height=750, width=670, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=50, top=50");
	<?php
		} else {
	?>
		alert("현재 주문상태(<?=$status?>)에서는 세금계산서를 발급할 수 없습니다. \n\n<?=$tax_status?> 이후에 세금계산서 발행이 가능합니다.");
	<?php
		}
	}
	?>

	}
	//-->
	</script>
<?php
	if(!strcmp($oper_info->tax_use, "Y")) {
		$tax_btn = "<span onClick=\"printTax('".$orderid."')\" style=\"cursor:pointer\">[세금계산서]</span>";
	}
}

// 보기페이지 이미지 리사이즈
function view_img_resize() {

	global $_ResizeCheck;

	if($_ResizeCheck) {
?>
<!-- 이미지 리사이즈를 위해서 처리하는 부분 -->
<script>
	function wiz_img_check(){
		var wiz_main_table_width = document.getElementById('wiz_get_table_width').style.width;
		wiz_main_table_width = wiz_main_table_width.replace("px", "");
		var wiz_target_resize_num = document.wiz_target_resize.length;
		for(i=0;i<wiz_target_resize_num;i++){
			if(document.wiz_target_resize[i].width > wiz_main_table_width) {
				document.wiz_target_resize[i].width = wiz_main_table_width;
			}
		}
	}
	window.onload = wiz_img_check;
</script>

<?
	}

}

//SQL 입력값 문자열 필터
//$str = 입력 문자열
function sql_filter($str) {
	//1단계 ? ',",NULL 문자 필터링. 각 문자들에 백슬래쉬(\) 삽입됨. 필수 항목
	//출력시 stripslashes()함수를 이용하여 백슬래쉬(\)를 제거
	if (!get_magic_quotes_gpc()) $str = addslashes($str);

	//3단계 ? 특수 문자 및 문자열 필터링
	//WHERE 구문에서 쓰여지는 데이터만 사용하는 것이 바람직하다.
	$search = array("--","#",";");
	$replace = array("\--","\#","\;");
	$str = str_replace($search, $replace, $str);

	return $str;
}

Function xss_check( $get_String, $get_HTML = true ) {

	// XSS (Cross Site Script) 막기
	$get_String = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $get_String);
	$get_String = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $get_String);
	$get_String = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $get_String);
	$get_String = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $get_String);
	$get_String = preg_replace("/(ex)(pression)/i", "&#101&#120;$2", $get_String);

   if(!$get_HTML) {
	  $get_String = STR_REPLACE( "<", "&lt;", $get_String );
	  $get_String = STR_REPLACE( ">", "&gt;", $get_String );
   }
   return $get_String;

}

// 문자열 변환 in_charset → out_charset
function str_conv($str, $mode) {
	if(!strcmp(strtolower($mode), 'euc-kr')) {
		$in_charset = "utf-8";
		$out_charset = "euc-kr";
	} else if(!strcmp(strtolower($mode), 'utf-8')) {
		$in_charset = "euc-kr";
		$out_charset = "utf-8";
	}

	if(iconv($out_charset,$out_charset,$str)==$str) return $str;
	else return iconv($in_charset,$out_charset,$str);
}


// 로봇 체크
function check_robots($user_agent) {
	$robots = array('1Noonbot', 'Accoona-AI-Agent', 'Allblog.net', 'Baiduspider', 'Blogbeat', 'Crawler', 'DigExt', 'DrecomBot', 'Exabot', 'FeedChecker', 'FeedFetcher', 'Gigabot', 'Googlebot', 'HMSE_Robot', 'IP*Works!', 'IRLbot', 'Jigsaw', 'LWP::Simple', 'Labrador', 'MJ12bot', 'Mirror Checking', 'Missigua Locator', 'NG/2.0', 'NaverBot', 'NutchCVS', 'PEAR HTTP_Request', 'PostFavorites', 'SBIder', 'W3C_Validator', 'WISEbot', 'Y!J-BSC', 'Yahoo! Slurp', 'ZyBorg', 'archiver', 'carleson', 'cfetch', 'compatible; Eolin', 'favicon', 'feedfinder', 'findlinks', 'genieBot', 'ichiro', 'kinjabot', 'larbin', 'lwp-trivial', 'msnbot', 'psbot', 'sogou', 'urllib/1.15', 'voyager');
	foreach($robots as $robot)
		if(strpos($user_agent, $robot) !== false)
			return false;
	return true;
}

// OS,브라우저 정보
function get_osbrowser($user_agent){

	if(strpos($user_agent, "Gecko") ){
		if(strpos($user_agent, "Netscape") ){
			$browser = "Netscape";
		}else if(strpos($user_agent, "Firefox") ){
			$browser = "Firefox";
		}else{
			$browser = "Mozilla";
		}
	}else if(strpos($user_agent, "MSIE") ){
		if (strpos($user_agent, "Opera") ){
			$browser = "Opera";
		}else if (strpos($user_agent, "MSIE or Firefox") ){
			$browser = "MSIE or Firefox";
		}else{
			$browser = "Explorer";
			$sidx = strpos($user_agent,"MSIE")+4; $eidx = strpos($user_agent,";",$sidx);
			if(strpos($user_agent, "Trident/4.0")) $browser .= " 8.0";
			else if($sidx-$eidx == 4) $browser .=  substr($user_agent,$sidx,4);
		}
	}else{
		$browser = "Etc";
	}

	if (strpos($user_agent, "Windows 95") ){
		$os = "Win95";
	}else if(strpos($user_agent, "Win98 95") || strpos($user_agent, "Windows 98")){
		$os = "Win98";
	}else if(strpos($user_agent, "Windows NT 6.1") ){
		$os = "Win7";
	}else if(strpos($user_agent, "Windows NT 6") ){
		$os = "Vista";
	}else if(strpos($user_agent, "Windows NT 5.2") ){
		$os = "Win2003";
	}else if( strpos($user_agent, "Windows NT 5.01") ){
		$os = "Win2000";
	}else if(strpos($user_agent, "Windows NT 5.1") ){
		$os = "WinXP";
	}else if(strpos($user_agent, "Windows NT 5") ){
		$os = "Win2000";
	}else if(strpos($user_agent, "Macintosh") || strpos($user_agent, "Mac_PowerPC") || strpos($user_agent, "mac")){
		$os = "Mac";
	}else if(strpos($user_agent, "Linux") || strpos($user_agent, "Wget")){
		$os = "Linux";
	}else if(strpos($user_agent, "Unix")){
		$os = "Unix";
	}else{
		$os = "Etc";
	}
	$data["browser"] = $browser;
	$data["os"] = $os;

	return $data;

}
// 현금영수증 발급사유
function get_cash_type_name($cash_type) {

	switch($cash_type) {
		case "C" : $name = "사업자 지출증빙용"; break;
		case "P" : $name = "개인소득 공제용"; break;
		default  : $name = ""; break;
	}

	return $name;

}
// 현금영수증 신청정보
function get_cash_type2_name($cash_type2) {

	switch($cash_type2) {
		case "CARDNUM" : $name = "현금영수증 카드번호"; break;
		case "COMNUM" : $name = "사업자번호"; break;
		case "HPHONE" : $name = "휴대전화번호"; break;
		case "RESNO" : $name = "주민등록번호"; break;
		default  : $name = ""; break;
	}

	return $name;

}

function xml2array($content, $get_attributes = 1, $priority = 'tag')
{
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return array ();
    }
    $parser = xml_parser_create('');
    $url = $content;
    $url_list = parse_url($url);

    // URL
    if($url_list['host'] != "") {

			if(!($socket = fsockopen($url_list['host'], 80, $errno, $errstr, 5))) { // URL에 소켓 연결
				echo " $errno : $errstr ";
				exit;
			}

			$header = "GET {$url} HTTP/1.0\n\n";
			fwrite($socket, $header);

			$data = '';
			while(!feof($socket)) { $data .= fgets($socket); }
			fclose($socket);

			$data = explode("\r\n\r\n", $data, 2);
			$contents = $data[1];

		// XML Data
		} else {

			$contents=$content;

		}

    //xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); //xml  파서에서 옵션설정 인코딩
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0); ///대문자로변경
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); //공백값무시
    xml_parse_into_struct($parser, trim($contents), $xml_values); //읽어들인 xml를 이용해 배열에 xml 구조를 담는다
    xml_parser_free($parser); //파서해제

    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array ();

    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        {
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }

    return ($xml_array);
}

// 우편번호 검색
function get_zipcode_list($address) {

	// 도로명주소 관련 변수
	$site_info['zipcode_key']	= "5C38658E5EBBDFC5AD88D24EC7D80449";	// API키
	$site_info['zipcode_url']	= "ws.didim365.com";									// API주소
	$site_info['zipcode_enc']	= "euc-kr";														// 인코딩 : euc-kr, utf-8
	$site_info['zipcode_dr']	= "t";																// 도로명주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_jb']	= "t";																// 지번주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_de']	= "f";																// 도로명영어주소 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_bn']	= "f";																// 대량배달/건물명 포함여부 : t(포함), f(포함안함)
	$site_info['zipcode_sp']	= "f";																// 건물번호/지번 제외여부 : t(포함), f(포함안함)

	$search_count = 0;
	if($address) {

		$WS_URL = $site_info['zipcode_url'];

		$GET_URL = "http://".$WS_URL."/address/addr.aspx?sd=" . urlencode("");
		$GET_URL .= "&sg=" . urlencode("");
		$GET_URL .= "&r=x";
		$GET_URL .= "&enc=".$site_info['zipcode_enc'];
		$GET_URL .= "&k=" . urlencode($address);
		$GET_URL .= "&dr=".$site_info['zipcode_dr'];
		$GET_URL .= "&jb=".$site_info['zipcode_jb'];
		$GET_URL .= "&de=".$site_info['zipcode_de'];
		$GET_URL .= "&bn=".$site_info['zipcode_bn'];
		$GET_URL .= "&sp=".$site_info['zipcode_sp'];
		$GET_URL .= "&key=".$site_info['zipcode_key'];
		$GET_URL .= "&ts=" . time();

		$parser = xml2array($GET_URL);

		$doc_el = $parser['Didim365-Address'];

		$Result = $doc_el['Result'];
		$Message = $doc_el['Message'];

		if ($Result == "True")
		{
			$Cnt = $doc_el['Count'];
			if($Cnt == 1) {
				$doc_el['Data']['Item'][0] = $doc_el['Data']['Item'];
			}
			foreach($doc_el['Data']['Item'] as $item)
			{
				//  <항상 포함>
				// zipno : 우편번호
				//  <옵션에 따라 포함>
				// doro : 도로명 주소
				// doroen : 도로면 영문 주소
				// jibun : 지번주소

				$ZipNo	= $item['ZipNo'];
				$Doro	= str_conv($item['Doro'], $site_info['zipcode_enc']);
				$Jibun	= str_conv($item['JiBun'], $site_info['zipcode_enc']);

				if(is_array($item)) {
					$list[$search_count][zip1]	= substr($ZipNo,0,3);
					$list[$search_count][zip2]	= substr($ZipNo,3,3);
					$list[$search_count][set_addr]	= $Doro;
					$list[$search_count][addr]	= $Doro.($Jibun ? '<br/>'.$Jibun : '');
					$list[$search_count][bunji]	= "";
					$list[$search_count][jibun]	= $Jibun;

					$list[$search_count][encode_addr] = urlencode($list[$search_count][addr]);
					$search_count++;
				}
			}
		}

		return $list;

	}
}
?>