<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tb_data_pendaftaran","id",uri_segment(3));
    include "pendaftaran_proposal_detail.php";
    break;
    default:
    include "pendaftaran_proposal_view.php";
    break;
}

?>