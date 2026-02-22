<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("pejabat","id_pejabat",uri_segment(3));
    include "pejabat_detail.php";
    break;
    default:
    include "pejabat_view.php";
    break;
}

?>