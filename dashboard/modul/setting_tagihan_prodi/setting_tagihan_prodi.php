<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("keu_tagihan","id",uri_segment(3));
    include "setting_tagihan_prodi_detail.php";
    break;
    default:
    include "setting_tagihan_prodi_view.php";
    break;
}

?>