<?php
      session_start();
      include "../../inc/config.php";

      $prodi = $_POST["prodi"];

      $data = $db->query("select ruang_id,nm_ruang from ruang_ref
inner join ruang_ref_prodi rr using(ruang_id)
where rr.kode_jur=? and jenis_ruang=?",array("prodi" => $prodi,'jenis_ruang' => '2'));
       echo "<option value=''>Pilih Ruangan</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->ruang_id'>$dt->nm_ruang</option>";
      }
      