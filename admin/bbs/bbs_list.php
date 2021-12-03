<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if(!isset($category)) $category = "";
if(!isset($searchopt)) $searchopt = "";
if(!isset($searchkey)) $searchkey = "";
$param = "code=$code&category=$category&searchopt=$searchopt&searchkey=$searchkey";
?>
<script language="JavaScript" type="text/javascript">
<!--

// 체크박스 전체선택
function selectAll(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = true;
			}
		}
	}
	return;
}

// 체크박스 선택해제
function selectCancel(){
	var i;
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].select_checkbox){
			if(document.forms[i].idx != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

// 체크박스선택 반전
function selectReverse(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectCancel();
	}
}

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selbbs = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selbbs = selbbs + document.forms[i].idx.value + "|";
				}
			}
	}
	return selbbs;
}

//선택게시물 삭제
function delBbs(){

	selbbs = selectValue();

	if(selbbs == ""){
		alert("삭제할 게시물을 선택하세요.");
		return false;
	}else{
		if(confirm("선택한 게시물을 정말 삭제하시겠습니까?")){
			document.location = "bbs_save.php?code=<?=$code?>&mode=delbbs&selbbs=" + selbbs;
		}
	}
}

//게시물이동
function moveBbs(){
	selbbs = selectValue();

	if(selbbs == ""){
		alert("이동할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "move.php?code=<?=$code?>&selbbs=" + selbbs;
		window.open(uri,"moveBbs","width=300,height=80");
	}
}

// 게시물복사
function copyBbs(){
	selbbs = selectValue();
	if(selbbs == ""){
		alert("복사할 게시물을 선택하세요.");
		return false;
	}else{
		var uri = "copy.php?code=<?=$code?>&selbbs=" + selbbs;
		window.open(uri,"copyBbs","width=300,height=80,resizable=yes");
	}
}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">게시물관리</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">게시물을 관리합니다.</td>
	    </tr>
	  </table>

	  <br>
    <form name="searchForm" action="bbs_list.php" method="get">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="90"><table border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 선택게시판</td></tr></table></td>
        <td>
        	<table border="0" cellpadding="0" cellspacing="0" class="t_style"><tr><td class="t_value">
        <select name="code" onChange="this.form.submit();">
        <?
      	$no = 0;
        $sql = "select * from wiz_bbsinfo where type != 'SCH'";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        while($row = mysqli_fetch_array($result)){
        	if($code == "" && $no == 0) $code = $row['code'];
        	if($row['code'] == $code) $bbs_info = $row;
        	echo "<option value='".$row['code']."'>".$row['title']."</option>";
      		$no++;
        }
        ?>
        </select>
        <?
        $sql = "select idx, catname from wiz_bbscat where code = '$code' and gubun != 'A' order by prior asc, idx asc";
        $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $total = mysqli_num_rows($result);
        if($total > 0) {
				?>
        <select name="category" onChange="this.form.submit();">
        <option value="">:: 분류 ::</option>
				<?
					while($row = mysqli_fetch_array($result)) {
						if($category == $row['idx']) $selected = "selected";
						else $selected = "";
        		echo "<option value='".$row['idx']."'>".$row['catname']."</option>";
					}
				}
				?>
				</select>
                </td></tr></table>
        </td>
        <td align="right">
        	<table border="0" cellpadding="0" cellspacing="0" class="t_style"><tr><td class="t_value">
          <select name="searchopt">
          <option value="subject">제목</option>
          <option value="content">내용</option>
          <option value="name">작성자</option>
          </select>
          <input type="text" size="13" name="searchkey" class="input">
          <input type="submit" class="AW-btn-search" value="검색" />
          </td></tr></table>
        </td>
      </tr>
      <tr><td height="3"></td></tr>
    </table>
    <script language="javascript">
    <!--
    code = document.searchForm.code;
    for(ii=0; ii<code.length; ii++){
      if(code.options[ii].value == "<?=$code?>")
      code.options[ii].selected = true;
    }
    if(document.searchForm.category != null){
    category = document.searchForm.category;
    for(ii=0; ii<category.length; ii++){
      if(category.options[ii].value == "<?=$category?>")
      category.options[ii].selected = true;
    }
  	}
    searchopt = document.searchForm.searchopt;
    for(ii=0; ii<searchopt.length; ii++){
      if(searchopt.options[ii].value == "<?=$searchopt?>")
      searchopt.options[ii].selected = true;
    }
    -->
    </script>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="layout:fixed;">
    	<form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="5%"><input type="checkbox" name="select_tmp" onClick="selectReverse(this.form)"></th>
        <th width="8%">번호</th>
        <th>제목</th>
        <th width="10%">작성자</th>
        <th width="10%">작성일</th>
        <th width="10%">조회</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
    	</form>
    <?
    include "../../inc/bbs_info.inc";

    // 공지글 가져오기

    $sql = "select wb.idx,wb.name,wb.subject,wb.notice, wb.wdate as wtime,date_format(from_unixtime(wb.wdate), '%Y-%m-%d') as wdate,wb.count, wc.catname as category
    				from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
    				where wb.code = '$code' and wb.notice = 'Y'";
    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
    while($row = mysqli_fetch_object($result)){
    ?>
      <form>
	    <input type="hidden" name="idx" value="<?=$row->idx?>">
      <tr>
        <td align="center"><input type="checkbox" name="select_checkbox"></td>
        <td align="center" height="30"><font color='red'>[공지]</font></td>
        <td style="word-break:break-all;"><a href="bbs_view.php?code=<?=$code?>&idx=<?=$row->idx?>&page=<?=$page?>"><font color='red'><?=$row->subject?></font></a></td>
        <td align="center"><?=$row->name?></td>
        <td align="center"><?=$row->wdate?></td>
        <td align="center"><?=$row->count?></td>
      </tr>
      </form>
      <tr><td colspan="20" class="t_line"></td></tr>
    <?
    }
    ?>
		<?
		if($category) $category_sql = " and category = '$category' ";
		if($searchopt) $search_sql = " and $searchopt like '%$searchkey%' ";

		if(!isset($category_sql)) $category_sql = "";
		if(!isset($search_sql)) $search_sql = "";
		$sql = "select wb.*, wb.wdate as wtime, from_unixtime(wb.wdate, '%Y-%m-%d') as wdate, wc.catname
						from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
						where wb.code = '$code' $category_sql $search_sql order by wb.prino desc";

		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$total = mysqli_num_rows($result);

		$rows = 20;
		$lists = 5;
    $ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$today = date('Ymd');
		$page_count = ceil($total/$rows);
		if(!isset($page) || !$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;

		$sql = "select wb.*, wb.wdate as wtime, from_unixtime(wb.wdate, '%Y-%m-%d') as wdate, wc.catname as category
						from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
						where wb.code = '$code' $category_sql $search_sql
						order by wb.prino desc limit $start, $rows";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

		while(($row = mysqli_fetch_array($result)) && $rows){

			$name = $row['name']; $wdate = $row['wdate']; $count = $row['count'];
			$tno = "<a href='bbs_view.php?idx=".$row['idx']."&$param'>$no</a>";
			$subject = "<a href='bbs_view.php?idx=".$row['idx']."&$param'>".$row['subject']."</a>";			//subject

			$comment=""; $category=""; $lock=""; $re=""; $new=""; $hot=""; $file=""; $re_space="";
			if($bbs_info['comment'] == "Y") $comment = "(".$row['comment'].")";
			if($row['category'] != "") $category = "[".$row['category']."]";																// category
			if($row['privacy'] == "Y") $lock = "<img src=../image/bbs/icon_lock.gif border=0 align=absmiddle>";							// privacy
			if($row['depno'] != 0) $re = "<img src='../image/bbs/icon_re.gif' border='0' align='absmiddle'>";																		// re
			//$wtime = mktime(0,0,0,substr($row['wdate'],5,2),substr($row['wdate'],8,2),substr($row['wdate'],0,4));
			$wtime = $row['wtime'];

			if(($ttime-$wtime)/86400 <= $bbs_info['newc']) $new = "<img src='../image/bbs/icon_new.gif' border='0' align='absmiddle'>";	// new
			if($row['count'] > $bbs_info['hotc']) $hot = "<img src='../image/bbs/icon_hot.gif' border='0' align='absmiddle'>";	// hot
			if(!empty($row['upfile1'])) $file = "<img src='../image/bbs/icon_file.gif' border='0' align='absmiddle'>";	// file
	    for($ii=0; $ii < $row['depno']; $ii++) $re_space .= "&nbsp;&nbsp;";						// respace

			if(!empty($row['prdcode'])) {

			 	// 상품정보
			 	$prd_sql = "select prdcode,prdname,prdimg_S1 from wiz_product where prdcode='".$row['prdcode']."'";
			 	$prd_result = mysqli_query($connect, $prd_sql);
			 	$prd_row = mysqli_fetch_array($prd_result);

			 	$prdimg = "<a href='/shop/prd_view.php?prdcode=".$prd_row['prdcode']."' target='_blank'><img src='/data/prdimg/".$prd_row['prdimg_S1']."' width='50' height='50' border='0'></a>";
			 	$prdname = $prd_row['prdname'];

			} else {

				$prdimg = "";
				$prdname = "";

			}
		?>
		  <form>
	    <input type="hidden" name="idx" value="<?=$row['idx']?>">
	    <tr class="t_tr">
	  	  <td align="center"><input type="checkbox" name="select_checkbox"></td>
        <td height="30" align="center"><?=$tno?></td>
        <td style="word-break:break-all;">

			    <table border="0" cellpadding="0" cellspacing="0">
			    <tr><td height="1"></td></tr>
			    <tr>
			      <td rowspan="2"><?=$prdimg?></td>
			      <td style="padding-left:3px"><font class="prdqna"><?=$prdname?></font></td>
			    </tr>
			    <tr>
			    	<td><?=$re_space?><?=$re?><?=$category?><?=$subject?> <?=$comment?> <?=$lock?> <?=$new?> <?=$hot?> <?=$file?></td>
			    </tr>
			    <tr><td height="1"></td></tr>
			    </table>
        </td>
        <td align="center"><?=$name?></td>
        <td align="center"><?=$wdate?></td>
        <td align="center"><?=$count?></td>
      </tr>
	      	</form>

      <tr><td colspan="20" class="t_line"></td></tr>
   	<?
   		$no--;
			$rows--;
    }
    if($total <= 0){
    ?>
    <tr class="t_tr"><td height=25 colspan=5 align=center>작성된 글이 없습니다.</td></tr>
    <tr><td colspan="20" class="t_line"></td></tr>
    <?
    }
    ?>
    </table>

    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
    	<tr>
        <td width="33%">
        	<a onClick="delBbs();" class="AW-btn">선택삭제</a>
        	<a onClick="document.location='bbs_list.php?code=<?=$code?>';" class="AW-btn">목록으로</a>
        	<a  onClick="document.location='bbs_input.php?code=<?=$code?>';" class="AW-btn">글쓰기</a>
        </td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, $param); ?></td>
        <td width="33%" align="right">
        	<a onClick="moveBbs();" class="AW-btn">게시물 이동</a>
        	<a onClick="copyBbs();" class="AW-btn">게시물 복사</a>
        </td>
      </tr>

    </table>


<? include "../footer.php"; ?>