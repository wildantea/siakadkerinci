<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
    include "periode_update_biodata_detail.php";
    break;
    default:
    include "periode_update_biodata_view.php";
    break;
}

?>