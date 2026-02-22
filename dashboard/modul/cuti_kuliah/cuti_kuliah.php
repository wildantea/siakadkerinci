<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tb_data_cuti_mahasiswa","id_cuti",uri_segment(3));
    include "cuti_kuliah_detail.php";
    break;
    default:
    include "cuti_kuliah_view.php";
    break;
}

?>