<?php
switch (uri_segment(1)) {
    case "detail":
    $data_edit = $db2->fetchSingleRow("tb_data_pendaftaran","id_pendaftaran",uri_segment(2));
    include "pendaftaran_detail.php";
    break;
    default:
	if ($_SESSION['group_level']=='mahasiswa') {
		include "mahasiswa/pendaftaran_view.php";
	} else {	
    	include "pendaftaran_view.php";
	}
    break;
}

?>