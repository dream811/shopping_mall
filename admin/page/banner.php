<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select title from wiz_bannerinfo where name='$code'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$banner_info = mysqli_fetch_object($result);
?>
<script language="JavaScript" type="text/javascript">
<!--
function delContent(idx, ban_img){
   if(confirm('해당배너를 삭제하시겠습니까?')){
      document.location = "banner_save.php?mode=delete&code=<?=$code?>&idx=" + idx + "&ban_img=" + ban_img;
   }
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">배너관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">배너를 생성,수정,삭제 합니다.</td>
        </tr>
      </table>

      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$banner_info->title?></td>
			    <td align="right"><a onClick="document.location='banner_input.php?code=<?=$code?>'" class="AW-btn">배너등록</a></td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="10%">코드명</th>
          <th>이미지</th>
          <th>링크주소</th>
          <th>우선순위</th>
          <th>사용여부</th>
          <th width="10%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      $sql = "select idx from wiz_banner where name='$code' order by align desc, prior asc, idx asc";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $total = mysqli_num_rows($result);

	      $rows = 12;
	      $lists = 5;
	    	$page_count = ceil($total/$rows);
	    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
	    	$start = ($page-1)*$rows;
	    	$no = $total-$start;

	      $sql = "select * from wiz_banner where name='$code' order by align desc, prior asc, idx asc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	      while(($row = mysqli_fetch_object($result)) && $rows){

	         if($row->isuse == "N") $row->isuse = "사용안함";
	         else $row->isuse = "사용함";

	      ?>
        <tr align="center">
          <td height="30" align="center"><?=$row->name?></td>
          <td>
          <?
          if($row->de_type == "IMG") echo "<img src='/data/banner/$row->de_img' style='max-width:800px;'>";
          else echo "<table><tr><td>$row->de_html</td></tr></table>";
          ?>
          </td>
          <td><?=$row->link_url?></td>
          <td><?=$row->prior?></td>
          <td><?=$row->isuse?></td>
          <td>
          	<a onclick="document.location='banner_input.php?mode=update&code=<?=$code?>&idx=<?=$row->idx?>'" class="AW-btn-s modify">수정</a>
            <a onclick="delContent('<?=$row->idx?>','<?=$row->de_img?>');" class="AW-btn-s del">삭제</a>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      	<?
      		$no--;
          $rows--;
        }

        if($total <= 0){
        ?>
        	<tr><td colspan='7' align='center' height='30'>작성된 배너가 없습니다.</td></tr>
        	<tr><td colspan="20" class="t_line"></td></tr>
				<?
        }
      	?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, "&code=$code"); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>


<? include "../footer.php"; ?>