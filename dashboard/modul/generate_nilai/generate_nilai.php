<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("generate_nilai","id",uri_segment(3));
    include "generate_nilai_detail.php";
    break;
    case "coba":
    include "generate_nilai_view_coba.php";
    break;
    default:
    include "generate_nilai_view_coba.php";
    break;
}

?>