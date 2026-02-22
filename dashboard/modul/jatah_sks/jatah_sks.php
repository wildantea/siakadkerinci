<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("jatah_sks","id",uri_segment(3));
    include "jatah_sks_detail.php";
    break;
    default:
    include "jatah_sks_view.php";
    break;
}

?>