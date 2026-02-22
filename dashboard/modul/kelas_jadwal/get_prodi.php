<?php
      session_start();
      include "../../inc/config.php";

      $fakultas = $_POST["id_fakultas"];

      $data = $db2->query("select jurusan.kode_jur,jurusan.nama_jur,jenjang_pendidikan.jenjang from jurusan inner join jenjang_pendidikan
on jurusan.id_jenjang=jenjang_pendidikan.id_jenjang
 where fak_kode=?",array("fak_kode" => $fakultas));
       echo "<option value='all'>Semua </option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->kode_jur'>$dt->jenjang $dt->nama_jur</option>";
      }
      
