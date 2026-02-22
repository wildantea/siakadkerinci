<?php
switch (uri_segment(2)) {
    case "create":
          if ($db2->userCan("insert")) {
             include "mahasiswa_lulus_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db2->fetchSingleRow("tb_data_kelulusan","id",
      uri_segment(3));
    $data_mhs = $db2->fetchCustomSingle('select nim,nama,nama_jurusan from view_simple_mhs where nim=? and kode_jur=?',array('nim' => $data_edit->nim,'kode_jur' => $data_edit->kode_jurusan));
          if ($db2->userCan("update")) {
             include "mahasiswa_lulus_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db2->fetchSingleRow("tb_data_kelulusan","id",uri_segment(3));
    include "mahasiswa_lulus_detail.php";
    break;
    default:
    include "mahasiswa_lulus_view.php";
    break;
}

?>