<?   
include '../inc/common.inc'; 				// DB컨넥션, 접속자 파악
include '../inc/oper_info.inc';			// 운영정보
include '../inc/util.inc';		      // 유틸라이브러리

function json_encode($data) {
    switch (gettype($data)) {
        case 'boolean':
            return $data?'true':'false';
        case 'integer':
        case 'double':
            return $data;
        case 'string':
            return '"'.strtr($data, array('\\'=>'\\\\','"'=>'\\"')).'"';
        case 'array':
            $rel = false; // relative array?
            $key = array_keys($data);
            foreach ($key as $v) {
                if (!is_int($v)) {
                    $rel = true;
                    break;
                }
            }

            $arr = array();
            foreach ($data as $k=>$v) {
                $arr[] = ($rel?'"'.strtr($k, array('\\'=>'\\\\','"'=>'\\"')).'":':'').json_encode($v);
            }

            return $rel?'{'.join(',', $arr).'}':'['.join(',', $arr).']';
        default:
            return '""';
    }
}

$sql = "SELECT * FROM wiz_dellist WHERE idx='$idx'";
$result = mysqli_query($connect, $sql) or die (mysqli_error($connect));
$data = mysqli_fetch_array($result);

echo iconv("CP949", "UTF-8", json_encode($data));
?>



