<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_tagihan_mahasiswa","id",uri_segment(3));
    include "info_tagihan_detail.php";
    break;
    default:
    include "info_tagihan_view.php";
    break;
}

?>