<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<?

if (!$year) {
	$year	= date("Y");
	$month	= date("m");
}

$prev		= date("Y-m",mktime(0,0,0,$month-1,1,$year));
$prevArray	= split("-",$prev);
$prevYear	= $prevArray[0];
$prevMonth	= $prevArray[1];

$next		= date("Y-m",mktime(0,0,0,$month+1,1,$year));
$nextArray	= split("-",$next);
$nextYear	= $nextArray[0];
$nextMonth	= $nextArray[1];

$nnext		= date("Y-m",mktime(0,0,0,$month+2,1,$year));
$nnextArray	= split("-",$nnext);
$nnextYear	= $nnextArray[0];
$nnextMonth	= $nnextArray[1];
?>

<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td><img src="../image/ic_tit.gif"></td>
    <td valign="bottom" class="tit">일정보기</td>
    <td width="2"></td>
    <td valign="bottom" class="tit_alt">일정을 관리합니다.</td>
  </tr>
</table>			
<br>	  
      
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td valign="top">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <form name="frm" action="<?=$PHP_SELF?>">
      <input type="hidden" name="code" value="<?=$code?>">
      	<tr height="20">
      		<td width="30%">
      		   <select name="year" onChange="this.form.submit();">
      	     <option value="2006">2006년
      	     <option value="2007">2007년
      	     <option value="2008">2008년
      	     <option value="2009">2009년
      	     <option value="2010">2010년
      	     <option value="2011">2011년
      	     <option value="2012">2012년
      	     <option value="2013">2013년
      	     </select>
      	     <select name="month" onChange="this.form.submit();">
      	     <option value="01">1월
      	     <option value="02">2월
      	     <option value="03">3월
      	     <option value="04">4월
      	     <option value="05">5월
      	     <option value="06">6월
      	     <option value="07">7월
      	     <option value="08">8월
      	     <option value="09">9월
      	     <option value="10">10월
      	     <option value="11">11월
      	     <option value="12">12월
      	     </select>
      		</td>
      		<td width="30%" align="center">
      		  <table cellpadding="0" cellspacing="0" border="0">
      				<tr>
      					<td><a href="<?echo $PHP_SELF?>?code=<?=$code?>&year=<?=$prevYear?>&month=<?=$prevMonth?>"><img src='../image/btn_prev.gif' border='0'></a></td>
      					<td>&nbsp;<b><?=$year?>년 <?=$month?>월</b>&nbsp;</td>
      					<td><a href="<?echo $PHP_SELF?>?code=<?=$code?>&year=<?=$nextYear?>&month=<?=$nextMonth?>"><img src='../image/btn_next.gif' border='0'></a></td>
      				</tr>
      			</table>
      		</td>
      		<td width="30%" align="right">
      			<img src="../image/btn_bbswrite.gif" style="cursor:hand" onClick="document.location='input.php?code=<?=$code?>'">
      		</td>
      	</tr>
      </form>
      </table>
      <script language="javascript">
      <!--
       year = document.frm.year;
       for(ii=0; ii<year.length; ii++){
          if(year.options[ii].value == "<?=$year?>")
             year.options[ii].selected = true;
       }
       month = document.frm.month;
       for(ii=0; ii<month.length; ii++){
          if(month.options[ii].value == "<?=$month?>")
             month.options[ii].selected = true;
       }
      -->
      </script>
      <? include "calendar.php"; ?>
    </td>
  </tr>
</table>

   
<? include "../footer.php"; ?>