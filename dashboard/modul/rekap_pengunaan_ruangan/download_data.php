<?php
session_start();
include "../../inc/config.php";
require_once '../../inc/lib/Writer.php';


$writer = new XLSXWriter();
function numberToAlphabet($number) {
    // Add 64 to the number to map it to the ASCII value of uppercase letters (A=65, B=66, ...).
    return chr(64 + $number);
}

$cell = array();
//column width
$col_width = array(
  1 => 16,
  2 => 21,
  3 => 20
  );


$header = array(
  'Hari'=>'string',
  'Gedung'=>'string',
  'Ruangan'=>'string'
);
$cell = array(
  'A1',
  'B1',
  'C1'
);
$sesi = $db->query("select * from sesi_waktu order by jam_mulai asc");
$index = 3;
foreach ($sesi as $jam) {
$header["$jam->jam_mulai"] = $jam->jam_mulai;
$index++;
$cell[]= numberToAlphabet($index).'1';
$col_width[$index] = 20;
}

$writer->setColWidth($col_width);

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
                'cells' => $cell,
                'border' => array(
                    'style' => 'thin',
                    'color' => '000000'
                    ),
                'verticalAlign' => 'center',
                'horizontalAlign' => 'center',
            ),
            );

$kode_prodi = aksesProdi('kode_jur');
$hari = "";
$ruang = "";
$gedung = "";
$semester = $_POST['sem_filter'];
$jur_filter = "";
$fakultas = "";


  if ($_POST['jur_filter']!='all') {
    $jur_filter = ' and r.kode_jur="'.$_POST['jur_filter'].'"';
  }
  if ($_POST['fakultas']!='all') {
    $fakultas = getProdiFakultas('r.kode_jur',$_POST['fakultas']);
  }
  
  if ($_POST['gedung_id']!='all') {
    $gedung = ' and gedung_id="'.$_POST['gedung'].'"';
  }

  if ($_POST['ruang_filter']!='all') {
    $ruang = ' and ruang_id="'.$_POST['ruang_filter'].'"';
  }

    if ($_POST['hari_filter']!='all') {
  $hari = array($_POST['hari_filter']);
} else {
  $hari = array(
            'senin' ,
            'selasa',
            'rabu', 
            'kamis',
            'jumat',
            'sabtu',
            'minggu'
          );
}
$sesi = $db->query("select * from sesi_waktu order by jam_mulai asc");
$jml_sesi = $sesi->rowCount();
$increment = 1;
foreach ($sesi as $jam) {
    foreach ($hari as $hr) {
          $query_where[$hr][] = "
    '$jam->jam_mulai' AS Time_Start_$increment,
    '$jam->jam_selesai' AS Time_End_$increment,
    CASE 
        WHEN EXISTS (
            SELECT 1 
            FROM view_jadwal vj 
            JOIN view_nama_kelas k ON vj.kelas_id = k.kelas_id
            WHERE vj.ruang_id = r.ruang_id 
              AND vj.hari = '$hr'
              AND vj.sem_id = '$semester'
              AND vj.jam_mulai <= '$jam->jam_mulai' 
              AND vj.jam_selesai > '$jam->jam_mulai'
        ) 
        THEN (
            SELECT concat(k.kode_jur,'/',k.nm_matkul)
            FROM view_jadwal vj 
            JOIN view_nama_kelas k ON vj.kelas_id = k.kelas_id
            WHERE vj.ruang_id = r.ruang_id 
              AND vj.hari = '$hr'
              AND vj.sem_id = '$semester'
              AND vj.jam_mulai <= '$jam->jam_mulai' 
              AND vj.jam_selesai > '$jam->jam_mulai'
            LIMIT 1
        )
        ELSE ''
    END AS Availability_$increment";
    $increment++;
    }

}

foreach ($hari as $hri) {
  $hasil_query = implode(", ",$query_where[$hri]);
  $query[] = "SELECT 
   r.nm_gedung,r.nm_ruang,
    
'".ucwords($hri)."' as hari,

$hasil_query

FROM 
    view_ruang_prodi r
where is_aktif='Y'
$gedung $ruang $jur_filter $fakultas";
}

$query_akhir = implode(" union all ", $query);

$temp_rec = $db->query($query_akhir);

                    foreach ($temp_rec as $value) {




  $data_r = array(
                                      ucwords($value->hari),
                                      $value->nm_gedung,
                                      $value->nm_ruang,
                        );
    for ($is=1; $is <= $jml_sesi; $is++) { 
      $is_av = $value->{"Availability_".$is};
      $data_r[] = $is_av; // Append to the same row
    }

    $data_rec[] = $data_r;


            }

$filename = "REKAP_RUANGAN_$hari.$semester.xlsx";


header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'REKAP RUANGAN', $header, $style);
$writer->writeToStdOut();
exit(0);
?>