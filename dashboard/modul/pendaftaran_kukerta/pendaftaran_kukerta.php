<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("kkn","id_kkn",uri_segment(3));
    include "pendaftaran_kukerta_detail.php";
    break;
    case "priode":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="priode") {
                      if ($role_act["insert_act"]=="Y") {
                         include "setting_priode.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    case "lokasi":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="lokasi") {
                      if ($role_act["insert_act"]=="Y") {
                         include "setting_lokasi.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;    
    default:
      if($_SESSION['group_level']=='mahasiswa'){
        include "pendaftaran_kukerta_view_mahasiswa.php";
      }else{
        include "pendaftaran_kukerta_view.php";
      }
    break;
}

?>