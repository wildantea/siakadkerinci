<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
    include "validasi_mahasiswa_baru_detail.php";
    break;
    default:
    include "validasi_mahasiswa_baru_view.php";
    break;
}

?>