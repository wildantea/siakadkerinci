<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("data_rumpun_dosen","kode",uri_segment(3));
    include "rumpun_dosen_detail.php";
    break;
    default:
    include "rumpun_dosen_view.php";
    break;
}

?>