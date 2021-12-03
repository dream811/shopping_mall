<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리

include "../inc/header.inc"; 				// 상단디자인
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" style="padding:5px 0px;">
			<!-- mall info -->

			<? include "../inc/mall_info.inc";				// 입점업체 정보 ?>

			<?php $mallbbs = true; include "../bbs/input.php"; ?>

		</td>
	</tr>
</table>


<?
include "../inc/footer.inc"; 		// 하단디자인
?>