<?php
switch (uri_segment(2)) {
 //case "add-nilai-komponen":
    case "add_nilai_new":
      error_reporting(0);
      $id_kelas = de(uri_segment(3));
      $jur      = de(uri_segment(4));
      $sem      = de(uri_segment(5));
      $jml_komponen =0;
     // echo "$id_kelas";
     // print_r($_SESSION);
      $pengampu  = "";
      $kelas_attribute = $db->fetch_single_row('view_nama_kelas','kelas_id',$id_kelas);
      if ($_SESSION['group_level']=='dosen') {
        include "input_nilai_new.php";
      } else {
        include "input_nilai_new_admin_komponen.php";
       /* foreach ($db->query("select k.kls_nama,m.nama_mk,j.kode_jur from kelas k 
                            join matkul m on k.id_matkul=m.id_matkul
                            join kurikulum ku on ku.kur_id=m.kur_id
                            join jurusan j on j.kode_jur=ku.kode_jur
                            where k.kelas_id='$id_kelas'") as $data_kelas) {
          $jur = $data_kelas->kode_jur;
          foreach ($db->query("select ds.nip,ds.nama_dosen,ds.gelar_depan,ds.gelar_belakang from dosen_kelas d join dosen ds on d.id_dosen=ds.nip where d.id_kelas='$id_kelas'") as $data_dosen) {
             $pengampu.="-&nbsp;&nbsp;$data_dosen->gelar_depan $data_dosen->nama_dosen, $data_dosen->gelar_belakang<br>";
          }
           include 'input_nilai.php';
        }*/
      }
     
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "nilai_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
    case "add_nilai":
      error_reporting(0);
      $id_kelas = de(uri_segment(3));
      $jur      = de(uri_segment(4));
      $sem      = de(uri_segment(5));
      $jml_komponen =0;
     // echo "$id_kelas";
     // print_r($_SESSION);
      $pengampu  = "";
      $kelas_attribute = $db->fetch_single_row('view_nama_kelas','kelas_id',$id_kelas);
       $check_if_mk_reguler = $db->fetch_custom_single("select k.kelas_id from `kelas` k join matkul m on m.id_matkul=k.id_matkul
          WHERE m.id_tipe_matkul!='S' and 
          (m.nama_mk not like '%skrip%' and m.nama_mk not like '%ppl%'
           and m.nama_mk not like '%kuliah%' and m.nama_mk not like '%kkn%' and m.nama_mk not like '%kuker%' and m.nama_mk not like '%tugas%'
          and m.nama_mk not like '%kompre%' and m.nama_mk not like '%tesis%' and m.nama_mk not like '%Pengabdian%') 
          and k.kelas_id='".$id_kelas."'");

	    if ($_SESSION['group_level']=='dosen') {
         if ($check_if_mk_reguler) {
           include "input_nilai_new_dosen_komponen.php";
        } else {
          include "input_nilai_new.php";
        }
	    	
	    } else {
       
        if ($check_if_mk_reguler) {
           include "input_nilai_new_admin_komponen.php";
        } else {
          include "input_nilai_new_admin.php";
        }
       // dump($_SESSION);
        //Kasubag_Fakultas
        //include "input_nilai_new_admin.php";
       
	     /* foreach ($db->query("select k.kls_nama,m.nama_mk,j.kode_jur from kelas k 
	                          join matkul m on k.id_matkul=m.id_matkul
	                          join kurikulum ku on ku.kur_id=m.kur_id
	                          join jurusan j on j.kode_jur=ku.kode_jur
	                          where k.kelas_id='$id_kelas'") as $data_kelas) {
	        $jur = $data_kelas->kode_jur;
	        foreach ($db->query("select ds.nip,ds.nama_dosen,ds.gelar_depan,ds.gelar_belakang from dosen_kelas d join dosen ds on d.id_dosen=ds.nip where d.id_kelas='$id_kelas'") as $data_dosen) {
	           $pengampu.="-&nbsp;&nbsp;$data_dosen->gelar_depan $data_dosen->nama_dosen, $data_dosen->gelar_belakang<br>";
	        }
	         include 'input_nilai.php';
	      }*/
	    }
     
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "nilai_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("agama","id_agama",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "nilai_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("agama","id_agama",uri_segment(3));
    include "nilai_detail.php";
    break;
    case 'tes':
      include "nilai_view_new_tes.php";
    break;
    default:
    if ($_SESSION['group_level']=='dosen') {
    	include "nilai_dosen_view.php";
    } else {
      if ($_SESSION['group_level']=='Kasubag_Fakultas') {
       include "nilai_view_akademik.php";
      }else{
        include "nilai_view_new.php";
      }
    	
    }
    break;
}

?>