<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
function deleteBanner( idx, code ){

	if(code == "main_roll" || code == "main_mid" || code == "main_bottom") {
		alert("삭제할 수 없는 그룹입니다.");
	} else if(confirm('선택한 배너그룹을 삭제하시겠습니까?\n\n삭제한 데이타는 복구할수 없습니다.')){
		document.location = 'banner_save.php?mode=ban_group_delete&idx=' + idx + '&code=' + code + '&page=<?=$page?>';
	}
}
//-->
</script>

			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">배너그룹관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">배너그룹을 생성,수정,삭제 합니다.</td>
        </tr>
      </table>

      <?
      $sql = "select idx from wiz_bannerinfo order by idx asc";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      $total = mysqli_num_rows($result);

      $rows = 12;
      $lists = 5;
    	$page_count = ceil($total/$rows);
    	if(!isset($page) || $page < 1 || $page > $page_count) $page = 1;
    	$start = ($page-1)*$rows;
    	$no = $total-$start;
      ?>
      <br>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>총 배너그룹수 : <b><?=$total?></b></td>
          <td align="right"><a onClick="document.location='banner_input.php?mode=ban_group_insert';" class="AW-btn">배너그룹생성</a></td>
        </tr>
        <tr><td height="3"></td></tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td class="t_rd" colspan=20></td></tr>
        <tr class="t_th">
          <th width="15%">그룹명</th>
          <th>코드</th>
          <th>이미지</th>
          <th>형태</th>
          <th>배너갯수</th>
          <th>사용여부</th>
          <th width="15%">기능</th>
        </tr>
        <tr><td class="t_rd" colspan=20></td></tr>
	      <?
	      $sql = "select * from wiz_bannerinfo order by idx asc limit $start, $rows";
	      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	      while(($row = mysqli_fetch_object($result)) && $rows){

	      $sql = "select * from wiz_banner where name='$row->name'";
	      $result2 = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	      $ban_image = mysqli_num_rows($result2);

	      	if($row->types == "W") $row->types = "가로";
	        else $row->types = "세로";

	        if($row->isuse == "N") $row->isuse = "사용안함";
	        else $row->isuse = "사용함";
	      ?>
        <tr align="center" class="t_tr">
          <td height="30" align="center"><?=$row->title?></td>
          <td align="center"><?=$row->name?></td>
          <td align="center"><?=$ban_image?></td>
          <td align="center"><?=$row->types?></td>
          <td align="center"><?=$row->types_num?></td>
          <td align="center"><?=$row->isuse?></td>
          <td>
			<a onClick="document.location='banner.php?code=<?=$row->name?>'" class="AW-btn-s">배너관리</a>
			<a onclick="document.location='banner_input.php?mode=ban_group_update&idx=<?=$row->idx?>'" class="AW-btn-s modify">수정</a>
            <? //if($row->name != "banner_01" && $row->name != "banner_02" && $row->name != "banner_03") { ?>
			<a onClick="deleteBanner('<?=$row->idx?>', '<?=$row->name?>');" class="AW-btn-s del">삭제</a>
            <? //} ?>
          </td>
        </tr>
        <tr><td colspan="20" class="t_line"></td></tr>
      	<?
      		$no--;
          $rows--;
        }

        if($total <= 0){
      	?>
        	<tr><td colspan='7' align='center' height='30'>작성된 배너 그룹이 없습니다.</td></tr>
        	<tr><td colspan="20" class="t_line"></td></tr>
      	<?
        }
      	?>
      </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td height="5"></td></tr>
      	<tr>
      		<td width="33%"></td>
      		<td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
      		<td width="33%"></td>
      	</tr>
      </table>


<? include "../footer.php"; ?>