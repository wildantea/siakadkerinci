<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "konsultasi_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "konsultasi_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "lihat":
    include "konsultasi_view_detail_mahasiswa_side.php";
    break;
    default:
    if ($_SESSION['group_level']=='mahasiswa') {
      include "konsultasi_view_detail_mahasiswa_side.php";
    } else {
      include "view_list_mhs.php";
    }
    
    break;
}

?>