<?php
      session_start();
      include "../../inc/config.php";

      $fakultas = $_POST["fakultas"];

      $data = $db->query("select * from jurusan where fak_kode=?",array("fak_kode" => $fakultas));
       echo "<option value='all'>Semua </option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->kode_jur'>$dt->nama_jur</option>";
      }
 ?>
      