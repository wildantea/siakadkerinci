<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("kompre","id",uri_segment(3));
    include "pendaftaran_komprehensif_detail.php";
    break;
    case "jadwal":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="jadwal") {
                      if ($role_act["insert_act"]=="Y") {
                         include "jadwal_kompre.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    default:
      if ($_SESSION['group_level']=='mahasiswa') {
       include "pendaftaran_komprehensif_view_mahasiswa.php";
      }
    	else {
            include "pendaftaran_komprehensif_view_jurusan.php";
        }
    break;
}

?>