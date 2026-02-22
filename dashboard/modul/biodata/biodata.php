<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "biodata_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
     case "view":
     //var_dump(checkBiodataMahasiswaDataDiri($_SESSION['username']));
      checkBiodataAll($_SESSION['username']);
   // if(checkBiodataMahasiswaDataDiri($_SESSION['username']));
        $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
     $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
       include "view.php";

    break;

  case "edits":

     $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
     $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edits") {
                          if ($role_act["up_act"]=="Y") {
                             if ($data_edit->is_submit_biodata=='N') {
                              include "biodata_edit_news.php";
                             }
                          } else {
                            echo "permission denied";
                          }
                       }

      }
  break;
  case "edit":
     $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
     $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             if (checkBiodataAllStatus($_SESSION['username'])) {
                              include "biodata_edit_news.php";
                             }
                             
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",uri_segment(3));
    include "biodata_detail.php";
    break;
    default:
          checkBiodataAll($_SESSION['username']);
   // if(checkBiodataMahasiswaDataDiri($_SESSION['username']));
        $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
     $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
       include "view.php";
       
    //   $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
    //  $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
    // include "biodata_detail.php";
    /*      $nim = $db->fetch_single_row('mahasiswa','nim',$_SESSION['username']);
     $data_edit = $db->fetch_single_row("mahasiswa","mhs_id",$nim->mhs_id);
     include "biodata_edit.php";*/
      //   foreach ($db->fetch_all("sys_menu") as $isi) {
      //                 if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
      //                     if ($role_act["up_act"]=="Y") {
      //                        include "biodata_edit.php";
      //                     } else {
      //                       echo "permission denied";
      //                     }
      //                  }

      // }
    break;
}

?>