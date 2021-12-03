<?
if($bbs_info[comment] == "Y"){
	get_spam_check(); // 자동등록글체크
?>
<script language="javascript">
<!--

function deleteComment(idx){
	var url = "/bbs/save.php?mode=delco&code=<?=$code?>&bbs_idx=<?=$idx?>&idx=" + idx;
	window.open(url, "deleteComment", "height=175, width=300, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, left=250, top=250");
}

function memberCheck(){
<?
if($cpermi < $mem_level){
	echo "alert('작성권한이 없습니다.');";
	if(empty($hide_passwd_start)) {
		echo "document.comment.name.value=''; comment.name.blur();comment.passwd.blur();";
	}
	echo "comment.content.blur();return false;";
}else {
	echo "return true;";
}
?>
}

function commentCheck(frm){

	if(memberCheck()){
		if(frm.content.value == ""){
	      alert("내용을 입력하세요");
	      frm.content.focus();
	      return false;
	   }
	   if(frm.name != undefined) {
		   if(frm.name.value == ""){
		      alert("이름을 입력하세요");
		      frm.name.focus();
		      return false;
		   }
		 }
		 if(frm.passwd != undefined) {
		   if(frm.passwd.value == ""){
		      alert("비밀번호를 입력하세요");
		      frm.passwd.focus();
		      return false;
		   }
		 }

	  if (frm.vcode != undefined && (hex_md5(frm.vcode.value) != md5_norobot_key)) {
	  	alert("자동등록방지코드를 정확히 입력해주세요.");
	    frm.vcode.focus();
	    return false;
		}

	}else{
		return false;
	}
}
-->
</script>
<div class="AW_comment_area">
<?

	$writer = $wiz_session[name];

	$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$total = mysqli_num_rows($result);

	if($rows == "") $rows = "12";
	if($lists == "") $lists = "5";

	$page_count = ceil($total/$rows);
	if(!$cpage || $cpage > $page_count) $cpage = 1;
	$start = ($cpage-1)*$rows;
	$no = $total-$start;

	$sql = "select * from wiz_comment where ctype='BBS' and cidx='$idx' order by idx desc limit $start, $rows";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	while($com_row = mysqli_fetch_array($result)){

	  $name = $com_row[name];
	  $ip = $com_row[wip];
	  $wdate = $com_row['wdate'];
		$content = str_replace("\n", "<br>", $com_row['content']);

		// 버튼설정
		$codel_btn = "<a href='".$bbs_info['auth_url']."?code=$code&mallid=$mallid&mode=delco&bbs_idx=$idx&idx=".$com_row['idx']."' class='codel_btn'>삭제</a>";

		include "$DOCUMENT_ROOT/$skin_dir/com_body.php";

	}

	include "$DOCUMENT_ROOT/$skin_dir/com_foot.php";

}
?>
</div><!-- //AW_comment_area -->