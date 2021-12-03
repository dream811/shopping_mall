<?
include "../inc/common.inc"; 				// DB컨넥션, 접속자 파악
include "../inc/util.inc"; 		   		// 유틸 라이브러리
include "../inc/shop_info.inc"; 		// 상점 정보
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>견적서 출력</title>
<style>
<!--
body {
SCROLLBAR-FACE-COLOR: #eeeeee; SCROLLBAR-HIGHLIGHT-COLOR: #ffffff; SCROLLBAR-SHADOW-COLOR: #b4b4b4; SCROLLBAR-3DLIGHT-COLOR: #b4b4b4; SCROLLBAR-ARROW-COLOR: #333333; SCROLLBAR-DARKSHADOW-COLOR: #ffffff; SCROLLBAR-BASE-COLOR: #eeeeee;
margin-left: 0px;
margin-top: 0px;
margin-right: 0px;
margin-bottom: 0px;
}
td {
	font-family: "돋움", "돋움체";
	font-size: 9pt;
	color: #000000;
	line-height: 17px;
	word-break:break-all;
}
-->
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function Go_Print(obj)
	{
		if(document.myform.name.value == "")
		{
			alert("상호를 입력하세요");
			document.myform.name.focus();
			return;
		}
		obj.style.display='none';
		window.print();
	}
//-->
</SCRIPT>
</head>
 <div id="Layer1" style="position:absolute; left:589px; top:74px; width:57px; height:57px; z-index:1">

 </div>
<body leftmargin="0" topmargin="0">
<TABLE width="650" cellpadding=0 cellspacing=0 border=0>
<form name='myform'>
	<TR>
		<TD>
			<TABLE width="100%" cellpadding=0 cellspacing=0 style="border:1px solid black">
			<TR>
				<TD>
					<TABLE width="100%" cellpadding=0 cellspacing=0>
					<TR height="50">
						<TD align=center><font size="5" color=black style="text-decoration:underline;line-height:300%"><b>견 적 서</b></font></TD>
					</TR>
					<TR>
						<TD>
							<TABLE width="100%" cellpadding=0 cellspacing=0 >
							<TR align="center">
								<TD align="left" style="padding:5;line-height:200%"><input type='text' name='pDate' style='font-size:9pt;border:0' value="<?=date('Y')?>년 <?=date('m')?>월 <?=date('d')?>일" maxlength=15><br>
								<input type="text" name='name' style="border:0;border-bottom:1px solid black" value="">귀하<br>
								아래와 같이 견적 합니다.
								</TD>
								<TD width="360">
									<TABLE width="350" border=0 cellpadding=0 cellspacing=0  >
									<TR align="center" bgcolor="#FFFFFF">
										<TD >
											<table width="100%" height="124" border="1" bordercolor="black" cellspacing="0" cellpadding="0">
												<tr>
													<td rowspan="5" width="20" align="center" bgcolor="#EEEEEE"> 공 급 자 </td>
													<td align="center" width="70">등록번호</td>
													<td colspan="3" align="center"><?=$shop_info->com_num?></td>
												</tr>
												<tr>
													<td align="center">상호</td>
													<td align="center"><?=$shop_info->com_name?></td>
													<td align="center">성명</td>
													<td align="center">
														<?=$shop_info->com_owner?>
														<? if(is_file("../data/config/com_seal.gif")){ ?>
														<img src='/data/config/com_seal.gif' align="absmiddle">
														<? } ?>
													</td>
												</tr>
												<tr>
													<td align="center">사업장주소</td>
													<td colspan="3" align="center"><?=$shop_info->com_address?></td>
												</tr>
												<tr>
													<td align="center">업태</td>
													<td align="center"><?=$shop_info->com_kind?></td>
													<td align="center">종목</td>
													<td align="center"><?=$shop_info->com_class?></td>
												</tr>
												<tr>
													<td align="center">전화번호</td>
													<td align="center"><?=$shop_info->com_tel?></td>
													<td align="center">FAX</td>
													<td align="center"><?=$shop_info->com_fax?></td>
												</tr>
											</table>
										</TD>
									</TR>
									</TABLE>
								</TD>
							</TR>
							</TABLE>
						</TD>
					</TR>
					<TR height=10>
						<TD></TD>
					</TR>
					</TABLE>
				</TD>
			</TR>
			<TR height=1 bgcolor=black><TD></TD></TR>
			<TR height=30>
				<TD>&nbsp;￦<input type="text" Id="Sum" style="border:0;border-bottom:1px solid black;text-align:right" readonly> (VAT 포함)</TD>
			</TR>
			<TR>
				<TD>
					<TABLE width="100%" cellpadding=0  cellspacing=0 border=1 bordercolor="black">
					<TR align=center bgcolor="#EEEEEE" height=30>
						<TD width=5%>품 목</TD>
						<TD width=55%>품 목</TD>
						<TD width=10%>수 량</TD>
						<TD width=15%>단 가 (VAT 포함)</TD>
						<TD width=15%>합 계</TD>
					</TR>
<?php
$basket_exist = false;
$no = 0;
$sql = "SELECT * FROM wiz_basket_tmp WHERE uniq_id='".$_COOKIE["uniq_id"]."'";
$btresult = mysqli_query($connect, $sql) or die(mysqli_error($connect));
while($brow = mysqli_fetch_array($btresult)){
	$basket_exist = true;
	$optcode = "";
	$prdimg = "";
	$prd_price += ($brow[prdprice] * $brow[amount]);

	if($brow[opttitle5] != '') $optcode = $brow[opttitle5]." : ".$brow[optcode5].", ";
	if($brow[opttitle6] != '') $optcode .= $brow[opttitle6]." : ".$brow[optcode6].", ";
	if($brow[opttitle7] != '') $optcode .= $brow[opttitle7]." : ".$brow[optcode7].", ";

	if($brow[opttitle3] != '') $optcode .= $brow[opttitle3]." : ".$brow[optcode3].", ";
	if($brow[opttitle4] != '') $optcode .= $brow[opttitle4]." : ".$brow[optcode4].", ";

	if($brow[opttitle] != '') $optcode .= $brow[opttitle];
	if($brow[opttitle2] != '') $optcode .= "/".$brow[opttitle2];
	if($brow[opttitle] != '') $optcode .= " : ".$brow[optcode];
?>
					<TR align=center bgcolor=white height="25">
							<TD><?=$no+1?></TD>
							<TD align=left style="padding:2px"><?=$brow[prdname]?> <?=$optcode?></TD>
							<TD><?=number_format($brow[amount])?></TD>
							<TD align=right><?=number_format($brow[prdprice])?>원</TD>
							<TD align=right><?=number_format($brow[prdprice]*$brow[amount])?>원</TD>
				  </TR>
<?php
	$no++;
}

for($ii = $no; $ii < 20; $ii++) {
?>
				  <TR align=center bgcolor=white height='25'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<?php
}
?>
					<TR align=center bgcolor=white height=30>
						<TD bgcolor="#EEEEEE">계</TD>
						<TD colspan=3>&nbsp;</TD>
						<TD align=right><?=number_format($prd_price)?>원</TD>
					</TR>
					</TABLE>
				</TD>
			</TR>
			</TABLE>
		</TD>
	</TR>
	<TR height="10">
		<TD></TD>
	</TR>
	<TR>
		<TD>
		<TABLE width="100%" cellpadding=0 cellspacing=0  border=1 bordercolor=black>
		<TR align="center">
			<TD width="100" bgcolor="#EEEEEE">비<p>고</TD>
			<TD align="left" style="padding:5px"><?=str_replace("\n", "<br>", $shop_info->estimate_bigo)?></TD>
		</TR>
		</TABLE>
		</TD>
	</TR>
	<TR height="30">
		<TD align=center><img src="/images/shop/btn_print2.gif" style="cursor:pointer" onclick="Go_Print(this);"></TD>
	</TR>
	</TABLE>
	</form>
</body>
</html>
<SCRIPT LANGUAGE="JavaScript">
<!--
	document.all.Sum.value = "<?=number_format($prd_price)?>";
//-->
</SCRIPT>
