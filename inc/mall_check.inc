<?
// 관리자 로그인 체크
$wiz_mall = $_SESSION['wiz_mall'];
if($wiz_mall['id'] == ""){
?>
<script language="javascript">
<!--
if(parent != null)
   parent.document.location = "/mall/admin_login.php";
else
   document.location = "/mall/admin_login.php";
-->
</script>
<?
}
?>