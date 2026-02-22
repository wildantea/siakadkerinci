<?php
      session_start();
      include "../../inc/config.php";

      $program_studi = $_POST["program_studi"];

      $data = $db->query("select * from kurikulum where kode_jur=? order by sem_id desc",array("kode_jur" => $program_studi));
       echo "<option value=''>Pilih </option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->kur_id'>$dt->nama_kurikulum</option>";
      }
      