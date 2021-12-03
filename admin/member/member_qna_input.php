<? include "../../inc/common.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select * from wiz_consult where idx = '$idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$con_info = mysqli_fetch_object($result);
?>


      <table border="0" cellspacing="0" cellpadding="2">
		    <tr>
		      <td><img src="../image/ic_tit.gif"></td>
		      <td valign="bottom" class="tit">1:1 상담관리</td>
		      <td width="2"></td>
		      <td valign="bottom" class="tit_alt">고객이 작성한 1:1 상담을 관리합니다.</td>
		    </tr>
		  </table>
		  		
		  <br>	
      <form action="member_save.php" method="post">
      <input type="hidden" name="mode" value="consult">
      <input type="hidden" name="idx" value="<?=$idx?>">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
              <tr> 
                <td width="15%" class="t_name">작성자</td>
                <td width="85%" class="t_value"><?=$con_info->name?>(<?=$con_info->memid?>)</td>
              </tr>
              <tr> 
                <td class="t_name">제 목</td>
                <td class="t_value"><?=$con_info->subject?></td>
              </tr>
              <tr> 
                <td class="t_name">내 용</td>
                <td class="t_value"><textarea name="question" cols="70" rows="10" class="textarea" style="width:100%"><?=$con_info->question?></textarea></td>
              </tr>
              <tr> 
                <td class="t_name">답 변</td>
                <td class="t_value"><textarea name="answer" cols="70" rows="10" class="textarea" style="width:100%"><?=$con_info->answer?></textarea></td>
              </tr>
            </table></td>
        </tr>
      </table>
      
      
<div class="AW-btn-wrap">
    <button type="submit" class="on">확인</button>
    <a onclick="document.location='member_qna.php';">목록</a>
</div><!-- .AW-btn-wrap -->

      </form>
   

<? include "../footer.php"; ?>