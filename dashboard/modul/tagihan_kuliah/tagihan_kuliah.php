<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "tagihan_kuliah_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("keu_tagihan_mahasiswa","id",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "tagihan_kuliah_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
 
    break;
    case "detail":
    $data_edit = $db->fetch_single_row("keu_tagihan_mahasiswa","id",uri_segment(3));
    include "tagihan_kuliah_detail.php";
    break;
    case "coba":
    $nim = $_SESSION['username'];
    // $nim = '1210705092';
    //get status keranjang va if exist
    $check_va = $db->fetch_custom_single("select *,keu_keranjang_va.nominal as total_nominal from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim."' and is_lunas='N' and is_active='Y' and exp_date > '".date('Y-m-d H:i:s')."'");
    // echo "select * from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim."' and is_lunas='N' and is_active='Y' and exp_date > '".date('Y-m-d H:i:s')."'";
    $status_va = 0;
   
    if ($check_va) {
       if ($check_va->id_bank=='01') {
         $no_briva = substr($check_va->no_va, 5, strlen($check_va->no_va));
        // echo "$no_briva";
         $status_briva = getStatusBriva($no_briva);
         $status_bayar = $status_briva['data']['statusBayar']; 
         if ($status_bayar=='N' && $check_va->is_lunas=='N') {
             include "tagihan_kuliah_detail.php";
         } else{

              include "tagihan_kuliah_view.php";
         }
      } elseif($check_va->id_bank=='03') {
        include "tagihan_kuliah_detail.php";
      } elseif($check_va->id_bank=='04') {
        include "tagihan_kuliah_detail_coba.php";
      }   
     
    } else {
      //$check_va = $db->query("delete from keu_keranjang_va where nim_mhs='".$_SESSION['username']."' and exp_date < '".date('Y-m-d H:i:s')."'");
      include "tagihan_kuliah_view_coba.php";
    }
    break;
    default:
    $nim = $_SESSION['username'];
    // $nim = '1210705092';
    //get status keranjang va if exist
    $check_va = $db->fetch_custom_single("select *,keu_keranjang_va.nominal as total_nominal from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim."' and is_lunas='N' and is_active='Y' and exp_date > '".date('Y-m-d H:i:s')."'");
    // echo "select * from keu_keranjang_va inner join keu_keranjang_va_detail using(no_kwitansi) where nim_mhs='".$nim."' and is_lunas='N' and is_active='Y' and exp_date > '".date('Y-m-d H:i:s')."'";
    $status_va = 0;
   
    if ($check_va) {
       if ($check_va->id_bank=='01') {
         $no_briva = substr($check_va->no_va, 5, strlen($check_va->no_va));
        // echo "$no_briva";
         $status_briva = getStatusBriva($no_briva);
         $status_bayar = $status_briva['data']['statusBayar']; 
         if ($status_bayar=='N' && $check_va->is_lunas=='N') {
             include "tagihan_kuliah_detail.php";
         } else{

              include "tagihan_kuliah_view.php";
         }
      } elseif($check_va->id_bank=='03') {
        include "tagihan_kuliah_detail.php";
      } elseif($check_va->id_bank=='04') {
        include "tagihan_kuliah_detail_bni.php";
      }    
     
    } else {
      //$check_va = $db->query("delete from keu_keranjang_va where nim_mhs='".$_SESSION['username']."' and exp_date < '".date('Y-m-d H:i:s')."'");
      include "tagihan_kuliah_view.php";
    }
    break;
}

?>