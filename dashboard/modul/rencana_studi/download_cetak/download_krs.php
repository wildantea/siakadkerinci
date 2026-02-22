<?php
session_start();
include "../../../inc/config.php";

require_once '../../../inc/lib/Writer.php';


$jur_kode = aksesProdi('mahasiswa.jur_kode');

$fakultas = "";
$periode = "";
$mulai_smt = "";
$is_bayar = "";
$disetujui = "";
$mulai_smt_end = "";


if (isset($_POST['jurusan'])) {
    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = ' and (left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end.")";
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }
      }

    if ($_POST['status_bayar']!='all') {
      if ($_POST['status_bayar']=='sudah') {
          $is_bayar = "and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0";

      } elseif ($_POST['status_bayar']=='belum') {
          $is_bayar = "and
  IFNULL(
    (SELECT id_affirmasi 
     FROM affirmasi_krs 
     WHERE nim = mahasiswa.nim 
       AND periode = '".$_POST['periode']."' 
     LIMIT 1), 
    0
  ) < 1

          and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) < 1
  and 
    IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0";
      } elseif ($_POST['status_bayar']=='aff') {
        $is_bayar = "and (select id_affirmasi from affirmasi_krs where nim=mahasiswa.nim and periode='".$_POST['periode']."' limit 1) > 0
                  and IFNULL(
    (SELECT SUM(IFNULL(nominal_bayar, 0)) 
     FROM keu_bayar_mahasiswa
     RIGHT JOIN keu_tagihan_mahasiswa ON keu_bayar_mahasiswa.id_keu_tagihan_mhs = keu_tagihan_mahasiswa.id
     RIGHT JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan USING(kode_tagihan)
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) < 1
          ";
      }
    }
    if ($_POST['disetujui']!='all') {
      $disetujui = ' and (select min(disetujui) from krs_detail where nim=mahasiswa.nim and id_semester="'.$_POST['periode'].'")="'.$_POST['disetujui'].'"';
    }

}

$periode = $_POST['periode'];

if ($_POST['status_krs']=='1') {
  $temp_rec = $db->query("SELECT mahasiswa.nim,mahasiswa.nama,mulai_smt,mahasiswa.jur_kode,

IFNULL(
    (SELECT SUM(sks) 
     FROM krs_detail 
     WHERE nim = mahasiswa.nim AND id_semester = '$periode'), 
    0
  ) AS sks_diambil,
 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim
and akm.sem_id!='$periode' and akm.sem_id<='$periode'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks

 from mahasiswa 
 left join view_nama_gelar_dosen on mahasiswa.dosen_pemb=view_nama_gelar_dosen.nip
where mahasiswa.nim in(select nim from krs_detail where id_semester='".$_POST['periode']."')
and   IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0
 $fakultas $jur_kode $mulai_smt $is_bayar $disetujui
");
} else {
  $temp_rec = $db->query("
    SELECT mahasiswa.nim,mahasiswa.nama,mulai_smt,mahasiswa.jur_kode,
'0' as sks_diambil,
 (select sks_mak from jatah_sks j where  IFNULL((select akm.ip  from akm where akm.mhs_nim=mahasiswa.nim
and akm.sem_id!='$periode' and akm.sem_id<='$periode'
and akm.id_stat_mhs='A' ORDER BY sem_id DESC LIMIT 1),4) BETWEEN j.ip_min and j.ip_mak) as jatah_sks

 from mahasiswa 
 left join view_nama_gelar_dosen on mahasiswa.dosen_pemb=view_nama_gelar_dosen.nip
where mahasiswa.nim not in(select nim from krs_detail where id_semester='".$_POST['periode']."')
and nim not in(select nim from tb_data_kelulusan)
and   IFNULL(
    (SELECT SUM(nominal_tagihan) 
     FROM keu_tagihan_mahasiswa 
     INNER JOIN keu_tagihan ON keu_tagihan_mahasiswa.id_tagihan_prodi = keu_tagihan.id
     INNER JOIN keu_jenis_tagihan ON keu_tagihan.kode_tagihan = keu_jenis_tagihan.kode_tagihan
     WHERE keu_jenis_tagihan.syarat_krs = 'Y' 
       AND keu_tagihan_mahasiswa.nim = mahasiswa.nim 
       AND keu_tagihan_mahasiswa.periode = '".$_POST['periode']."'), 
    0
  ) > 0
 ");


}



$writer = new XLSXWriter();
$style =
        array (
                      array(
              'border' => array(
                'style' => 'thin',
                'color' => '000000'
                ),
            'allfilleddata' => true
            ),
                        array(
                'fill' => array(
                    'color' => '00ff00'
                    ),
                'cells' => array(
                      'A1',
                      'B1',
                      'C1',
                      'D1',
                      'E1'
                  ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
                )
            );

//column width
$col_width = array(
  1 => 19,
  2 => 27,
  3 => 30,
  4 => 20,
  5 => 20
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Nama'=>'string',
  'Program Studi'=>'string',
  'Jatah SKS'=>'string',
  'SKS Diambil' => 'string',
);
  
$prodi_jenjang = getProdiJenjang();
$data_rec = array(); 

                    foreach ($temp_rec as $key) {


                                    $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $prodi_jenjang[$key->jur_kode],
                                      $key->jatah_sks,
                                      $key->sks_diambil
                        );

                    }

$filename = 'Download_Rekap_KRS.xlsx';


header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data KRS', $header, $style);
$writer->writeToStdOut();
exit(0);
?>