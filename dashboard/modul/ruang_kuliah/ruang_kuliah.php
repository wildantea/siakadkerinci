<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("ruang_ref","ruang_id",uri_segment(3));
    include "ruang_kuliah_detail.php";
    break;
    default:
    include "ruang_kuliah_view.php";
    break;
}

?>