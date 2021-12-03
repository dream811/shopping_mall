<?
include "../../inc/common.inc";
include "../../inc/util.inc";
include "../../inc/admin_check.inc";

// 배너추가
if($mode == "insert"){

	$banner_path = "../../data/banner";

	if($de_img[size] > 0){
		file_check($de_img[name]);

    $de_img_ext = strtolower(substr($de_img[name],-3));
    $de_img_name = date('ymdhis').rand(10,99).".".$de_img_ext;
		copy($de_img[tmp_name], $banner_path."/".$de_img_name);
		chmod($banner_path."/".$de_img_name, 0606);
	}

	$content = str_replace("\\\"", "\'", $content);

	$sql = "insert into wiz_banner(idx,name,align,prior,isuse,link_url,link_target,de_type,de_img,de_html) values('', '$name','$align', '$prior', '$isuse', '$link_url', '$link_target', '$de_type', '$de_img_name', '$content')";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("배너가 추가되었습니다.","banner".$popup.".php?code=$name&align=$align");


// 배너수정
}else if($mode == "update"){

	$banner_path = "../../data/banner";

	if($de_img[size] > 0){

		file_check($de_img[name]);

		$sql = "select de_img from wiz_banner where idx = '$idx';";
		$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
		$row = mysqli_fetch_array($result);

		if($row[de_img] != "") @unlink($banner_path."/".$row[de_img]);

    $de_img_ext = strtolower(substr($de_img[name],-3));
    $de_img_name = date('ymdhis').rand(10,99).".".$de_img_ext;
		copy($de_img[tmp_name], $banner_path."/".$de_img_name);
		chmod($banner_path."/".$de_img_name, 0606);
		$de_img_sql = " de_img='$de_img_name', ";
	}

	$content = str_replace("\\\"", "\'", $content);

	$sql = "update wiz_banner set name='$name',align='$align', prior='$prior', isuse='$isuse', link_url='$link_url', link_target='$link_target',
							de_type='$de_type', $de_img_sql de_html='$content' where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("배너가 수정되었습니다.","banner_input".$popup.".php?mode=update&code=$name&idx=$idx&align=$align");


// 배너삭제
}else if($mode == "delete"){

	$banner_path = "../../data/banner";

	if($ban_img != "") @unlink($banner_path."/".$ban_img);

	$sql = "DELETE FROM wiz_banner WHERE idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("삭제되었습니다.","banner".$popup.".php?code=$code");


// 배너그룹추가
}else if($mode == "ban_group_insert") {


	$sql = "insert into wiz_bannerinfo (idx,title,name,types,types_num,padding,isuse) values('','$title','$name','$types','$types_num','$padding','$isuse')";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("배너그룹을 추가 하였습니다.","banner_list.php");

// 배너그룹수정
}else if($mode == "ban_group_update") {


	$sql = "update wiz_bannerinfo set title='$title', types='$types', types_num='$types_num', padding='$padding', isuse='$isuse' where idx = '$idx'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("배너그룹이 수정되었습니다.","banner_input".$popup.".php?mode=ban_group_update&idx=$idx&?place=$place");

// 배너그룹삭제
}else if($mode == "ban_group_delete"){

	$sql = "delete from wiz_bannerinfo where idx = '$idx'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	$banner_path = "../../data/banner";

	$sql = "SELECT de_img FROM wiz_banner WHERE name = '$code'";
	$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
	while ($row = mysqli_fetch_array($result)) {
		if(!empty($row[de_img])) {
			@unlink($banner_path."/".$row[de_img]);
		}
	}

	$sql = "delete from wiz_banner where name = '$code'";
	mysqli_query($connect, $sql) or die(mysqli_error($connect));

	complete("해당배너그룹을 삭제하였습니다.","banner_list.php");

}
?>