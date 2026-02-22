<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("jenis_daftar","id_jenis_daftar",uri_segment(3));
    include "setting_jenis_daftar_detail.php";
    break;
    default:
    include "setting_jenis_daftar_view.php";
    break;
}

?>