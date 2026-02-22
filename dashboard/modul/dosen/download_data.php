<?php
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
                   'A1','B1','C1','F1'
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
                  'D1','E1','G1','H1'
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
1 => 29,
2 => 21,
3 => 27,
4 => 20,
5 => 18,
6 => 20,
7 => 20,
8 => 20
  );
$writer->setColWidth($col_width);

$header = array(
"NIP" => 'string',
"NIDN/NIDK/NUP" => "string",
"Nama Lengkap" => "string",
"Email" => "string",
"No HP" => "string",
"Kode Jurusan" => "string",
"Kode Rumpun" => "string",
"Jenis Dosen" => "string"
);

$data_rec = array();

$kode_jur = "";
  $aktif = "";
  $rumpun = "";
$sub_rumpun = "";
$bidang_ilmu = "";

$id_jenis_dosen = "";


      if ($_POST['kode_jur']!='all') {
        $kode_jur = ' and dosen.kode_jur="'.$_POST['kode_jur'].'"';
      }
  
      if ($_POST['aktif']!='all') {
        $aktif = ' and dosen.aktif="'.$_POST['aktif'].'"';
      }

      if ($_POST['rumpun']!='all' && $_POST['rumpun']!='') {
        $rumpun = ' and d.kode="'.$_POST['rumpun'].'"';
      }
      if (isset($_POST['sub_rumpun'])) {
        if ($_POST['sub_rumpun']!='all' && $_POST['sub_rumpun']!='') {
            $sub_rumpun = ' and dw.kode="'.$_POST['sub_rumpun'].'"';
        }
      }
      if (isset($_POST['bidang_ilmu'])) {
        if ($_POST['bidang_ilmu']!='all' && $_POST['bidang_ilmu']!='') {
          $bidang_ilmu = ' and dwc.kode="'.$_POST['bidang_ilmu'].'"';
        }
      }

      if ($_POST['id_jenis_dosen']!='all') {
        $id_jenis_dosen = ' and id_jenis_dosen="'.$_POST['id_jenis_dosen'].'"';
      }


        $order_by = "order by nama_dosen asc";


function getJenisDosen() {
  global $db;
  $jns_dosen = $db->query("select * from jenis_dosen");
  foreach ($jns_dosen as $jenis) {
    $data_jenis_dosen[$jenis->id_jenis_dosen] = $jenis->nama_jenis;
  }
  return $data_jenis_dosen;
}

  $jenis_dosen_array = getJenisDosen();


        $temp_rec = $db->query("select dosen.kode_jur,kode_rumpun,dosen.email,no_hp,dosen.aktif,dosen.nip,dosen.nidn,dosen.nama_dosen,id_jenis_dosen,view_prodi_jenjang.jurusan,dosen.id_dosen 
from dosen
inner join sys_users on dosen.nip=sys_users.username
left join view_prodi_jenjang on dosen.kode_jur=view_prodi_jenjang.kode_jur
left join data_rumpun_dosen dwc on dosen.kode_rumpun=dwc.kode
left join data_rumpun_dosen dw on dwc.id_induk=dw.kode
left join data_rumpun_dosen d on dw.id_induk=d.kode where dosen.id_dosen is not null $kode_jur $aktif $rumpun $sub_rumpun $bidang_ilmu $id_jenis_dosen");
        $prodi_dikti = get_prodi_dikti();
                    foreach ($temp_rec as $key) {

                      $jenis_dosen = (in_array($key->id_jenis_dosen,array_keys($jenis_dosen_array)))?$jenis_dosen_array[$key->id_jenis_dosen]:'';

                      $data_rec[] = array(
                      				$key->nip,
                              $key->nidn,
															$key->nama_dosen,
                              $key->email,
                              $key->no_hp,
															$key->kode_jur,
                              $key->kode_rumpun,
                              $jenis_dosen
                        );

            }


ob_clean();
flush();
$filename = 'Dosen.xlsx';
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
$writer->writeSheet($data_rec,'Data Dosen', $header, $style);
$writer->writeToStdOut();
exit(0);
?>