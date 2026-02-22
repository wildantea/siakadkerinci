<?php
session_start();
include "../../inc/config.php";

if ($_POST['id_fakultas']!='all') {
	$id_fakultas = "and kode_fak='".$_POST["id_fakultas"]."'";
} else {
	$id_fakultas = aksesProdi('view_prodi_jenjang.kode_jur');
}

$data = $db->query("select view_prodi_jenjang.kode_jur,jurusan from view_prodi_jenjang where kode_jur is not null $id_fakultas");
	echo "<option value='all'>Semua</option>";
foreach ($data as $dt) {
echo "<option value='$dt->kode_jur'>".$dt->jurusan."</option>";
}
