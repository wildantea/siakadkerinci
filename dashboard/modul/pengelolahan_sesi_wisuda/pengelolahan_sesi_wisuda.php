<?php
switch (uri_segment(2)) {
    case "detail":
    $data_edit = $db->fetch_single_row("tugas_akhir","id_ta",uri_segment(3));
    include "pengelolahan_sesi_wisuda_detail.php";
    break;
    case "setting":
      foreach ($db->fetch_all("sys_menu") as $isi) {
           if (uri_segment(1)==$isi->url&&uri_segment(2)=="setting") {
                      if ($role_act["insert_act"]=="Y") {
                         include "setting_priode.php";
                      } else {
                        echo "permission denied";
                      }
                   }

      }
    break;
    default:
    	if($_SESSION['level'] == '1'){
        $data_priode = $db->fetch_single_row("kelola_wisuda","id_wisuda",uri_segment(3));
    		include "pengelolahan_sesi_wisuda_view.php";
    	} else if($_SESSION['level'] == '6') {
        $data_priode = $db->fetch_single_row("kelola_wisuda","id_wisuda",uri_segment(3));
        include "pengelolahan_sesi_wisuda_view_fakultas.php";
      } else{
        $data_edit = $db->fetch_single_row("tugas_akhir","id_ta",uri_segment(3));
    		include "pengelolahan_sesi_wisuda_view_mahasiswa.php";
    	}
    break;
}

?>