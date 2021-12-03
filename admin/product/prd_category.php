<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if($mode == "update"){
   $sql = "select * from wiz_category where catcode = '$catcode'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   $cat_info = mysqli_fetch_object($result);
}
if(!isset($cat_info)) {
  $cat_info = new stdClass();
  $cat_info->catname = "";
  $cat_info->catuse = "";
  $cat_info->catimg = "";
  $cat_info->catimg_over = "";
  $cat_info->subimg_type = "";
  $cat_info->prd_width = "";
  $cat_info->prd_height = "";
  $cat_info->prd_num = "";
  $cat_info->recom_use = "";
  $cat_info->cms_rate = "";
}
if(!isset($catcode)) $catcode = "";
if(!isset($depthno)) $depthno = "";
?>
<script Language="Javascript">
<!--

// 하위 카테고리 수수료 지정
function getCms(frm) {

	var cms = frm.cms_rate.value;

	if(cms == "" || Check_Num(cms) == false) {
		alert("수수료율은 숫자만 입력하세요.");
		frm.cms_rate.focus();
		return false;
	}

	if(confirm("모든 하위 카테고리의 수수료가 수정됩니다. \n\n하위 카테고리의 수수료를 "+cms+"%로 수정하시겠습니까?")) {
		document.location = "category_save.php?mode=cms&catcode=<?=$catcode?>&depthno=<?=$depthno?>&cms="+cms;
	}

}

//-->
</script>
			<table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td><img src="../image/ic_tit.gif"></td>
          <td valign="bottom" class="tit">상품분류관리</td>
          <td width="2"></td>
          <td valign="bottom" class="tit_alt">상품분류를 설정합니다.</td>
        </tr>
      </table>

			<br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td width="40%" valign="top">
         <table width="100%" border="0" cellspacing="1" cellpadding="0">
           <tr>
             <td valign="top">

                <table width="100%" height="400" border="0" cellspacing="6" cellpadding="6" bgcolor="B7AEAB">
                <tr><td valign="top" bgcolor="#ffffff">
                <? include "category_list.php"; ?>
                </td></tr>
                </table>

             </td>
             <td width="5"></td>
             <td>
               <a href="category_save.php?mode=updateprior&posi=up&catcode=<?=$catcode?>&depthno=<?=$depthno?>"><img src="../image/cat_up.gif" border="0"></a><br><br><br>
               <a href="category_save.php?mode=updateprior&posi=down&catcode=<?=$catcode?>&depthno=<?=$depthno?>"><img src="../image/cat_down.gif" border="0"></a>
             </td>
             <td width="10"></td>
           </tr>
         </table>
      </td>
      <td width="60%" height="400" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <td>
                <script language="JavaScript" type="text/javascript">
                <!--

                  function inputCheck(frm){
                    if(frm.catname.value == ""){
                      alert("분류명을 입력하세요");
                      frm.catname.focus();
                      return false;
                    }
                  }

                  function showCatsub(gubun){

                  	cat_sub.style.display = 'none';
                  	cat_sub2.style.display = 'none';

                  	if(gubun == "NON") cat_sub.style.display = 'none';
                  	else if(gubun == "FIL") cat_sub.style.display = '';
                  	else if(gubun == "HTM") cat_sub2.style.display = '';

						     }
								 function delConfirm(){
								   if(confirm("카테고리를 삭제 하시겠습니까?")){
								      document.location='category_save.php?mode=delete&catcode=<?=$catcode?>&depthno=<?=$depthno?>';
								   }
								 }
                 -->
                </script>
                <?
                if($mode == "") $mode = "insert";
                ?>
                <form action="category_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
                <input type="hidden" name="tmp">
                <input type="hidden" name="mode" value="<?=$mode?>">
                <input type="hidden" name="catcode" value="<?=$catcode?>">
                <input type="hidden" name="depthno" value="<?=$depthno?>">
                <table width="100%" border="0" cellspacing="0" cellpadding="0"  valign="top">
                 <tr>
                   <td bgcolor="D5D3D3">
                     <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
                       <tr>
                         <td width="20%" class="t_name">위치</td>
                         <td width="80%" class="t_value">
                         <?
                         $catname = "최상위";
                         if($catcode != ""){
                       		$catcode1 = substr($catcode,0,2);
												   $catcode2 = substr($catcode,0,4);
												   $catcode3 = substr($catcode,0,6);
												   $sql = "select * from wiz_category where (catcode like '$catcode1%' and depthno = 1)
												                                                or (catcode like '$catcode2%' and depthno = 2)
                                                                        or (catcode like '$catcode3%' and depthno = 3)
												                                                or (catcode = '$catcode') order by depthno asc";
												   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

												   while($prow = mysqli_fetch_object($result)){
												      $catname .= " &gt; <a href=prd_category.php?mode=update&catcode=$prow->catcode>$prow->catname</a>";
												   }
													}
                         	echo $catname;
                         ?>
                         </td>
                       </tr>
                       <?
                       if($catcode != ""){
                       ?>
                       <tr>
                         <td class="t_name">링크주소</td>
                         <td class="t_value">/shop/prd_list.php?catcode=<?=$catcode?></td>
                       </tr>
                       <?
                       }
                       ?>
                       <tr>
                         <td class="t_name">분류명</td>
                         <td class="t_value">
                         <input name="catname" value="<?=$cat_info->catname?>" size="30" type="text" class="input">&nbsp;
                         <input type="checkbox" name="catuse" value="N" <? if($cat_info->catuse == "N") echo "checked"; ?>>분류숨김
                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">메뉴이미지</td>
                         <td class="t_value">
                         <?
                         if(is_file("../../data/catimg/$cat_info->catimg")) echo "<img src='/data/catimg/$cat_info->catimg'> <a href=category_save.php?mode=delcatimg&catcode=$catcode&depthno=$depthno class='AW-btn-s del'>삭제</a><br>";
                         ?>
                         <input name="catimg" type="file" class="input">
                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">롤오버이미지</td>
                         <td class="t_value">
                         <?
                         if(is_file("../../data/catimg/$cat_info->catimg_over")) echo "<img src='/data/catimg/$cat_info->catimg_over'> <a href=category_save.php?mode=delcatimg_over&catcode=$catcode&depthno=$depthno class='AW-btn-s del'>삭제</a><br>";
                         ?>
                         <input name="catimg_over" type="file" class="input">
                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">서브상단</td>
                         <td class="t_value">
                         <input type="radio" name="subimg_type" onClick="showCatsub('NON');" value="NON" <? if($cat_info->subimg_type == "NON" || $cat_info->subimg_type == "") echo "checked"; ?>>없음
                         <input type="radio" name="subimg_type" onClick="showCatsub('FIL');" value="FIL" <? if($cat_info->subimg_type == "FIL") echo "checked"; ?>>이미지
                         <input type="radio" name="subimg_type" onClick="showCatsub('HTM');" value="HTM" <? if($cat_info->subimg_type == "HTM") echo "checked"; ?>>HTML

                          <div id='cat_sub' style="display:<? if($cat_info->subimg_type == "FIL") echo "show"; else echo "none"; ?>">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="t_value">
                            <?
                            if(is_file("../../data/subimg/$cat_info->subimg")){
                            	$img_ext = substr($cat_info->subimg,-3);
                           	echo "<img src='/data/subimg/$cat_info->subimg' width='290' height='50'>";
                            }
                            ?>
                            <input name="subimg" type="file" class="input">
                            </td>
                          </tr>
                          </table>
                          </div>

                          <div id='cat_sub2' style="display:<? if($cat_info->subimg_type == "HTM") echo "show"; else echo "none"; ?>">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td class="t_value">
                            <textarea name="subimg02" cols="45" rows="5" class="textarea"><?=$cat_info->subimg?></textarea>
                            </td>
                          </tr>
                          </table>
                          </div>

                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">상품크기</td>
                         <td class="t_value">
                         가로 <input type="text" name="prd_width" value="<?=$cat_info->prd_width?>" size="5" class="input"> px&nbsp;
                         세로 <input type="text" name="prd_height" value="<?=$cat_info->prd_height?>" size="5" class="input"> px
                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">상품진열수</td>
                         <td class="t_value">
                         <input type="text" name="prd_num" value="<? if($cat_info->prd_num=="") echo "20"; else echo $cat_info->prd_num; ?>" size="5" class="input"> 개&nbsp;
                         </td>
                       </tr>
                       <tr>
                         <td class="t_name">추천상품 진열</td>
                         <td class="t_value">
                         <input type="radio" name="recom_use" value="Y" <? if($cat_info->recom_use == "Y") echo "checked";?>>사용
                         <input type="radio" name="recom_use" value="N" <? if($cat_info->recom_use == "N" || $cat_info->recom_use == "" ) echo "checked";?>>사용안함<br>
                         상품목록 페이지 상단에 추천상품 진열
                         </td>
                       </tr>
                       <tr>
                       	<td class="t_name">수수료율</td>
                       	<td class="t_value">
													<input type="text" name="cms_rate" value="<?=$cat_info->cms_rate?>" size="5" class="input" maxlength="3">%
													<? if(!empty($catcode) && $depthno < 3) { ?>
													<input type="button" value="하위 카테고리 적용" class="AW-btn" onClick="getCms(this.form)">
													<? } ?>
                       	</td>
                      </tr>
                     </table>
                   </td>
                 </tr>
               </table>
               <table width="10" height="10" border="0" cellpadding="0" cellspacing="0">
                 <tr>
                   <td></td>
                 </tr>
               </table>
               <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
               <?
               if($mode == "insert"){
               ?>
                 <tr>
                   <td align="center"><div class="AW-btn-wrap"><button type="submit" class="on">등록</button></div></td>
                 </tr>
               <?
               }else if($mode == "update"){
               ?>
                 <tr>
                   <td width="33%">
                   <?
                   if($depthno != 4){
                   ?>
                   <a onClick="document.location='prd_category.php?mode=insert&catcode=<?=$catcode?>&depthno=<?=$depthno?>';" class="AW-btn">하위분류등록</a>
                   <?
                   }
                   ?>
                   </td>
                   <td width="33%" align="center">
                        <div class="AW-btn-wrap">
                            <button type="submit" class="on">수정</button>
                            <a onClick="delConfirm();">삭제</a>
                        </div><!-- .AW-btn-wrap -->
                   </td>
                   <td width="33%"></td>
                 </tr>
               <?
               }
               ?>
               </table>
               </form>

<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    - 카테고리 정보 수정시 카테고리 클릭후 오른쪽에서 변경합니다.<br>
    - 카테고리 순서 변경시 클릭후 위아래 화살표를 이용합니다.<br>
    - 상품 가로, 세로 사이즈를 입력하면 임의로 사이즈 변경이 가능합니다.<br>&nbsp; &nbsp;임의 변경시 이미지가 깨질 수 있습니다.
    </div><!-- .cont -->
</div><!-- .AW-manage-checkinfo -->

             </td>
           </tr>
         </table>
      </td>
      </tr>
      </table>

<? include "../footer.php"; ?>