<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("batas_sks","id",uri_segment(3));
    include "setting_batas_sks_detail.php";
    break;
    default:
    include "setting_batas_sks_view.php";
    break;
}

?>