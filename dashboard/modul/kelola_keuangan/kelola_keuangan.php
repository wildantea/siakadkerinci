<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("mhs_registrasi","",uri_segment(3));
    include "kelola_keuangan_detail.php";
    break;
    default:
    include "kelola_keuangan_view.php";
    break;
}

?>