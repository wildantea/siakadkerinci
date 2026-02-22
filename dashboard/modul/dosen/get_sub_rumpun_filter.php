<?php
session_start();
include "../../inc/config.php";
session_check();
echo '<option value="all">Semua</option>';
foreach ($db->query("
select kode,nama_rumpun from data_rumpun_dosen where id_induk=?
and kode in(select dd.id_induk from data_rumpun_dosen d
inner join data_rumpun_dosen dd on d.kode=dd.id_induk where dd.id_level='3' and
dd.kode in(select kode_rumpun from dosen  where kode_rumpun is not null group by kode_rumpun ) )",array('id_induk' => $_POST['rumpun'])) as $isi) {
echo "<option value='$isi->kode'>$isi->nama_rumpun</option>";
}

?>
