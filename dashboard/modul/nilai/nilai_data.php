<?php
session_start();
include "../../inc/config.php";

$columns = array(
  'nm_matkul',
  'kls_nama',
  'fungsi_dosen_kelas(vnk.kelas_id)',
  'semua_peserta',
  'semua_peserta - jml_belum_nilai',
  'jml_belum_nilai',
  'jurusan',
  'vnk.kelas_id',
);

//if you want to exclude column for searching, put columns name in array
$datatable2->setDisableSearchColumn('vnk.kelas_id', 'semua_peserta', 'jml_belum_nilai', 'semua_peserta - jml_belum_nilai', 'jurusan');

//set numbering is true
$datatable2->setNumberingStatus(1);

//set order by column
//$datatable2->set_order_by("keu_tagihan_mahasiswa.id");

//set order by type
//$datatable2->set_order_type("desc");
$jur_filter = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_filter = "and vnk.kode_jur in(" . $akses_jur->kode_jur . ")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_filter = "and vnk.kode_jur in(0)";
}
//default semester aktif
$semester_aktif = $db->fetch_single_row("semester_ref", "aktif", 1);
$sem_filter = "and vnk.sem_id='" . $semester_aktif->id_semester . "'";
$matkul_filter = "";
$sem_filter = "";
if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter'] != 'all') {
    $jur_filter = ' and vnk.kode_jur="' . $_POST['jur_filter'] . '"';
  }

  if ($_POST['sem_filter'] != 'all') {
    $sem_filter = ' and vnk.sem_id="' . $_POST['sem_filter'] . '"';
  }

  if ($_POST['matkul_filter'] != 'all') {
    $matkul_filter = ' and vnk.id_matkul="' . $_POST['matkul_filter'] . '"';
  }

}


//set group by column
//$new_table->group_by = "group by keu_tagihan_mahasiswa.id";
$datatable2->setDebug(1);
$datatable2->setFromQuery("view_nama_kelas vnk
    INNER JOIN dosen_kelas 
        ON vnk.kelas_id = dosen_kelas.id_kelas
    WHERE vnk.kelas_id IS NOT NULL 
       $sem_filter $jur_filter $matkul_filter");
$query = $datatable2->execQuery("
    SELECT 
        vnk.nm_matkul,
        vnk.kls_nama,
        sem_matkul,
       
        -- semua peserta
        (
            SELECT COUNT(*) 
            FROM krs_detail 
            WHERE krs_detail.id_kelas = vnk.kelas_id and disetujui='1'  and nim in(select nim from mahasiswa)
        ) AS semua_peserta,

        -- peserta belum dinilai (ID 2–6 bernilai 0 semua)
(
    CASE 
        -- =====================================
        -- 1️⃣ SEMESTER LAMA (BELUM ADA KOMPONEN)
        -- =====================================
        WHEN vnk.sem_id < '20251'
        THEN
            (
                SELECT COUNT(id_krs_detail)
                FROM krs_detail
                WHERE id_kelas = vnk.kelas_id  AND nim IN (SELECT nim FROM mahasiswa)
                  AND (TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = '')
            )

        -- =====================================
        -- 2️⃣ SEMESTER BARU & MK REGULER
        -- =====================================
        WHEN 
            vnk.sem_id >= '20251'
            AND vnk.id_tipe_matkul != 'S'
            AND vnk.nama_mk NOT LIKE '%skrip%'
            AND vnk.nama_mk NOT LIKE '%ppl%'
            AND vnk.nama_mk NOT LIKE '%kuliah%'
            AND vnk.nama_mk NOT LIKE '%kkn%'
            AND vnk.nama_mk NOT LIKE '%kuker%'
            AND vnk.nama_mk NOT LIKE '%tugas%'
            AND vnk.nama_mk NOT LIKE '%kompre%'
            AND vnk.nama_mk NOT LIKE '%tesis%'
            AND vnk.nama_mk NOT LIKE '%pengabdian%'

        THEN
            (
                SELECT COUNT(*) 
                FROM krs_detail 
                WHERE id_kelas = vnk.kelas_id  AND nim IN (SELECT nim FROM mahasiswa)
                  AND disetujui = '1'
                  AND (
                        komponen_nilai IS NULL
                        OR komponen_nilai = ''
                        OR komponen_nilai = '[]'
                        OR komponen_nilai = 'null'
                        OR (
                            komponen_nilai REGEXP '\"id\":\"2\",\"nilai\":\"0\"'
                            AND komponen_nilai REGEXP '\"id\":\"3\",\"nilai\":\"0\"'
                            AND komponen_nilai REGEXP '\"id\":\"4\",\"nilai\":\"0\"'
                            AND komponen_nilai REGEXP '\"id\":\"5\",\"nilai\":\"0\"'
                            AND komponen_nilai REGEXP '\"id\":\"6\",\"nilai\":\"0\"'
                        )
                  )
            )

        -- =====================================
        -- 3️⃣ SEMESTER BARU & MK NON REGULER
        -- =====================================
        ELSE
            (
                SELECT COUNT(id_krs_detail)
                FROM krs_detail
                WHERE id_kelas = vnk.kelas_id  AND nim IN (SELECT nim FROM mahasiswa)
                  AND (TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = '')
            )
    END
) AS jml_belum_nilai,


        vnk.jurusan,
        vnk.kode_jur,
        fungsi_dosen_kelas(vnk.kelas_id) AS nama_dosen,
        sem_id,
        vnk.kelas_id

    FROM view_nama_kelas vnk
    INNER JOIN dosen_kelas 
        ON vnk.kelas_id = dosen_kelas.id_kelas
    WHERE vnk.kelas_id IS NOT NULL 
       $sem_filter $jur_filter $matkul_filter
", $columns);

//buat inisialisasi array data
$data = array();

$i = 1;

foreach ($query as $value) {

  $input = '';
  if ($_SESSION['level'] == '1') {
    $input = '<a href="nilai/add_nilai/' . en($value->kelas_id) . "/" . $value->sem_id . '" data-id="' . $value->kelas_id . '" class="btn edit_data btn-primary btn-sm"><i class="fa fa-pencil"></i> Input Nilai</a>';
  }
  //array data
  $ResultData = array();
  $ResultData[] = $datatable->number($i);
  $ResultData[] = $value->nm_matkul . ' - Semester ' . $value->sem_matkul;
  $ResultData[] = $value->kls_nama;
  if ($value->nama_dosen != '') {
    $nama_dosen = array_map('trim', explode('#', $value->nama_dosen));
    $nama_dosen = trim(implode("<br>- ", $nama_dosen));
    $ResultData[] = '- ' . $nama_dosen;
  } else {
    $ResultData[] = '';
  }

  $ResultData[] = $value->semua_peserta;
  $ResultData[] = $value->semua_peserta - $value->jml_belum_nilai;
  if ($value->jml_belum_nilai > 0) {
    $ResultData[] = '<span class="btn btn-xs btn-danger">' . $value->jml_belum_nilai . '</span>';
  } else {
    $ResultData[] = $value->jml_belum_nilai;
  }

  $ResultData[] = $value->jurusan;
  $ResultData[] = $input . ' <a target="_BLANK" href="' . base_url() . 'dashboard/modul/nilai/cetak_nilai.php?id_kelas=' . en($value->kelas_id) . '&jur=&sem" class="btn edit_data btn-success "><i class="fa fa-print"></i> Cetak</a>';

  $data[] = $ResultData;
  $dos = array();
  $i++;
}

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();