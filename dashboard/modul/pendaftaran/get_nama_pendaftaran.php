<?php
      session_start();
      include "../../inc/config.php";

      $program_studi = $_POST["kode_jur"];

      $data = $db2->query("select * from view_jenis_pendaftaran where kode_jur=?",array("kode_jur" => $program_studi));
      if ($data->rowCount()>0) {
        echo "<option value=''>Pilih Nama Pendaftaran</option>";
        foreach ($data as $dt) {
          echo "<option value='$dt->id_jenis_pendaftaran_setting'>$dt->nama_jenis_pendaftaran</option>";
        }
      } else {
          echo "<option value=''>Belum Ada Pengaturan Pendaftaran</option>";
      }

      