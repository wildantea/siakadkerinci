<?php
switch (uri_segment(2)) {
	case "konversi_matkul":
        //  $mhs = $db->fetch_single_row("mhs_pindah","id",uri_segment(3)); 
          $q = $db->query("select p.*,j.nama_jur from mhs_pindah p join jurusan j on p.jurusan_baru=j.kode_jur where 
            p.id='".uri_segment(3)."' ");
          foreach ($q as $mhs) {
              if ($mhs->kampus_lama=='') {
                $mhs->kampus_lama = "Institut Agama Islam Negeri Kerinci";   
              }
              if ($mhs->kampus_baru=='') {
                 $mhs->kampus_baru="Institut Agama Islam Negeri Kerinci";   
              }
               include "mahasiswa_pindah_edit_material.php"; 
          }
         
		
        
	break;
    case "detail":
    $data_edit = $db->fetch_single_row("mhs_pindah","id",uri_segment(3));
    include "mahasiswa_pindah_detail.php";
    break;
    default:
    include "mahasiswa_pindah_view.php";  
    break;
}

?>