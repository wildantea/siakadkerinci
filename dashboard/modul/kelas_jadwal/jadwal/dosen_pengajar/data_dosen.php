<?php
session_start();
include "../../../../inc/config.php";
session_check_json();
if ($_SESSION['group_level']=='admin' or $_SESSION['group_level']=='tim_kecil') {
    $is_admin = 'yes';
} else {
	$is_admin = 'no';
}
//max_sks 
$sks_max = $db->fetch_single_row("jatah_ajar_dosen","id",1)->sks_max;

if (isset($_GET['q'])) {
    $gelar = $_GET['q'];
    if (strlen($gelar) > 1) {
        // New query with the subquery for fetching `sks`
        $dosen = $db->query("
            SELECT view_dosen.*, IFNULL(subquery.sks, 0) AS sks,
            IFNULL((select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='".$_GET['sem_id']."'
 and dosen_kelas.id_dosen=view_dosen.nip and view_nama_kelas.kode_jur in (select kode_jur from jurusan where id_jenjang=35) ),0) as sks_s2
            FROM view_dosen
            LEFT JOIN (
                SELECT dk.id_dosen, IFNULL(SUM(sks_tm + sks_prak + sks_prak_lap + sks_sim), 0) AS sks
                FROM dosen_kelas dk
                JOIN kelas k ON k.kelas_id = dk.id_kelas
                JOIN matkul m ON m.id_matkul = k.id_matkul
                WHERE k.sem_id = '".$_GET['sem_id']."'
                GROUP BY dk.id_dosen
            ) AS subquery ON subquery.id_dosen = view_dosen.nip
            WHERE (dosen LIKE '%$gelar%' OR nip LIKE '%$gelar%') AND aktif = 'Y'
        ");
    } else {
        // Default query for getting a limited set of dosen records
        $dosen = $db->query("
            SELECT view_dosen.*, IFNULL(subquery.sks, 0) AS sks,
           IFNULL((select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='".$_GET['sem_id']."'
 and dosen_kelas.id_dosen=view_dosen.nip and view_nama_kelas.kode_jur in (select kode_jur from jurusan where id_jenjang=35) ),0) as sks_s2
            FROM view_dosen
            LEFT JOIN (
                SELECT dk.id_dosen, IFNULL(SUM(sks_tm + sks_prak + sks_prak_lap + sks_sim), 0) AS sks
                FROM dosen_kelas dk
                JOIN kelas k ON k.kelas_id = dk.id_kelas
                JOIN matkul m ON m.id_matkul = k.id_matkul
                WHERE k.sem_id = '".$_GET['sem_id']."'
                GROUP BY dk.id_dosen
            ) AS subquery ON subquery.id_dosen = view_dosen.nip
            WHERE aktif = 'Y'
            LIMIT 5
        ");
    }

    // Prepare results for response
    $results['results'] = array();
    foreach ($dosen as $dos) {
        $array_push = array(
            'id' => $dos->nip,
            'text' => $dos->nip.' - '.$dos->dosen.' ('.$dos->sks.' SKS)',
            'sks' => $dos->sks,
            'sks_max' => $sks_max,
            'min' => $is_admin
        );
        if ($_SESSION['group_level']=='admin_jurusan') {
            $array_push['sks'] = $dos->sks_s2 - $dos->sks;
        }
        $results['results'][] = $array_push;
    }

    // Output the JSON response
    echo json_encode($results);
} else {
    // Default case when 'q' is not set, fetch all active dosen with 'sks' data
    $dosen = $db->query("
        SELECT view_dosen.*, IFNULL(subquery.sks, 0) AS sks,
        IFNULL((select sum(view_nama_kelas.sks) from dosen_kelas inner join view_nama_kelas on dosen_kelas.id_kelas=view_nama_kelas.kelas_id where view_nama_kelas.sem_id='".$_GET['sem_id']."'
 and dosen_kelas.id_dosen=view_dosen.nip and view_nama_kelas.kode_jur in (select kode_jur from jurusan where id_jenjang=35) ),0) as sks_s2
        FROM view_dosen
        LEFT JOIN (
            SELECT dk.id_dosen, IFNULL(SUM(sks_tm + sks_prak + sks_prak_lap + sks_sim), 0) AS sks
            FROM dosen_kelas dk
            JOIN kelas k ON k.kelas_id = dk.id_kelas
            JOIN matkul m ON m.id_matkul = k.id_matkul
            WHERE k.sem_id = '".$_GET['sem_id']."'
            GROUP BY dk.id_dosen
        ) AS subquery ON subquery.id_dosen = view_dosen.nip
        WHERE aktif = 'Y'
        LIMIT 5
    ");

    // Prepare results for response
    $results['results'] = array();
    foreach ($dosen as $dos) {

        $array_push = array(
            'id' => $dos->nip,
            'text' => $dos->nip.' - '.$dos->dosen.' ('.$dos->sks.' SKS)',
            'sks' => $dos->sks,
            'sks_max' => $sks_max,
            'min' => $is_admin
        );
        if ($_SESSION['group_level']=='admin_jurusan') {
            $array_push['sks'] = $dos->sks_s2 - $dos->sks;
        }
        $results['results'][] = $array_push;
    }

    // Output the JSON response
    echo json_encode($results);
}
?>