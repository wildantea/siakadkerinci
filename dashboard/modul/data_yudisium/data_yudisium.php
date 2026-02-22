<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tugas_akhir","id_ta",uri_segment(3));
    include "data_yudisium_detail.php";
    break;
    default:
    	if($_SESSION['level'] == '1') {
    		include "data_yudisium_view.php";
    	} else if($_SESSION['level'] == '5'){
    		include "data_yudisium_view_jurusan.php";
    	} else if($_SESSION['level'] == '6'){
            include "data_yudisium_view_fakultas.php";
        }
    break;
}

?>
