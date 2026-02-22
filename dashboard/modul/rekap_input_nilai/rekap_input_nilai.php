<?php
switch (uri_segment(2)) {

  case "sudah":
  if ($_SESSION['level']=='5') {
   
      include "sudah_input_jur.php";
    }else{
      include "sudah_input.php";
    }
    break;
    case "belum":
    if ($_SESSION['level']=='5') {
      include "belum_input_jur.php";
    }else{
      include "belum_input.php";
    }
    break;
    
    case "belum_lengkap":
    if ($_SESSION['level']=='5') {
      include "belum_lengkap_jur.php";
    }else{
      include "belum_lengkap.php";
    }
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "rekap_input_nilai_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("","",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "rekap_input_nilai_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("","",uri_segment(3));
    include "rekap_input_nilai_detail.php";
    break;
   /* case 'new':
      include "rekap_view.php";
      break;*/
    default:
   // include "rekap_view.php";
 include "rekap_view.php";
  /*  if ($_SESSION['level']=='1') {
       include "rekap_input_nilai_view.php";
    }
     
     elseif ($_SESSION['level']=='6') {
       include "rekap_input_nilai_view_fak.php";
    }
    elseif ($_SESSION['level']=='5') {
       include "rekap_input_nilai_view_jur.php";
    }*/
    break;
}

?>