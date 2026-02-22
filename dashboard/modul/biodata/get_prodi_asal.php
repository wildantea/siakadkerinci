<?php
      session_start();
      include "../../inc/config.php";

      $id_sp = $_POST["id_sp"];

      $data = $db->query("SELECT concat(jenjang,' ',nm_lemb) as nama_jurusan,kode_prodi,id_sms from jenjang_pendidikan INNER join sms on id_jenjang=id_jenj_didik  where id_sp=?",array("id_sp" => $id_sp));
       echo "<option value=''>Pilih Prodi Asal</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id_sms'>$dt->nama_jurusan</option>";
      }
      