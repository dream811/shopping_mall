<?php

  $sql = "SELECT * FROM wiz_page WHERE type = '$page_type'";
  $result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

  $page_info = mysqli_fetch_object($result);

  if(isset($page_info->subimg) && is_file("$DOCUMENT_ROOT/data/subimg/$page_info->subimg"))
    $subimg = "<img src='/data/subimg/$page_info->subimg'>";

  else
    $subimg = "";

?>