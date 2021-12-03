<?php
include '../config.php';

// default redirection
$url = 'callback.html?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if($bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];

	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif");

	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	} else {
		$uploadDir = WEBDATA_PATH.'/';
		if(!is_dir($uploadDir)){
			mkdir($uploadDir, 0777);
		}

		$newName = date("YmdHis").mt_rand().".".$filename_ext;
		$newPath = $uploadDir.$newName;

		@move_uploaded_file($tmp_name, $newPath);

		$url .= "&bNewLine=true";
		$url .= "&sFileName=".urlencode(urlencode($name));
		$url .= "&sFileURL=".WEBDATA_URL."/".urlencode(urlencode($newName));
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}

header('Location: '. $url);
?>