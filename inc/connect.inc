<?

if(isset($HTTP_COOKIE_VARS["wiz_connect"]) && isset($_SERVER['HTTP_USER_AGENT']) && strlen($HTTP_COOKIE_VARS["wiz_connect"])==0 && check_robots($_SERVER['HTTP_USER_AGENT'])){
	
   setcookie("wiz_connect", "true");

	// 접속시간 저장
   $time = date('YmdH');
   $sql = "select time from wiz_contime where time = '$time'";
   $result = @mysqli_query($connect, $sql);

   if(@mysqli_num_rows($result) > 0){

   	$sql = "update wiz_contime set cnt = cnt + 1 where time = '$time'";
   	$result = @mysqli_query($connect, $sql);

	}else{

		$sql = "insert into wiz_contime(time,cnt) values('$time',1)";
   	$result = @mysqli_query($connect, $sql);

	}

	// 접속레퍼러 저장
	if(isset($_SERVER['HTTP_REFERER'])){
		$referer = $_SERVER['HTTP_REFERER'];
	}else{
		$referer = "";
	}
	
	$parse_url = parse_url($referer);
	$host="";
	if(isset($parse_url['host'])){
		$host = $parse_url['host'];
	}
	

	// 현재 host와 다른 경우 저장
	if(strcmp($host, $_SERVER['HTTP_HOST'])) {

	   $sql = "select referer from wiz_conrefer where referer = '$referer'";
	   $result = @mysqli_query($connect, $sql);

	   if(@mysqli_num_rows($result) > 0){

	   	$sql = "update wiz_conrefer set cnt = cnt + 1 where referer = '$referer'";
	   	$result = @mysqli_query($connect, $sql);

		}else{

			$sql = "insert into wiz_conrefer(referer,host,cnt) values('$referer','$host',1)";
	   	$result = @mysqli_query($connect, $sql);

		}

	}
	
	// 브라우저 OS저장
	$os_browser = get_osbrowser($_SERVER['HTTP_USER_AGENT']);
	$browser = $os_browser["browser"];
	$os = $os_browser["os"];
	
	$sql = "select cnt from wiz_conother where browser='$browser'";
	$result = mysqli_query($connect, $sql); $total = mysqli_num_rows($result);
	if($total > 0) $sql = "update wiz_conother set cnt = cnt+1 where browser='$browser'";
	else $sql = "insert into wiz_conother(browser,cnt) values('$browser','1')";
	mysqli_query($connect, $sql);
	
	$sql = "select cnt from wiz_conother where os='$os'";
	$result = mysqli_query($connect, $sql); $total = mysqli_num_rows($result);
	if($total > 0) $sql = "update wiz_conother set cnt = cnt+1 where os='$os'";
	else $sql = "insert into wiz_conother(os,cnt) values('$os','1')";
	mysqli_query($connect, $sql);

}
?>