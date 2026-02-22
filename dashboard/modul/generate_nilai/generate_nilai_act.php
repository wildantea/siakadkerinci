<?php
session_start();
include "../../inc/config.php";
session_check_json();

/*
|--------------------------------------------------------------------------
| TEMPLATE KOMPONEN NILAI
|--------------------------------------------------------------------------
*/
$komponen_template = [
    ["id" => "1", "nama_komponen" => "Aktivitas Partisipatif", "value_komponen" => 15],
    ["id" => "2", "nama_komponen" => "Hasil Proyek", "value_komponen" => 15],
    ["id" => "3", "nama_komponen" => "QUIZ", "value_komponen" => 15],
    ["id" => "4", "nama_komponen" => "TUGAS", "value_komponen" => 15],
    ["id" => "5", "nama_komponen" => "UTS", "value_komponen" => 20],
    ["id" => "6", "nama_komponen" => "UAS", "value_komponen" => 20],
];

/*
|--------------------------------------------------------------------------
| HELPER: BUILD KOMPONEN NILAI
|--------------------------------------------------------------------------
*/
function build_komponen_nilai($template, $nilai)
{
    $out = [];
    foreach ($template as $k) {
        $out[] = [
            'id' => $k['id'],
            'nama_komponen' => $k['nama_komponen'],
            'persentase' => $k['value_komponen'],
            'nilai' => $nilai
        ];
    }
    return json_encode($out);
}

/*
|--------------------------------------------------------------------------
| HELPER: AMBIL SKALA NILAI B
|--------------------------------------------------------------------------
*/
function get_skala_nilai_B($kode_jurusan, $angkatan)
{
    global $db;

    $where_angkatan = ($angkatan >= 20202)
        ? "AND berlaku_angkatan = '$angkatan'"
        : "AND (berlaku_angkatan IS NULL OR berlaku_angkatan = '')";

    return $db->fetch_custom_single("
        SELECT *
        FROM skala_nilai
        WHERE kode_jurusan = ?
          AND nilai_huruf = 'B'
          $where_angkatan
        LIMIT 1
    ", ['kode_jurusan' => $kode_jurusan]);
}

/*
|--------------------------------------------------------------------------
| PROSES UTAMA
|--------------------------------------------------------------------------
*/
$ids = array_map('intval', explode(',', $_POST['id']));
$implode_ids = implode(',', $ids);

$data_mhs = $db->query("
    SELECT 
        kd.id_krs_detail,
        m.nim,
        m.jur_kode,
        m.mulai_smt
    FROM krs_detail kd
    INNER JOIN mahasiswa m ON m.nim = kd.nim
    WHERE kd.id_krs_detail IN ($implode_ids)
");

$data_update = [];
$id_update = [];
$nim_update = [];

foreach ($data_mhs as $row) {

    // Ambil skala nilai huruf B
    $skala = get_skala_nilai_B($row->jur_kode, $row->mulai_smt);
    if (!$skala) {
        continue;
    }

    // Nilai angka dari skala (tengah range B)
    $nilai_angka = round(
        ($skala->bobot_nilai_min + $skala->bobot_nilai_maks) / 2,
        2
    );

    // Semua komponen = nilai angka B
    $komponen_nilai = build_komponen_nilai($komponen_template, $nilai_angka);

    $data_update[] = [
        'nilai_angka' => $nilai_angka,
        'nilai_huruf' => 'B',
        'bobot' => $skala->nilai_indeks,
        'komponen_nilai' => $komponen_nilai,
        'generate' => 1,
        'pengubah_nilai' => 'ADMIN SISTEM',
        'tgl_generate' => date('Y-m-d H:i:s'),
        'tgl_perubahan_nilai' => date('Y-m-d H:i:s')
    ];

    $id_update[] = $row->id_krs_detail;
    $nim_update[$row->nim] = $row->nim;
}

/*
|--------------------------------------------------------------------------
| UPDATE KRS_DETAIL
|--------------------------------------------------------------------------
*/
if (!empty($data_update)) {
    $db->updateMulti('krs_detail', $data_update, 'id_krs_detail', $id_update);
}

/*
|--------------------------------------------------------------------------
| UPDATE AKM
|--------------------------------------------------------------------------
*/
foreach ($nim_update as $nim) {
    update_akm($nim);
}

action_response($db->getErrorMessage());
