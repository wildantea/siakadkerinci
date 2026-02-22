<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("jenis_keluar","id_jns_keluar",uri_segment(3));
    include "setting_jalur_keluar_detail.php";
    break;
    default:
    include "setting_jalur_keluar_view.php";
    break;
}

?>