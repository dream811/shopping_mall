<script>
function mallWish() {
	<? if($wiz_session['id'] == "") { ?>
	alert("로그인 후 이용해주세요.");
	<? } else if($mallid == "") { ?>
		alert("입점업체 정보가 전달되지 않았습니다.");
	<? } else { ?>
		document.location = "/shop/prd_save.php?mode=mall_wish_add&mallid=<?=$mallid?>";
	<? } ?>
}
</script>

<?php
$m_sql = "select * from wiz_mall where id = '$mallid'";
$m_result = mysqli_query($connect, $m_sql) or error(mysqli_error($connect));
$m_info = mysqli_fetch_array($m_result);

if($m_info[photo] != "" && file("../data/mall/".$m_info[photo])) $photo = "../data/mall/".$m_info[photo];
else $photo = "../images/minishop_img.gif";

// 단골샵
$sql = "select count(idx) as cnt from wiz_mall_wish where mallid = '$mallid'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$row = mysqli_fetch_array($result);
$mall_wish_cnt = $row['cnt'];
?>
<div class="minimall_area clearfix">
	<div class="mini_img">
		<a href="<?=$PHP_SELF?>?mallid=<?=$mallid?>"><img src="<?=$photo?>" class="img_border"></a>
	</div>
	<div class="mini_info">
		<dl>
			<dt>
				<?=$m_info[com_name]?>
				<button type="button" class="mall_save" onclick="mallWish();">찜스토어 <?=number_format($mall_wish_cnt)?>명</button>
				<a href="minibbs_list.php?code=qna&mallid=<?=$mallid?>">상품 Q&#38;A</a>
				<a href="minibbs_list.php?code=review&mallid=<?=$mallid?>">상품 후기</a>
			</dt>
			<dd><?=nl2br($m_info[intro])?></dd>
		</dl>
		<div class="mall_basic">
			<h3>기본정보</h3>
			<table width="100%" class="basic_info">
				<tr>
					<th>판매자</th>
					<td><?=$m_info[com_owner]?>(<?=$m_info['id']?>)</td>
					<th>개설일</th>
					<td><?=$m_info[adate]?></td>
				</tr>
				<tr>
					<th>대표번호</th>
					<td><?=$m_info[com_tel]?></td>
					<th>이메일</th>
					<td><?=$m_info[email]?></td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3"><?=$m_info[address]?> <?=$m_info[address2]?></td>
				</tr>
			</table>
		</div>
	</div>
</div>
