<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("beasiswa_mhs","id_beasiswamhs",uri_segment(3));
    include "pendaftaran_beasiswa_detail.php";
    break;
    case "jenis":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="jenis") {
                      if ($role_act["insert_act"]=="Y") {
                         include "jenis_beasiswa.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    case "beasiswa":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="beasiswa") {
                      if ($role_act["insert_act"]=="Y") {
                         include "beasiswa.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    default:
      if($_SESSION['level'] == '1') {
        include "pendaftaran_beasiswa_view.php";
      } elseif($_SESSION['level'] == '5') {
        # code...
      } elseif($_SESSION['level'] == '6') {
        # code...
      } else{
        include "pendaftaran_beasiswa_view_mahasiswa.php";
      }
    break;
}

?>