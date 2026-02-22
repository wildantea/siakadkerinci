<?php
header('Access-Control-Allow-Origin: *');
session_start();
include "../../inc/config.php";
if (isset($_GET['q'])) {
$gelar = $_GET['q'];
	if (strlen($gelar)>1) {
		$dosen = $db->query("select * from satuan_pendidikan where nm_lemb like '%$gelar%'");
	} else {
		$dosen = $db->query("select * from satuan_pendidikan limit 5");
	}
		$results['results'] = array();
		foreach ($dosen as $dos) {
			$array_push = array(
				'id' => $dos->npsn,
				'text' => $dos->nm_lemb
			);
			$results['results'][] = $array_push;
		}
		echo json_encode($results);
	
} else {
	$dosen = $db->query("select * from satuan_pendidikan limit 5");
	$results['results'] = array();
	foreach ($dosen as $dos) {
		$array_push = array(
			'id' => $dos->npsn,
			'text' => $dos->nm_lemb
		);
		$results['results'][] = $array_push;
	}
	echo json_encode($results);
}


?>