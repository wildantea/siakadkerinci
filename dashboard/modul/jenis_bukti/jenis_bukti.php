<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tb_data_pendaftaran_jenis_bukti","id_jenis_bukti",uri_segment(3));
    include "jenis_bukti_detail.php";
    break;
    default:
    include "jenis_bukti_view.php";
    break;
}

?>