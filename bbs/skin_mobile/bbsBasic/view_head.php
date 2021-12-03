<script language="javascript">
<!--
function viewImg(img){
	var url = "/adm/bbs/view_img.php?code=<?=$code?>&img=" + img;
	window.open(url, "viewImg", "height=100, width=100, menubar=no, scrollbars=no, resizable=yes, toolbar=no, status=no");
}
//-->
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="M_bbs_View">
<tr>
	<th class="M_bbs_View_th" colspan="2"><?=$catname?><?=$subject?></th>

</tr>

<tr>
	<th class="M_bbs_View_td">작성자 : <?=$name?> </th>
	<th class="M_bbs_View_td" width="70"><span class="date"><?=$wdate?></span></th>

</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td id="M_bbs_View_cont">
		<?=$upimg1?><?=$upimg2?><?=$upimg3?><?=$upimg4?><?=$upimg5?><?=$upimg6?>
		<?=$upimg7?><?=$upimg8?><?=$upimg9?><?=$upimg10?><?=$upimg11?><?=$upimg12?>
		<?=$movie1?><?=$movie2?><?=$movie3?>
		<?=$content?>
	</td>
</tr>

</table>



<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mNextPrev">
<tr>
	<th>이전글</th>
	<td><?=$prev?></td>
</tr>
<tr>
	<th>다음글</th>
	<td><?=$next?></td>
</tr>
</table>
<div style="margin:0 0 10px;"></div>
