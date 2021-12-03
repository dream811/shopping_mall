</table>
<!-- 게시물 끝 -->   
  
<!-- 페이지 번호 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" align="center">
			<? print_pagelist($page, $lists, $page_count, $param); ?>
    </td>
  </tr>
</table>  
<!-- 페이지 번호끝 -->

<!-- 검색 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="50" align="center" bgcolor="#f9f9f9" style="border-top:1px solid #a9a9a9; border-bottom:1px solid #d7d7d7; padding:5px 0px;">
        
        <table width="0%" border="0" cellpadding="0" cellspacing="0">
        <form name="sfrm" action="<?=$PHP_SELF?>">
      	<input type="hidden" name="code" value="<?=$code?>">
      	<input type="hidden" name="category" value="<?=$category?>">
          <tr>
            <td style="padding-right:10px;"><img src="<?=$skin_dir?>/image/search_tit.gif" width="47" height="9" border="0" /></td>
            <td style="padding-right:5px;">
							<select name="searchopt" class="select">
							<option value="subject">제 목</option>
							<option value="content">내 용</option>
							<option value="subcon">제목 + 내용</option>
							<option value="name">작성자</option>
							<option value="memid">아이디</option>
							</select>	
							<script language="javascript">
							<!--
							searchopt = document.sfrm.searchopt;
							for(ii=0; ii<searchopt.length; ii++){
							 if(searchopt.options[ii].value == "<?=$searchopt?>")
							    searchopt.options[ii].selected = true;
							}
							-->
							</script>
            </td>
            <td style="padding-right:10px;"><input name="searchkey" type="text" class="search_input" value="<?=$searchkey?>" size="50"></td>
            <td><input type="image" src="<?=$skin_dir?>/image/btn_search.gif" border="0" align="absmiddle" width="75" height="21" /></td>
          </tr>
        </form>
        </table>
        
    </td>
  </tr>
</table>  
<!-- 검색 끝 -->                                                 

<!-- 버튼 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td align="left" style="padding-top:10px"><?=$sel_delete_btn?> <?=$sel_copy_btn?> <?=$sel_move_btn?> <?=$order_btn?></td>
    <td align="right" style="padding-top:10px"><?=$list_btn?>&nbsp;<?=$write_btn?></td>
  </tr>
</table>  
<!-- 버튼 끝 -->