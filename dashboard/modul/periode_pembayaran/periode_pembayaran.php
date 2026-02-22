<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("periode_pembayaran","id_periode_pembayaran",uri_segment(3));
    include "periode_pembayaran_detail.php";
    break;
    default:
    include "periode_pembayaran_view.php";
    break;
}

?>