<?
include "../inc/common.inc"; 			// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   	// 유틸 라이브러리

$now_position = "<a href=/>Home</a> &gt; 고객센터 &gt; <strong>사이트맵</strong>";
$page_type = "sitemap";

include "../inc/page_info.inc"; 	// 페이지 정보
include "../inc/header.inc"; 			// 상단디자인
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="padding:27px 0px 30px 10px;">
             	
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left"><img src="../images/other/sitemap_stit01.gif" /></td>
        </tr>
        <tr>
          <td style="border:1px solid #dedede; padding:5px 10px 9px 10px;">
          	
          	<table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?
            $no = 0;
            $sql = "select * from wiz_category where depthno <= 2 order by priorno01 asc, catcode asc";
            $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
            while($row = mysqli_fetch_object($result)){
             if($row->depthno == 1){
             		if($no != 0) echo "<tr><td colspan=\"2\" bgcolor=\"#dedede\" height=\"1\"></td></tr>";
                echo "<tr>";
                echo "<td width=\"200\" height=\"33\"  align=\"left\" class=\"site_dot\"><a href=/shop/prd_list.php?catcode=$row->catcode><strong>$row->catname</strong></a></td>";
                echo "<td align=\"left\">";
                $no++;
             }else{
                echo "<a href=\"/shop/prd_list.php?catcode=$row->catcode\">$row->catname</a> <a href=#>/</a> ";
             }
            }
            ?>
        		</table>

          </td>
        </tr>                    
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td align="left"><img src="../images/other/sitemap_stit02.gif" /></td>
        </tr>
        <tr>
          <td style="border:1px solid #dedede; padding:8px 10px 8px 10px;">
          	
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr align="left">
                <td width="20%" height="30" class="site_dot02"><a href="/member/login.php">로그인</a></td>
                <td width="20%" class="site_dot02"><a href="/member/join.php">회원가입</a></td>
                <td width="20%" class="site_do02t"><a href="/member/join.php">이용약관</a></td>
                <td width="20%" class="site_dot02"><a href="/member/id_search.php">아이디,비밀번호찾기</a></td>
                <td width="20%" class="site_dot02"><a href="/member/my_info.php">마이페이지</a></td>
              </tr>
              <tr align="left">
                <td width="20%" height="30" class="site_dot02"><a href="/member/my_order.php">주문/배송조회</a></td>
                <td width="20%" class="site_dot02"><a href="/member/my_reserve.php">적립금내역</a></td>
                <td width="20%" class="site_dot02"><a href="/member/my_qna.php">1:1 Q&A</a></td>
                <td width="20%" class="site_dot02"><a href="/member/my_wish.php">나의관심상품</a></td>
                <td width="20%" class="site_dot02"><a href="/member/my_out.php">회원탈퇴</a></td>
              </tr>                                                    
            </table>
          
          </td>
        </tr>        
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td align="left"><img src="../images/other/sitemap_stit03.gif" /></td>
        </tr>
        <tr>
          <td style="border:1px solid #dedede; padding:8px 10px 8px 10px;">
          	
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr align="left">
                <td width="20%" height="30" class="site_dot02"><a href="/shop/prd_basket.php">장바구니</a></td>
                <td width="20%" class="site_dot02"><a href="/shop/order_list.php">주문배송조회</a></td>
                <td width="20%" class="site_dot02"><a href="/shop/prd_search.php">상세검색</a></td>
                <td width="20%"><a href="/shop/prd_list.php?grp=best">베스트상품</a></td>
                <td width="20%"><a href="/shop/prd_list.php?grp=popular">인기상품</a></td>
              </tr>                                                  
            </table>
          
          </td>
        </tr>      
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td align="left"><img src="../images/other/sitemap_stit04.gif" /></td>
        </tr>
        <tr>
          <td style="border:1px solid #dedede; padding:8px 10px 8px 10px;">
          	
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr align="left">
                <td width="20%" height="30" class="site_dot02"><a href="/center/center.php">고객센터</a></td>
                <td width="20%" class="site_dot02"><a href="/center/privacy.php">개인정보취급방침</a></td>
                <td width="20%" class="site_dot02"><a href="/bbs/list.php?code=notice">공지사항</a></td>
                <td width="20%" class="site_dot02"><a href="/center/faq.php">자주하는 질문</a></td>
                <td width="20%" class="site_dot02"><a href="/center/guide.php">이용안내</a></td>
              </tr>     
              <tr align="left">
                <td width="20%" height="30" class="site_dot02"><a href="/bbs/list.php?code=qna">질문과답변</a></td>
                <td width="20%" class="site_dot02"><a href="/bbs/list.php?code=review">상품후기</a></td>
                <td width="20%"><a href="/center/company.php">회사소개</a></td>
                <td width="20%"></td>
                <td width="20%"></td>
              </tr>                                                 
            </table>
          
          </td>
        </tr>                    
      </table>

    </td>
  </tr>              
</table>

<?

include "../inc/footer.inc"; 		// 하단디자인

?>