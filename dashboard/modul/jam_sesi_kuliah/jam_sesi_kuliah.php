<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("sesi_waktu","id_sesi",uri_segment(3));
    include "jam_sesi_kuliah_detail.php";
    break;
    default:
    include "jam_sesi_kuliah_view.php";
    break;
}

?>