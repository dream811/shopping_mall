<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
if(empty($mode)) $mode = "insert";

if($mode == "update"){
   $sql = "select * from wiz_brand where idx = '$idx'";
   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
   $brd_info = mysqli_fetch_array($result);
}
?>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">브랜드관리</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">브랜드를 설정합니다.</td>
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
                <? include "brand_list.php"; ?>
                </td></tr>
                </table>

             </td>
             <td width="5"></td>
             <td>
               <br><br><br>
               <a href="brand_save.php?mode=updateprior&posi=up&idx=<?=$idx?>"><img src="../image/cat_up.gif" border="0"></a><br><br><br><br>
               <a href="brand_save.php?mode=updateprior&posi=down&idx=<?=$idx?>"><img src="../image/cat_down.gif" border="0"></a>
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
                    if(frm.brdname.value == ""){
                      alert("분류명을 입력하세요");
                      frm.brdname.focus();
                      return false;
                    }
                  }
                  
                  function showBrdsub(gubun){

                  	brd_sub.style.display = 'none';
                  	brd_sub2.style.display = 'none';
                  	
                  	if(gubun == "NON") brd_sub.style.display = 'none';
                  	else if(gubun == "FIL") brd_sub.style.display = '';
                  	else if(gubun == "HTM") brd_sub2.style.display = '';

						     }
								 function delConfirm(){
								   if(confirm("브랜드를 삭제 하시겠습니까?")){
								      document.location='brand_save.php?mode=delete&idx=<?=$idx?>';
								   }
								 }
                 -->
                </script>
                <?
                if($mode == "") $mode = "insert";
                ?>
                <form action="brand_save.php" method="post" enctype="multipart/form-data" onSubmit="return inputCheck(this);">
                <input type="hidden" name="tmp">
                <input type="hidden" name="mode" value="<?=$mode?>">
                <input type="hidden" name="idx" value="<?=$idx?>">
                <table width="100%" border="0" cellspacing="0" cellpadding="0"  valign="top">
                 <tr>
                   <td bgcolor="D5D3D3">
                     <table width="100%" border="0" cellspacing="1" cellpadding="2" class="t_style">
                       <tr> 
                         <td width="20%" class="t_name">위치</td>
                         <td width="80%" class="t_value">
                         <?
                         $brdname = "최상위";
                         if($idx != ""){
												   $sql = "select * from wiz_brand where idx = '$idx'";
												   $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
												   
												   while($prow = mysqli_fetch_object($result)){
												      $catname .= " &gt; <a href=prd_brand.php?mode=update&idx=$prow->idx>$prow->brdname</a>";
												   }
													}
                         	echo $brdname;
                         ?>
                         </td>
                       </tr>
                       <?
                       if($idx != ""){
                       ?>
                       <tr> 
                         <td class="t_name">링크주소</td>
                         <td class="t_value">/shop/prd_list.php?brand=<?=$idx?></td>
                       </tr>
                       <?
                       }
                       ?>
                       <tr> 
                         <td class="t_name">브랜드명</td>
                         <td class="t_value">
                         <input name="brdname" value="<?=$brd_info[brdname]?>" size="30" type="text" class="input">&nbsp; 
                         <input type="checkbox" name="brduse" value="N" <? if($brd_info[brduse] == "N") echo "checked"; ?>>브랜드숨김
                         </td>
                       </tr>
                       <!--tr> 
                         <td class="t_name">메뉴이미지</td>
                         <td class="t_value">
                         <?
                         if(is_file("../../data/brdimg/$brd_info[brdimg]")) echo "<img src='/data/brdimg/$brd_info[brdimg]'> <a href=brand_save.php?mode=delbrdimg&idx=$idx><font color=red>[삭제]</font></a>";
                         ?>
                         <input name="brdimg" type="file" class="input">
                         </td>
                       </tr>
                       <tr> 
                         <td class="t_name">롤오버이미지</td>
                         <td class="t_value">
                         <?
                         if(is_file("../../data/brdimg/$brd_info[brdimg_over]")) echo "<img src='/data/brdimg/$brd_info[brdimg_over]'> <a href=brand_save.php?mode=delbrdimg_over&idx=$idx><font color=red>[삭제]</font></a>";
                         ?>
                         <input name="brdimg_over" type="file" class="input">
                         </td>
                       </tr//-->
                       <tr> 
                         <td class="t_name">서브상단</td>
                         <td class="t_value">
                         <input type="radio" name="subimg_type" onClick="showBrdsub('NON');" value="NON" <? if($brd_info[subimg_type] == "NON" || $brd_info[subimg_type] == "") echo "checked"; ?>>없음
                         <input type="radio" name="subimg_type" onClick="showBrdsub('FIL');" value="FIL" <? if($brd_info[subimg_type] == "FIL") echo "checked"; ?>>이미지
                         <input type="radio" name="subimg_type" onClick="showBrdsub('HTM');" value="HTM" <? if($brd_info[subimg_type] == "HTM") echo "checked"; ?>>HTML

                          <div id='brd_sub' style="display:<? if($brd_info[subimg_type] == "FIL") echo "show"; else echo "none"; ?>">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td class="t_value">
                            <?
                            if(is_file("../../data/subimg/$brd_info[subimg]")){
                            	$img_ext = substr($brd_info[subimg],-3);
                           	echo "<img src='/data/subimg/$brd_info[subimg]' width='290' height='50'>";
                            }
                            ?>
                            <input name="subimg" type="file" class="input">
                            </td>
                          </tr>
                          </table>
                          </div>
                          
                          <div id='brd_sub2' style="display:<? if($brd_info[subimg_type] == "HTM") echo "show"; else echo "none"; ?>">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td class="t_value">
                            <textarea name="subimg02" cols="45" rows="5" class="textarea"><?=$brd_info[subimg]?></textarea>
                            </td>
                          </tr>
                          </table>
                          </div>
                       
                         </td>
                       </tr>
                       <tr> 
                         <td class="t_name">상품크기</td>
                         <td class="t_value">
                         가로 <input type="text" name="prd_width" value="<?=$brd_info[prd_width]?>" size="5" class="input"> px&nbsp; 
                         세로 <input type="text" name="prd_height" value="<?=$brd_info[prd_height]?>" size="5" class="input"> px 
                         </td>
                       </tr>
                       <tr> 
                         <td class="t_name">상품진열수</td>
                         <td class="t_value">
                         <input type="text" name="prd_num" value="<? if($brd_info[prd_num]=="") echo "20"; else echo $brd_info[prd_num]; ?>" size="5" class="input"> 개&nbsp; 
                         </td>
                       </tr>
                       <tr> 
                         <td class="t_name">추천상품 진열</td>
                         <td class="t_value">
                         <input type="radio" name="recom_use" value="Y" <? if($brd_info[recom_use] == "Y") echo "checked";?>>사용
                         <input type="radio" name="recom_use" value="N" <? if($brd_info[recom_use] == "N" || $brd_info[recom_use] == "" ) echo "checked";?>>사용안함<br>
                         상품목록 페이지 상단에 추천상품 진열
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
               <table border="0" cellspacing="0" cellpadding="0" align="center">
               <?
               if($mode == "insert"){
               ?>
                 <tr>
                   <td align="center"> <div class="AW-btn-wrap"><button type="submit" class="on">확인</button></div></td>
                 </tr>
               <?
               }else if($mode == "update"){
               ?>
                 <tr>
                   <td width="100"></td>
                   <td colspan="4">
						<div class="AW-btn-wrap">
                   			<button type="submit" class="on">수정</button>
                            <a onClick="delConfirm();">삭제</a>
                        </div>
                   </td>
                   <td width="100"></td>
                 </tr>
               <?
               }
               ?>
               </table>

               </form>





<div class="AW-manage-checkinfo">
	<div class="tit">체크사항</div>
    <div class="cont">
        - 브랜드 정보 수정시 브랜드 클릭후 오른쪽에서 변경합니다.<br>
        - 브랜드 순서 변경시 클릭후 위아래 화살표를 이용합니다.<br>
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