<?php
session_start();
include "../../inc/config.php";
session_check();
$jur_filter = "";
/*$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
$jur_filter = "and vnk.kode_jur in(".$akses_jur->kode_jur.")";
} else {
//jika tidak group tidak punya akses prodi, set in 0
$jur_filter = "and vnk.kode_jur in(0)";
}*/

echo '<option value="all">Semua</option>';
	foreach ($db->query("select * from ruang_ref
where ruang_ref.gedung_id='".$_POST['gedung_id']."'") as $isi) {
                  echo "<option value='$isi->ruang_id'>$isi->nm_ruang</option>";
	}


?>