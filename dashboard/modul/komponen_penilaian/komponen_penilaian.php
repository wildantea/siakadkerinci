<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("komponen_nilai","id",uri_segment(3));
    include "komponen_penilaian_detail.php";
    break;
    default:
    include "komponen_penilaian_view.php";
    break;
}

?>