<?
    /*
     * [상점결제요청 페이지(ActiveX)]
     *
     * 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가하시기 바랍니다.
     * hashdata 암호화는 거래 위변조를 막기위한 방법입니다.
     *
     */


    /*
     * 1. 기본결제정보 변경
     *
     * 결제기본정보를 변경하여 주시기 바랍니다.
     */
    $platform               = $_POST["platform"];             //LG데이콤 결제서비스 선택(test:테스트, service:서비스)
	$mid                    = $_POST["LGD_MID"];              //상점아이디(LG데이콤으로 부터 발급받으신 상점아이디를 입력하세요)
	                                                                   //테스트 아이디는 't'를 제외하고 입력하세요.
	$LGD_MID                = (("test" == $platform)?"t":"").$mid;     //상점아이디(자동생성)
    $LGD_OID                = $_POST["LGD_OID"];              //주문번호(상점정의 유니크한 주문번호를 입력하세요)
    $LGD_AMOUNT             = $_POST["LGD_AMOUNT"];           //결제금액("," 를 제외한 결제금액을 입력하세요)
    $LGD_MERTKEY            = $_POST["LGD_MERTKEY"];          //상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
    $LGD_TIMESTAMP          = $_POST["LGD_TIMESTAMP"];        //타임스탬프
    $LGD_BUYER              = $_POST["LGD_BUYER"];            //구매자명
    $LGD_PRODUCTINFO        = $_POST["LGD_PRODUCTINFO"];      //상품명
    $LGD_BUYEREMAIL         = $_POST["LGD_BUYEREMAIL"];       //구매자 이메일
    $LGD_CUSTOM_SKIN        = $_POST["LGD_CUSTOM_SKIN"];      //상점정의 결제창 스킨 (red, blue, cyan, green, yellow)

    /*
     * 2. 결제결과 DB처리 페이지 링크 변경
     *
     * LGD_NOTEURL : 상점결제결과 처리(DB) 페이지 URL을 넘겨주세요.
     * LGD_CASNOTEURL : 가상계좌(무통장) 결제 연동을 하시는 경우 아래 LGD_CASNOTEURL 을 설정하여 주시기 바랍니다.
     */
    $LGD_NOTEURL            = $_POST["LGD_NOTEURL"];          //상점결제결과 처리(DB) 페이지(URL을 변경해 주세요)
    $LGD_CASNOTEURL			= "http://상점URL/cas_noteurl.php";

    /*
     * 3. hashdata 암호화 (수정하지 마세요)
     *
     * hashdata 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
     * LGD_MID : 상점아이디
     * LGD_OID : 주문번호
     * LGD_AMOUNT : 금액
     * LGD_TIMESTAMP : 타임스탬프
     * LGD_MERTKEY : 상점키(mertkey)
     *
     * hashdata 검증을 위한
     * LG데이콤에서 발급한 상점키(MertKey)를 반드시 입력해 주시기 바랍니다.
     */
    $LGD_HASHDATA = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_TIMESTAMP.$LGD_MERTKEY);
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>데이콤 eCredit서비스 결제테스트</title>

<script language = 'javascript'>
<!--
/*
 * 결제요청 및 결과화면 처리
 */

function doPay_ActiveX(){
    ret = xpay_check(document.getElementById('LGD_PAYINFO'), '<?= $platform ?>');

    if (ret=="00"){     //ActiveX 로딩 성공
        var LGD_RESPCODE        = dpop.getData('LGD_RESPCODE');       //결과코드
        var LGD_RESPMSG         = dpop.getData('LGD_RESPMSG');        //결과메세지

        if( "0000" == LGD_RESPCODE ) { //결제성공
	        var LGD_TID             = dpop.getData('LGD_TID');            //LG데이콤 거래KEY
	        var LGD_PAYTYPE         = dpop.getData('LGD_PAYTYPE');        //결제수단
	        var LGD_PAYDATE         = dpop.getData('LGD_PAYDATE');        //결제일자
	        var LGD_FINANCECODE     = dpop.getData('LGD_FINANCECODE');    //결제기관코드
	        var LGD_FINANCENAME     = dpop.getData('LGD_FINANCENAME');    //결제기관이름
	        var LGD_NOTEURL_RESULT  = dpop.getData('LGD_NOTEURL_RESULT'); //상점DB처리(LGD_NOTEURL)결과 ('OK':정상,그외:실패)
	        //메뉴얼의 결제결과 파라미터내용을 참고하시어 필요하신 파라미터를 추가하여 사용하시기 바랍니다.

            var msg = "결제결과 : " + LGD_RESPMSG + "\n";
            msg += "LG데이콤거래TID : " + LGD_TID +"\n\n";
            if( LGD_NOTEURL_RESULT != "null" ) msg += LGD_NOTEURL_RESULT +"\n";
            alert(msg);
            /*
             * 결제성공 화면 처리
             */
        } else { //결제실패
             alert("결제가 실패하였습니다. " + LGD_RESPMSG);
            /*
             * 결제실패 화면 처리
             */
        }
    } else {
        alert("LG데이콤 전자결제를 위한 ActiveX 설치 실패");
    }
}

//-->
</script>

</head>
<body>
<form method="post" id="LGD_PAYINFO">
<table>
    <tr>
        <td>구매자 이름 </td>
        <td><?= $LGD_BUYER ?></td>
    </tr>
    <tr>
        <td>상품정보 </td>
        <td><?= $LGD_PRODUCTINFO ?></td>
    </tr>
    <tr>
        <td>결제금액 </td>
        <td><?= $LGD_AMOUNT ?></td>
    </tr>
    <tr>
        <td>구매자 이메일 </td>
        <td><?= $LGD_BUYEREMAIL ?></td>
    </tr>
    <tr>
        <td>주문번호 </td>
        <td><?= $LGD_OID ?></td>
    </tr>
    <tr>
        <td colspan="2">* 추가 상세 결제요청 파라미터는 메뉴얼을 참조하세요.</td>
    </tr>
    <tr>
        <td>
        <input type="button" value="결제요청(ActiveX)" onclick="doPay_ActiveX()"/><br>
        </td>
    </tr>
</table>

<input type="hidden" name="LGD_MID"             value="<?= $LGD_MID ?>">                		<!-- 상점아이디 -->
<input type="hidden" name="LGD_OID"             value="<?= $LGD_OID ?>">                		<!-- 주문번호 -->
<input type="hidden" name="LGD_BUYER"           value="<?= $LGD_BUYER ?>">           			<!-- 구매자 -->
<input type="hidden" name="LGD_PRODUCTINFO"     value="<?= $LGD_PRODUCTINFO ?>">     			<!-- 상품정보 -->
<input type="hidden" name="LGD_AMOUNT"          value="<?= $LGD_AMOUNT ?>">             		<!-- 결제금액 -->
<input type="hidden" name="LGD_BUYEREMAIL"      value="<?= $LGD_BUYEREMAIL ?>">         		<!-- 구매자 이메일 -->
<input type="hidden" name="LGD_CUSTOM_SKIN"     value="<?= $LGD_CUSTOM_SKIN ?>">        		<!-- 결제창 SKIN -->
<input type="hidden" name="LGD_TIMESTAMP"       value="<?= $LGD_TIMESTAMP ?>">          		<!-- 타임스탬프 -->
<input type="hidden" name="LGD_HASHDATA"        value="<?= $LGD_HASHDATA ?>">           		<!-- 해쉬값 -->
<input type="hidden" name="LGD_NOTEURL"         value="<?= $LGD_NOTEURL ?>">         			<!-- 결제결과처리_URL(LGD_NOTEURL) -->
<input type="hidden" name="LGD_VERSION"         value="PHP_XPay_Lite_1.0">						<!-- 버전정보 (삭제하지 마세요) -->
<!-- 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 데이콤에 전송해야 합니다 . -->
<!-- input type="hidden" name="LGD_CASNOTEURL"          	value="<?= $LGD_CASNOTEURL ?>"-->					<!-- 가상계좌 NOTEURL -->


</form>
</body>
<!--  xpay.js는 반드시 body 밑에 두시기 바랍니다. -->
<script language="javascript" src="<?= $_SERVER['SERVER_PORT']!=443?"http":"https" ?>://xpay.lgdacom.net<?=($platform == "test")?":7080":""?>/xpay/js/xpay.js" type="text/javascript"></script>
</html>

