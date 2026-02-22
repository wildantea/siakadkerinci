<?php
session_start();
include "../../../inc/config.php";
session_check_json();
if (isset($_GET['q'])) {
$gelar = $_GET['q'];
	if (strlen($gelar)>1) {
		$dosen = $db2->query("select * from view_nama_gelar_dosen where nama_gelar like '%$gelar%'");
	} else {
		$dosen = $db2->query("select * from view_nama_gelar_dosen limit 5");
	}
		$results['results'] = array();
		foreach ($dosen as $dos) {
			$array_push = array(
				'id' => $dos->nip,
				'text' => $dos->nama_gelar
			);
			$results['results'][] = $array_push;
		}
		echo json_encode($results);
	
} else {
	$dosen = $db2->query("select * from view_nama_gelar_dosen limit 5");
	$results['results'] = array();
	foreach ($dosen as $dos) {
		$array_push = array(
			'id' => $dos->nip,
			'text' => $dos->nama_gelar
		);
		$results['results'][] = $array_push;
	}
	echo json_encode($results);
}


?>