<script language="javascript">
<!--
function cpcGuard(){
	alert("시피시가드와 제휴하여 제공되는 서비스입니다.\n\n자세한 사항은 시피시가드에 문의하시기 바랍니다.\n\n설치 연동은 시피시가드에서 무상으로 해드립니다.");
	window.open("http://mycpc.cpcguard.com/","","");
}
-->
</script>
<ul class="lnb">
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_info") !== false){ echo 'active'; } ?>"><a href="shop_info.php">기본정보설정</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_oper") !== false){ echo 'active'; } ?>"><a href="shop_oper.php">운영정보설정</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_guide") !== false){ echo 'active'; } ?>"><a href="shop_guide.php">구매가이드</a></li>
    <li class="<?php if(strpos($_SERVER['PHP_SELF'],"/shop_out") !== false){ echo 'active'; } ?>"><a href="shop_out.php">탈퇴하기</a></li>
</ul><!-- .lnb -->