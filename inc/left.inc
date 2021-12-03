<table width="175" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>

    	<? if(strpos($PHP_SELF,"/shop/")!==false || strpos($PHP_SELF,"/index")!==false){ ?>

    	<!-- 카테고리 -->
			<table cellpadding="0" cellspacing="0" border="0" width="180">
				<tr>
					<td><img src="/images/left_menu_top.gif"></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="180">
				<tr>
					<td><img src="/images/left_menu_img01.gif"></td>
					<td><img src="/images/left_menu_tab01_ov.gif"></td>
					<td><img src="/images/left_menu_tab02.gif"></td>
					<td><img src="/images/left_menu_img02.gif"></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="180" background="/images/left_menu_bg.gif">
				<tr>
					<td align="center" style="padding-top:10px; padding-bottom:5px; padding-left:10px;"><? include "$DOCUMENT_ROOT/inc/category.inc"; ?></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" border="0" width="180">
				<tr>
					<td><img src="/images/left_menu_bottom.gif"></td>
				</tr>
			</table>
      <!-- 카테고리 끝 -->

      <!-- 서브 레이어 메뉴 -->
      <div id='displayer' style="display:none; position:absolute; left:expression(document.body.clientWidth/2-310); top:150; z-index:7; background:url('/images/catimg/sub_cate_bg.gif') top right no-repeat;">
       <table width="150" border="0" cellpadding=3 cellspacing=0 onMouseOver="displayLay('0');" onMouseOut="disableLay('0');" style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
         <tr><td class="sub_dot"><a href="#" onfocus='blur()'>하위분류 1</a></td></tr>
         <tr><td class="sub_dot"><a href="#" onfocus='blur()'>하위분류 2</a></td></tr>
       </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:131; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
      <table width='150' cellpadding=3 cellspacing=0 onMouseOver="displayLay('1')"; onMouseOut="disableLay('1');" style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table>
      </div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:152; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
        <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('2'); onmouseout=disableLay('2') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:173; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
       <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('3'); onmouseout=disableLay('3') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:194; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
       <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('4'); onmouseout=disableLay('4') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:215; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
       <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('5'); onmouseout=disableLay('5') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:236; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
        <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('6'); onmouseout=disableLay('6') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:257; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
        <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('7'); onmouseout=disableLay('7') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:278; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
       <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('8'); onmouseout=disableLay('8') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>

      <div id='displayer' style='display:none;position:absolute;left:expression(document.body.clientWidth/2-310); top:299; width:100; z-index:7; background:url(/images/catimg/sub_cate_bg.gif) top right no-repeat;'>
       <table width='150' cellpadding=3 cellspacing=0 onmouseover=displayLay('9'); onmouseout=disableLay('9') style="background:url('/images/catimg/sub_cate_line.gif') bottom right no-repeat;">
      </table></div>
      <!-- 서브 레이어 메뉴 끝 -->
			<? } ?>


			<!-- 마이페이지 메뉴 끝 -->
      <? if(strpos($PHP_SELF,"/member/")!==false){ ?>
			<table width="175" border="0" cellpadding="0" cellspacing="0">
				<tr><td><img src="/images/catimg/cate_mypage.gif"></td></tr>
				<tr><td><a href="/member/my_info.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage01','','../images/catimg/cate_mypage01_up.gif',1)"><img src="../images/catimg/cate_mypage01.gif" name="mypage01" border="0" /></a></td></tr>
				<tr><td><a href="/member/my_out.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage02','','../images/catimg/cate_mypage02_up.gif',1)"><img src="../images/catimg/cate_mypage02.gif" name="mypage02" border="0"></a></td></tr>
				<tr><td><a href="/member/my_order.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage03','','../images/catimg/cate_mypage03_up.gif',1)"><img src="../images/catimg/cate_mypage03.gif" name="mypage03" border="0"></a></td></tr>
				<tr><td><a href="/member/my_reserve.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage04','','../images/catimg/cate_mypage04_up.gif',1)"><img src="../images/catimg/cate_mypage04.gif" name="mypage04" border="0"></a></td></tr>
				<tr><td><a href="/member/my_qna.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage05','','../images/catimg/cate_mypage05_up.gif',1)"><img src="../images/catimg/cate_mypage05.gif" name="mypage05" border="0"></a></td></tr>
				<tr><td><a href="/member/my_wish.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage06','','../images/catimg/cate_mypage06_up.gif',1)"><img src="../images/catimg/cate_mypage06.gif" name="mypage06" border="0"></a></td></tr>
				<!-- <tr><td><a href="/member/del_list.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('mypage07','','../images/catimg/cate_mypage07_up.gif',1)"><img src="../images/catimg/cate_mypage07_up.gif" name="mypage07" border="0"></a></td></tr> -->
				<tr><td><img src="/images/catimg/cate_foot.gif"></td></tr>
			</table>
    	<? } ?>
  		<!-- 마이페이지 메뉴 끝 -->


  		<!-- 고객센터 메뉴 -->
			<? if(strpos($PHP_SELF,"/center/")!==false || strpos($PHP_SELF,"/bbs/")!==false ){ ?>
			<table width="175" border="0" cellpadding="0" cellspacing="0">
				<tr><td><img src="/images/catimg/cate_cscenter.gif" width="175" height="47"></td></tr>
				<tr><td><a href="/bbs/list.php?code=notice" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter01','','../images/catimg/cate_cs01_up.gif',1)"><img src="../images/catimg/cate_cs01.gif" name="cscenter01" border="0" /></a></td></tr>
				<tr><td><a href="/bbs/list.php?code=faq" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter02','','../images/catimg/cate_cs02_up.gif',1)"><img src="../images/catimg/cate_cs02.gif" name="cscenter02" border="0"></a></td></tr>
				<tr><td><a href="/bbs/list.php?code=qna" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter03','','../images/catimg/cate_cs03_up.gif',1)"><img src="../images/catimg/cate_cs03.gif" name="cscenter03" border="0"></a></td></tr>
				<tr><td><a href="/center/guide.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter04','','../images/catimg/cate_cs04_up.gif',1)"><img src="../images/catimg/cate_cs04.gif" name="cscenter04" border="0"></a></td></tr>
				<tr><td><a href="/center/privacy.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter05','','../images/catimg/cate_cs05_up.gif',1)"><img src="../images/catimg/cate_cs05.gif" name="cscenter05" border="0"></a></td></tr>
				<tr><td><a href="/bbs/list.php?code=photo" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter06','','../images/catimg/cate_cs06_up.gif',1)"><img src="../images/catimg/cate_cs06.gif" name="cscenter06" border="0"></a></td></tr>
				<tr><td><a href="/bbs/list.php?code=review" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('cscenter07','','../images/catimg/cate_cs07_up.gif',1)"><img src="../images/catimg/cate_cs07.gif" name="cscenter07" border="0"></a></td></tr>
				<tr><td><img src="/images/catimg/cate_foot.gif"></td></tr>
			</table>
      <? } ?>
    	<!-- 고객센터 메뉴 끝 -->

    </td>
  </tr>
  <tr>
    <td height="15"></td>
  </tr>
  <tr>
    <td>

      <!-- 배너 영역 -->
      <? $code = "banner_01"; include "$DOCUMENT_ROOT/inc/banner.inc"; ?>
      <!-- 배너 영역 끝-->

    </td>
  </tr>
</table>