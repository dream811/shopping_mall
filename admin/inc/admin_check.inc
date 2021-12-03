<?

if($wiz_session['id'] == "" || $wiz_session['level_value'] != "0"){

   echo "<script>parent.document.location='../admin_login.php';</script>";

}else{

   if(strpos($PHP_SELF, "/main.php") !== false) $page_id = "00";
   else if(strpos($PHP_SELF, "/shop/") !== false) $page_id = "01";
   else if(strpos($PHP_SELF, "/design/") !== false) $page_id = "02";
   else if(strpos($PHP_SELF, "/page/") !== false) $page_id = "03";
   else if(strpos($PHP_SELF, "/product/") !== false) $page_id = "04";
   else if(strpos($PHP_SELF, "/order/") !== false) $page_id = "05";
   else if(strpos($PHP_SELF, "/member/") !== false) $page_id = "06";
   else if(strpos($PHP_SELF, "/marketing/") !== false) $page_id = "07";
   else if(strpos($PHP_SELF, "/bbs/") !== false) $page_id = "08";

   $pos = strpos($wiz_session[permi], $page_id);
   if($pos === false){
      error("권한이 없습니다.");
   }

}

?>