<?php
      session_start();
      include "../../inc/config.php";

      $kode_jur = $_POST["kode_jur"];

      $data = $db->query("select kurikulum.kur_id,kurikulum.nama_kurikulum,view_semester.tahun_akademik from kurikulum
inner join view_semester on kurikulum.sem_id=view_semester.id_semester where kurikulum.kode_jur=?
        order by kurikulum.sem_id desc",array('kode_jur' => $kode_jur));
       echo "<option value=''>Pilih Kurikulum</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->kur_id'>$dt->nama_kurikulum $dt->tahun_akademik</option>";
      }
      