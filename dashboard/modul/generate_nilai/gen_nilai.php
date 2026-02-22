<?php
session_start();
header('Access-Control-Allow-Origin: *');
include "../../inc/config.php";
session_check_json();
function sortSemestersAsc($a, $b)
{
	return $a - $b;
}

$komponen_template = [
	["id" => "1", "nama_komponen" => "Aktivitas Partisipatif", "value_komponen" => 15],
	["id" => "2", "nama_komponen" => "Hasil Proyek", "value_komponen" => 15],
	["id" => "3", "nama_komponen" => "QUIZ", "value_komponen" => 15],
	["id" => "4", "nama_komponen" => "TUGAS", "value_komponen" => 15],
	["id" => "5", "nama_komponen" => "UTS", "value_komponen" => 20],
	["id" => "6", "nama_komponen" => "UAS", "value_komponen" => 20],
];
function generate_komponen_nilai($komponen_template, $nilai)
{
	$data = [];
	foreach ($komponen_template as $k) {
		$data[] = [
			'id' => $k['id'],
			'nama_komponen' => $k['nama_komponen'],
			'persentase' => $k['value_komponen'],
			'nilai' => $nilai
		];
	}
	return json_encode($data);
}
function hitung_nilai_dari_komponen($komponen)
{
	$total = 0;
	foreach ($komponen as $k) {
		$total += floatval($k['nilai']) * ($k['persentase'] / 100);
	}
	return round($total, 2);
}

$json_response = array();
if ($_POST['total_data'] > 0) {

	$jumlah = array();
	$offset = $_POST['offset'];
	$jumlah['offset'] = $offset;

	$sukses_count = 0;
	$error_count = 0;


	$error_msg = array();
	$nim_data = array();
	$data_id_update = array();
	$data_update_akm = array();
	$data = array();
	$all_errors = array();
	$nim_mhs_periode = array();
	$array_semester = array();

	$kelas = "";
	$semester = "";
	$fakultas = "";
	$mulai_smt = "";
	$jurusan = "";
	$id_matkul = "";
	if ($_POST['fakultas'] != 'all') {
		$fakultas = getProdiFakultas('vnk.kode_jur', $_POST['fakultas']);
	}

	if ($_POST['semester'] != 'all') {
		$semester = ' and vnk.sem_id="' . $_POST['semester'] . '"';
	}

	if ($_POST['jurusan'] != 'all') {
		$jurusan = ' and vnk.kode_jur="' . $_POST['jurusan'] . '"';
	}
	if ($_POST['id_matkul'] != 'all') {
		$id_matkul = ' and vnk.id_matkul="' . $_POST['id_matkul'] . '"';
	}
	if ($_POST['kelas'] != 'all') {
		$kelas = ' and vnk.kelas_id="' . $_POST['kelas'] . '"';
	}

	//fist check kelas apakah Jika nilai dalam 1 kelas itu belum diisi sama sekali maka nilainya akan di generate ke B.

	//Tapi jika nilainya ada yg sdh di input beberapa di kelas itu, maka nilai yg belum di input akan tergenerate menjadi E.
	$db->setDebugQuery(1);
	$check_kelas = $db->query("

			select vd.nama_dosen,kls_nama, vnk.nm_matkul,



  (select count(id_krs_detail) from krs_detail where id_kelas=vnk.kelas_id) AS jumlah_peserta,
  (select count(id_krs_detail) from krs_detail where id_kelas=vnk.kelas_id and (TRIM(nilai_angka) IS NOT NULL OR TRIM(nilai_angka) <> '')) AS jml_sudah_nilai,
  (select count(id_krs_detail) from krs_detail where id_kelas=vnk.kelas_id and (TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = '')) AS jml_belum_nilai,
  vnk.jurusan,
  vnk.kelas_id
   from view_nama_kelas vnk
   inner join view_dosen_kelas_single vd
   on vnk.kelas_id=vd.id_kelas
      WHERE     
    id_tipe_matkul!='S' and kelas_id in(select id_kelas from krs_detail where sem_id='" . $_POST['semester'] . "' and disetujui='1' and nilai_angka is null) $semester $jurusan $fakultas $id_matkul $kelas
");

	$kelas_nilai_b = array();
	$kelas_nilai_e = array();

	/*dump($db->getErrorMessageQuery());
	echo $db->getErrorMessage();*/

	foreach ($check_kelas as $dt_kelas) {
		if ($dt_kelas->jml_belum_nilai == $dt_kelas->jumlah_peserta) {
			$kelas_nilai_b[$dt_kelas->kelas_id] = $dt_kelas->kelas_id;
		} else {
			$kelas_nilai_e[$dt_kelas->kelas_id] = $dt_kelas->kelas_id;
		}
	}

	$query_not_in = "and unik_ids not in(" . $_POST['random_number'] . ")";

	$datas = $db2->query("SELECT *
          FROM krs_detail   
          WHERE id_kelas IN (  
              SELECT kelas_id   
              FROM view_nama_kelas vnk
              inner join view_dosen_kelas_single vd on vnk.kelas_id=vd.id_kelas
              WHERE id_tipe_matkul!='S' $semester $jurusan $fakultas $id_matkul $kelas  
          ) and disetujui='1' and nilai_angka is null $query_not_in limit 50");

	$data_update_nilai_b = array();
	$data_update_nilai_e = array();
	$data_update_nilai_b = [];
	$data_update_nilai_e = [];
	$data_id_b = [];
	$data_id_e = [];

	foreach ($datas as $value) {

		// ===== NILAI B (75) =====
		if (in_array($value->id_kelas, $kelas_nilai_b)) {

			$komponen = json_decode(
				generate_komponen_nilai($komponen_template, 75),
				true
			);

			$nilai_akhir = hitung_nilai_dari_komponen($komponen); // hasil = 75

			$data_update_nilai_b[] = [
				'generate' => 1,
				'nilai_angka' => $nilai_akhir,
				'nilai_huruf' => 'B',
				'bobot' => '3.00',
				'komponen_nilai' => json_encode($komponen),
				'pengubah_nilai' => 'ADMIN SISTEM',
				'tgl_generate' => date('Y-m-d H:i:s'),
				'tgl_perubahan_nilai' => date('Y-m-d H:i:s')
			];

			$data_id_b[] = $value->id_krs_detail;
		}

		// ===== NILAI E (0) =====
		elseif (in_array($value->id_kelas, $kelas_nilai_e)) {

			$komponen = json_decode(
				generate_komponen_nilai($komponen_template, 0),
				true
			);

			$nilai_akhir = hitung_nilai_dari_komponen($komponen); // hasil = 0

			$data_update_nilai_e[] = [
				'generate' => 1,
				'nilai_angka' => $nilai_akhir,
				'nilai_huruf' => 'E',
				'bobot' => '0.00',
				'komponen_nilai' => json_encode($komponen),
				'pengubah_nilai' => 'ADMIN SISTEM',
				'tgl_generate' => date('Y-m-d H:i:s'),
				'tgl_perubahan_nilai' => date('Y-m-d H:i:s')
			];

			$data_id_e[] = $value->id_krs_detail;
		}

		$all_id[] = $value->id_krs_detail;
	}


	dump($value->id_krs_detail);
	exit();




	if (!empty($data_update_nilai_b)) {
		$db2->updateMulti('krs_detail', $data_update_nilai_b, 'id_krs_detail', $data_id_b);
	}
	if (!empty($data_update_nilai_e)) {
		$db2->updateMulti('krs_detail', $data_update_nilai_e, 'id_krs_detail', $data_id_e);
	}
	$implode_id = implode(",", $all_id);
	$db->query(
		"update krs_detail set unik_ids=" .
		$_POST["random_number"] .
		" where id_krs_detail in (" .
		$implode_id .
		")"
	);

	if (isset($_POST['id_data'])) {
		$jml_error_sukses = $db2->fetchCustomSingle("SELECT COUNT(*) AS jml_sukses FROM krs_detail where unik_ids=" . $_POST['random_number']);
		$sukses_count = $jml_error_sukses->jml_sukses;
		$jumlah_sukses = $sukses_count;
		$db2->query("update krs_detail set unik_ids=0 where  unik_ids=" . $_POST['random_number']);
		$msg = "<div class=\"alert alert-warning \" role=\"alert\">
		<font color=\"#3c763d\">" . $jumlah_sukses . " data Nilai berhasil Generate</font><br />";
		//echo "<br />Total: ".$i." baris data";
		$msg .= "<div class=\"collapse\" id=\"collapseExample\">";
		$i = 1;
		foreach ($all_errors as $pesan) {
			$msg .= "<div class=\"bs-callout bs-callout-danger\">" . $i . ". " . $pesan . "</div><br />";
			$i++;
		}
		$msg .= "</div>
		</div>";


		$jumlah['last_notif'] = $msg;
	}


} else {
	$msg = "<span style='color:#f00'>Tidak ada data yang bisa diproses</span><p>";
	$jumlah['last_notif'] = $msg;
}


array_push($json_response, $jumlah);

echo json_encode($json_response);
exit();