<?php
switch (uri_segment(2)) {
    case "show-nilai":
   // echo de(uri_segment(3)); die();
    $nim=de(uri_segment(3));
    $data_mhs = $db->fetch_single_row("view_simple_mhs_data","nim",$nim);
    update_akm($nim); 
    include "nilai_permahasiswa_all_semester.php";
    break;
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "nilai_permahasiswa_add.php";
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
                             include "nilai_permahasiswa_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("agama","id_agama",uri_segment(3));
    include "nilai_permahasiswa_detail.php";
    break;
    default:
/*    $q=$db->query("select * from kelas_dpna k where k.id_kelas like '70%' ");
    $i=1;
    foreach ($q as $k) {
      $qq=$db->query("select * from komponen_nilai");
      foreach ($qq as $kk) {
        //presensi mandiri terstruktur lain_lain uts uas
        if ($kk->id=='1') {
          $nilai = $k->presensi;
        }elseif ($kk->id=='2') {
          $nilai = $k->mandiri;
        }elseif ($kk->id=='3') {
          $nilai = $k->terstruktur;
        }elseif ($kk->id=='4') {
          $nilai = $k->lain_lain;
        }elseif ($kk->id=='5') {
          $nilai = $k->uts;
        }elseif ($kk->id=='6') {
          $nilai = $k->uas;
        }
        $data = array('id_kelas'    => $k->id_kelas ,
                      'id_komponen' => $kk->id,
                      'nilai'       => $nilai);
        $db->insert("kelas_penilaian",$data);
      }
      $i++;
    }
    echo "$i";
    die();*/
    if ($_SESSION['level']=='5') {
       include "nilai_permahasiswa_view_jur.php";
    }
     else{
       include "nilai_permahasiswa_view.php";
    }
    break;
}

?>