<?php
switch (uri_segment(2)) {
   case "show-nilai":
   // echo de(uri_segment(3)); die();
   $nim = de(uri_segment(3));
    $q=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$nim'");
    foreach ($q as $k) {
       include "hasil_studi_mahasiswa_all.php";
    }

    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "hasil_studi_mahasiswa_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("krs_detail","id_krs_detail",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "hasil_studi_mahasiswa_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("krs_detail","id_krs_detail",uri_segment(3));
    include "hasil_studi_mahasiswa_detail.php";
    break;
       
   default:
        if ($_SESSION['group_level']=='admin') {
       include "hasil_studi_mahasiswa_view.php";
       $btn_simpan = "Simpan KRS";
    }
    elseif ($_SESSION['group_level']=='mahasiswa') {
        // print_r($_SESSION);
         $mhs_id = $_SESSION['username'];
         $sem_aktif = get_semester_aktif($_SESSION['id_jur']);
         $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);
         $btn_simpan = "Ajukan KRS";
    $qq=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                  join jurusan j on j.kode_jur=m.jur_kode
                  join fakultas f on f.kode_fak=j.fak_kode where m.nim='$mhs_id'");
    foreach ($qq as $kk) {
          //include "rencana_studi_add.php";
        include "hasil_studi_mahasiswa_all_mhs.php";
      }
    }

    elseif ($_SESSION['level']=='5') {
      include "hasil_studi_mahasiswa_view_jur.php";
    }
    elseif ($_SESSION['level']=='6') {
      include "hasil_studi_mahasiswa_view_fak.php";
    }
    break;
}

?>