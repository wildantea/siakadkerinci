<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tugas_akhir","id_ta",uri_segment(3));
    include "bimbingan_tugas_akhir_detail.php";
    break;
    default:
    	if($_SESSION['level'] == '1') {
    		include "bimbingan_tugas_akhir_view.php";
    	} else if($_SESSION['level'] == '5'){
    		include "bimbingan_tugas_akhir_view_jurusan.php";
    	} else if($_SESSION['level'] == '4'){
            include "bimbingan_tugas_akhir_view_dosen.php";
        }
    break;
}

?>