<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "rekap_distribusi_nilai_add.php";
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
                             include "rekap_distribusi_nilai_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("","",uri_segment(3));
    include "rekap_distribusi_nilai_detail.php";
    break;
    default:
    if ($_SESSION['level']=='1') {
       include "rekap_distribusi_nilai_view.php";
    }
     elseif ($_SESSION['level']=='6') {
       include "rekap_distribusi_nilai_view_fak.php";
    }
    elseif ($_SESSION['level']=='5') {
       include "rekap_distribusi_nilai_view_jur.php";
    }
    break;
}

?>