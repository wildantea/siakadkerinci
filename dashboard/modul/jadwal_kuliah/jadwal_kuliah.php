<?php
switch (uri_segment(2)) {
    case "generate_jadwal":
      include "generate_jadwal.php";
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "jadwal_kuliah_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("jadwal_kuliah","jadwal_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "jadwal_kuliah_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("jadwal_kuliah","jadwal_id",uri_segment(3));
    include "jadwal_kuliah_detail.php";
    break;
    default:
    //print_r($_SESSION); die();
    if ($_SESSION['group_level']=='admin') {
       include "jadwal_kuliah_view.php";
       $btn_simpan = "Simpan KRS";
    }
    elseif ($_SESSION['group_level']=='mahasiswa') {
         //print_r($_SESSION); 
         $mhs_id = $_SESSION['username'];
         $sem_aktif = get_semester_aktif($_SESSION['id_jur']);
        // print_r($_SESSION); 
        // print_r($sem_aktif); 
         $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);
         $btn_simpan = "Ajukan KRS";
        $qq=$db->query("select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
                      join jurusan j on j.kode_jur=m.jur_kode
                      join fakultas f on f.kode_fak=j.fak_kode where m.nim='$mhs_id'");
        // echo "select m.nim,m.nama,j.nama_jur as jurusan,f.nama_resmi as fakultas from mahasiswa m
        //               join jurusan j on j.kode_jur=m.jur_kode
        //               join fakultas f on f.kode_fak=j.fak_kode where m.nim='$mhs_id'";
        foreach ($qq as $kk) {
              //include "rencana_studi_add.php";
            include "tabel_jadwal_mhs.php";
          }
    }
    else if ($_SESSION['group_level']=='dosen') {
         $mhs_id = $_SESSION['username'];
        // $sem_aktif = get_semester_aktif($_SESSION['id_jur']);
        // print_r($sem_aktif); 
       //  $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);
        include "tabel_jadwal_dosen.php";
    }
   
    break;
}

?>