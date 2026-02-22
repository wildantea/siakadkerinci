<?php
switch (uri_segment(1)) {
    case "create":
          if ($db->userCan("insert")) {
             include "aktivitas_kuliah_mahasiswa_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db->fetchSingleRow("tb_data_akm","akm_id",uri_segment(2));
          if ($db->userCan("update")) {
             include "aktivitas_kuliah_mahasiswa_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db->fetchSingleRow("tb_data_akm","akm_id",uri_segment(2));
    include "aktivitas_kuliah_mahasiswa_detail.php";
    break;
    default:
    include "aktivitas_kuliah_mahasiswa_view.php";
    break;
}

?>