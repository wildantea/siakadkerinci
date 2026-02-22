<?php
switch (uri_segment(2)) {
    case "generate-jadwal":
      include "generate_jadwal.php";
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "kelas_jadwal_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("view_nama_kelas","kelas_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "kelas_jadwal_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("mhs","id",uri_segment(3));
    include "kelas_jadwal_detail.php";
    break;
    case 'acc':
     include "new_file.php";
           break;
    default:
    include "kelas_jadwal_view.php";
    break;
}

?>