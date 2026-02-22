<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("jadwal_kuliah","jadwal_id",uri_segment(3));
    include "rekap_pengunaan_ruangan_detail.php";
    break;
    default:
    include "rekap_pengunaan_ruangan_view.php";
    break;
}

?>