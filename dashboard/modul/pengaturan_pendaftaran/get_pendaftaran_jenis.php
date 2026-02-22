<?php
      session_start();
      include "../../inc/config.php";

      $id_jenis_pendaftaran = $_POST["id_jenis"];

      $data = $db2->query("select * from tb_data_pendaftaran_jenis where id_jenis_pendaftaran!=?",array("id_jenis_pendaftaran" => $id_jenis_pendaftaran));
      echo "<option value=''></option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id_jenis_pendaftaran'>$dt->nama_jenis_pendaftaran</option>";
      }
