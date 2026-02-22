<?php
session_start();
include "../../inc/config.php";
$data_mhs = $db->query("select * from mahasiswa");
foreach ($data_mhs as $mhs) {
	update_akm($mhs->nim);
	echo $mhs->nim."<br>";
}