<?php
    //	return value
	//  true  : 결과연동이 성공
	//  false : 결과연동이 실패

	function write_success($noti){
        //결제에 관한 log남기게 됩니다. log path수정 및 db처리루틴이 추가하여 주십시요.
		  // 결과구분(C=수령확인결과, R=구매취소요청, D=구매취소결과, N=NC처리결과 )
	  // 수령확인결과
		if($noti[txtype] == "C"){

			$sql = "UPDATE wiz_order SET status = 'DC',escrow_stats = 'US' where orderid = '$noti[oid]'";
			$result = mysqli_query($connect, $sql);

		// 구매취소요청
		}else if($noti[txtype] == "R"){

			$sql = "update wiz_order set status = 'RD', cancelmsg='에스크로 구매취소 요청', escrow_stats = 'UX' where orderid = '$noti[oid]'";
			$result = mysqli_query($connect, $sql);

		}
	    return true;
	}

	function write_failure($noti){
	    return true;
	}

    function write_hasherr($noti) {
		return true;
    }

	function get_param($name){
		global $_POST, $_GET;
		if (!isset($_POST[$name]) || $_POST[$name] == "") {
			if (!isset($_GET[$name]) || $_GET[$name] == "") {
				return false;
			} else {
                 return $_GET[$name];
			}
		}
		return $_POST[$name];
	}

?>

