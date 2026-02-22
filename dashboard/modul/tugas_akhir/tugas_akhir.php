<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tugas_akhir","id_ta",uri_segment(3));
    include "tugas_akhir_detail.php";
    break;
    case "jadwal":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="jadwal") {
                      if ($role_act["insert_act"]=="Y") {
                         include "jadwal_tugas_akhir.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    default:
    if ($_SESSION['group_level'] == 'admin') {
        include "tugas_akhir_view.php";
    } else if($_SESSION['group_level'] == 'mahasiswa'){
        include "tugas_akhir_view_mahasiswa.php";
    } else {
        include "tugas_akhir_view_fakultas.php";
    }
    break;
}

?>
