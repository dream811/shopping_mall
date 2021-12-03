<?
$_REQUEST["code"] = "faq";
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   // 유틸 라이브러리
include "../inc/design_info.inc"; 	// 디자인 정보
include "../inc/bbs_info.inc"; 	 	// 게시판 정보

$now_position = "<a href=/>Home</a> &gt; 고객센터 &gt; 자주하는질문";
$page_type = "faq";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
include "../inc/now_position.inc";	// 현재위치

include "../inc/bbs_info_set.inc"; 	 								// 게시판 정보

echo "<script Language=\"JavaScript\" src=\"/js/util_lib.js\"></script>";

// 게시물 분류
$sql = "select idx, gubun, catname, catimg, catimg_over from wiz_bbscat where code = '$code' order by gubun desc,idx asc";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$total = mysqli_num_rows($result);

if($total > 0) {

	//$catlist = "<a href='$PHP_SELF?ptype=list&code=".$code."'>전체</a> | ";

	$ii = 0;
	while($row = mysqli_fetch_array($result)) {

		if($total < 2 && !strcmp($row[gubun], "A")) {

		} else {

	  	if(empty($row[catimg_over])) $row[catimg_over] = $row[catimg];

	  	if(empty($category) && !strcmp($row[gubun], "A")) $row[catimg] = $row[catimg_over];
	  	
	  	if(!empty($row[catimg])) $catname = "<img src='/data/category/".$code."/".$row[catimg]."' name='bc_".$ii."' border=0 id='bc_".$ii."' onMouseOver=WIZ_swapImage('bc_".$ii."','','/data/category/".$code."/".$row[catimg_over]."',1) onMouseOut=WIZ_swapImgRestore()>";
	  	else $catname = $row[catname];

	    if($category == $row['idx']) {
	    	if(!empty($row[catimg])) $catname = "<img src='/data/category/".$code."/".$row[catimg_over]."' name='bc_".$ii."' border=0 id='bc_".$ii."' onMouseOver=WIZ_swapImage('bc_".$ii."','','/data/category/".$code."/".$row[catimg_over]."',1) onMouseOut=WIZ_swapImgRestore()>";
	    	else $catname = "<b>".$catname."</b>";
	    }

			if(!strcmp($row[gubun], "A")) {
				$catlist .= "<a href='$PHP_SELF?ptype=list&code=".$code."'>".$catname."</a>";
			} else {
	    	$catlist .= "<a href='$PHP_SELF?ptype=list&code=".$code."&category=".$row['idx']."'>".$catname."</a>";
	    }

	   	if(empty($row[catimg]))  if($ii < $total-1) $catlist .= " | ";
	    $ii++;
	  }
  }

  /* select박스형태
  $catlist = "<select name=\"category\" onChange=\"document.location='".$PHP_SELF."?ptype=list&category=' + this.value;\">";
  $catlist .= "<option value=\"\">:: 전체목록 ::</option>";
	while($row = mysqli_fetch_array($result)) {
  	$catname = $row[catname];
  	$selected = "";
		if($category == $row['idx']) $selected = "selected";
    $catlist .= "<option value=\"".$row['idx']."\" ".$selected.">".$catname."</option>";

  }
  $catlist .= "</select>";
  */

}

?>

<table border=0 cellpadding=0 cellspacing=0 width=98% align=center>
	<tr>
	  <td>
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<form name="frm" action="faq.php" method="post">
			  <tr>
			  	<td><img src="/images/faq_search.gif"></td>
				  <td><input type=text name="keyword" value="<?=$keyword?>" size=45 class="input"></input></td>
				  <td><input type="image" src="/images/but_search.gif" border=0></a></td>
				</tr>
			</form>
			</table>
	  </td>
	</tr>
	<tr>
		<td align=center>
			<table border=0 cellpadding=5 cellspacing=0 width=98%>
		  	<tr>
		  		<td background="/images/faq_bar.gif" style="padding:0 10 0 0">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr><td></td>
					<td align=right><?=$catlist?></td></tr>
					</table>
			   </td>
			 </tr>
	     <?	     
				// 게시물 쿼리
				if($category) $category_sql = " and category = '$category' ";
				if($keyword) $search_sql = " and (subject like '%$keyword%' or content like '%$keyword%') ";
				if($mybbs) $my_sql=" and memid='$wiz_session['id']'";
				
				$sql = "select idx from wiz_bbs where code = '$code' $my_sql $category_sql $search_sql order by prino desc";
				
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				$total = mysqli_num_rows($result);
				
				$idx = 0;
				$rows = $bbs_info[rows];
				$lists = $bbs_info[lists];
				if($rows == "" || $rows < 1) $rows = "20";
				if($lists == "" || $rows < 1) $lists = "5";
				
				$ttime = mktime(0,0,0,date('m'),date('d'),date('Y'));
				$page_count = ceil($total/$rows);
				if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				
				$sql = "select wb.*,wb.wdate as wtime,from_unixtime(wb.wdate, '".$bbs_info[datetype_list]."') as wdate, wc.catname, wc.caticon
								from wiz_bbs as wb left join wiz_bbscat as wc on wb.category = wc.idx
								where wb.code = '$code' $category_sql $search_sql $my_sql
								order by wb.prino desc limit $start, $rows";
				
				$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
				
				while($row = mysqli_fetch_array($result)){
	
					$content = $row['content'];
					if($row[ctype] != "H"){
						$content = str_replace("\n", "<br>", $content);
					}
	     ?>
			<tr><td bgcolor=#E3D0EB height=1></td></tr>
			<tr><td height=30><font color="#A54ACF"><b><?=$no?>. <?=$row['subject']?></b></font></td></tr>
			<tr><td bgcolor=#E3D0EB height=1></td></tr>
			<tr><td style="padding:10 15 10 15"><?=$content?></td></tr>
			<tr><td height=4 background="/images/customer_r_line.gif"></td></tr>
			<?
				$no--;
				$idx++;
			}
			?>
			</table>
			<br>
			
			<? print_pagelist($page, $bbs_info[lists], $page_count, "&code=$code&category=$category&keyword=$keyword"); ?>
			
	  </td>
	</tr>
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>