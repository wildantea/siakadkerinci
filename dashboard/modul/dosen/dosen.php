<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("dosen","id_dosen",uri_segment(3));
    include "dosen_detail.php";
    break;
    default:
    include "dosen_view.php";
    break;
}

?>