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

<form action="../../shop/order_update.php" method="post" onSubmit="return inputCheck(this);">
<input type="hidden" name="orderid" value="<?=$orderid?>">
<input type="hidden" name="pay_method" value="<?=$pay_method?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="pay_table">
  <tr>
    <th>결제방법</th>
    <td>무통장입금</td>
  </tr>
  <tr>
    <th>입금계좌</th>
    <td>
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
    <th>입금자명</th>
    <td><input name="account_name" value="<?=$wiz_session[name]?>" type="text" style="width:120px;" class="input_style" /></td>
  </tr>
</table><!-- //pay_table -->

<div class="button_common">
	<button type="submit">결제하기</button>
</div>

</form>