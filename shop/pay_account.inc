<!-- 무통장 입금 결제 -->
<script language="javascript">
<!--
function inputCheck(frm){
	if(frm.account.value == ""){
		alert("계좌번호를 선택하세요.");
		return false;
	}
	if(frm.account_name.value == ""){
		alert("입금자명 입력하세요.");
		return false;
	}
}
-->
</script>
<form action="order_update.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">

<table width=100% class="AW_order_table" style="margin:30px 0;">
	<tr>
		<td width="20%" class="tit">결제방법</td>
		<td width="80%" class="val">무통장입금</td>
	</tr>
	<tr>
		<td class="tit">입금 계좌번호</td>
		<td class="val">
			<select name="account">
			<?
			$pay_account = explode("\n", $oper_info->pay_account);
			for($ii=0; $ii < count($pay_account); $ii++){
				if(!empty($pay_account[$ii])) {
					$account_tmp = explode("^", $pay_account[$ii]);
					$account = $account_tmp[1]." ".$account_tmp[2]." ".$account_tmp[3];
					echo "<option value='$account'>$account</option>";
				}
			}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="tit">입금자명</td>
		<td class="val"><input type=text name="account_name" value="<?=$wiz_session[name]?>" size=25 class="input"></td>
	</tr>
</table>
<div class="AW_btn_area">
	<button type="submit" class="submit_btn">결제하기</button>
	<button type="button" class="cancle_btn" onclick="history.go(-1);">주문취소</button>
</div>

</form>