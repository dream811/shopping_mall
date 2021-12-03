<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/mall_check.inc";

// 업체정보설정
if($mode == "mall_info"){

	$com_tel 	= $com_tel."-".$com_tel2."-".$com_tel3;
	$com_hp 	= $com_hp."-".$com_hp2."-".$com_hp3;
	$com_fax 	= $com_fax."-".$com_fax2."-".$com_fax3;

	$post		 	= $post."-".$post2;
	

	$sql = "update wiz_mall set passwd='$passwd',com_name='$com_name',com_owner='$com_owner',com_num='$com_num',com_kind='$com_kind',
					com_class='$com_class',com_tel='$com_tel',com_hp='$com_hp',com_fax='$com_fax',acc_name='$acc_name',
					acc_bank='$acc_bank',acc_num='$acc_num',manager='$manager',email='$email',homepage='$homepage',
					post='$post',address='$address',address2='$address2'
					where id = '$wiz_mall['id']'";

	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("기본정보 설정이 저장되었습니다.","shop_info.php");

// 운영정보설정
}else if($mode == "oper_info"){
	$del_info = explode("|",$del_trace);

	$sql = "update wiz_mall set del_com='$del_info[0]', del_trace='$del_info[1]', del_prd='$del_prd', del_prd2='$del_prd2', del_method ='$del_method', del_fixprice ='$del_fixprice', del_staprice ='$del_staprice', del_staprice2 ='$del_staprice2', del_staprice3 ='$del_staprice3',
						del_extrapost1 ='$del_extrapost1', del_extrapost12 ='$del_extrapost12', del_extraprice1 ='$del_extraprice1',
						del_extrapost2 ='$del_extrapost2', del_extrapost22 ='$del_extrapost22', del_extraprice2 ='$del_extraprice2',
						del_extrapost3 ='$del_extrapost3', del_extrapost32 ='$del_extrapost32', del_extraprice3 ='$del_extraprice3'
						where id = '$wiz_mall['id']'";

	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("운영정보 설정이 저장되었습니다.","shop_oper.php");

// 회원발급쿠폰 삭제
}else if(!strcmp($mode, "delmycoupon")) {

	$sql = "delete from wiz_mycoupon where idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("쿠폰이 삭제되었습니다.","shop_mycoupon.php?prdcode=$prdcode");

// 구매가이드설정
} else if(!strcmp($mode, "guide")) {

	$sql = "update wiz_mall set guide = '$content' where id = '$wiz_mall['id']'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("구매가이드 설정이 저장되었습니다.","shop_guide.php");

// 입점업체 탈퇴
} else if(!strcmp($mode, "out")) {

	// 입점업체 상품 미승인상태로 변경
	$sql = "update wiz_product set status = 'N' where mallid = '$wiz_mall['id']'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 장바구니에 해당 업체 상품 삭제
	$sql = "delete from wiz_basket_tmp where mallid = '".$wiz_mall['id']."'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// 탈퇴신청 내용 삭제(입점업체 아이디를 [out] 으로 처리)
	$sql = "insert into wiz_bbs(idx,code,memid,name,tphone,subject,content,ip,wdate)
					values('','mallout','$wiz_mall['id']','$wiz_mall[name]','$wiz_mall[com_tel]','$subject','$content','$REMOTE_ADDR',unix_timestamp('".date('Y-m-d H:i:s')."'))";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("탈퇴신청이 접수되었습니다.","shop_out.php");

}

?>