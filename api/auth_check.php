<?
include_once "../inc/common.inc"; 		// DB컨넥션, 접속자 파악
include_once "../inc/util.inc"; 			// 라이브러리 함수

$api_token = $_GET['api_token'];
$sql = "select * from tb_users where api_token = '".$api_token."'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$user_info = mysqli_fetch_object($result);

if(!isset($user_info) || $user_info->api_token != $api_token){
    $data = array("status"=>"error", "message"=>"authentication failed!");
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
// }else{
//     $data = array("status"=>"success", "message"=>"authentication success!");
//     header('Content-Type: application/json');
//     echo json_encode($data);
//     exit;
}
?>