<?php
session_start();
include "../../inc/config.php";

$id_fakultas = $_POST["id_fakultas"];

$data = $db2->query("select view_prodi_jenjang.kode_jur,nama_jurusan from view_prodi_jenjang where kode_jur in (select kode_jurusan from tb_data_kelulusan) and id_fakultas=? group by kode_jur",array("id_fakultas" => $id_fakultas));
echo "<option value='all'>Semua</option>";
foreach ($data as $dt) {
echo "<option value='$dt->kode_jur'>".$dt->nama_jurusan."</option>";
}
