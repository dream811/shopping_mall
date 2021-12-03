<?

	$upfile_path = "../data/prdimg";		// 이미지 위치
	$deimgcnt = 0;
	// 상품이미지 자동저장
	//if($realimg[size] > 0){
	if($data['images'][0] != ""){
		$deimgcnt = 1;
		$realimg_ext = strtolower(substr($data['images'][0],-3));
		$realimg_name = $prdcode."_tmp";
   		//copy("http:".$data['images'][0], $upfile_path."/".$realimg_name);
		$imgUrl = $data['images'][0];
		if(substr($imgUrl, 0, 4) != "http") $imgUrl = "http:".$imgUrl;
	   	$content = file_get_contents($imgUrl);
	   	file_put_contents($upfile_path."/".$realimg_name, $content);
		$prdimg_R_name = $prdcode."_R.".$realimg_ext;
		$prdimg_L1_name = $prdcode."_L1.".$realimg_ext;
		$prdimg_M1_name = $prdcode."_M1.".$realimg_ext;
		$prdimg_S1_name = $prdcode."_S1.".$realimg_ext;

		img_resize($realimg_name, $prdimg_R_name, $upfile_path, 270, 270);//$oper_info->prdimg_R, $oper_info->prdimg_R
		img_resize($realimg_name, $prdimg_L1_name, $upfile_path, 700, 700);//$oper_info->prdimg_L, $oper_info->prdimg_L
		img_resize($realimg_name, $prdimg_M1_name, $upfile_path, 500, 500);//$oper_info->prdimg_M, $oper_info->prdimg_M
		img_resize($realimg_name, $prdimg_S1_name, $upfile_path, 70, 70);//$oper_info->prdimg_S, $oper_info->prdimg_S

		@unlink($upfile_path."/".$realimg_name);
	}else{
		$prdimg_R_name = "";
		$prdimg_L1_name = "";
		$prdimg_M1_name = "";
		$prdimg_S1_name = "";
	}


	for($ii = 2; $ii <= 5; $ii++) {

		if($data['images'][$ii] != ""){
			$deimgcnt++;
			$realimg_ext = strtolower(substr($data['images'][$ii],-3));
			${'realimg'.$ii.'_name'} = $prdcode."_tmp";
	   		//copy($_FILES['realimg'.$ii]['tmp_name'],$upfile_path."/".${'realimg'.$ii.'_name'});
			$imgUrl = $data['images'][$ii];
			if(substr($imgUrl, 0, 4) != "http") $imgUrl = "http:".$imgUrl;
			$content = file_get_contents($imgUrl);
	   		file_put_contents($upfile_path."/".$realimg_name, $content);

			chmod($upfile_path."/".${'realimg'.$ii.'_name'}, 0606);
			${'prdimg_L'.$ii.'_name'} = $prdcode."_L".$ii.".".$realimg_ext;
			${'prdimg_M'.$ii.'_name'} = $prdcode."_M".$ii.".".$realimg_ext;
			${'prdimg_S'.$ii.'_name'} = $prdcode."_S".$ii.".".$realimg_ext;

			img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_L'.$ii.'_name'}, $upfile_path, 700, 700);
			img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_M'.$ii.'_name'}, $upfile_path, 500, 500);
			img_resize(${'realimg'.$ii.'_name'}, ${'prdimg_S'.$ii.'_name'}, $upfile_path, 70, 70);
			
			@unlink($upfile_path."/".${'realimg'.$ii.'_name'});
		}else{
			${'prdimg_L'.$ii.'_name'} = "";
			${'prdimg_M'.$ii.'_name'} = "";
			${'prdimg_S'.$ii.'_name'} = "";
		}

	}
?>