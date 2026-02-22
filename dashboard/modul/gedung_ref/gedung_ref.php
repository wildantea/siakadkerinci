<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("gedung_ref","gedung_id",uri_segment(3));
    include "gedung_ref_detail.php";
    break;
    default:
    include "gedung_ref_view.php";
    break;
}

?>