<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "s_status=$s_status&searchopt=$searchopt&searchkey=$searchkey";
$param .= "&date_type=$date_type&prev_year=$prev_year&prev_month=$prev_month&prev_day=$prev_day&next_year=$next_year&next_month=$next_month&next_day=$next_day";
//------------------------------------------------------------------------------------------------------------------------------------

// 정산요청 및 진행상태 변경
if(!strcmp($mode, "chgstatus")) {

	if(!strcmp($acc_status, "AW")) {
		$status = "AA";

		$mall_name = urldecode($mall_name);
		$mall_tel = urldecode($mall_tel);

		$sql = "insert into wiz_account (idx,mall_id,mall_name,mall_tel,supprice,prdprice,resprice,delprice,couprice,total_price,acc_date,app_date,com_date,status)
						values('','$mall_id','$mall_name','$mall_tel','$supprice','$prdprice','$resprice','$delprice','$couprice','$total_price','$acc_date',now(),'$com_date','$status')";
		mysqli_query($connect, $sql) or die(mysqli_error($connect));
	}

	complete("진행상태를 변경하였습니다.","account_list.php?$param");

// 진행상태 일괄변경
}else if($mode == "batchStatus"){

	$selvalue_list = explode("|", $selvalue);

	for($ii = 0; $ii < count($selvalue_list); $ii++) {

		if(!empty($selvalue_list[$ii])) {
			list($idx, $acc_status, $mall_id, $mall_name, $mall_tel, $supprice, $prdprice, $resprice, $delprice, $couprice, $total_price, $acc_date) = explode(":", $selvalue_list[$ii]);

			if(empty($idx)) {

				$mall_name = urldecode($mall_name);
				$mall_tel = urldecode($mall_tel);

				if(!strcmp($chg_status, "AC")) $com_date = date('Y-m-d H:i:s');

				$sql = "insert into wiz_account (idx,mall_id,mall_name,mall_tel,supprice,prdprice,resprice,delprice,couprice,total_price,acc_date,app_date,com_date,status)
								values('','$mall_id','$mall_name','$mall_tel','$supprice','$prdprice','$resprice','$delprice','$couprice','$total_price','$acc_date',now(),'$com_date','$chg_status')";

				mysqli_query($connect, $sql) or die(mysqli_error($connect));
			}

		}

	}

	echo "<script>alert('상태를 변경하였습니다.');opener.document.location.reload();self.close();</script>";

}

?>