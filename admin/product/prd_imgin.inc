<?

	$upfile_path = "../../data/prdimg";		// 이미지 위치

	// 상품이미지 자동저장
   if($realimg[size] > 0){

		file_check($realimg[name]);

		$realimg_ext = strtolower(substr($realimg[name],-3));
		$realimg_name = $prdcode."_tmp";
   	copy($realimg[tmp_name], $upfile_path."/".$realimg_name);

   	$prdimg_R_name = $prdcode."_R.".$realimg_ext;
   	$prdimg_L1_name = $prdcode."_L1.".$realimg_ext;
   	$prdimg_M1_name = $prdcode."_M1.".$realimg_ext;
   	$prdimg_S1_name = $prdcode."_S1.".$realimg_ext;

   	img_resize($realimg_name, $prdimg_R_name, $upfile_path, $oper_info->prdimg_R, $oper_info->prdimg_R);
   	img_resize($realimg_name, $prdimg_L1_name, $upfile_path, $oper_info->prdimg_L, $oper_info->prdimg_L);
   	img_resize($realimg_name, $prdimg_M1_name, $upfile_path, $oper_info->prdimg_M, $oper_info->prdimg_M);
   	img_resize($realimg_name, $prdimg_S1_name, $upfile_path, $oper_info->prdimg_S, $oper_info->prdimg_S);

		@unlink($upfile_path."/".$realimg_name);
	}


	for($ii = 2; $ii <= 5; $ii++) {

		if($_FILES['realimg'.$ii]['size'] > 0){
			file_check($_FILES['realimg'.$ii]['name']);

			$realimg_ext = strtolower(substr($_FILES['realimg'.$ii]['name'],-3));
			${realimg.$ii._name} = $prdcode."_tmp";
	   	copy($_FILES['realimg'.$ii]['tmp_name'],$upfile_path."/".${realimg.$ii._name});
	   	chmod($upfile_path."/".${realimg.$ii._name}, 0606);
	   	${prdimg_L.$ii._name} = $prdcode."_L".$ii.".".$realimg_ext;
	   	${prdimg_M.$ii._name} = $prdcode."_M".$ii.".".$realimg_ext;
	   	${prdimg_S.$ii._name} = $prdcode."_S".$ii.".".$realimg_ext;

	   	img_resize(${realimg.$ii._name}, ${prdimg_L.$ii._name}, $upfile_path, $oper_info->prdimg_L, $oper_info->prdimg_L);
	   	img_resize(${realimg.$ii._name}, ${prdimg_M.$ii._name}, $upfile_path, $oper_info->prdimg_M, $oper_info->prdimg_M);
	   	img_resize(${realimg.$ii._name}, ${prdimg_S.$ii._name}, $upfile_path, $oper_info->prdimg_S, $oper_info->prdimg_S);
	   	
	   	@unlink($upfile_path."/".${realimg.$ii._name});

		}

	}

	// 상품이미지 개별저장
	if($realimg[size] <= 0){

		if($prdimg_R[size] > 0){
			file_check($prdimg_R[name]);
			$prdimg_R_ext = strtolower(substr($prdimg_R[name],-3));
			$prdimg_R_name = $prdcode."_R.".$prdimg_R_ext;
			copy($prdimg_R['tmp_name'], $upfile_path."/".$prdimg_R_name);
			chmod($upfile_path."/".$prdimg_R_name, 0606);
		}

		if($prdimg_L1[size] > 0){
			file_check($prdimg_L1[name]);
			$prdimg_L1_ext = strtolower(substr($prdimg_L1[name],-3));
			$prdimg_L1_name = $prdcode."_L1.".$prdimg_L1_ext;
			copy($prdimg_L1['tmp_name'], $upfile_path."/".$prdimg_L1_name);
			chmod($upfile_path."/".$prdimg_L1_name, 0606);
		}
		if($prdimg_M1[size] > 0){
			file_check($prdimg_M1[name]);
			$prdimg_M1_ext = strtolower(substr($prdimg_M1[name],-3));
			$prdimg_M1_name = $prdcode."_M1.".$prdimg_M1_ext;
			copy($prdimg_M1['tmp_name'], $upfile_path."/".$prdimg_M1_name);
			chmod($upfile_path."/".$prdimg_M1_name, 0606);
		}
		if($prdimg_S1[size] > 0){
			file_check($prdimg_S1[name]);
			$prdimg_S1_ext = strtolower(substr($prdimg_S1[name],-3));
			$prdimg_S1_name = $prdcode."_S1.".$prdimg_S1_ext;
			copy($prdimg_S1['tmp_name'], $upfile_path."/".$prdimg_S1_name);
			chmod($upfile_path."/".$prdimg_S1_name, 0606);
		}

	}

	for($ii = 2; $ii <= 5; $ii++) {

		if($_FILES['realimg'.$ii]['size'] <= 0){

		   if($_FILES['prdimg_L'.$ii]['size'] > 0){

			 	file_check($_FILES['prdimg_L'.$ii]['name']);

			 	if(!empty($row->{prdimg_L.$ii})) @unlink($upfile_path."/".$row->{prdimg_L.$ii});
			 	
	      ${prdimg_L.$ii._ext} = strtolower(substr($_FILES['prdimg_L'.$ii]['name'],-3));
	      ${prdimg_L.$ii._name} = $prdcode."_L".$ii.".".${prdimg_L.$ii._ext};

		    copy($_FILES['prdimg_L'.$ii]['tmp_name'],$upfile_path."/".${prdimg_L.$ii._name});
		    chmod($upfile_path."/".${prdimg_L.$ii._name}, 0606);

		   }else{
		   	${prdimg_L.$ii._name} = $row->{prdimg_L.$ii};
			 }
		   if($_FILES['prdimg_M'.$ii]['size'] > 0){

					file_check($_FILES['prdimg_M'.$ii]['name']);

					if(!empty($row->{prdimg_M.$ii})) @unlink($upfile_path."/".$row->{prdimg_M.$ii});

					${prdimg_M.$ii._ext} = strtolower(substr($_FILES['prdimg_M'.$ii]['name'],-3));
					${prdimg_M.$ii._name} = $prdcode."_M".$ii.".".${prdimg_M.$ii._ext};
					copy($_FILES['prdimg_M'.$ii]['tmp_name'],$upfile_path."/".${prdimg_M.$ii._name});
					chmod($upfile_path."/".${prdimg_M.$ii._name}, 0606);

		   }else{
		   	${prdimg_M.$ii._name} = $row->{prdimg_M.$ii};
			 }
		   if($_FILES['prdimg_S'.$ii]['size'] > 0){

			 	 	file_check($_FILES['prdimg_S'.$ii]['name']);

					if(!empty($row->{prdimg_S.$ii})) @unlink($upfile_path."/".$row->{prdimg_S.$ii});

		      ${prdimg_S.$ii._ext} = strtolower(substr($_FILES['prdimg_S'.$ii]['name'],-3));
		      ${prdimg_S.$ii._name} = $prdcode."_S".$ii.".".${prdimg_S.$ii._ext};
				   copy($_FILES['prdimg_S'.$ii]['tmp_name'],$upfile_path."/".${prdimg_S.$ii._name});
				   chmod($upfile_path."/".${prdimg_S.$ii._name}, 0606);
		   }else{
		   	${prdimg_S.$ii._name} = $row->{prdimg_S.$ii};
			 }

		}

	}

?>