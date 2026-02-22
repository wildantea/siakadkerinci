<?php
session_start();
include "../../inc/config.php";
session_check();
echo '<option value="all">Semua</option>';
if ($_POST['gedung_id']!='all') {
	foreach ($db->query("select * from ruang_ref_prodi inner join ruang_ref on ruang_ref_prodi.ruang_id=ruang_ref.ruang_id
where ruang_ref.gedung_id='".$_POST['gedung_id']."'") as $isi) {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jurusan</option>";
	}
} else {
	foreach ($db->query("select * from ruang_ref_prodi group by kode_jur") as $isi) {
                  echo "<option value='$isi->kode_jur'>$isi->nama_jurusan</option>";
}
}


?>