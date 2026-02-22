<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "template_pendaftaran_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("tb_data_pendaftaran","id_pendaftaran",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "template_pendaftaran_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("tb_data_pendaftaran","id_pendaftaran",uri_segment(3));
    include "template_pendaftaran_detail.php";
    break;
    case "bimbingan":
    $data_edit = $db->fetch_single_row("tb_data_pendaftaran","id_pendaftaran",uri_segment(3));
    include "list_bimbingan.php";
    break;
    default:
    include "template_pendaftaran_view.php";
    break;
}

?>