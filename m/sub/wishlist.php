<?
$sub_tit="관심상품";
?>
<? include "../inc/header.php" ?>
<? include "../inc/sub_title.php" ?>
<div class="gry_bar"></div>
<div class="wish_list_wrap">
<script>

// 체크박스 선택리스트
function selectValue(){
	var i;
	var selprd = "";
	for(i=0;i<document.forms.length;i++){
		if(document.forms[i].idx != null){
			if(document.forms[i].select_checkbox){
				if(document.forms[i].select_checkbox.checked)
					selprd = selprd + document.forms[i].idx.value + "|";
				}
			}
	}
	return selprd;
}

//선택상품 삭제
function delPrd(){

	selprd = selectValue();

	if(selprd == ""){
		alert("삭제할 상품을 선택하세요.");
		return false;
	}else{
		if(confirm("정말 삭제하시겠습니까?")){
			goURL("../../member/my_save.php?mode=my_wishdel&selprd=" + selprd);
		}
	}
}

// 전체상품 삭제
function delPrdAll() {
	if(confirm("정말 삭제하시겠습니까?")){
		goURL("../../member/my_save.php?mode=my_wishdel_all");
	}
}

//선택상품 장바구니담기
function basketPrd() {

	selprd = selectValue();

	if(selprd == ""){
		alert("장바구니에 담을 관심상품을 선택하세요.");
		return false;
	}else{
		document.location = "../../shop/prd_save.php?mode=insert&direct=basket&selprd=" + selprd;
	}
}

</script>

<?
// 정렬순서
if(empty($orderby)) $order_sql = "order by ww.wdate desc";
else $order_sql = "order by $orderby";

$no = 0;
$sql = "select ww.*, wp.prdcode, wp.prdname, wp.sellprice, wp.strprice, wp.reserve, wp.prdimg_R, wp.popular, wp.recom, wp.new, wp.sale, wp.shortage, wp.stock, wp.conprice from wiz_wishlist ww, wiz_product wp where ww.memid = '".$wiz_session['id']."' and ww.prdcode = wp.prdcode $order_sql";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

while($row = mysqli_fetch_object($result)){
	$sp_img = "";
	$optcode = "";

	if($row->popular == "Y") $sp_img .= "<img src='/images/icon_hit.gif'>&nbsp;";
	if($row->recom == "Y") $sp_img .= "<img src='/images/icon_rec.gif'>&nbsp;";
	if($row->new == "Y") $sp_img .= "<img src='/images/icon_new.gif'>&nbsp;";
	if($row->sale == "Y"){ $sp_img .= "<img src='/images/icon_sale.gif'>&nbsp;"; $sellprice = "<s>".number_format($row->conprice)." 원</s> → "; }
	if($row->shortage == "Y" || ($row->shortage == "S" && $row->stock <= 0)) $sp_img .= "<img src='/images/icon_not.gif'>&nbsp;";

	$optlist = explode("^",$row->optcode);
	$row->optcode = $optlist[0];

	$optlist = explode("^",$row->optcode3);
	$row->optcode3 = $optlist[0];

	$optlist = explode("^",$row->optcode4);
	$row->optcode4 = $optlist[0];

	if($row->opttitle5 != '') $optcode .= $row->opttitle5." : ".$row->optcode5.", ";
	if($row->opttitle6 != '') $optcode .= $row->opttitle6." : ".$row->optcode6.", ";
	if($row->opttitle7 != '') $optcode .= $row->opttitle7." : ".$row->optcode7.", ";

	if($row->opttitle3 != '') $optcode .= $row->opttitle3." : ".$row->optcode3.", ";
	if($row->opttitle4 != '') $optcode .= $row->opttitle4." : ".$row->optcode4.", ";

	if($row->opttitle != '') $optcode .= $row->opttitle;
	if($row->opttitle != '' && $row->opttitle2 != '') $optcode .= "/";
	if($row->opttitle2 != '') $optcode .= $row->opttitle2;
	if($row->opttitle != '' || $row->opttitle2 != '') $optcode .= " : ".$row->optcode.", ";

	$row->sellprice = number_format($row->sellprice)."원";
	$row->reserve = number_format($row->reserve)."원";

	if(!empty($row->strprice)) {
		$row->sellprice = $row->strprice;
		$row->reserve = "";
	}

	// 상품 이미지
	if(!@file($_SERVER[DOCUMENT_ROOT]."/data/prdimg/".$row->prdimg_R)) $row->prdimg_R = "/images/noimage.gif";
	else $row->prdimg_R = "/data/prdimg/".$row->prdimg_R;

	$purl = "prdview.php?prdcode=".$row->prdcode."&catcode=".$catcode;
?>

<form name="wishList_<?=$row->idx?>" action="/shop/prd_save.php" method="post">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="direct" value="basket">
<input type="hidden" name="idx" value="<?=$row->idx?>">

<div class="prd_view">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
      	<td colspan="2">
      		<input name="select_checkbox" type="checkbox" value="" />
      	</td>
      </tr>
      <tr>
        <td width="75" valign="top">
			<a href="<?=$purl?>" target="_blank" class="img_box"><img src="<?=$row->prdimg_R?>" width="75" height="75" /></a>
        </td>
        <td style="padding-left:12px; vertical-align:top;" valign="top">
        	<div class="prd_tit"><a href="<?=$purl?>" target="_blank"><?=$row->prdname?></a> <?=$sp_img?></div>
            <div style="padding-top:8px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="wish_inner">
				  <tr>
					<th width="70">상품가격</th>
					<td><font class="prd_price"><?=$row->sellprice?></font></td>
				  </tr>
				  <? if($oper_info->reserve_use == "Y") { ?>
				  <tr>
					<th>적립금</th>
					<td><?=$row->reserve?></td>
				  </tr>
				  <? } ?>
				  <tr>
					<th>상품옵션</th>
					<td><?=$optcode?></td>
				  </tr>
				</table>
            </div>
        </td>
      </tr>
    </table>
</div>
</form>

<?
	$no++;
	$rows--;
}

if($no <= 0){
?>
<div style="padding:20px 10px; text-align:center;"><b>등록된 항목이 없습니다.</b></div>
<?
} else {
?>
<div class="wish_btn">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="70" style="text-align:left;"><input name="" type="button" class="btn_small" value="전체삭제" onClick="delPrdAll();" /></td>
			<td width="70" style="padding-left:2px;"><input name="" type="button" class="btn_small" value="선택삭제" onClick="delPrd();" /></td>
			<td style="text-align:right;"><button type="button" class="btn_small" onclick="basketPrd();">장바구니 담기</button></td>
		</tr>
	</table>
</div>
<?
}
?>
</div>
<? include "../inc/footer.php" ?>
