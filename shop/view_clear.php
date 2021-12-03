<?

if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

if(!empty($view_list)){

   session_unset("view_list"); 

}

Header("Location: $HTTP_REFERER");

?>