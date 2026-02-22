<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "rekap_presensi_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("kelas","kelas_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "rekap_presensi_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("kelas","kelas_id",uri_segment(3));
    include "rekap_presensi_detail.php";
    break;
    default:
   /* if ($_SESSION['group_level']=='admin') {
          include "rekap_presensi_view.php";
    }*/if ($_SESSION['group_level']=='dosen') {
         include "rekap_presensi_view_dosen.php";
    } else {
       include "rekap_presensi_view.php";
    }
   
    break;
}

?>