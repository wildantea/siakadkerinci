<?php
session_start();
include "../../inc/config.php";

$columns = array(
  'nama_dosen',
  'nm_matkul',
  'kls_nama',
  'jml_peserta',
  'jml_sudah_nilai',
  'jml_belum_nilai',
  'kelas_id'
);


//if you want to exclude column for searching, put columns name in array
$datatable2->setDisableSearchColumn(
  'jml_peserta',
  'jml_sudah_nilai',
  'jml_belum_nilai'
);

//set numbering is true
$datatable2->setNumberingStatus(1);

//set group by column
//$new_table->group_by = "group by kelas.kelas_id";
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

$status_nilai = "";

if (isset($_POST['jur_filter'])) {

  if ($_POST['jur_filter'] != 'all') {
    $jur_filter = ' and vnk.kode_jur="' . $_POST['jur_filter'] . '"';
  }

  if ($_POST['sem_filter'] != 'all') {
    $sem_filter = ' and vnk.sem_id="' . $_POST['sem_filter'] . '"';
  }

  if ($_POST['status_nilai'] == 1) {
    // ✅ SUDAH DINILAI
    $status_nilai = "
      AND (
        CASE
          WHEN vnk.sem_id <= '20251'
          THEN
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id 
               AND TRIM(nilai_angka) IS NULL) = 0

          WHEN vnk.sem_id > '20251'
           AND vnk.id_tipe_matkul != 'S'
           AND LOWER(vnk.nama_mk) NOT LIKE '%skrip%'
           AND LOWER(vnk.nama_mk) NOT LIKE '%tesis%'
           AND LOWER(vnk.nama_mk) NOT LIKE '%kkn%'
          THEN
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id
               AND (
                 komponen_nilai IS NULL
                 OR komponen_nilai = ''
                 OR komponen_nilai = '[]'
               )) = 0

          ELSE
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id 
               AND TRIM(nilai_angka) IS NULL) = 0
        END
      )
    ";
  } elseif ($_POST['status_nilai'] == 2) {
    // ❌ BELUM DINILAI
    $status_nilai = "
      AND (
        CASE
          WHEN vnk.sem_id <= '20251'
          THEN
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id 
               AND TRIM(nilai_angka) IS NULL) > 0

          WHEN vnk.sem_id > '20251'
           AND vnk.id_tipe_matkul != 'S'
           AND LOWER(vnk.nama_mk) NOT LIKE '%skrip%'
           AND LOWER(vnk.nama_mk) NOT LIKE '%tesis%'
           AND LOWER(vnk.nama_mk) NOT LIKE '%kkn%'
          THEN
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id
               AND (
                 komponen_nilai IS NULL
                 OR komponen_nilai = ''
                 OR komponen_nilai = '[]'
               )) > 0

          ELSE
            (SELECT COUNT(*) FROM krs_detail 
             WHERE id_kelas=vnk.kelas_id 
               AND TRIM(nilai_angka) IS NULL) > 0
        END
      )
    ";
  }
}

//    fungsi_belum_dinilai(vnk.kelas_id) as belum,
// fungsi_sudah_dinliai(vnk.kelas_id) as sudah,
//fungsi_get_jml_krs(vnk.kelas_id) as peserta,
$datatable2->setDebug(1);
$datatable2->setFromQuery("view_nama_kelas vnk
INNER JOIN view_dosen_kelas_single vd
  ON vnk.kelas_id = vd.id_kelas
WHERE vnk.kelas_id IS NOT NULL
  AND kelas_id IN (
    SELECT id_kelas FROM krs_detail WHERE sem_id = '" . $_POST['sem_filter'] . "'
  )
  $sem_filter $jur_filter $status_nilai");

$query = $datatable2->execQuery("SELECT 
  vd.nama_dosen,
  vnk.kls_nama,
  vnk.nm_matkul,

  -- jumlah peserta
  (
    SELECT COUNT(id_krs_detail)
    FROM krs_detail
    WHERE id_kelas = vnk.kelas_id
      AND nim IN (SELECT nim FROM mahasiswa)
  ) AS jml_peserta,

  -- =============================
  -- JUMLAH SUDAH DINILAI
  -- =============================
  (
    CASE
      WHEN vnk.sem_id < '20251'
      THEN
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id
           AND TRIM(nilai_angka) IS NOT NULL
           AND TRIM(nilai_angka) <> '')

      WHEN vnk.sem_id >= '20251'
       AND vnk.id_tipe_matkul != 'S'
       AND LOWER(vnk.nama_mk) NOT LIKE '%skrip%'
       AND LOWER(vnk.nama_mk) NOT LIKE '%tesis%'
       AND LOWER(vnk.nama_mk) NOT LIKE '%kkn%'
      THEN
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id
           AND nim IN (SELECT nim FROM mahasiswa)
           AND komponen_nilai IS NOT NULL
           AND komponen_nilai <> ''
           AND komponen_nilai <> '[]')

      ELSE
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id
           AND TRIM(nilai_angka) IS NOT NULL
           AND TRIM(nilai_angka) <> '')
    END
  ) AS jml_sudah_nilai,

  -- =============================
  -- JUMLAH BELUM DINILAI
  -- =============================
  (
    CASE
      WHEN vnk.sem_id < '20251'
      THEN
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id
           AND nim IN (SELECT nim FROM mahasiswa)
           AND (TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = ''))

      WHEN vnk.sem_id >= '20251'
       AND vnk.id_tipe_matkul != 'S'
       AND LOWER(vnk.nama_mk) NOT LIKE '%skrip%'
       AND LOWER(vnk.nama_mk) NOT LIKE '%tesis%'
       AND LOWER(vnk.nama_mk) NOT LIKE '%kkn%'
      THEN
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id  AND nim IN (SELECT nim FROM mahasiswa)
           AND (
             komponen_nilai IS NULL
             OR komponen_nilai = ''
             OR komponen_nilai = '[]'
           ))

      ELSE
        (SELECT COUNT(*) FROM krs_detail
         WHERE id_kelas = vnk.kelas_id  AND nim IN (SELECT nim FROM mahasiswa)
           AND (TRIM(nilai_angka) IS NULL OR TRIM(nilai_angka) = ''))
    END
  ) AS jml_belum_nilai,

  vnk.jurusan,
  vnk.kelas_id

FROM view_nama_kelas vnk
INNER JOIN view_dosen_kelas_single vd
  ON vnk.kelas_id = vd.id_kelas
WHERE vnk.kelas_id IS NOT NULL
  AND kelas_id IN (
    SELECT id_kelas FROM krs_detail WHERE sem_id = '" . $_POST['sem_filter'] . "'
  )
  $sem_filter $jur_filter $status_nilai
", $columns);

//buat inisialisasi array data
$data = array();

$i = 1;
$dos = array();
foreach ($query as $value) {

  //array data
  $ResultData = array();
  $ResultData[] = $datatable->number($i);
  $ResultData[] = $value->nama_dosen;
  $ResultData[] = $value->nm_matkul;
  $ResultData[] = $value->kls_nama;
  $ResultData[] = $value->jml_peserta;
  $ResultData[] = $value->jml_sudah_nilai;
  if ($value->jml_belum_nilai > 0) {
    $ResultData[] = '<span class="btn btn-xs btn-danger">' . $value->jml_belum_nilai . '</span>';
  } else {
    $ResultData[] = $value->jml_belum_nilai;
  }
  $ResultData[] = $value->kelas_id;
  $data[] = $ResultData;
  $dos = array();
  $i++;
}

$datatable2->setData($data);
//create our json
$datatable2->createData();

?>