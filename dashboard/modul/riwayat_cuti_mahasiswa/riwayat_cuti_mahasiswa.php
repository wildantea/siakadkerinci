<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("cuti_mahasiswa","id_cuti",uri_segment(3));
    include "riwayat_cuti_mahasiswa_detail.php";
    break;
    default:
    	if($_SESSION['level'] == '1'){
    		include "riwayat_cuti_mahasiswa_view.php";
    	} else if($_SESSION['level'] == '5'){
    		include "table_riwayat_jurusan.php";
    	} else if($_SESSION['level'] == '6') {
            include "riwayat_cuti_mahasiswa_view_fakultas.php";
        }
    break;
}

?>