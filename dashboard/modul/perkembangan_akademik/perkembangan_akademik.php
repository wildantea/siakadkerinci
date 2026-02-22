<?php
switch (uri_segment(2)) {
      case "show-grafik":
   // echo de(uri_segment(3)); die();
    $nim=de(uri_segment(3));
    $q=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$nim'");
    foreach ($q as $k) {
       include "lihat_data_grafik.php";
    }
   
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "perkembangan_akademik_add.php";
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
                             include "perkembangan_akademik_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("","",uri_segment(3));
    include "perkembangan_akademik_detail.php";
    break;
    default:
    include "perkembangan_akademik_view.php";
    break;
}

?>