<?

include_once "../inc/common.inc"; 		// DB컨넥션, 접속자 파악
include_once "../inc/util.inc"; 		// 라이브러리 함수
//require_once('../db.inc.php');//db config
include('./auth_check.php');//db config

include_once('./db.process.php');//db config
$type = $_REQUEST['type'];

switch ($type) {
	case 'registProduct':
		$data = json_decode(file_get_contents('php://input'), true);
		registProduct($data);
		$response_array['status'] = 'success';
		echo (json_encode($response_array));
		break;
	
	default:
		# code...
		break;
}
?>