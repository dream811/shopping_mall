<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";


// 기본정보설정
if($mode == "shop_info"){

	$upfile_path = "../../data/config";

	// 관라자 로고
	if($admin_logo[size] > 0){
		file_check($admin_logo[name]);
		copy($admin_logo[tmp_name], $upfile_path."/admin_logo.gif");
		chmod($upfile_path."/admin_logo.gif", 0606);
	}

	if($designer_pw != "") {
		$designer_pw = md5($designer_pw);
		$designer_pw_sql = " designer_pw = '$designer_pw', ";
	}


	$sql = "update wiz_shopinfo set admin_title='$admin_title', admin_footer='$admin_footer',site_key='$site_key'
					,designer_id='$designer_id', $designer_pw_sql start_page='$start_page'
					,sch_use='$sch_use',poll_use='$poll_use',design_use='$design_use',addbbs_use='$addbbs_use'
					,sms_use='$sms_use',namecheck_use='$namecheck_use',namecheck_id='$namecheck_id',namecheck_pw='$namecheck_pw',estimate_use='$estimate_use',ssl_use='$ssl_use',ssl_port='$ssl_port'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	
	$sql = "update wiz_operinfo set sms_id='$sms_id', sms_pw='$sms_pw'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("기본정보 설정이 저장되었습니다.","basic_config.php");

}

?>