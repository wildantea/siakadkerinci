<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_bayar_mahasiswa","id",uri_segment(3));
    include "input_pembayaran_detail.php";
    break;
    default:
    include "input_pembayaran_add.php";
    break;
}

?>