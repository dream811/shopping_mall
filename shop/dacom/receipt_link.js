/*
	 [authdate 생성규칙]

	 1. mertid + tid + mert_key => 인증문자열
	 2. md5(인증문자열) => authdata
	 mert_key는 데이콤에서 발급한 상점용 개인키(private key)로 상점관리자에서 확인 가능
*/

function showReceiptByTID(mertid, tid, authdata, mode)
{
	var receiptURL = "";
	var testURL = "http://pgweb.dacom.net:7085";
	var svcURL = "http://pgweb.dacom.net";

	if (mode == "service") {
		receiptURL = svcURL;
	} else {
		receiptURL = testURL;
	}

	window.open(receiptURL+"/pg/wmp/etc/jsp/Receipt_Link.jsp?mertid="+mertid+"&tid="+tid+"&authdata="+authdata,"eCreditReceipt","toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, width=450, height=600");
}

/*
	 [authdate 생성규칙]
	 1. mertid + orderdate + orderid + mert_key => 인증문자열
	 2. md5(인증문자열) => authdata
	 mert_key는 데이콤에서 발급한 상점용 개인키(private key)로 상점관리자에서 확인 가능
*/

function showReceipt(mertid, orderdate, orderid, authdata)
{
	window.open("http://pgweb.dacom.net/pg/wmp/etc/jsp/Receipt_Link.jsp?mertid="+mertid+"&orderdate="+orderdate+"&orderid="+orderid+"&authdata="+authdata,"eCreditReceipt","toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, width=450, height=600");
}

/*
	[authdate 생성규칙]

	 1. mertid + orderid + paytype + mert_key => 인증문자열
	 2. md5(인증문자열) => authdata
	 mert_key는 데이콤에서 발급한 상점용 개인키(private key)로 상점관리자에서 확인 가능
*/

function showReceiptByOID(mertid, orderid, paytype, authdata, mode)
{
	var receiptURL = "";
	var testURL = "http://pgweb.dacom.net:7085";
	var svcURL = "http://pgweb.dacom.net";

	if (mode == "service") {
		receiptURL = svcURL;
	} else {
		receiptURL = testURL;
	}

	if(paytype=="SC0040") {
		window.open(receiptURL+"/pg/wmp/etc/jsp/Receipt_Link.jsp?mertid="+mertid+"&orderid="+orderid+"&paytype="+paytype+"&authdata="+authdata,"eCreditReceipt","toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, width=800, height=300");
	} else {
		window.open(receiptURL+"/pg/wmp/etc/jsp/Receipt_Link.jsp?mertid="+mertid+"&orderid="+orderid+"&paytype="+paytype+"&authdata="+authdata,"eCreditReceipt","toolbar=no, location=no, status=no, menubar=no, scrollbars=no, resizable=no, width=450, height=600");
	}
}


/*
	 [거래내역확인서 + 현금영수증 자바스크립트 통합]
	 작업목적  : 고객사에서 영수증을 스크립트로 연결하기위해 2개의 js 파일을 import해야하는 불편 해소

	1. 요청파라미터
 	var paramStr = "";
	var receiptURL = "";
	var testURL = "http://pg.dacom.net:7080/transfer/cashreceipt.jsp";
	var svcURL = "http://pg.dacom.net/transfer/cashreceipt.jsp";
	----------------------------------------------------------------------------------
	2. 서비스 타입 설정
	if(stype == "CAS" || stype == "cas" || stype == "SC0040"){
	}

*/

function showCashReceipts(mid, oid, seqno, stype, mode) {
		var paramStr = "";
		var receiptURL = "";
		var testURL = "http://pg.dacom.net:7080/transfer/cashreceipt.jsp";
		var svcURL = "http://pg.dacom.net/transfer/cashreceipt.jsp";

		if (mid == "" || oid == "") {
			return ;
		}

		if(stype == "CAS" || stype == "cas" || stype == "SC0040"){
			stype = "SC0040";
			if (seqno == "") seqno = "001";
			paramStr = "orderid="+oid+"&mid="+mid+"&seqno="+seqno+"&servicetype="+stype;
		}else if(stype == "BANK" || stype == "bank" || stype == "SC0030"){
			stype = "SC0030";
			paramStr = "orderid="+oid+"&mid="+mid+"&servicetype="+stype;
		}else if(stype == "CR" || stype == "cr" || stype == "SC0100"){
			stype = "SC0100";
					paramStr = "orderid="+oid+"&mid="+mid+"&servicetype="+stype;
		}

		if (mode == "service") {
			receiptURL = svcURL;
		} else {
			receiptURL = testURL;
		}

		popupWin = window.open(receiptURL+"?"+paramStr, "popWinName","menubar=0,toolbar=0,scrollbars=no,width=380,height=600,resize=1,left=252,top=116");
}

/*
  주석 표기에 있어서 주의!
  이 스크립트 파일에 함수를 추가할 땐, 한줄 주석 (//) 을 절대 쓰지 마십시오.
  특정 상점들 중 이 스크립트를 인클루드 해서 쓸때 개행을 제거해서 쓰는 곳이 있는 듯 합니다.
  그 때문에 문제가 발생하게 됩니다.
  주석은 반드시 블럭으로 쓰세요.
*/
