<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<?

$sql = "select upfile1,upfile2,upfile3,upfile4,upfile5,upfile6,upfile7,upfile8,upfile9,upfile10,upfile11,upfile12,upfile1_name,upfile2_name,upfile3_name,upfile4_name,upfile5_name,upfile6_name,upfile7_name,upfile8_name,upfile9_name,upfile10_name,upfile11_name,upfile12_name from wiz_bbs where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);

$file = "../../data/bbs/$code/".$row['upfile'.$no]; $filename = str_conv($row['upfile'.$no.'_name'], "euc-kr");

if(file_exists($file)) {

   if( strstr($HTTP_USER_AGENT,"MSIE 5.5")){
       Header("Content-Type: doesn/matter");
       Header("content-length: ". filesize("$file"));
       Header("Content-Disposition: attachment; filename=$filename");
       Header("Content-Transfer-Encoding: binary");
       Header("Cache-Control: cache,must-revalidate");
       Header("Pragma: cache");
       Header("Expires: 0");
   }else{
       Header("Content-type: file/unknown");
       Header("content-length: ". filesize("$file"));
       Header("Content-Disposition: attachment; filename=$filename");
       Header("Content-Description: PHP3 Generated Data");
       Header("Cache-Control: cache,must-revalidate");
       Header("Pragma: cache");
       Header("Expires: 0");
   }

   if(is_file("$file")){
       $fp = fopen("$file","r");
       if(!fpassthru($fp)) {
           fclose($fp);
       }
   }

}else{
   echo "<script>alert('첨부파일이 존재하지 않습니다.');history.go(-1);</script>";
}

?>