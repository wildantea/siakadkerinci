<?php
      session_start();
      include "../../inc/config.php";

      $semester = $_POST["semester"];
      $prodi = $_POST['prodi'];
      $id_pendaftaran = $_POST['id_pendaftaran'];

      $id_pendaftaran = $db->fetch_single_row("tb_jenis_pendaftaran","kode",$id_pendaftaran);

      $data = $db->query("select * from tb_data_jadwal_pendaftaran where semester=? and kode_jur=? and id_pendaftaran=? order by periode_bulan desc",array("semester" => $semester,'kode_jur' => $prodi,'id_pendaftaran' => $id_pendaftaran->id));
       echo "<option value='all'>Semua</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->id'>".bulan_tahun($dt->periode_bulan)."</option>";
      }