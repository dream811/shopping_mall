<? include "../../inc/common.inc"; ?>
<? include "../../inc/util.inc"; ?>
<? include "../../inc/shop_info.inc"; ?>
<? include "../../inc/admin_check.inc"; ?>
<? include "../header.php"; ?>
<?
// 페이지 파라메터 (검색조건이 변하지 않도록)
//------------------------------------------------------------------------------------------------------------------------------------
$param = "s_status=$s_status&searchopt=$searchopt&searchkey=$searchkey";
//------------------------------------------------------------------------------------------------------------------------------------
?>

<script language="JavaScript" type="text/javascript">
<!--

// 주문상태 변경
function chgStatus(status){
   document.searchForm.status.value = status;
   document.searchForm.submit();
}

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
		if(document.forms[i].elements['id'] != null){
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
		if(document.forms[i].select_checkbox){
			if(document.forms[i].elements['id'] != null){
				document.forms[i].select_checkbox.checked = false;
			}
		}
	}
	return;
}

//선택회원 삭제
function userDelete(){

	var i;
	var seluser = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					seluser = seluser + document.forms[i].elements['id'].value + "|";
				}
			}
	}

	if(seluser == ""){
		alert("삭제할 업체를 선택하세요.");
		return false;
	}else{
		if(confirm("입점업체 삭제 시 입점업체가 등록한 모든 상품 및 데이터가 삭제됩니다.\n\n선택한 업체를 정말 삭제하시겠습니까?")){
			document.location = "mall_save.php?mode=deluser&seluser=" + seluser;
		}
	}
}

// 회원정보 엑셀다운
function excelDown(){
	var url = "mall_excel.php?<?=$param?>";
	window.open(url,"excelDown","height=270, width=570, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
}

// 고객 메일발송
function sendEmail(seluser){

   if(seluser == ""){
      var i;
   	var seluser = "";
   	for(i=0;i<document.forms.length;i++){
   		if(document.forms[i].elements['id'] != null){
   			if(document.forms[i].select_checkbox){
   				if(document.forms[i].select_checkbox.checked)
   					seluser = seluser + document.forms[i].com_name.value + ":" + document.forms[i].email.value + ",";
   				}
   			}
   	}
	}

   if(seluser == ""){
		alert("이메일 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "../member/send_email.php?seluser=" + seluser;
	   window.open(url,"sendEmail","height=700, width=800, menubar=no, scrollbars=yes, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 고객 sms발송
function sendSms(seluser){

   if(seluser == ""){
      var i;
   	var seluser = "";
   	for(i=0;i<document.forms.length;i++){
   		if(document.forms[i].elements['id'] != null){
   			if(document.forms[i].select_checkbox){
   				if(document.forms[i].select_checkbox.checked)
   					seluser = seluser + document.forms[i].com_hp.value + ",";
   				}
   			}
   	}
	}

   if(seluser == ""){
		alert("SMS 발송할 회원을 선택하세요.");
		return false;
	}else{
	   var url = "../member/send_sms.php?seluser=" + seluser;
	   window.open(url,"sendSms","height=600, width=500, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}

}

// 선택주문 상태변경
function batchStatus(){

	var i;
	var selvalue = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].elements['id'] != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selvalue = selvalue + document.forms[i].elements['id'].value + ":" + document.forms[i].status.value + "|";
				}
			}
	}

	if(selvalue == ""){
		alert("변경할 업체를 선택하지 않았습니다.");
		return;
	}else{
		var url = "mall_status.php?selvalue=" + selvalue;
		window.open(url,"batchStatus","height=300, width=400, menubar=no, scrollbars=no, resizable=no, toolbar=no, status=no, top=100, left=100");
	}
	return;

}

// 기간설정
function setPeriod(sdate,edate){

	document.searchForm.sdate.value = sdate;
	document.searchForm.edate.value = edate;
	document.searchForm.submit();
}

//-->
</script>

	  <table border="0" cellspacing="0" cellpadding="2">
	    <tr>
	      <td><img src="../image/ic_tit.gif"></td>
	      <td valign="bottom" class="tit">입점업체목록</td>
	      <td width="2"></td>
	      <td valign="bottom" class="tit_alt">입점업체정보를 관리합니다.</td>
	    </tr>
	  </table>
	  <br>

    <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <form name="searchForm" action="<?=$PHP_SELF?>" method="get">
    <input type="hidden" name="page" value="<?=$page?>">
      <tr>
        <td bgcolor="ffffff">
        <table width="100%" border="0" cellspacing="1" cellpadding="3" class="t_style">
        <tr>
        	<td class="t_name">기간</td>
        	<td class="t_value">
        	<input type="text" name="sdate" id="datepicker1" class="input w100 datepicker" value="<?=$sdate?>" /> ~
        	<input type="text" name="edate" id="datepicker2" class="input w100 datepicker" value="<?=$edate?>" />　
				<?
					$to_day = date('Y-m-d');
					$yes_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*1));
					$week_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*7));
					$month_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*30));
					$sixmonth_day = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y'))-(3600*24*183));
				?>
				<a href="javascript:setPeriod('<?=$to_day?>','<?=$to_day?>')" class="selec">오늘</a>
				<a href="javascript:setPeriod('<?=$yes_day?>','<?=$yes_day?>')" class="selec">어제</a>
				<a href="javascript:setPeriod('<?=$week_day?>','<?=$to_day?>')" class="selec">1주일</a>
				<a href="javascript:setPeriod('<?=$month_day?>','<?=$to_day?>')" class="selec">1개월</a>
				<a href="javascript:setPeriod('<?=$sixmonth_day?>','<?=$to_day?>')" class="selec">6개월</a>
        	</td>
      	</tr>
      	<tr>
        <td width="15%" class="t_name">조건</td>
        <td width="85%" class="t_value">
					<select name="s_status">
						<option value=""> 승인여부 </option>
						<option value="N"> 미승인 </option>
						<option value="Y"> 승인 </option>
					</select>
					<script language="javascript">
					<!--
					 s_status = document.searchForm.s_status;
					 for(ii=0; ii<s_status.length; ii++){
					   if(s_status.options[ii].value == "<?=$s_status?>")
					     s_status.options[ii].selected = true;
					   }
					-->
					</script>
         	<select name="searchopt" class="select">
            <option value="com_name" <? if($searchopt == "com_name") echo "selected"; ?>>업체명
            <option value="id" <? if($searchopt == "id") echo "selected"; ?>>아이디
            <option value="com_num" <? if($searchopt == "com_num") echo "selected"; ?>>사업자등록번호
            <option value="email" <? if($searchopt == "email") echo "selected"; ?>>이메일
            <option value="com_tel" <? if($searchopt == "com_tel") echo "selected"; ?>>전화번호
            <option value="com_hp" <? if($searchopt == "com_hp") echo "selected"; ?>>휴대폰
          </select>
          <input type="text" name="searchkey" value="<?=$searchkey?>" class="input">
          <input type="submit" value="검색" class="AW-btn-search" />
        </td>
        </tr>
        </table>
      	</td>
      </tr>
    </form>
    </table>

    <br>
    <?
		$sql = "select count(id) as all_total from wiz_mall";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_object($result);
		$all_total = $row->all_total;

		$sql = "select id from wiz_mall wm";
		$sql .= " where wm.id != ''";
		if($sdate != "") 		$sql .= " and wm.wdate > '$sdate'";
    if($edate != "") 		$sql .= " and wm.wdate <= '$edate 23:59:59'";
		if($searchopt != "" && $searchkey !="")   $sql .= " and wm.$searchopt like '%$searchkey%'";
		if($s_status != "")  $sql .= " and wm.status = '$s_status'";
		$sql .=" order by wm.wdate desc";
		//echo $sql;
  	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  	$total = mysqli_num_rows($result);

		$rows = 20;
		$lists = 5;
		$page_count = ceil($total/$rows);
		if(!isset($page) || !$page ||  $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>총 입점업체수 : <b><?=$all_total?></b> , 검색 입점업체수 : <b><?=$total?></b></td>
        <td align="right">
	      	<font color="6DCFF6">■</font> 승인
	      	<font color="ED1C24">■</font> 미승인 &nbsp;
            <a onClick="document.location='mall_info.php?mode=insert';" class="AW-btn">입점업체등록</a>
        </td>
      </tr>
      <tr><td colspan="2" height="5"></td></tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <form>
      <tr><td class="t_rd" colspan="20"></td></tr>
      <tr class="t_th">
        <th width="30" align="center"><input type="checkbox" name="select_tmp" onClick="onSelect(this.form)"></th>
        <th align="center">번호</th>
        <th align="center">업체명</th>
        <th align="center">담당자</th>
        <th align="center">아이디</th>
        <th align="center">휴대폰</th>
        <th align="center">이메일</th>
        <th align="center">상품수</th>
        <th align="center">미니샵</th>
        <th align="center">정산</th>
        <th align="center">신청일</th>
        <th width="140" align="center">승인여부</th>
        <th width="60" align="center">기능</th>
      </tr>
      <tr><td class="t_rd" colspan="20"></td></tr>
      </form>
			<?
			$sql = "select id,passwd,com_name,manager,com_hp,email,wdate,status from wiz_mall wm";
			$sql .= " where wm.id != ''";
			
			if($sdate != "") 		$sql .= " and wm.wdate > '$sdate'";
    	if($edate != "") 		$sql .= " and wm.wdate <= '$edate 23:59:59'";
			if($searchopt != "" && $searchkey !="")   $sql .= " and wm.$searchopt like '%$searchkey%'";
			if($s_status != "")  $sql .= " and wm.status = '$s_status'";
			$sql .=" order by wm.wdate desc limit $start, $rows";

	  	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

			while(($row = mysqli_fetch_object($result)) && $rows){

				if($shop_info->sms_use == "Y") $hphone = "<a href=javascript:sendSms('".$row->com_hp."');>".$row->com_hp."</a>";
				else $hphone = $row->com_hp;

				if($row->status == "Y") $stacolor = "6DCFF6";
				else if($row->status == "N") $stacolor = "ED1C24";
				else $stacolor = "";
			?>
     <form name="frm<?=$no?>" action="mall_save.php" name="<?=$row->prdcode?>" method="get">
      <input type="hidden" name="mode" value="chgstatus">
      <input type="hidden" name="page" value="<?=$page?>">
      <input type="hidden" name="id" value="<?=$row->id?>">
      <input type="hidden" name="com_name" value="<?=$row->com_name?>">
      <input type="hidden" name="email" value="<?=$row->email?>">
      <input type="hidden" name="com_hp" value="<?=$row->com_hp?>">
      <input type="hidden" name="status" value="<?=$row->status?>">
      <input type="hidden" name="s_status" value="<?=$s_status?>">
      <input type="hidden" name="searchopt" value="<?=$searchopt?>">
      <input type="hidden" name="searchkey" value="<?=$searchkey?>">
      <tr class="t_tr">
        <td height="30" align="center"><input type="checkbox" name="select_checkbox"></td>
        <td align="center"><a href="mall_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$no?></a></td>
        <td align="center"><a href="mall_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$row->com_name?></a></td>
        <td align="center"><a href="mall_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$row->manager?></a></td>
        <td align="center"><a href="mall_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>"><?=$row->id?></a></td>
        <td align="center"><?=$hphone?></td>
        <td align="center"><a href="javascript:sendEmail('<?=$row->com_name?>:<?=$row->email?>')"><?=$row->email?></a></td>
        <?
        $mall_sql = "select prdcode,status from wiz_product where mallid='$row->id'";
        $mall_result = mysqli_query($connect, $mall_sql) or error(mysqli_error($connect));
        $prd_total = mysqli_num_rows($mall_result);
        ?>
        <td align="center"><a href="../product/prd_list.php?s_mallid=<?=$row->id?>" target="_blank"><?=$prd_total?></a></td>
        <td align="center"><a href="/shop/minishop.php?mallid=<?=$row->id?>" target="_blank">[방문]</a></td>
        <td align="center"><a href="./account_list.php?searchopt=wb.mallid&searchkey=<?=$row->id?>" target="_blank">[검색]</a></td>
        <td align="center"><?=substr($row->wdate,0,10)?></td>
        <td align="center">
          <table cellpadding="3">
          	<tr>
          		<td bgcolor="<?=$stacolor?>" align="center">
        				<select name="chg_status" class="state">
									<option value="Y" <? if($row->status == "Y") echo "selected"; ?>>승인</option>
									<option value="N" <? if($row->status == "N" || $row->status == "") echo "selected"; ?>>미승인</option>
        				</select>
        			</td>
        			<td><input type="submit" value="적용" class="AW-btn-s modify" /></td>
        		</tr>
        	</table>
        </td>
        <td align="center"><a onClick="document.location='mall_info.php?mode=update&id=<?=$row->id?>&<?=$param?>&page=<?=$page?>';" class="AW-btn-s">상세보기</a></td>
      </tr>
      <tr><td colspan="20" class="t_line"></td></tr>
    </form>
   	<?
   		$no--;
			$rows--;
    }
  	if($total <= 0){
  	?>
  		<tr><td height=30 colspan=11 align=center>등록된 회원이 없습니다.</td></tr>
  		<tr><td colspan='20' class='t_line'></td></tr>
  	<?
  	}
    ?>
    </table>

    <table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
    	<tr><td height="5"></td></tr>
      <tr>
        <td width="33%">
        	<a onClick="userDelete();" class="AW-btn">회원삭제</a>
        	<a onClick="sendEmail('');" class="AW-btn">이메일발송</a>
            <? if(!strcmp($shop_info->sms_use, "Y")) { ?>
        	<a onClick="sendSms('');" class="AW-btn">SMS발송</a>
            <? } ?>
        	
        </td>
        <td width="33%"><? print_pagelist($page, $lists, $page_count, "&$param"); ?></td>
        <td width="33%" align="right">
        	<a onClick="batchStatus();" class="AW-btn">상태일괄변경</a>
        </td>
      </tr>
    </table>


<? include "../footer.php"; ?>