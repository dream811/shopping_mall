<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/oper_info.inc"; ?>
<? include "../../inc/mall_check.inc"; ?>
<? include "../header.php"; ?>
<?
$sql = "select * from wiz_mall where id = '$wiz_mall['id']'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$oper_info = mysqli_fetch_object($result);

if(empty($oper_info->del_com)) {
	$sql = "select * from wiz_operinfo";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$oper_info = mysqli_fetch_object($result);
}
?>

			<table border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td><img src="../image/ic_tit.gif"></td>
			    <td valign="bottom" class="tit">운영정보설정</td>
			    <td width="2"></td>
			    <td valign="bottom" class="tit_alt">운영에 필요한 정보를 설정합니다.</td>
			  </tr>
			</table>

<form name="frm" action="shop_save.php" method="post">
<input type="hidden" name="tmp">
<input type="hidden" name="mode" value="oper_info">


      <a name="del"></a><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
      
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 배송정보</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td class="t_name" width="15%">택배사</td>
          <td class="t_value">
<?php
//$del_com_str = "대한통운,로젠택배,아주택배,옐로우캡,우체국택배,이젠택배,트라넷,한진택배,현대택배,훼미리택배,Bell Express,CJ GLS,HTH,KGB택배,KT로지스택배";
//$del_com_list = explode(",", $del_com_str);
?>
<!--
          	<select name="del_com">
          	<? for($ii = 0; $ii < count($del_com_list); $ii++) { ?>
          		<option value="<?=$del_com_list[$ii]?>" <? if(!strcmp(trim($oper_info->del_com), $del_com_list[$ii])) echo "selected" ?>><?=$del_com_list[$ii]?></option>
						<? } ?>
          	</select>
-->
			<select name="del_trace" id="">
			<?php
			$sql = "select del_trace from wiz_operinfo";
			$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
			$admin_oper_info = mysqli_fetch_object($result);

			$del_info = explode("\n", $admin_oper_info->del_trace);
			for($ii = 0; $ii < count($del_info); $ii++) {
				$dellist = explode("^", $del_info[$ii]);
				if(!empty($dellist[0])) {
				?>
					<option <? if($oper_info->del_com == $dellist[1]) echo "selected" ?> value="<?=$dellist[1]?>|<?=$dellist[2]?>"><?=$dellist[1]?></option>
				<?php
				}
			}
			?>
			</select>			
          </td>
        </tr>

      </table>



      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 기본 배송정책</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">배송무료</td>
          <td width="85%" class="t_value">
          <input type="radio" name="del_method" value="DA" <? if($oper_info->del_method == "DA") echo "checked"; ?>>
          배송비 전액무료</td>
        </tr>
        <tr>
          <td class="t_name">수신자부담</td>
          <td class="t_value">
          <input type="radio" name="del_method" value="DB" <? if($oper_info->del_method == "DB") echo "checked"; ?>>
          수신자부담 (착불)</td>
        </tr>
        <tr>
          <td class="t_name">고정값</td>
          <td class="t_value">
          <input type="radio" name="del_method" value="DC" <? if($oper_info->del_method == "DC") echo "checked"; ?>>
          <input name="del_fixprice" type="text" value="<?=$oper_info->del_fixprice?>" class="input">원</td>
        </tr>
        <tr>
          <td class="t_name">구매가격별</td>
          <td class="t_value">
            <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>
                  <input type="radio" name="del_method" value="DD" <? if($oper_info->del_method == "DD") echo "checked"; ?>>
                  <input type="text" name="del_staprice" value="<?=$oper_info->del_staprice?>" class="input">
                </td>
                <td>&nbsp;이상구매시 <input type="text" name="del_staprice2" value="<?=$oper_info->del_staprice2?>" class="input"> 원</td>
              </tr>
              <tr>
                <td></td>
                <td>&nbsp;이하구매시 <input type="text" name="del_staprice3" value="<?=$oper_info->del_staprice3?>" class="input"> 원</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="t_name">지역할증</td>
          <td class="t_value">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="10"></td>
              <td>우편번호</td>
              <td>할증료</td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost1" type="text" value="<?=$oper_info->del_extrapost1?>" class="input" size="9"> 부터
              <input name="del_extrapost12" type="text" value="<?=$oper_info->del_extrapost12?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice1" type="text" value="<?=$oper_info->del_extraprice1?>" class="input" size="20">원
              </td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost2" type="text" value="<?=$oper_info->del_extrapost2?>" class="input" size="9"> 부터
              <input name="del_extrapost22" type="text" value="<?=$oper_info->del_extrapost22?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice2" type="text" value="<?=$oper_info->del_extraprice2?>" class="input" size="20">원
              </td>
            </tr>
            <tr>
              <td width="10"></td>
              <td>
              <input name="del_extrapost3" type="text" value="<?=$oper_info->del_extrapost3?>" class="input" size="9"> 부터
              <input name="del_extrapost32" type="text" value="<?=$oper_info->del_extrapost32?>" class="input" size="9"> 까지
              </td>
              <td>
              <input name="del_extraprice3" type="text" value="<?=$oper_info->del_extraprice3?>" class="input" size="20">원
              </td>
            </tr>
          </table>
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0">
        <tr><td height="10"></td></tr>
        <tr>
          <td width="10"></td>
          <td width="16%" valign="top" class="alert">배송료 선택</td>
          <td>: 배송료를 4가지 형태로 구분하며 각 상황별 배송료 설정을 합니다.</td>
        </tr>
        <tr>
          <td width="10"></td>
          <td valign="top" class="alert">지역할증</td>
          <td>: 각지역별로 할증 배송료를 설정 합니다. 북제주군 한경면인 경우 695840 부터 695844 까지 2000원</td>
        </tr>
      </table>


      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="2">
			  <tr>
			    <td class="tit_sub"><img src="../image/ics_tit.gif"> 상품별 배송정책</td>
			  </tr>
			</table>
      <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
          <td width="15%" class="t_name">무료배송 상품</td>
          <td width="85%" class="t_value">
          <input type="radio" name="del_prd" value="DA" <? if($oper_info->del_prd == "DA") echo "checked"; ?>> 무료배송 상품과 함께 주문할 경우, 전체 배송비를 무료로합니다.<br>
          <input type="radio" name="del_prd" value="DB" <? if($oper_info->del_prd == "DB") echo "checked"; ?>> 무료배송 상품과 함께 주문할 경우, 무료배송 상품을 제외한 상품은 배송비를 부과합니다.
          </td>
        </tr>
        <tr>
          <td class="t_name">상품별 배송비</td>
          <td class="t_value">
          <input type="radio" name="del_prd2" value="DA" <? if($oper_info->del_prd2 == "DA") echo "checked"; ?>> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비를 합산한 금액을 배송비로 지정합니다.<br>
          <input type="radio" name="del_prd2" value="DB" <? if($oper_info->del_prd2 == "DB") echo "checked"; ?>> 상품을 2개 이상 주문할 경우, 상품별 배송비와 기본 배송비 중 더 큰 배송비를 전체 배송비로 지정합니다.
          </td>
        </tr>
      </table>

	<div class="AW-btn-wrap">
        <input type="submit" value="확인" class="on">
        <a href="javascript:history.go(-1);">취소</a>
    </div>

</form>
<? include "../footer.php"; ?>