<?php
switch (uri_segment(2)) {
        case "proses":
      $id_kelas = de(uri_segment(3));
      $jur      = de(uri_segment(4));
      $sem      = de(uri_segment(5));
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
         include "proses.php";
      }
     
    break;

    case "tambah":

     $btn_simpan = "Ajukan KRS";
         //show page excel dosen & mahasiswa
    if ($_SESSION['group_level']!='mahasiswa' && $_SESSION['group_level']!='dosen') {
          $nim = $dec->dec($_GET['n']);
          $sem = $dec->dec($_GET['s']);
        $is_super_user = 'yes';
        $array_super_user = array('admin','TIPD');

        if (!in_array($_SESSION['group_level'], $array_super_user)) {
          $is_super_user = 'no';
        }

        $is_periode = check_current_periode('krs',get_sem_aktif(),$data_mhs->jur_kode);
        $is_periode_krs = "yes";
        if ($is_periode==false) { 
          $is_periode_krs = "no";
        }
        $is_current_periode = 'yes';
        if ($sem!=get_sem_aktif()) {
        $is_current_periode = 'no';
        }

        $is_aktif_krs = 'no';
        if ($is_super_user=='yes') {
          $is_aktif_krs = 'yes';

        } else {
          if ($data_krs->status_disetujui<1 && $is_periode_krs=='yes' && $is_current_periode=='yes') {
            $is_aktif_krs = 'yes';
          }
        }

        if ($is_aktif_krs=='yes') {
            include "rencana_studi_add_by_admin.php";
        }
    } elseif ($_SESSION['group_level']=='mahasiswa') {
     /*  $mhs_id = $_SESSION['username'];
       $sem_aktif = get_semester_aktif($_SESSION['kode_jur']);
       $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);*/
       $btn_simpan = "Ajukan KRS";
       include "rencana_studi_add_mahasiswa.php";
      } elseif( $_SESSION['group_level']=='dosen') {
         $nim = $dec->dec($_GET['n']);
          $sem = $dec->dec($_GET['s']);
         include "rencana_studi_add_by_dosen.php";
      }

      /*else{
       $mhs_id = $_GET['nim'];
       $sem_aktif = get_semester_aktif(get_kode_jur_by_nim($mhs_id));
       $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);
       $btn_simpan = "Ajukan KRS";
       include "rencana_studi_add.php";
      }*/
                             // echo get_kode_jur_by_nim($mhs_id)." ".$sem_aktif->id_semester." ".$akm_id;
                             
                        
     
    break;
    case 'dosen':
         include "krs_dosen/rencana_studi_view.php";
        break;
  case "edit":
    $data_edit = $db->fetch_single_row("krs","krs_id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "rencana_studi_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;

    case "detail":
    //show page excel dosen & mahasiswa
     update_akm($dec->dec($_GET['n']));   
    if ($_SESSION['group_level']!='mahasiswa' && $_SESSION['group_level']!='dosen') {
      //encrypt
      $nim = $dec->dec($_GET['n']);
      $sem = $dec->dec($_GET['s']);
      include "rencana_studi_mahasiswa_view_by_admin.php";
    } else {
       $nim = $dec->dec($_GET['n']);
      $sem = $dec->dec($_GET['s']);
      include "rencana_studi_mahasiswa_view_by_dosen.php";
    } 
    break;
    case "manage_krs":   
    if ($_SESSION['group_level']=='admin') {
       $mhs_id = uri_segment(3);
    }
    elseif ($_SESSION['group_level']=='mahasiswa') {
       $mhs_id = $_SESSION['username'];
    }
       include "rencana_studi_view.php";
    break;
   /* case 'baru':
        include "krs_admin/rencana_studi_view.php";
    break; */   
    default:
   // print_r($_SESSION);
    //show page excel dosen & mahasiswa
    if ($_SESSION['group_level']!='mahasiswa' && $_SESSION['group_level']!='dosen') {
       include "krs_admin/rencana_studi_view.php";
    } elseif ($_SESSION['group_level']=='mahasiswa') {
        // print_r($_SESSION);
/*         $mhs_id = $_SESSION['username'];
         //get semester aktif 
         $tahun_akademik_siswa = get_sem_aktif();
         $sem_aktif = get_semester_aktif($_SESSION['kode_jur']);
         $akm_id = get_semester_aktif_mhs($sem_aktif->id_semester, $mhs_id);*/
         update_akm($_SESSION['username']); 
         $btn_simpan = "Ajukan KRS";
          //include "rencana_studi_add.php";
        include "rencana_studi_mahasiswa_view.php";
    } else if($_SESSION['group_level']=='dosen'){
          include "krs_dosen/rencana_studi_view.php";
        // include "rencana_studi_view_dosen_new.php";
    }
       
    break;
}

?>