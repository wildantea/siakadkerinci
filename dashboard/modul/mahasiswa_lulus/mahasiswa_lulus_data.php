<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'tb_data_kelulusan.nim',
    'mahasiswa.nama',
    'mahasiswa.mulai_smt',
    'jenis_keluar.ket_keluar',
    'tb_data_kelulusan.tanggal_keluar',
    'tb_data_kelulusan.semester',
    '((left(tb_data_kelulusan.semester,4)-left(mulai_smt,4))*2)+right(tb_data_kelulusan.semester,1)-(floor(right(mulai_smt,1)/2))',
    'ipk',
    'tb_data_kelulusan.kode_jurusan',
    'tb_data_kelulusan.id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
  $datatable2->setDisableSearchColumn(
    'mahasiswa.mulai_smt',
    'jenis_keluar.ket_keluar',
    'tb_data_kelulusan.tanggal_keluar',
    'tb_data_kelulusan.semester',
    '((left(tb_data_kelulusan.semester,4)-left(mulai_smt,4))*2)+right(tb_data_kelulusan.semester,1)-(floor(right(mulai_smt,1)/2))',
    'ipk',
    'tb_data_kelulusan.kode_jurusan',
    'tb_data_kelulusan.id'
  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
 // $datatable2->setOrderBy("tb_data_kelulusan.id desc");


  //set group by column
  //$datatable2->setGroupBy("tb_data_kelulusan.id");

$datatable2->setDebug(1);

  //set order by column
 // $datatable2->setOrderBy("mulai_smt desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";
$jur_kode = aksesProdi('tb_data_kelulusan.kode_jurusan');


$sem_filter = "";
$fakultas = "";
$mulai_smt = "";
$jenis_keluar = "";
$mulai_smt_end = "";
$semester = "";
$ipk = "";
  
if (isset($_POST['jur_kode'])) {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'fakultas' => $_POST['fakultas'],
        'sem_filter' => $_POST['sem_filter'],
        'prodi' => $_POST['jur_kode'],
        'mulai_smt' => $_POST['mulai_smt'],
        'mulai_smt_end' => $_POST['mulai_smt_end'],
        'jenis_keluar' => $_POST['jenis_keluar'],
        'input_search' => $_POST['input_search']
      );
      if (hasFakultas()) {
        $array_filter['fakultas'] = $_POST['fakultas'];
        if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
          $fakultas = getProdiFakultas('tb_data_kelulusan.kode_jurusan',$_POST['fakultas']);
        }
      }
      setFilter('filter_data_kelulusan',$array_filter);
    }

    if ($_POST['sem_filter']!='all') {
      $sem_filter = ' and tb_data_kelulusan.semester="'.$_POST['sem_filter'].'"';
    }
      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and tb_data_kelulusan.kode_jurusan="'.$_POST['jur_kode'].'"';
      }
  
      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and mulai_smt between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and mulai_smt="'.$_POST['mulai_smt'].'"';
        }

      }
  
      if ($_POST['jenis_keluar']!='all') {
        $jenis_keluar = " and tb_data_kelulusan.id_jenis_keluar='".$_POST['jenis_keluar']."'";
      }
      if ($_POST['semester']!='' && $_POST['control_semester']!='all') {
        $semester = ' and ((left(tb_data_kelulusan.semester,4)-left(mulai_smt,4))*2)+right(tb_data_kelulusan.semester,1)-(floor(right(mulai_smt,1)/2))'.$_POST['control_semester'].'"'.$_POST['semester'].'"';
      }
      if ($_POST['ipk']!='' && $_POST['control_ipk']!='all') {
        $ipk = ' and ipk'.$_POST['control_ipk'].'"'.$_POST['ipk'].'"';
      }
}

$prodi_jenjang = getProdiJenjang();

$datatable2->setFromQuery("tb_data_kelulusan inner join mahasiswa on tb_data_kelulusan.nim=mahasiswa.nim 
    where tb_data_kelulusan.id is not null $sem_filter $jur_kode $fakultas $mulai_smt $jenis_keluar $semester $ipk");

//$datatable2->setDebug(1);
  $query = $datatable2->execQuery("select tb_data_kelulusan.nim,mahasiswa.nama,mahasiswa.mulai_smt,jenis_keluar.ket_keluar,tb_data_kelulusan.tanggal_keluar,tb_data_kelulusan.semester,tb_data_kelulusan.keterangan_kelulusan,ipk,tb_data_kelulusan.kode_jurusan,
    ((left(tb_data_kelulusan.semester,4)-left(mulai_smt,4))*2)+right(tb_data_kelulusan.semester,1)-(floor(right(mulai_smt,1)/2)) as smt,
    tb_data_kelulusan.id from tb_data_kelulusan inner join jenis_keluar on tb_data_kelulusan.id_jenis_keluar=jenis_keluar.id_jns_keluar inner join mahasiswa on tb_data_kelulusan.nim=mahasiswa.nim 
    where tb_data_kelulusan.id is not null $sem_filter $jur_kode $fakultas $mulai_smt $jenis_keluar $semester $ipk",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {

    //array data
    $ResultData = array();
    $ResultData[] = $datatable2->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = trimmer($value->nama);
    $ResultData[] = $value->mulai_smt;
    $ResultData[] = $value->ket_keluar;
    $ResultData[] = tgl_indo($value->tanggal_keluar);
    $ResultData[] = $value->semester;
    $ResultData[] = $value->ipk;
    if ($value->semester!="") {
      $ResultData[] = $value->smt;
    } else {
      $ResultData[] = "";
    }
    $ResultData[] = $prodi_jenjang[$value->kode_jurusan];
    $ResultData[] = $value->id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>