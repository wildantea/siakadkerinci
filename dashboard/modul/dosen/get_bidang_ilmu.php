<?php
session_start();
include "../../inc/config.php";
session_check();
echo '<option value="">Semua</option>';
foreach ($db->query("select d.kode,d.nama_rumpun from data_rumpun_dosen d
where id_induk=? ",array('id_induk' => $_POST['sub_rumpun'])) as $isi) {
echo "<option value='$isi->kode'>$isi->nama_rumpun</option>";
}

?>
