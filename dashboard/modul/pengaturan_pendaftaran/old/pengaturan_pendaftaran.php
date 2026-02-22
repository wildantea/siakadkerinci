<?php
include "inc/pendaftaran_function.php";
switch (uri_segment(2)) {
    case "create":
          if ($db2->userCan("insert")) {
             include "pengaturan_pendaftaran_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db2->fetchSingleRow("tb_data_pendaftaran_jenis_pengaturan","id_jenis_pendaftaran_setting",uri_segment(3));
          if ($db2->userCan("update")) {
             include "pengaturan_pendaftaran_edit.php";
          } 
      break;
    default:
    include "pengaturan_pendaftaran_view.php";
    break;
}

?>