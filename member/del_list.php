<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/login_check.inc";	// 로그인 체크
include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/oper_info.inc"; 		// 운영 정보

$page_type = "myshop";
$now_position = "<a href=/>Home</a> &gt; 마이페이지 &gt; <strong>배송지관리</strong>";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/mem_info.inc"; 		// 회원 정보

?>
<script language="JavaScript">
<!--

function delDel(idx){

	if(confirm("해당 배송지를 정말 삭제하시겠습니까?")){
		document.location = "del_save.php?mode=delete&idx=" + idx;
	}else{
		return;
	}

}
//-->
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:30px 0px;">

        <table width="100%" border="0" cellpadding="0" cellspacing="0">
           <tr>
             <td bgcolor="#a9a9a9" height="2"></td>
           </tr>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="20%" height="35" align="center" bgcolor="#f9f9f9"><strong>배송지명</strong></td>
            <td width="20%" align="center" bgcolor="#f9f9f9"><strong>수취인명</strong></td>
            <td width="20%" align="center" bgcolor="#f9f9f9"><strong>주소</strong></td>
            <td width="20%" align="center" bgcolor="#f9f9f9"><strong>휴대폰/연락처</strong></td>
            <td width="20%" align="center" bgcolor="#f9f9f9"><strong>기능</strong></td>
          </tr>
          <tr>
             <td colspan="8" bgcolor="#d7d7d7" height="1"></td>
          </tr>
          <?
					$sql = "select orderid from wiz_order where send_id = '$wiz_session['id']' and status != '' order by order_date desc";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
					$total = mysqli_num_rows($result);

					$rows = 12;
					$lists = 5;
					$page_count = ceil($total/$rows);
					if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
					$start = ($page-1)*$rows;

					$sql = "select * from wiz_dellist where id = '$wiz_session['id']' order by idx desc limit $start, $rows";
					$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

					while(($row = mysqli_fetch_object($result)) && $rows){
					?>
          <tr height="30">
            <td align="center"><?=$row->delname?></td>
            <td align="center"><?=$row->name?></td>
            <td align="center">
				<?
					$address = explode("|", $row->address);
					echo $address[0]." ".$address[1];
				?>
			</td>
            <td align="center"><?=$row->tphone?><br /><?=$row->hphone?></td>
            <td align="center">
				<img src="/images/btn_edit_s.gif" style="cursor:hand" onclick="location.href='del_add.php?mode=update&idx=<?=$row->idx?>'">
				<img src="/images/btn_delete_s.gif" style="cursor:hand" onclick="delDel(<?=$row->idx?>);">
			</td>
          </tr>
          <tr>
             <td colspan="8" bgcolor="#d7d7d7" height="1"></td>
          </tr>
          <?
						$rows--;
					}

					if($total <= 0){
					?>
						<tr><td colspan="8" align="center" height="50"><img src="/images/no_icon.gif" align=absmiddle> 추가된 배송지가 없습니다.</td></tr>
						<tr><td colspan="8" bgcolor="#d7d7d7" height="1"></td></tr>
					<?
					}
					?>
      	</table>
		<div style="text-align:left;margin-top:5px">
		<img style="cursor:hand" src="/images/btn_add.gif" onclick="location.href='del_add.php?mode=insert';">
		</div>
    		<table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="30" align="center">

            	<? print_pagelist($page, $lists, $page_count, ""); ?>

            </td>
          </tr>
        </table>

    </td>
  </tr>
</table>

<?
include "../inc/footer.inc"; 		// 하단디자인
?>