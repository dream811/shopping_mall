<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<title>퀵링크 메뉴관리</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="wiz_style.css" rel="stylesheet" type="text/css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script language="JavaScript">
<!--
$(document).ready(function(){

	$(":checkbox").click(function(){
		var len = $(":checkbox:checked").length;

		var max = 5;
		if( len > max )
		{
			alert(max + " 개 이상 등록할 수 없습니다");
			$(this).attr("checked",false);
			return;
		}

	});
/*
	$(".quick_btn").click(function(){
		var $chk = $(":checkbox:checked");
		var max = 5;
		var quick;

		if($chk.length < 1)
		{
			alert("Quick Link에 등록할 메뉴를 하나이상 선택하세요");
			return;
		}
		else if( $chk.length > max )
		{
			alert(max + " 개 이상 등록할 수 없습니다");
			return;
		}

		quick = $chk.serialize();

		$.post("./quick_link.act.php",{quick:quick},function(data){
			alert("Quick Link 등록 완료");
			location.reload();
		});
	});
*/
});
//-->
</script>
</head>

<body>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
	<td><h3 style="background: url(image/sub/h3.gif) left 6px no-repeat;line-height: 1.6;font-size: 16px;font-weight: bold;color: #2f2f2f;padding-left: 16px;">퀵링크 메뉴관리</h3></td>
  </tr>
</table>
<br />
<form name="frm" method="post" action="menu_save.php" onSubmit="return inputCheck(this)">


<table style="margin:5px" width="820px"cellpadding=2 cellspacing=1 class="table_basic">
	<tr>
		<th>번호</th>
		<th>메뉴명</th>
		<th>경로</th>
		<th style="text-align:center">사용여부</th>
	</tr>
<?
	$sql = "select * from wiz_quickmenu order by idx asc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$no = 1;
	while($row = mysqli_fetch_array($result)){
?>
	<tr>
		<td><?=$no?></th>
		<td>
			<?=$row['title']?>
			<input type="hidden" name="title" value="<?=$row['title']?>">
		</td>
		<td><input type="text" name="url" value="<?=$row[url]?>" class="input" size="60"></td>
		<td style="text-align:center"><input type="checkbox" name="menu_use[]" value="<?=$row['idx']?>,Y" <? if($row[menu_use] == 'Y') echo 'checked' ?>></td>
	</tr>
<?
		$no++;
	}
?>
</table>

<table width="100%">
  <tr><td height="5"></td></tr>
  <tr>
    <td align="center">
		<button style="border:0" type="submit" class="quick_btn b h28 t5 color blue_big">저장</button>&nbsp;
		<button style="border:0" type="button" class="b h28 t5 color gray_big" onClick="self.close();">닫기</button>
    </td>
  </tr>
</table>
</form>

</body>
</html>

