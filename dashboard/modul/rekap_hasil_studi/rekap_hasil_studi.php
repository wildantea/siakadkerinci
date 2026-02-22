<?php
switch (uri_segment(2)) {
  case "add_nilai":
      $id_kelas = de(uri_segment(3));
      $jur      = de(uri_segment(4));
      $sem      = de(uri_segment(5));
      $ang      = de(uri_segment(6));
      $jml_komponen =0;
     // echo "$id_kelas";
     // print_r($_SESSION);
      $pengampu  = "";
      $jur = "";
      foreach ($db->query("select k.kls_nama,m.nama_mk,j.kode_jur from kelas k 
                          join matkul m on k.id_matkul=m.id_matkul
                          join kurikulum ku on ku.kur_id=m.kur_id
                          join jurusan j on j.kode_jur=ku.kode_jur
                          where k.kelas_id='$id_kelas'") as $data_kelas) {
        $jur = $data_kelas->kode_jur;
        foreach ($db->query("select ds.nip,ds.nama_dosen,ds.gelar_depan,ds.gelar_belakang from dosen_kelas d join dosen ds on d.id_dosen=ds.nip where d.id_kelas='$id_kelas'") as $data_dosen) {
           $pengampu.="-&nbsp;&nbsp;$data_dosen->gelar_depan $data_dosen->nama_dosen, $data_dosen->gelar_belakang<br>";
        }
         include 'input_nilai.php';
      }
     
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "rekap_hasil_studi_add.php";
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
                             include "rekap_hasil_studi_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("","",uri_segment(3));
    include "rekap_hasil_studi_detail.php";
    break;
    default:
    if ($_SESSION['level']=='1') {
       include "rekap_hasil_studi_view.php";
    }
     elseif ($_SESSION['level']=='6') {
       include "rekap_hasil_studi_view_fak.php";
    }
    elseif ($_SESSION['level']=='5') {
       include "rekap_hasil_studi_view_jur.php";
    }
    break;
}

?>