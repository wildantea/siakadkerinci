<?php
session_start();
include "../../inc/config.php";

require_once '../../inc/lib/Writer.php';


      $jurusan = trim($_POST['jur_filter']);
      if ($jurusan=='all') {
          $jurusan = "";
          if ($_SESSION['level']!=1) {
              $jurusan = "and kode_jur='".$_SESSION['jurusan']."'";
          }
          $nama_jurusan = "Semua KRS";
      } else {
        $jurusan = "kode_jur='$jurusan'";

        $nama_jurusan = $db->fetch_custom_single("select jurusan from view_prodi_jenjang where $jurusan");
        $nama_jurusan = $nama_jurusan->jurusan;
        $nama_jurusan = str_replace(" ", "_", $nama_jurusan);

      }

 


        $jur_filter = "";
        $is_bayar = "";
        $disetuji = "";
        $angkatan_filter = "";
        $sem_filter = "";

        if (isset($_POST['jur_filter'])) {

          if ($_POST['jur_filter']!='all') {
            $jur_filter = ' and kode_jur="'.$_POST['jur_filter'].'"';
          }

          if ($_POST['sem_filter']!='all') {
            $sem_filter = ' and id_semester="'.$_POST['sem_filter'].'"';
          }

          if ($_POST['angkatan_filter']!='all') {
            $angkatan_filter = ' and mulai_smt="'.$_POST['angkatan_filter'].'"';
          }
  if ($_POST['is_bayar']!='all') {
    if ($_POST['is_bayar']=='1') {
      //Sudah Bayar
        $is_bayar = 'and tagihan.sisa_tagihan < 1';
    } elseif ($_POST['is_bayar']=='2') {
      //Affirmasi
      $is_bayar = 'and (select id_affirmasi from affirmasi_krs where nim=krs_detail.nim and periode=krs_detail.id_semester) is not null';
    } elseif ($_POST['is_bayar']=='3') {
      //Belum Lunas
      $is_bayar = 'and (tagihan.sisa_tagihan > 0 and (select id_affirmasi from affirmasi_krs where nim=krs_detail.nim and periode=krs_detail.id_semester) is null)';
       //$is_bayar = 'and tagihan.sisa_tagihan > 0';
    }  else {
      //Belum Bayar
      $is_bayar = 'and (tagihan.sisa_tagihan > 0 && tagihan.sisa_tagihan=tagihan.nominal_akhir_tagihan)';
    }

  }
          if ($_POST['disetujui']!='all') {
              $disetuji = "and disetujui='".$_POST['disetujui']."'";
          }

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
                    'color' => 'ff0000'
                    ),
                'cells' => array(
                    'A1',
                     'C1',
                    'D1',
                   'F1',
                    'G1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
                        array(
                'fill' => array(
                    'color' => '00ff00'
                    ),
                'cells' => array(
                  'B1',
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
  3 => 20,
  4 => 20,
  5 => 20,
  6 => 15,
  7 => 19
  );
$writer->setColWidth($col_width);

$header = array(
  'NIM'=>'string',
  'Nama'=>'string',
  'Semester'=>'string',
  'Kode Matakuliah' => 'string',
  'Nama Matakuliah' => 'string',
  'Kelas' => 'string',
  'Kode Prodi'=>'string'
);
  

$data_rec = array(); 

    

        $temp_rec = $db->query("
select krs_detail.nim,nama,id_semester,view_nama_kelas.kode_mk,nama_mk,kls_nama,kode_jur from krs_detail inner join view_nama_kelas on krs_detail.id_kelas=view_nama_kelas.kelas_id
 inner join mahasiswa on krs_detail.nim=mahasiswa.nim 

left join (
select ktm.nim,keu_tagihan.nominal_tagihan,nominal_tagihan - ktm.potongan as nominal_akhir_tagihan,nominal_bayar,
(keu_tagihan.nominal_tagihan - potongan) - sum(kbm.nominal_bayar) as sisa_tagihan,
sum(kbm.nominal_bayar),potongan,syarat_krs,periode FROM keu_tagihan_mahasiswa ktm
          JOIN keu_bayar_mahasiswa kbm ON ktm.id=kbm.id_keu_tagihan_mhs
          JOIN keu_tagihan ON ktm.id_tagihan_prodi=keu_tagihan.id
JOIN keu_jenis_tagihan kjt ON keu_tagihan.kode_tagihan=kjt.kode_tagihan
group by ktm.id

having 
#sum(nominal_bayar) <  nominal_tagihan - ktm.potongan 
#and 
 kjt.syarat_krs='Y'
) tagihan on tagihan.nim=krs_detail.nim and tagihan.periode=krs_detail.id_semester
where krs_detail.id_kelas is not null
$sem_filter $jur_filter  $angkatan_filter $is_bayar $disetuji  order by krs_detail.nim asc");

//        echo $db->getErrorMessage();
    

                    foreach ($temp_rec as $key) {


                                    $data_rec[] = array(
                                      $key->nim,
                                      $key->nama,
                                      $key->id_semester,
                                      $key->kode_mk,
                                      $key->nama_mk,
                                      $key->kls_nama,
                                      $key->kode_jur
                        );

                    }
       

$filename = $nama_jurusan.'.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeSheet($data_rec,'Data KRS', $header, $style);
$writer->writeToStdOut();
exit(0);
?>