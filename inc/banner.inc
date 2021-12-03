<?

$sql = "select name,types,types_num,padding from wiz_bannerinfo where name = '$code' and isuse != 'N'";
$result = mysqli_query($connect, $sql) or die(mysqli_error($connect));

$ban_info = mysqli_fetch_object($result);

$banner = "<table border=0 cellpadding=0 cellspacing=0 align=center><tr>";

$sql = "select * from wiz_banner where name = '$ban_info->name' and isuse != 'N' order by prior asc, idx asc";
$result2 = mysqli_query($connect, $sql) or die(mysqli_error($connect));

$total = 1;
while($row = mysqli_fetch_object($result2)){

  if($row->de_type == "IMG")
    $ban_content = "<img src=/data/banner/".$row->de_img." border=0>";

  else
    $ban_content = "<table cellpadding=0 cellspacing=0 border=0><tr><td>".$row->de_html."</td></tr></table>";

  if($ban_info->types == "H") {
    if($row->link_url == "")
      $banner .= "<tr>\n<td>".$ban_content."</td>\n</tr>\n";
    else
      $banner .= "<tr>\n<td><a href=".$row->link_url." target=".$row->link_target.">".$ban_content."</a></td>\n</tr>\n";

			if($ban_info->padding > 0) $banner .= "<tr><td height=".$ban_info->padding."></td></tr>";

  } else {
    $mod = ($total%$ban_info->types_num);

    if($mod != 0) {
      if($row->link_url == "")
        $banner .= "<td align='left'>".$ban_content."</td>\n";
      else
        $banner .= "<td align='left'><a href=".$row->link_url." target=".$row->link_target.">".$ban_content."</a></td>\n";
    } else {
      if($row->link_url == "")
        $banner .= "<td align='left'>".$ban_content."</td>\n</tr>\n<tr><td colspan='".($ban_info->types_num * 2)."' height='".$ban_info->padding."'></td></tr>\n<tr>\n";
      else
        $banner .= "<td align='left'><a href=".$row->link_url." target=".$row->link_target.">".$ban_content."</a></td>\n</tr>\n<tr>\n";
    }
    if($mod > 0 && $ban_info->padding > 0) $banner .= "<td width=".$ban_info->padding."></td>";
  }

  $total++;
}

$banner .= "</tr></table>";

echo $banner;
?>