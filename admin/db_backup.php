<?
include "../inc/common.inc";
include "../inc/admin_check.inc";


// mysqldump 명령어 경로 찾기
$dump_result = mysqli_query($connect, "show variables");

while(1) {
	$dump_array = mysql_fetch_row($dump_result);
	if($dump_array == false) { break; }
	if($dump_array[0] == "basedir") { $bindir = $dump_array[1]."bin/"; }
}


// 전체백업
if($table == ""){

	$file_name = "db_backup_".date('Ymd').".sql";
	$file = $DOCUMENT_ROOT."/images/dbdump.sql";
	exec($bindir."/mysqldump -h '$db_host' -u '$db_user' -p'$db_pass' '$db_name' > $file");

// 특정테이블 백업
}else{

	$file_name = $table."_backup_".date('Ymd').".sql";
	$file = $DOCUMENT_ROOT."/images/dbdump.sql";
	exec($bindir."/mysqldump -h '$db_host' -u '$db_user' -p'$db_pass' '$db_name' '$table' > $file");

}

if(file_exists($file)){

   if( strstr($HTTP_USER_AGENT,"MSIE 5.5")){
       header("Content-Type: doesn/matter");
       header("Content-Disposition: filename=$file_name");
       header("Content-Transfer-Encoding: binary");
       header("Pragma: no-cache");
       header("Expires: 0");
   }else{
       Header("Content-type: file/unknown");
       Header("Content-Disposition: attachment; filename=$file_name");
       Header("Content-Description: PHP3 Generated Data");
       header("Pragma: no-cache");
       header("Expires: 0");
   }

   if(is_file("$file")){
       $fp = fopen("$file","r");
       if(!fpassthru($fp)) {
           fclose($fp);
       }
   }

}else{

   error("백업파일이 존재하지 않습니다.");

}

?>