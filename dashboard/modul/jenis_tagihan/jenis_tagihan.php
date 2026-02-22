<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_jenis_tagihan","kode_tagihan",uri_segment(3));
    include "jenis_tagihan_detail.php";
    break;
    default:
    include "jenis_tagihan_view.php";
    break;
}

?>