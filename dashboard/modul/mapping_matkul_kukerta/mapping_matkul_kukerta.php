<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("matkul_kukerta","",uri_segment(3));
    include "mapping_matkul_kukerta_detail.php";
    break;
    default:
    include "mapping_matkul_kukerta_view.php";
    break;
}

?>