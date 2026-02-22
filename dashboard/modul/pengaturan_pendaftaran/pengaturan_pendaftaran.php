<?php
include "inc/pendaftaran_function.php";
switch (uri_segment(2)) {
    case "create":
    if ($db2->userCan("insert")) {
    if (uri_segment(3)=='mahasiswa') {
        include "get_pengaturan_mhs_add.php";
    } elseif (uri_segment(3)=='dopeg') {
        include "get_pengaturan_dopeg_add.php";
    } else {
             include "pengaturan_pendaftaran_add.php";
    } 
     }
      break;
    case "edit":
    if ($db2->userCan("update")) {
    $data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",uri_segment(4));

        if (uri_segment(3)=='mahasiswa') {
            include "pengaturan_pendaftaran_edit_mhs.php";
        } elseif (uri_segment(3)=='dopeg') {
            include "pengaturan_pendaftaran_edit_dopeg.php";
        }
    } 
      break;
    case "detail":
    $data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",uri_segment(4));
    include "pengaturan_pendaftaran_detail.php";
    break;
    default:
    include "pengaturan_pendaftaran_view.php";
    break;
}

?>