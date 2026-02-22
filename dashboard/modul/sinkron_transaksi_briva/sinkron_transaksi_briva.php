<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("transaksi_briva","id_transaksi",uri_segment(3));
    include "sinkron_transaksi_briva_detail.php";
    break;
    default:
    include "sinkron_transaksi_briva_view.php";
    break;
}

?>