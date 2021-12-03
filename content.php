<?
include "./inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "./inc/util.inc"; 	      // 라이브러리 함수

$sql = "select * from wiz_content where idx='$con_idx'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$page_info = mysqli_fetch_object($result);

$now_position = "<a href='/'>Home</a> &gt; ".$page_info->title;

include "./inc/header.inc"; 			// 상단디자인
?>

<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<tr>
		<td align="left" style="text-align:left" valign="top">
		<?=$page_info->content?>
		</td>
	</tr>
</table>

<?
include "./inc/footer.inc"; 			// 하단디자인
?>