<?
include_once "../inc/common.inc"; 		// DB컨넥션, 접속자 파악
include_once "../inc/util.inc"; 			// 라이브러리 함수
require_once('./db.process.php');//db config
$url = $_REQUEST['url'];
switch ($url) {
	case 'getBetListLR':
		$dateTime = $_REQUEST['dateTime'];
		$query = "select * from tbl_result where DATE(strTime) = '".$dateTime."' order by nCode";
		$response_array = array();
		$response_array['data'] = getBetList($conn, $query);
		$response_array['status'] = 'success';
		echo (json_encode($response_array));
		break;
	case 'getBetListWB':
		$dateTime = $_REQUEST['dateTime'];
		$query = "select * from tbl_result where  DATE(strTime) = '".$dateTime."' order by nCode";
		$response_array = array();
		$response_array['data'] = getBetList($conn, $query);
		$response_array['status'] = 'success';
		echo (json_encode($response_array));
		break;
	case 'getBetRecentData':
		$query = 'select * from tbl_result order by nCode desc limit 1';
		$response_array = array();
		$response_array['data'] = getBetRecentData($conn, $query);
		$response_array['status'] = 'success';
		echo (json_encode($response_array));
		break;
	case 'getBetPageData':
		$pageSize = 10;//$_REQUEST['pageSize'];
		$query = 'select * from tbl_result order by nCode desc limit '.$pageSize;
		$response_array = array();
		$response_array['data'] = getBetRecentData($conn, $query);
		$response_array['status'] = 'success';
		echo (json_encode($response_array));
		break;
	default:
		# code...
		break;
}
?>