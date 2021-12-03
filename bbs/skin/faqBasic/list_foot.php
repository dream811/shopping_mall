</table>
<!-- 게시물 끝 -->   
  
<!-- 페이지 번호 -->
<? print_pagelist($page, $lists, $page_count, $param); ?>
<!-- 페이지 번호끝 -->

<!-- 검색 -->
<div class="AW_bbs_search">
	<form name="sfrm" action="<?=$PHP_SELF?>">
	<input type="hidden" name="code" value="<?=$code?>">
	<input type="hidden" name="category" value="<?=$category?>">
		<select name="searchopt" class="select">
			<option value="subject">제 목</option>
			<option value="content">내 용</option>
			<option value="subcon">제목 + 내용</option>
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
		<input name="searchkey" type="text" class="search_input" value="<?=$searchkey?>" size="50">
		<button type="submit">검색</button>
	</form>
</div>
<!-- 검색 끝 -->                                                 

<!-- 버튼 -->
<div class="AW_board_btn clearfix">
	<div class="left">
		<?=$sel_delete_btn?> <?=$sel_copy_btn?> <?=$sel_move_btn?> <?=$order_btn?>
	</div>
	<div class="right">
		<?=$list_btn?><?=$write_btn?>
	</div>
</div>
<!-- 버튼 끝 -->