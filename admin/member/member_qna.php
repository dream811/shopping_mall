<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>

<script language="JavaScript" type="text/javascript">
<!--
//체크박스선택 반전
function onSelect(form){
	if(form.select_tmp.checked){
		selectAll();
	}else{
		selectEmpty();
	}
}

//체크박스 전체선택
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

//체크박스 선택해제
function selectEmpty(){

	var i;

	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택상담 삭제
function qnaDelete(){

	var i;
	var selconsult = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selconsult = selconsult + document.forms[i].idx.value + "|";
				}
			}
	}

	if(selconsult == ""){
		alert("삭제할 상담을 선택하지 않았습니다.");
		return;
	}else{
		if(confirm("선택한 상담을 정말 삭제하시겠습니까?")){
			document.location = "member_save.php?mode=condelete&selconsult=" + selconsult;
		}else{
			return;
		}
	}
	return;
	
}

function searchEstimate(searchopt,keyword){
	document.searchForm.searchopt.value = searchopt;
	document.searchForm.keyword.value = keyword;
	document.searchForm.page.value = "1";
	document.searchForm.submit();
}
//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">1:1 상담관리</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">고객이 작성한 1:1 상담을 관리합니다.</td>
	    </tr>
	  </table>
	  
	  <br>	
        <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
        <input type="hidden" name="page" value="<?=$page?>">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><table border="0" cellpadding="0" cellspacing="0"><tr><td class="tit_sub"><img src="../image/ics_tit.gif"> 1:1상담목록</td></tr></table></td>
        <td align="right">
        <table cellspacing="0" cellpadding="0" border="0" class="t_style">
        <tr>
        <td class="t_value">
        <select name="searchopt" onChange="this.form.page.value=1;">
        <option value="">:: 선택 ::
        <option value="name" <? if($searchopt == "name") echo "selected"; ?>>작성자
        <option value="memid" <? if($searchopt == "memid") echo "selected"; ?>>아이디
        </select>
        <input type="text" name="keyword" value="<?=$keyword?>" size="13" class="input">
        <input type="submit" value="검색" class="AW-btn-search" />
        </td>
        </tr>
        </table>
        </td>
      </tr>
      <tr><td height="3"></td></tr>
    </table> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="30" align="center"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
        <th width="60">번호</th>
        <th width="80">처리상황</th>
        <th>제목</th>
        <th width="80">작성자</th>
        <th width="80">작성일</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>       
      </form>
	    <?
	    if(!empty($searchopt)) $search_sql = " where $searchopt like '%$keyword%' ";
	    
	    $sql = "select idx from wiz_consult $search_sql order by idx desc";
	    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	    $total = mysqli_num_rows($result);
	    
	    $rows = 12;
	    $lists = 5;
	    $page_count = ceil($total/$rows);
	    if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
	    $start = ($page-1)*$rows;
	    $no = $total-$start;
	  	
	    $sql = "select * from wiz_consult $search_sql order by idx desc limit $start, $rows";
	    $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	    
	    while(($row = mysqli_fetch_object($result)) && $rows){
	    ?>
	    <form>
	    <input type="hidden" name="idx" value="<?=$row->idx?>">
	      <tr class="t_tr"> 
	        <td align="center" height="30"><input type="checkbox" name="select_checkbox"></td>
	        <td align="center" align="center"><?=$no?></td>
	        <td align="center"><? if($row->status == "Y") echo "<font color=red>[답변완료]</font>"; else echo "[접수완료]"; ?></td>
	        <td><a href="member_qna_input.php?idx=<?=$row->idx?>"><?=$row->subject?></a></td>
	        <td align="center"><a href="javascript:searchEstimate('name','<?=$row->name?>');" class="3"><?=$row->name?></a>(<a href="javascript:searchEstimate('memid','<?=$row->memid?>');" class="3"><?=$row->memid?></a>)</td>
	        <td align="center"><?=$row->wdate?></td>
	      </tr>
	    <tr><td colspan="20" class="t_line"></td></tr>
	    <?
				$no--;
				$rows--;
			}
			if($total <= 0){
			?>
				<tr><td height=30 colspan=10 align=center>등록된 상담이 없습니다.</td></tr>
				<tr><td colspan='20' class='t_line'></td></tr>
			<?
			}
	    ?>
    </table>

	    </form>
    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
      <tr> 
        <td width="33%"><a onClick="qnaDelete();" class="AW-btn">선택삭제</a></td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, ""); ?></td>
        <td width="33%"></td>
      </tr>
    </table>


<? include "../footer.php"; ?>