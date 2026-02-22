<?php
session_start();
include "../../../inc/config.php";
session_check_json();

dump($_POST);

$current_date = date('Y-m-d');

if ($_POST['tanggal']==$current_date) {
	$sesi = $db->query("SELECT *
FROM sesi_waktu
WHERE jam_mulai >= (
    SELECT jam_mulai
    FROM sesi_waktu
    WHERE CURTIME() BETWEEN jam_mulai AND jam_selesai
    LIMIT 1
);");
} else {
	$sesi = $db->query("SELECT *
FROM sesi_waktu");
}

echo $db->getErrorMessage();

foreach ($sesi as $jam) {
	echo "<option value='".$jam->jam_mulai."'>$jam->jam_mulai</option>";
}