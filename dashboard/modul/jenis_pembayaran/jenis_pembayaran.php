<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_jenis_pembayaran","kode_pembayaran",uri_segment(3));
    include "jenis_pembayaran_detail.php";
    break;
    default:
    include "jenis_pembayaran_view.php";
    break;
}

?>