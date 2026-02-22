<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "kurikulum_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("kurikulum","kur_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "kurikulum_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case 'tambah_mk':
     include "matkul_add.php";
      break;
    case 'edit_mk':
   // echo "string"; die();
       $data_edit = $db->fetch_single_row("matkul","id_matkul",uri_segment(3));
         include "matkul_edit.php";
  
      break;
    case "detail_mk":
   // $data_edit = $db->fetch_single_row("matkul","kur_id",uri_segment(3));
    include "matkul_view.php";
    break;
    case "detail_matkul":
    $data_edit = $db->fetch_single_row("matkul","id_matkul",uri_segment(3));
    include "matkul_detail.php";
    break;
    //prasayarat
    case 'prasayarat':
      include "matkul_prasyarat_view.php";
      break;
    case 'edit_prasayarat':
      include "matkul_prasyarat_change.php";
      break;
    case "detail":
    $data_edit = $db->fetch_single_row("kurikulum","kur_id",uri_segment(3));
    include "kurikulum_detail.php";
    break;
    default:
    include "kurikulum_view.php";
    break;
}

?>