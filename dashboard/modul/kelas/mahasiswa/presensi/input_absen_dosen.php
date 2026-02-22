<?php
session_start();
include "../../../inc/config.php";

dump($_POST);

$row = $db2->fetchCustomSingle("SELECT kehadiran_dosen FROM tb_data_kelas_pertemuan WHERE id_pertemuan=?",array('id_pertemuan' => $_POST['pert']));


if ($row->kehadiran_dosen!="") {
    $kehadiran = json_decode($row->kehadiran_dosen,true);

    $found = false;
    $new_tanggal_absen = date('Y-m-d H:i:s');
    // 2. Check if nip already exists, if so update
    foreach ($kehadiran as &$dosen) {
        if ($dosen['nip'] === $_POST['nip']) {
            $dosen['tanggal_absen'] = $new_tanggal_absen;
            $found = true;
            break;
        }
    }

    // 3. If not found, append new entry
    if (!$found) {
        $kehadiran[] = [
            'nip' => $_POST['nip'],
            'tanggal_absen' => $new_tanggal_absen
        ];
    }

    dump($kehadiran);

    // 4. Update the database
    $updated_json = json_encode($kehadiran, JSON_UNESCAPED_UNICODE);

    $update = array('kehadiran_dosen' => $updated_json);

    $db2->update('tb_data_kelas_pertemuan',$update,'id_pertemuan',$_POST['pert']);

    echo "Success!";
} else {
		$array_absen[] = array(
		'nip' => $_POST['nip'],
		'tanggal_absen' => date('Y-m-d H:i:s')
		);


		$json = json_encode($array_absen, JSON_UNESCAPED_UNICODE);

		$data_update = array(
		'kehadiran_dosen' => $json
		);

		$db2->update('tb_data_kelas_pertemuan',$data_update,'id_pertemuan',$_POST['pert']);
}



//get per