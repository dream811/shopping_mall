<?php
function error($error_msg, $go_url="")
{
	echo "<meta http-equiv='Content-Type' c&#111;&#110;tent='text/html; charset=utf-8'>";
    if($go_url=="")
    {
        echo "<script>alert(\"".$error_msg."\");history.go(-1);</script>";
        exit();
    }
    echo "<script>alert(\"".$error_msg."\");document.location=\"{$go_url}\";</script>";
    exit();
}

function comalert($com_msg, $go_url="") {
	echo "<meta http-equiv='Content-Type' c&#111;&#110;tent='text/html; charset=utf-8'>";
	if($go_url=="") {
		echo "<script>alert(\"".$com_msg."\");history.go(-1);</script>";
	} else {
		echo "<script>alert(\"".$com_msg."\");document.location=\"{$go_url}\";</script>";
	}
}

function complete($com_msg, $go_url="") {
	echo "<meta http-equiv='Content-Type' c&#111;&#110;tent='text/html; charset=utf-8'>";
	if($go_url=="") {
		echo "<script>window.setTimeout(\"history.go(-1)\",600);</script>";
	} else {
		echo "<script>window.setTimeout(\"document.location='".$go_url."';\",600);</script>";
	}
	echo "<body><table width=100% height=100%><tr><td align=center><font size=2>".$com_msg."</font></td></tr></table></body>";
}

@header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');

@ini_set("session.use_trans_sid", 0);	// PHPSESSID를 자동으로 넘기지 않음	=> session.auto_start = 0 으로 설정 / PHP 5 이상 버전부터 session.use_trans_sid 설정을 ini_set으로 바꿀 수 없음
@ini_set("url_rewriter.tags","");			// 링크에 PHPSESSID가 따라다니는것을 무력화함

session_save_path("$_SERVER[DOCUMENT_ROOT]/data/session");

if($SESSION_CACHE_LIMITER) session_cache_limiter($SESSION_CACHE_LIMITER);
else session_cache_limiter('private, must-revalidate');

@ini_set("session.cache_expire", 1440);			// 세션 캐쉬 보관시간 (분)
@ini_set("session.gc_maxlifetime", 86400);	// session data의 gabage collection 존재 기간을 지정 (초)

session_set_cookie_params(0, "/");
@ini_set("session.cookie_domain", "");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// register_globals off 처리
@extract($HTTP_GET_VARS);
@extract($HTTP_POST_VARS);
@extract($HTTP_SERVER_VARS);
@extract($HTTP_ENV_VARS);
@extract($HTTP_SESSION_VARS);
@extract($HTTP_COOKIE_VARS);
@extract($_REQUEST);
@extract($_FILES);
@extract($HTTP_HOST_VARS);
@extract($_GET);
@extract($_POST);
@extract($_REQUEST);
@extract($_SERVER);
@extract($_ENV);
@extract($_SESSION);
@extract($_COOKIE);

$wiz_session = $_COOKIE[wiz_session];

define(WIZSHOP_PATH, $_SERVER['DOCUMENT_ROOT']);

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

/******************************************************************************
* 위즈샵 설치확인
******************************************************************************/
if(!file_exists(WIZSHOP_PATH."/admin/dbcon.php")){
	echo "<script>document.location='/admin/install.php';</script>";
	exit;
}

/******************************************************************************
* lib.php
******************************************************************************/
include WIZSHOP_PATH."/inc/util_puny.php";

/******************************************************************************
* 데이타 베이스 접속
******************************************************************************/
include WIZSHOP_PATH."/admin/dbcon.php";
$connect = @mysqli_connect($db_host, $db_user, $db_pass) or die("DB 접속시 에러가 발생했습니다.");
@mysqli_select_db($connect, $db_name) or die("DB Select 에러가 발생했습니다");

/******************************************************************************
* 접속상황 및 이동경로 파악
******************************************************************************/
$con_file = WIZSHOP_PATH."/data/connect/".$_SERVER['REMOTE_ADDR'];
@touch($con_file);

?>
