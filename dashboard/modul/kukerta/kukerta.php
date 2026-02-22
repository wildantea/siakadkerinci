<?php
switch (uri_segment(2)) {
    case "tambah":
          foreach ($db->fetch_all("sys_menu") as $isi) {
               if (uri_segment(1)==$isi->url&&uri_segment(2)=="tambah") {
                          if ($role_act["insert_act"]=="Y") {
                             include "kukerta_add.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }
    break;
  case "input_nilai":  
  // echo de(uri_segment(3)); 
  // die();
   $data_edit = $db->fetch_single_row("v_dpl","id_lokasi",de(uri_segment(3))); 
   // echo "select pp.priode, k.*,m.nama,m.jk,m.mulai_smt,m.mulai_smt as angkatan,m.jur_kode,j.nama_jur from kkn k join mahasiswa m on m.nim=k.nim join jurusan j on j.kode_jur=m.jur_kode join priode_kkn pp on pp.id_priode=k.id_priode where  k.id_lokasi='$data_edit->id_lokasi' and k.id_priode='$data_edit->id_priode' order by m.nama asc";
   $peserta = $db->query("select pp.priode, k.*,m.nama,m.jk,m.mulai_smt,m.mulai_smt as angkatan,m.jur_kode,j.nama_jur from kkn k join mahasiswa m on m.nim=k.nim join jurusan j on j.kode_jur=m.jur_kode join priode_kkn pp on pp.id_priode=k.id_priode where  k.id_lokasi='$data_edit->id_lokasi' and k.id_priode='$data_edit->id_priode' order by m.nama asc ");
   foreach ($peserta as $p) { 
     $qm = $db->fetch_custom_single("select id_matkul from v_matkul_kukerta where kode_jur=?  order by kur_id desc limit 1",array($p->jur_kode));
     $kode_mk = $qm->id_matkul;   
//       echo " select d.id_semester,d.nim,d.kode_mk, id_krs_detail,m.nama_mk,d.kode_mk from krs_detail d join matkul m on m.id_matkul=d.kode_mk  where d.nim='$p->nim' and d.kode_mk in (select id_matkul from v_matkul_kukerta) 
// order by id_semester desc limit 1;<br>";
     $qkk = $db->query("select d.id_semester,d.nim,d.kode_mk, id_krs_detail,m.nama_mk,d.kode_mk from krs_detail d join matkul m on m.id_matkul=d.kode_mk  where d.nim='$p->nim' and d.kode_mk in (select id_matkul from v_matkul_kukerta) 
order by id_semester desc limit 1");
     if ($qkk->rowCount()==0) {  
      // $qc = $db->query("select id_krs_detail from krs_detail where id_semester=? and kode_mk=? and id_kelas=? and nim=?",array($data_edit->id_priode,$kode_mk,'1',$p->nim));
      // if ($qc->rowCount()==0) {
         $data = array('kode_mk' => $kode_mk,  
                       'id_kelas' => '1',
                       'id_semester' => $data_edit->priode,
                       'sks' => '4',
                       'nim' => $p->nim,
                       'disetujui' => '1');
         $db->insert("krs_detail",$data);  
       // echo "<pre>"; 
        //print_r($data); 
       // }   
       // else{   
       //  foreach ($qkk as $kk) { 
       //    $data = array('kode_mk' => $kk->kode_mk, 
       //                 'id_kelas' => '1',
       //                 'id_semester' => $data_edit->priode,
       //                 'sks' => '4',
       //                 'nim' => $p->nim,
       //                 'disetujui' => '1');
       //    $db->update("krs_detail",$data,"id_krs_detail",$kk->id_krs_detail);  
       //  }
       // }  
     }else{ 

      foreach ($qkk as $kv) { 
        //$kode_mk 
        if ($kv->id_krs_detail!='') {
          $kode_mk = $kv->kode_mk; 
        }
        $data = array('kode_mk' => $kode_mk, 
                       'id_kelas' => '1', 
                       'id_semester' => $data_edit->priode,
                       'sks' => '4',
                       'nim' => $p->nim,
                       'disetujui' => '1'); 
         $db->update("krs_detail",$data,"id_krs_detail",$kv->id_krs_detail);  
      }
       
     }
     
    //   echo "</pre>";
   }

     include 'input_nilai.php';

     
    break;
  case "edit":
    $data_edit = $db->fetch_single_row("v_dpl","id_lokasi",uri_segment(3));
        foreach ($db->fetch_all("sys_menu") as $isi) {
                      if (uri_segment(1)==$isi->url&&uri_segment(2)=="edit") {
                          if ($role_act["up_act"]=="Y") {
                             include "kukerta_edit.php";
                          } else {
                            echo "permission denied";
                          }
                       }

      }

    break;
    case "detail":
    $data_edit = $db->fetch_single_row("v_dpl","",uri_segment(3));
    include "kukerta_detail.php";
    break;
    default:
    include "kukerta_view.php";
    break;
}

?>