<?php
  $sql = "SELECT * FROM wiz_operinfo";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

  $oper_info = mysqli_fetch_object($result);

	$review_code = "review";
  $sql = "select usetype from wiz_bbsinfo where code = '$review_code'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $row = mysqli_fetch_array($result);

  $oper_info->review_usetype = $row['usetype'];

	$qna_code = "qna";
  $sql = "select usetype from wiz_bbsinfo where code = '$qna_code'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));
  $row = mysqli_fetch_array($result);

  $oper_info->qna_usetype = $row['usetype'];
?>