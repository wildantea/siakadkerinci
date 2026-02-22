<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';
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
                   'A1','B1','C1'
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
                  'D1',
                  'E1'
                    ),
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
            );
//column width
$col_width = array(
1 => 13,
2 => 12,
3 => 30,
4 => 30,
5 => 40
  );
$writer->setColWidth($col_width);

$header = array(
"Kode Gedung" => "string",
"Kode Ruang" => "string",
"Nama Ruang" => "string",
"Penggunaan Untuk Prodi " => "string",
"Keterangan" => "string"
);

$data_rec = array();

$kode_jur = "";
$gedung_id = "";


      if ($_POST['kode_jur']!='all') {
        $kode_jur = 'and ruang_ref.ruang_id in(
select ruang_ref.ruang_id from ruang_ref inner join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id where ruang_ref_prodi.kode_jur="'.$_POST['kode_jur'].'")';
      }
  
      if ($_POST['gedung_id']!='all') {
        $gedung_id = ' and ruang_ref.gedung_id="'.$_POST['gedung_id'].'"';
      }

        $temp_rec = $db->query("select ruang_ref.kode_ruang, ruang_ref.nm_ruang,gedung_ref.kode_gedung,ruang_ref.ruang_id,group_concat(ruang_ref_prodi.kode_jur) as penggunaan,ruang_ref.ket from ruang_ref
left join gedung_ref on ruang_ref.gedung_id=gedung_ref.gedung_id
left join ruang_ref_prodi on ruang_ref.ruang_id=ruang_ref_prodi.ruang_id
where ruang_ref.ruang_id is not null $kode_jur $gedung_id
group by ruang_ref.ruang_id ");

                    foreach ($temp_rec as $key) {


                      $data_rec[] = array(
                      				$key->kode_gedung,
                                    $key->kode_ruang,
									$key->nm_ruang,
									$key->penggunaan,
									$key->ket
                        );

            }


$filename = 'ruang_kuliah.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Ruang', $header, $style);
$writer->writeToStdOut();
exit(0);
?>