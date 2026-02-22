<?php
      session_start();
      include "../../inc/config.php";

      if ($_POST['id_fakultas']!='all') {
        $id_fakultas = $_POST["id_fakultas"];
        $data = $db2->query("select * from view_simple_mhs inner join tb_data_pendaftaran using(nim) where id_fakultas=? group by kode_jur",array("id_fakultas" => $id_fakultas));
      } else {
        $id_fakultas = aksesProdi('view_simple_mhs.kode_jur');
         $data = $db2->query("select * from view_simple_mhs inner join tb_data_pendaftaran using(nim) where 1=1 $id_fakultas group by kode_jur");
      }

     
      echo "<option value='all'>Semua</option>";
      foreach ($data as $dt) {
        echo "<option value='$dt->kode_jur'>$dt->nama_jurusan</option>";
      }
      