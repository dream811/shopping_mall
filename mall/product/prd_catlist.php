<?
include "../../inc/common.inc";

function print_position($catcode){
	
	global $prdcode;
	
   $catcode1 = substr($catcode,0,2);
   $catcode2 = substr($catcode,0,4);
   $sql = "select * from wiz_category where catuse != 'N' and (catcode like '$catcode1%' and depthno = 1)
                                                or (catcode like '$catcode2%' and depthno = 2)
                                                or (catcode = '$catcode')";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   
   $now_position = " &nbsp; Home";
   while($row = mysqli_fetch_object($result)){
      $now_position .= " &gt; $row->catname";
   }
   
   $now_position .= " <a href=prd_save.php?mode=catlist&submode=delete&prdcode=$prdcode&catcode=$catcode><font color=red>[삭제]</font></a>";
   
   return $now_position;
}

?>
<html>
<head>
<title>:: 상품 카테고리 ::</title>
<link href="../style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript">
<!--
var prd_class = new Array();
<?
   $no = 0;
   $sql = "select catcode, catname, depthno from wiz_category order by priorno01 asc, priorno02 asc, priorno03 asc";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   $total = mysqli_num_rows($result);
   while($row = mysqli_fetch_object($result)){
      
      $code01 = substr($row->catcode,0,2);
      $code02 = substr($row->catcode,0,4);
      $code03 = substr($row->catcode,0,6);
      
      if($row->depthno == 1){ $catcode = $code01; $parent = 0; }
      if($row->depthno == 2){ $catcode = $code02; $parent = $code01; }
      if($row->depthno == 3){ $catcode = $code03; $parent = $code02; }
?>

prd_class[<?=$no?>] = new Array();
prd_class[<?=$no?>][0] = "<?=$catcode?>";
prd_class[<?=$no?>][1] = "<?=$row->catname?>";
prd_class[<?=$no?>][2] = "<?=$parent?>";
prd_class[<?=$no?>][3] = "<?=$row->depthno?>";

<?
   $no++;
   }
?>
var tno = <?=$total?>;

function setClass01(){
   
  var arrayClass = eval("document.write_form.class01");
  var arrayClass1 = eval("document.write_form.class02");
  var arrayClass2 = eval("document.write_form.class03");

  arrayClass.options[0] = new Option(":: 대분류 ::","");
  arrayClass1.options[0] = new Option(":: 중분류 ::","");
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='1'){
		 arrayClass.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }
}

function changeClass01(){
  
  var arrayClass = eval("document.write_form.class01");
  var arrayClass1 = eval("document.write_form.class02");
  var arrayClass2 = eval("document.write_form.class03");
  
  var selidx = arrayClass.selectedIndex;
  var selvalue = arrayClass.options[selidx].value;
  
  arrayClass1.options.length=0;
  arrayClass2.options.length=0;
  arrayClass1.options[0] = new Option(":: 중분류 ::","");
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='2' && prd_class[no][2]==selvalue){
		 arrayClass1.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }
  
}

function changeClass02(){
   
  var arrayClass1 = eval("document.write_form.class02");
  var arrayClass2 = eval("document.write_form.class03");
  
  var selidx = arrayClass1.selectedIndex;
  var selvalue = arrayClass1.options[selidx].value;
  
  arrayClass2.options.length=0;
  arrayClass2.options[0] = new Option(":: 소분류 ::","");

  for(no=0,sno=1 ; no < tno ; no++){
	  if(prd_class[no][3]=='3' && prd_class[no][2]==selvalue){
		 arrayClass2.options[sno] = new Option(prd_class[no][1],prd_class[no][0]);
		 sno++;
	  }
  }
  
}

function changeClass03(){
}

function showChangeprice(){
   if(write_form.changeprice.checked == true){
    divprice.style.display = '';
  }else{
    divprice.style.display='none';
  }
}
function showOption02(){
   if(option02.style.display == ''){
    option02.style.display = 'none';
  }else{
    option02.style.display='';
  }
}
function showOption03(){
   if(option03.style.display == ''){
    option03.style.display = 'none';
  }else{
    option03.style.display='';
  }
}

-->
</script>
<body onLoad="setClass01();" topmargin=0 leftmargin=0>
<table><tr><td height="4"></td></table>
<table width="98%" align="center" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="tit_sub"><img src="../image/ics_tit.gif"> <?=$product?>상품 카테고리</td>
  </tr>
</table>
<table width="98%" align="center" border="0" cellspacing="1" cellpadding="2" class="t_style">
<form name="write_form" action="prd_save.php" method="post">
<input type="hidden" name="mode" value="catlist">
<input type="hidden" name="submode" value="insert">
<input type="hidden" name="prdcode" value="<?=$prdcode?>">
  <tr> 
    <td height="25" width="120" class="t_name">&nbsp; &nbsp; 현재분류</td>
    <td width="380" class="t_value">
      <table>
      <?
      $sql = "select * from wiz_cprelation where prdcode='$prdcode'";
      $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
      while($row = mysqli_fetch_object($result)){
      	echo "<tr><td>".print_position($row->catcode)."</td></tr>";
      }
      ?>
      </table>
    </td>
  <tr> 
    <td height="25" class="t_name">&nbsp; &nbsp; 분류추가<br></td>
    <td class="t_value"> &nbsp; 
    <select name="class01" onChange="changeClass01();">
    </select>
    <select name="class02" onChange="changeClass02();">
    </select>
    <select name="class03" onChange="changeClass03();">
    </select>
		<input type="submit" value="등록하기" />
    </td>
  </tr>
</table>
</form>
</body>
</html>