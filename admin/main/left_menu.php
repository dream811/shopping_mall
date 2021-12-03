<?
function str_insert_pattern($str, $len)
{
	$ereg = '/[^ \n<>]{".$len."}/i';

	return preg_replace($ereg, "\\0\n", $str);    // 대소문자 구분안함
}
?>
<script>
function go_popup() {

	$('#popup').bPopup({
		speed: 650,
		transition: 'slideIn',
		transitionClose: 'slideBack'
	});

};

function openMenu() {
	window.open('../menu_config.php', 'cm', 'width=850,height=700,menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100');
}

$(document).ready(function(){

	$(":checkbox").click(function(){
		var len = $(":checkbox:checked").length;

		var max = 10;
		if( len > max )
		{
			alert(max + " 개 이상 등록할 수 없습니다");
			$(this).attr("checked",false);
			return;
		}

	});

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

});
function addurl() {

	var frm = document.frm;
	var tbl = document.getElementById('quick');
	var row = tbl.insertRow(-1);
	var t = 1;
	for (i=0;i<tbl.rows[0].cells.length;i++){
		cell = row.insertCell(0);
		cell.innerHTML = "링크명 : <input type=\"text\" size='15' class=\"input\" name=\"url[]\">";
		cell.innerHTML += " Url : <input type=\"text\" size=\"50\" class=\"input\" name=\"urlname[]\">";
		cell.innerHTML += " 사용여부 : <input id='c" +(tbl.rows.length - 1) +"' type=\"checkbox\" onclick=used('c" +(tbl.rows.length - 1)+"','t"+(tbl.rows.length - 1)+"'); checked>";
		cell.innerHTML += " <input id='t" +(tbl.rows.length - 1)+"' type=\"hidden\" value='' name=\"used[]\">";

	}
}

function delurl() {

	var tbl = document.getElementById('quick');
	if (tbl.rows.length > 1) tbl.deleteRow(-1);
}

function used (idx,uid) {
	if (document.getElementById(idx).checked == true) {
		document.getElementById(uid).value = 'Y';
	}
	else {
		document.getElementById(uid).value = 'N';
	}

}
</script>
<div class="License-info">
	<div class="tit">라이센스</div>
    <div class="cont">
        <dl>
            <dt>솔루션</dt>
            <dd>Wizmall</dd>
        </dl>
        <dl>
            <dt>라이센스</dt>
            <dd><?
					if($shop_info->site_key == ''){
						echo "라이센스 비활성";
					}
					else{
						echo str_insert_pattern($shop_info->site_key,25);
					}
				?></dd>
        </dl>
        <dl>
            <dt>설치일</dt>
            <dd><?=date("Y-m-d H:i:s", $shop_info->site_date)?></dd>
        </dl>
        <dl>
            <dt>도메인</dt>
            <dd><?=$_SERVER["HTTP_HOST"];?></dd>
        </dl>
    </div><!-- .cont -->
</div><!-- .License-info -->

<? /*
<div class="left-quick">
	<div class="tit"><span>퀵</span> 링크</div>
	<a href="javascript:go_popup();void(0);" class="modify">메뉴관리</a><!-- .modify -->
	<div class="cont">
		<ul>
			<?
			$sql = "select * from wiz_quicklink where info != ''";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			$row = mysqli_fetch_array($result);
			$url_arr = explode("^^", $row[info]);
			for($ii = 0; $ii < count($url_arr) - 1; $ii++) {
				list($url, $urlname, $used) = explode("^", $url_arr[$ii]);
				if($used == 'Y'){
			?>
			<li><a href="<?=$urlname?>"><?=$url?></a></li>
			<?
				}
			}
			?>
		</ul>
	</div><!-- .cont -->
</div><!-- .left-quick -->
<form name="frm" action="../menu_save.php" method="post">
<div class="left-quick-modify" style="display:none;">
	<div class="inner-box">
		<div class="tit">퀵링크 메뉴관리</div>
		<a href="#" class="close"></a>
		<?
		$sql = "select * from wiz_quicklink where info != ''";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);
		?>
		<div class="cont">
			<a href="#" onClick="addurl()" class="AW-btn-s modify">추가</a>
			<a href="#" onClick="delurl()" class="AW-btn-s delete">삭제</a>
			<ul>
				<?
				$url_arr = explode("^^", $row[info]);
				for($ii = 0; $ii < count($url_arr) - 1; $ii++) {
					list($url, $urlname, $used) = explode("^", $url_arr[$ii]);
				?>
				<li>
					<span>링크명<input type="text" size="15" class="input" name="url[]" value="<?=$url?>"></span>
					<span>URL<input type="text" size="50" class="input" name="urlname[]" value="<?=$urlname?>"></span>
					<span><label>사용여부<input id="c<?=$ii+1?>" <? if($used == 'Y') echo checked ?> type="checkbox" onclick="used('c<?=$ii+1?>','t<?=$ii+1?>');"></label><input id="t<?=$ii+1?>" type="hidden" class="input" name="used[]" value="<?=$used?>"></span>
				</li>
				<?
				}
				?>
			</ul>
		</div><!-- .cont -->
		<div class="AW-btn-wrap">
            <button class="on" type="submit">저장</button>
            <a onclick="history.go(-1);">취소</a>
        </div><!-- .AW-btn-wrap -->
	</div><!-- .inner-box -->
</div><!-- .left-quick-modify -->
</form>
*/ ?>