<?
$wiz_md = $_SESSION['wiz_md'];
// 관리자 로그인 체크
if($wiz_md['id'] == ""){
?>
<script language="javascript">

if(parent != null)
   parent.document.location = "/md/md_login.php";
else
   document.location = "/md/md_login.php";

</script>
<?
}
?>