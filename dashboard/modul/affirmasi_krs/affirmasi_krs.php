<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("affirmasi_krs","id_affirmasi",uri_segment(3));
    include "affirmasi_krs_detail.php";
    break;
    default:
    include "affirmasi_krs_view.php";
    break;
}

?>