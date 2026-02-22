<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "kelas_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $id_kelas = de(uri_segment(3));
    $data_edit = $db->fetch_single_row("kelas","kelas_id",$id_kelas);
    $kelas_dpna  = $db->fetch_single_row("kelas_dpna","id_kelas",$data_edit->kelas_id);
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "kelas_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("kelas","kelas_id",uri_segment(3));
    include "kelas_detail.php";
    break;
    default:
    if ($_SESSION['level']=='1') {
       include "kelas_view.php";
    }
     elseif ($_SESSION['level']=='6') {
       include "kelas_view_fak.php";
    }
    elseif ($_SESSION['level']=='5') {
       include "kelas_view_jur.php";
    }
    break;
}

?>