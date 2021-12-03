<script language="javascript">
<!--



function chgImage(file){
	
		document.prdimg.src = "/adm/data/bbs/<?=$code?>/M"+file;

}
//-->
</script>

<?
							for($ii = 1; $ii <= 5; $ii++) {
		if(img_type(WIZHOME_PATH."/data/bbs/$code/M".$bbs_row[upfile.$ii])) {
			${prdupimg.$ii} = "<img src='/adm/data/bbs/$code/M".$bbs_row[upfile.$ii]."' border='0' style='width:100%;' name='prdimg'>";

			${prdupimg_s.$ii} = "<img src='/adm/data/bbs/$code/S".$bbs_row[upfile.$ii]."' border='0' style='width:100%;' onmouseover=chgImage('".$bbs_row[upfile.$ii]."');>";
			
		}
	}
		?>



<table width="100%" cellpadding="0" cellspacing="0" class="M_prd_View">
							<tr>
								<td align="left" style="padding-top:20px; ">
										<div class="M_prd_View_img"><?=$prdupimg1?></div>
										<div class="M_prd_View_simg">

											<!-- 확대보기 -->
											<table border="0" cellpadding="0" cellspacing="0" align="left">
												<tr>
												<?if($prdupimg_s1){?> 
												  <td width="70">
														<table border="0" cellpadding="0" cellspacing="0" class="prdThimg"><tr><td width="66" height="66" border="0">
														<?=$prdupimg_s1?>	
														</td></tr></table>
												  </td>
												<?}if($prdupimg_s2){?> 
												 <td width="70">
														<table border="0" cellpadding="0" cellspacing="0" class="prdThimg"><tr><td width="66" height="66" border="0">
														<?=$prdupimg_s2?>	
														</td></tr></table>
												  </td>
												  <?}if($prdupimg_s3){?> 
												  <td width="70">
														<table border="0" cellpadding="0" cellspacing="0" class="prdThimg"><tr><td width="66" height="66" border="0">
														<?=$prdupimg_s3?>	
														</td></tr></table>
												  </td>
												  <?}if($prdupimg_s4){?> 
												  <td>
														<table border="0" cellpadding="0" cellspacing="0" class="prdThimg"><tr><td width="66" height="66" border="0">
														<?=$prdupimg_s4?>	
														</td></tr></table>
												  </td>
												  <?}?>
												</tr>
											</table>
											<!-- //확대보기 -->
										</div>
										<div class="M_prd_View_name"><?=$subject?></div>
										<div class="M_prd_View_info"><?=$addinfo1?></div>
										
										<p class="subtit">상세설명</p>
										<div id="M_prd_View_cont"><?=$content?></div>

								</td>
							</tr>

						</table>

						




<!--  이전글 다음글 미리보기 --------------------------------------------------------------------------
<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="mNext_Prev">
<tr>
	<th>이전글</th>
	<td><?=$prev?></td>
</tr>
<tr>
	<th>다음글</th>
	<td><?=$next?></td>
</tr>
</table>
------------------------------------------------------------------------------------------------------------>


<div style="margin:0 0 10px;"></div>
