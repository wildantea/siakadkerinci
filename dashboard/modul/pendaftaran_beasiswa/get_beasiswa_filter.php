<?php
      session_start();
      include "../../inc/config.php";
      session_check();
      $jenisbeasiswa = $_POST["jenisbeasiswa"];

      if($_SESSION['level'] == '1') {
          $data = $db->query("select * from beasiswa where jns_beasiswa='$jenisbeasiswa'");
          $sem = $db->query("select * from semester_ref s join jenis_semester j 
                              on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
          echo "<option value='all'>Semua </option>";
          foreach ($data as $dt) {
            foreach ($sem as $isi2) {
              if ($dt->priode_beasiswa == $isi2->id_semester) {
               echo "<option value='$dt->id_beasiswa'>$dt->nama_beasiswa ($isi2->jns_semester $isi2->tahun/".($isi2->tahun+1).")</option>";
              } 
            }        
          }
      } elseif($_SESSION['level'] == '5') {
        # code...
      } elseif($_SESSION['level'] == '6') {
        # code...
      } else{
          $data = $db->query("select * from beasiswa where jns_beasiswa='$jenisbeasiswa' AND batas_awal <= NOW() AND batas_akhir >= NOW()");
          $sem = $db->query("select * from semester_ref s join jenis_semester j 
                              on s.id_jns_semester=j.id_jns_semester order by s.id_semester desc");
          echo "<option value='all'>Semua </option>";
          foreach ($data as $dt) {
            foreach ($sem as $isi2) {
              if ($dt->priode_beasiswa == $isi2->id_semester) {
               echo "<option value='$dt->id_beasiswa'>$dt->nama_beasiswa ($isi2->jns_semester $isi2->tahun/".($isi2->tahun+1).")</option>";
              } 
            }        
          }
      }
 ?>
      