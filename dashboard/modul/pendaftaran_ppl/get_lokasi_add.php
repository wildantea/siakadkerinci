<?php
      session_start();
      include "../../inc/config.php";
      session_check();

      $mhs = $db->fetch_custom_single("select * from view_simple_mhs_data where nim=?",array('nim' => $_POST['nim']));


$id_prodi = '';
  $fakultas = '';
      $akses_prodi = get_akses_prodi();
        $akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
        if ($akses_jur) {
          $id_prodi = "and kuota_jurusan_ppl.kode_jur in(".$akses_jur->kode_jur.")";
        } else {
        //jika tidak group tidak punya akses prodi, set in 0
          $id_prodi = "and kuota_jurusan_ppl.kode_jur in(0)";
        }


       $id_periode = '';
        if($_POST['id_periode']!='all') {
          $id_periode = ' and lokasi_ppl.id_periode="'.$_POST['id_periode'].'"';
        }

     
          $fakultas = ' and jurusan.fak_kode="'.$mhs->kode_fak.'"';

      
          $id_prodi = ' and kuota_jurusan_ppl.kode_jur="'.$mhs->jur_kode.'"';


      $data = $db->query("select lokasi_ppl.id_lokasi,nama_lokasi from lokasi_ppl inner join kuota_jurusan_ppl using(id_lokasi) inner join jurusan using(kode_jur) where 1=1 $id_periode $fakultas $id_prodi group by lokasi_ppl.id_lokasi order by nama_lokasi asc");
       echo "<option value='all'>Semua </option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id_lokasi'>$dt->nama_lokasi</option>";
      }
 ?>
      