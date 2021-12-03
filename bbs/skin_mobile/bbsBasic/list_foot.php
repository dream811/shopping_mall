</table>
<!-- 게시물 끝 -->   
  
<!-- 페이지 번호 -->
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td height="50" align="center" class="mPaging">
	<? print_pagelist($page, $lists, $page_count, $param); ?>
</td></tr></table>  
<!-- 페이지 번호끝 -->
                                                
<!-- MOBILE SEARCH -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="center">
			<table border="0" cellspacing="0" cellpadding="0">
			<form name="sfrm" action="<?=$PHP_SELF?>">
			<input type="hidden" name="code" value="<?=$code?>">
			<input type="hidden" name="category" value="<?=$category?>">
				<tr> 
					<td valign="middle">
								<select name="searchopt" class="mSch_select">
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
					<td width="2"></td>
					<td><div align="left"><input name="searchkey" type="text" class="mSch_input" value="<?=$searchkey?>" /></div></td>
					<td width="2"></td>
					<td><button type="image" class="btnS" >검색</button></td>
				</tr>
			 </form>
			</table>
		</td>
	</tr>
</table>			
<!-- //MOBILE SEARCH -->
