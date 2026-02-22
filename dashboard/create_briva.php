<?php
include "inc/config.php";

$data = array(
    'no_briva' => $_POST['no_pendaftaran'],
    'nama' => $_POST['nama'],
    'nominal' => $_POST['nominal'],
    'ket' => $_POST['keterangan'],
    'exp_date' => $_POST['exp_date']
);
deleteBriva($_POST['no_pendaftaran']);
$create = createBriva($data);

echo json_encode($create);