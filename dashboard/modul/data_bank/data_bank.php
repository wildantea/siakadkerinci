<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_bank","kode_bank",uri_segment(3));
    include "data_bank_detail.php";
    break;
    default:
    include "data_bank_view.php";
    break;
}

?>