<?php
include "../inc/shop_info.inc";
if(!strcmp($oper_info->pay_test, "Y")) {
	$oper_info->pay_id = "T0007";
	$oper_info->pay_key = "3CRB7XHFjUp6fjf1FLEM.g6__";
}

if(!strcmp($oper_info->pay_id, "tanywiz") || !strcmp($oper_info->pay_id, "T0000") || !strcmp($oper_info->pay_id, "T0007")) {
	$oper_info->pay_id = "T0007";
	$oper_info->pay_key = "3CRB7XHFjUp6fjf1FLEM.g6__";
	$payplus = "http://pay.kcp.co.kr/plugin/payplus_test.js";
} else {
	$payplus = "http://pay.kcp.co.kr/plugin/payplus.js";
}
?>
<!--
    /* ============================================================================== */
    /* =   PAGE : 결제 시작 PAGE                                                    = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2006   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */
//-->
<html>
<head>
<title>*** KCP Online Payment System [Escrow PHP Version] ***</title>
<link href="css/sample.css" rel="stylesheet" type="text/css">

<script language='javascript' src='<?=$payplus?>'></script>
<!-- ※ 주의!!!
     테스트 결제시 : src='https://pay.kcp.co.kr/plugin/payplus_test.js'
     리얼   결제시 : src='https://pay.kcp.co.kr/plugin/payplus.js'     로 설정해 주시기 바랍니다. -->
<script language='javascript'>

    // 플러그인 설치(확인)
    StartSmartUpdate();

    function  jsf__pay( form )
    {
        if( document.Payplus.object == null )
        {
            openwin = window.open( '/shop/kcp/chk_plugin.html', 'chk_plugin', 'width=420, height=100, top=300, left=300' );
        }

        if ( MakePayMessage( form ) == true )
        {
            openwin = window.open( '/shop/kcp/proc_win.html', 'proc_win', 'width=420, height=100, top=300, left=300' );
            return  true;
        }
        else
        {
            return  false;
        }
    }


    // 에스크로 장바구니 상품 상세 정보 생성 예제
    function create_goodInfo()
    {
        var chr30 = String.fromCharCode(30);
        var chr31 = String.fromCharCode(31);

        var good_info = "seq=1" + chr31 + "ordr_numb=<?=$orderid?>" + chr31 + "good_name=<?=$payment_prdname?>" + chr31 + "good_cntx=1" + chr31 + "good_amtx=<?=$order_info->total_price?>";
        document.order_info.good_info.value = good_info;

    }
</script>
</head>
<body onLoad="create_goodInfo()">
<form name="order_info" action="/shop/kcp/order_update.php" method="post" onSubmit="return jsf__pay(this)">


<?
if($pay_method == "PC"){
   $pay_method = "100000000000";       // 신용카드
   $pay_method_name = "신용카드";
}else if($pay_method == "PN"){
   $pay_method = "010000000000";       // 계좌이체
   $pay_method_name = "계좌이체";
}else if($pay_method == "PV"){
   $pay_method = "001000000000";       // 가상계좌
   $pay_method_name = "가상계좌";
}else if($pay_method == "PH"){
   $pay_method = "000010000000";       // 휴대폰
   $pay_method_name = "휴대폰";
}
?>
<input type="hidden" name="pay_method" value="<?=$pay_method?>">                           <!-- 결제방법 -->
<input type='hidden' name='good_name' value='<?=$payment_prdname?>' size='30'>        <!-- 상품명 -->
<input type='hidden' name='good_mny' value='<?=$order_info->total_price?>' size='10'>     <!-- 결제금액 -->
<input type='hidden' name='buyr_name' value='<?=$order_info->send_name?>' size='20'>      <!-- 주문자명 -->
<input type='hidden' name='buyr_mail' value='<?=$order_info->send_email?>' size='25'>     <!-- E-Mail -->
<input type='hidden' name='buyr_tel1' value='<?=$order_info->send_tphone?>' size='20'>    <!-- 전화번호 -->
<input type='hidden' name='buyr_tel2' value='<?=$order_info->send_hphone?>' size='20'>    <!-- 휴대폰번호 -->
<input type='hidden' name='quotaopt' value='12'>                                          <!-- 할부옵션 -->

<!-- 에스크로정보 : 에스크로 사용업체에 적용되는 정보입니다. -->
<input type='hidden' name='rcvr_name' value='<?=$order_info->rece_name?>' size='20'>      <!-- 수취인 이름 -->
<input type='hidden' name='rcvr_tel1' value='<?=$order_info->rece_tphone?>' size='20'>    <!-- 수취인 전화번호 -->
<input type='hidden' name='rcvr_tel2' value='<?=$order_info->rece_hphone?>' size='20'>    <!-- 수취인 휴대폰번호 -->
<input type='hidden' name='rcvr_mail' value='' size='40'>                                 <!-- 수취인 E-Mail -->
<input type='hidden' name='rcvr_zipx' value='<?=$order_info->rece_post?>' size='6'>       <!-- 수취인 우편번호 -->
<input type='hidden' name='rcvr_add1' value='<?=$order_info->rece_address?>' size='50'>   <!-- 수취인 주소 -->
<input type='hidden' name='rcvr_add2' value='-' size='50'>                                <!-- 수취인 상세주소 -->


<!-- 필수 항목 -->
<!-- 요청종류 승인(pay)/취소,매입(mod) 요청시 사용 -->
<input type='hidden' name='req_tx'    value='pay'>
<!-- 테스트 결제시 : T0007 으로 설정, 리얼 결제시 : 부여받은 사이트코드 입력 -->
<input type='hidden' name='site_cd'   value='<?=$oper_info->pay_id?>'>

<!-- MPI 결제창에서 사용 한글 사용 불가 -->
<input type='hidden' name='site_name' value='<?=$shop_info->shop_name?>'>
<!-- http://testpay.kcp.co.kr/Pay/Test/site_key.jsp 로 접속하신후 부여받은 사이트코드를 입력하고 나온 값을 입력하시기 바랍니다. -->
<input type='hidden' name='site_key'  value='<?=$oper_info->pay_key?>'>

<!-- 필수 항목 : PULGIN 설정 정보 변경하지 마세요 -->
<input type='hidden' name='module_type' value='01'>

<!-- 필수 항목 : 결제 금액/화폐단위 -->
<input type='hidden' name='currency' value='WON'>

<!-- 주문 번호 (자바 스크립트 샘플(init_orderid()) 참고) -->
<input type='hidden' name='ordr_idxx' value='<?=$order_info->orderid?>'>

<!-- 교통카드 테스트용 파라미터 (교통카드 테스트 시에만 이용하시기 바랍니다.) -->
<!--input type='hidden' name='test_flag' value='T_TEST'//-->

<!-- 에스크로 항목 -->

<!-- 에스크로 사용 여부 : 반드시 Y 로 세팅 -->
<input type='hidden' name='escw_used' value='Y'>

<!-- 에스크로 결제처리 모드 : 에스크로: Y, 일반: N, KCP 설정 조건: O -->
<input type='hidden' name='pay_mod' value='O'>

<!-- 배송 소요일 : 예상 배송 소요일을 입력 -->
<input type='hidden' name='deli_term' value='03'>

<!-- 장바구니 상품 개수 : 장바구니에 담겨있는 상품의 개수를 입력 -->
<input type='hidden' name='bask_cntx' value='1'>

<!-- 장바구니 상품 상세 정보 (자바 스크립트 샘플(create_goodInfo()) 참고) -->
<input type='hidden' name='good_info' value='<?=$order_info->orderid?>'>

<!-- 필수 항목 : PLUGIN에서 값을 설정하는 부분으로 반드시 포함되어야 합니다. ※수정하지 마십시오.-->
<input type='hidden' name='res_cd'         value=''>
<input type='hidden' name='res_msg'        value=''>
<input type='hidden' name='tno'            value=''>
<input type='hidden' name='trace_no'       value=''>
<input type='hidden' name='enc_info'       value=''>
<input type='hidden' name='enc_data'       value=''>
<input type='hidden' name='ret_pay_method' value=''>
<input type='hidden' name='tran_cd'        value=''>
<input type='hidden' name='bank_name'      value=''>
<input type='hidden' name='bank_issu'      value=''>
<input type='hidden' name='use_pay_method' value=''>

<!-- 현금영수증 관련 정보 : PLUGIN 에서 내려받는 정보입니다 -->
<input type='hidden' name='cash_tsdtime'   value=''>
<input type='hidden' name='cash_yn'        value=''>
<input type='hidden' name='cash_authno'    value=''>


<table width=100% class="AW_order_table" style="margin:30px 0;">
	<tr>
		<td width="20%" class="tit">결제수단</td>
		<td width="80%" class="val"><?=$pay_method_name?></td>
	</tr>
	<tr>
		<td width="20%" class="tit">결제금액</td>
		<td width="80%" class="val"><span class="price"><?=number_format($order_info->total_price)?>원</span></td>
	</tr>
</table>
<div class="AW_btn_area">
	<button type="submit" class="submit_btn">결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">주문취소</button>
</div>	 
  
</form>
