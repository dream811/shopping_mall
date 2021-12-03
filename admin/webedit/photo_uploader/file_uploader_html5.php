<?php
include '../config.php';

if (!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data) {
		$f = @fopen($filename, 'w');
		if (!$f) {
			return false;
		} else {
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
	}
}

$sFileInfo = '';
$headers = array();

foreach($_SERVER as $k => $v) {
	if(substr($k, 0, 9) == "HTTP_FILE") {
		$k = substr(strtolower($k), 5);
		$headers[$k] = $v;
	}
}

$filename = rawurldecode($headers['file_name']);
$filename_ext = strtolower(array_pop(explode('.',$filename)));
$allow_file = array("jpg", "png", "bmp", "gif");

if(!in_array($filename_ext, $allow_file)) {
	echo "NOTALLOW_".$filename;
} else {
	$file = new stdClass;
	$file->name = date("YmdHis").mt_rand().".".$filename_ext;
	$file->content = file_get_contents("php://input");

	$uploadDir = WEBDATA_PATH.'/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}

	$newPath = $uploadDir.$file->name;

	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$filename;
		$sFileInfo .= "&sFileURL=".WEBDATA_URL."/".$file->name;
	}

	echo $sFileInfo;
}
?>