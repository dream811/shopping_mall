<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/shop_info.inc"; 		// 상점정보

$now_position = "<a href=/>Home</a> &gt; <strong>고객센터</strong>";
$page_type = "center";

include "../inc/page_info.inc"; 		// 페이지 정보
include "../inc/header.inc"; 				// 상단디자인

?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:20px 0px;">
    
			 <!-- 실제 컨텐츠 부분 -->               	
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr valign="top">
          <td width="57%">
          
           <!-- 고객센터 안내 -->
          	<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="72" valign="top"><img src="../images/customer/customer_tit.gif" /></td>
              </tr>
              <tr>
                <td bgcolor="#f7f7f7" style="border:1px solid #e9e9e9; padding:20px;">
                	
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="23%"><img src="../images/customer/customer_img.gif" /></td>
                        <td width="77%" align="left">
                                
                            <?=$page_info->content?>
                        
                        </td>
                      </tr>
                    </table>
                	
                </td>
              </tr>
              <tr><td height="10"></td></tr>
              <tr>
                <td bgcolor="#fcfcfc" style="border:1px solid #b1b1b1; padding:12px 21px;">
                	
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="30%" valign="middle"><img src="../images/customer/faq_search.gif" /></td>
                        <td width="70%">
                        
                        
                        	<table width="100%" border="0" cellpadding="4" cellspacing="0">
                        	<form action="/bbs/list.php">
                        	<input type="hidden" name="code" value="faq">
                            <tr>
                              <td><select name="searchopt" class="eleven" style="width:90px;">
                                <option value="subject">제목</option>
                                <option value="content">내용</option>
                              </select>
                              </td>
                              <td><input type="text" name="searchkey" style="width:178px; background:#fafafa; border-top:1px solid #d0d0d0; border-right:1px solid #e9e9e9; border-bottom:1px solid #e9e9e9; border-left:1px solid #d0d0d0;"></td>                          
                              <td align="right"><input type="image" src="../images/customer/but_search.gif" /></td>
                           </tr>
                           <tr>
                             <td colspan="3" style="font-size:8pt; font-family:Dotum; color:#888888">고객님께서 찾고자 하는 질문을 빠르게 찾아드립니다.</td>
                           </tr>
                          </form>
                        	</table>                                    
                        </td>                                    
                      </tr>
                    </table>
                
                </td>
              </tr>
              <tr><td height="20"></td></tr>
              <tr>
                <td><a href="/bbs/list.php?ptype=list&code=faq&category=17"><img src="../images/customer/btn_mem.gif" border="0" /></a><a href="/bbs/list.php?ptype=list&code=faq&category=18"><img src="../images/customer/btn_order.gif" border="0" /></a><a href="/bbs/list.php?ptype=list&code=faq&category=19"><img src="../images/customer/btn_deliver.gif" border="0" /></a></td>
              </tr>
              <tr>
                <td><a href="/bbs/list.php?ptype=list&code=faq&category=20"><img src="../images/customer/btn_chang.gif" border="0" /></a><a href="/bbs/list.php?ptype=list&code=faq&category=21"><img src="../images/customer/btn_cancel.gif" border="0" /></a><a href="/bbs/list.php?ptype=list&code=faq&category=23"><img src="../images/customer/btn_other.gif" border="0" /></a></td>
              </tr> 
            </table>
           <!-- 고객센터 안내 끝 -->                      
          
          </td>
          <td width="5%"></td>
          <td width="38%" style="padding:10px 10px 0 0;">
          	
            <!--  자주묻는 질문 -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left"><img src="../images/customer/faq_tit.gif" /></td>
                <td align="right"><a href="/bbs/list.php?code=faq"><img src="../images/customer/more.gif" border="0" /></a></td>                        
              </tr>
              <tr>
                <td colspan="2" height="12"></td>
              </tr>
              <tr>
                <td colspan="2" height="200" valign="top" align="left">
                	
                    <table border="0" cellpadding="0" cellspacing="0">
                    	<?
                    	$code = "faq";
                    	$bbs_num = 10;
                    	$bbs_len = 20;
											$sql = "select idx,subject from wiz_bbs where code='$code' order by prino desc limit $bbs_num";
											$result = mysqli_query($connect, $sql);
											while($row = mysqli_fetch_array($result)){
											?>
                      <tr>
                        <td class="cust_dot"><a href="/bbs/list.php?code=<?=$code?>"><?=cut_str($row['subject'],$bbs_len)?></a></td>
                      </tr>
                      <?
                    	}
                    	?>                                
                    </table>
                
                </td>
              </tr>
              <tr>
                <td colspan="2" height="40"></td>
              </tr> 
              
             <!--  상품후기 -->                          
              <tr>
                <td align="left"><img src="../images/customer/product_tit.gif" /></td>
                <td align="right"><a href="/bbs/list.php?code=review"><img src="../images/customer/more.gif" border="0" /></a></td>                        
              </tr>
              <tr>
                <td colspan="2" height="12"></td>
              </tr>
              <tr>
                <td colspan="2" height="100" valign="top" align="left">
                	
                    <table border="0" cellpadding="0" cellspacing="0">
                      <?
                    	$code = "review";
                    	$bbs_num = 10;
                    	$bbs_len = 20;
											$sql = "select idx,subject from wiz_bbs where code='$code' order by prino desc limit $bbs_num";
											$result = mysqli_query($connect, $sql);
											while($row = mysqli_fetch_array($result)){
											?>
                      <tr>
                        <td class="cust_dot"><a href="/bbs/view.php?code=<?=$code?>&idx=<?=$row['idx']?>"><?=cut_str($row['subject'],$bbs_len)?></a></td>
                      </tr>
                      <?
                    	}
                    	?>                               
                    </table>
                
                </td>
              </tr>  
              
              <tr>
                <td colspan="2" height="40"></td>
              </tr> 
              
             <!--  공지사항 -->
              <tr>
                <td align="left"><img src="../images/customer/notice_tit.gif" /></td>
                <td align="right"><a href="/bbs/list.php?code=notice"><img src="../images/customer/more.gif" border="0" /></a></td>                        
              </tr>
              <tr>
                <td colspan="2" height="12"></td>
              </tr>
              <tr>
                <td colspan="2" height="100" valign="top" align="left">
                	
                    <table border="0" cellpadding="0" cellspacing="0">
                      <?
                    	$code = "notice";
                    	$bbs_num = 10;
                    	$bbs_len = 20;
											$sql = "select idx,subject from wiz_bbs where code='$code' order by prino desc limit $bbs_num";
											$result = mysqli_query($connect, $sql);
											while($row = mysqli_fetch_array($result)){
											?>
                      <tr>
                        <td class="cust_dot"><a href="/bbs/view.php?code=<?=$code?>&idx=<?=$row['idx']?>"><?=cut_str($row['subject'],$bbs_len)?></a></td>
                      </tr>
                      <?
                    	}
                    	?>                                 
                    </table>
                
                </td>
              </tr>                                                                             
            </table>
          
          </td>
        </tr>
      </table>
     <!-- 실제 컨텐츠 끝 -->  
    
    </td>
  </tr>              
</table>
         
<?
include "../inc/footer.inc"; 		// 하단디자인
?>