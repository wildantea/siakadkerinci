<?php
session_start();
include "../../inc/config.php";
if (isset($_GET['q'])) {
$gelar = $_GET['q'];
	if (strlen($gelar)>1) {
		$dosen = $db->query("SELECT 
        s.npsn, 
        s.kelurahan,
        s.nama AS nama_sekolah, 
        kec.nama AS kecamatan, 
        kab.nama AS kabupaten, 
        prov.nama AS provinsi
    FROM sekolah s
    JOIN sekolah_kecamatan kec ON s.kecamatan_kode = kec.kode
    JOIN sekolah_kabupaten kab ON kec.kabupaten_kode = kab.kode
    JOIN sekolah_provinsi prov ON kab.province_kode = prov.kode
WHERE s.nama LIKE '%$gelar%' or s.npsn  LIKE '%$gelar%'
LIMIT 20
");
	} else {
		$dosen = $db->query("SELECT 
        s.npsn, 
        s.kelurahan,
        s.nama AS nama_sekolah, 
        kec.nama AS kecamatan, 
        kab.nama AS kabupaten, 
        prov.nama AS provinsi
    FROM sekolah s
    JOIN sekolah_kecamatan kec ON s.kecamatan_kode = kec.kode
    JOIN sekolah_kabupaten kab ON kec.kabupaten_kode = kab.kode
    JOIN sekolah_provinsi prov ON kab.province_kode = prov.kode
LIMIT 5
");
	}
		$results['results'] = array();
		foreach ($dosen as $dos) {
			$array_push = array(
				'id' => $dos->npsn,
				'text' => $dos->npsn.' - '.$dos->nama_sekolah . ' ('.$dos->kelurahan.', '. $dos->kecamatan . ', ' . $dos->kabupaten . ', ' . $dos->provinsi.')'
			);
			$results['results'][] = $array_push;
		}
		echo json_encode($results);
	
} else {
	$dosen = $db->query("SELECT 
        s.npsn, 
        s.kelurahan,
        s.nama AS nama_sekolah, 
        kec.nama AS kecamatan, 
        kab.nama AS kabupaten, 
        prov.nama AS provinsi
    FROM sekolah s
    JOIN sekolah_kecamatan kec ON s.kecamatan_kode = kec.kode
    JOIN sekolah_kabupaten kab ON kec.kabupaten_kode = kab.kode
    JOIN sekolah_provinsi prov ON kab.province_kode = prov.kode
LIMIT 5
");
	$results['results'] = array();
	foreach ($dosen as $dos) {
		$array_push = array(
			'id' => $dos->npsn,
			'text' => $dos->npsn.' - '.$dos->nama_sekolah . ' ('.$dos->kelurahan.', '. $dos->kecamatan . ', ' . $dos->kabupaten . ', ' . $dos->provinsi.')'
		);
		$results['results'][] = $array_push;
	}
	echo json_encode($results);
}
?>