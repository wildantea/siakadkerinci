<?php
switch (uri_segment(1)) {
    case "create":
          if ($db2->userCan("insert")) {
             include "input_pembayaran_add.php";
          } 
      break;
    case "edit":
    $data_edit = $db2->fetchSingleRow("keu_bayar_mahasiswa","id",uri_segment(2));
          if ($db2->userCan("update")) {
             include "input_pembayaran_edit.php";
          } 
      break;
      
    case "detail":
    $data_edit = $db2->fetchSingleRow("keu_bayar_mahasiswa","id",uri_segment(2));
    include "input_pembayaran_detail.php";
    break;
    default:
    include "input_pembayaran_view.php";
    break;
}

?>