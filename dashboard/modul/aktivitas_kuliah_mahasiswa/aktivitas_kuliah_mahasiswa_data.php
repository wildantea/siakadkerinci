<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mahasiswa.mulai_smt',
    '((left(akm.sem_id,4)-left(mulai_smt,4))*2)+right(akm.sem_id,1)-(floor(right(mulai_smt,1)/2))',
    'akm.sem_id',
    'akm.id_stat_mhs',
    'akm.ip',
    'akm.ipk',
    'akm.sks_diambil',
    'akm.sks_total',
    'mahasiswa.jur_kode',
    'akm.akm_id',
  );

  //if you want to exclude column for searching, put columns name in quote and separate with comma if multi
$datatable2->setDisableSearchColumn(
    'mahasiswa.mulai_smt',
    'akm.sem_id',
    'akm.id_stat_mhs',
    '((left(akm.sem_id,4)-left(mulai_smt,4))*2)+right(akm.sem_id,1)-(floor(right(mulai_smt,1)/2))',
    'akm.ip',
    'akm.ipk',
    'akm.sks_diambil',
    'akm.sks_total',
    'mahasiswa.jur_kode',
    'akm.akm_id');
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
  //$datatable2->setOrderBy("akm.akm_id desc");


$jur_kode = aksesProdi('mahasiswa.jur_kode');

$nim = "";
$periode = "";
$fakultas = "";
$mulai_smt = "";
$status_mahasiswa = "";
$mulai_smt_end = "";


if ($_POST['nim']=='all') {
    //if filter session enable
    if (getPengaturan('filter_session')=='Y') {
      $array_filter = array(
        'nim' => $_POST['nim'],
        'prodi' => $_POST['jurusan'],
        'semester' => $_POST['periode'],
        'mulai_smt' => $_POST['mulai_smt'],
        'mulai_smt_end' => $_POST['mulai_smt_end'],
        'status_mahasiswa' => $_POST['status_mahasiswa'],
        'input_search' => $_POST['input_search']
      );
      if (hasFakultas()) {
        $array_filter['fakultas'] = $_POST['fakultas'];
        if ($_POST['fakultas']!='all' && $_POST['fakultas']!='') {
          $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
        }
      }
      setFilter('filter_akm',$array_filter);
    }
    
    if ($_POST['periode']!='all') {
      $periode = ' and akm.sem_id="'.$_POST['periode'].'"';
    }

    if ($_POST['jurusan']!='all') {
      $jur_kode = ' and mahasiswa.jur_kode="'.$_POST['jurusan'].'"';
    }

      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }

      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end>=$_POST['mulai_smt']) {
            $mulai_smt = ' and left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }

      }

    if ($_POST['status_mahasiswa']!='all') {
      $status_mahasiswa = ' and akm.id_stat_mhs="'.$_POST['status_mahasiswa'].'"';
    }
} else {
      $array_filter = array(
        'value_nim' => $_POST['value_nim'],
        'nim' => $_POST['nim']
      );
      setFilter('filter_akm',$array_filter);
  $nim = "and mahasiswa.nim='".$_POST['value_nim']."'";
}

$stat_mahasiswa = getStatusMahasiswa();
$prodi_jenjang = getProdiJenjang();

$datatable2->setDebug(1);

  //set group by column
  //$datatable2->setGroupBy("akm.akm_id");

$datatable2->setFromQuery("akm inner join mahasiswa on nim=mhs_nim
inner join view_prodi_jenjang on jur_kode=kode_jur
 where akm.akm_id is not null $periode $fakultas $jur_kode $mulai_smt $status_mahasiswa $nim");


  $query = $datatable2->execQuery("select mahasiswa.nim,sem_id,mahasiswa.nama,mahasiswa.mulai_smt,akm.id_stat_mhs,
    ((left(akm.sem_id,4)-left(mulai_smt,4))*2)+right(akm.sem_id,1)-(floor(right(mulai_smt,1)/2)) as smt,
akm.ip,akm.ipk,akm.sks_diambil as sks_semester,akm.total_sks as sks_total,jur_kode,akm.akm_id 
from akm inner join mahasiswa on nim=mhs_nim
inner join view_prodi_jenjang on jur_kode=kode_jur
 where akm.akm_id is not null $periode $fakultas $jur_kode $mulai_smt $status_mahasiswa $nim",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  foreach ($query as $value) {
 $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=aktivitas-kuliah-mahasiswa" class="btn btn-success btn-xs" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    //array data
    $ResultData = array();
  $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>'.$datatable2->number($i);
  
    $ResultData[] = $value->nim.' '.$login_as;
    $ResultData[] = trimmer($value->nama);
    $ResultData[] = getAngkatan($value->mulai_smt);
    if (strlen($value->sem_id)<4) {
      $ResultData[] = 'Konversi';
      $ResultData[] = 'Konversi';
    } elseif(substr($value->sem_id, -1)=='3') {
      $ResultData[] = 'Pendek '.$value->sem_id;
      $ResultData[] = 'Pendek '.$value->sem_id;
    } else {
      $ResultData[] = $value->smt;
      $ResultData[] = $value->sem_id;
    }
    if ($value->id_stat_mhs=='A') {
       $ResultData[] = '<span class="btn btn-xs btn-success btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Aktif"><i class="fa fa-check"></i> Aktif</span>';
    } elseif ($value->id_stat_mhs=='C') {
       $ResultData[] = '<span class="btn btn-xs btn-warning btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Sedang Cuti"><i class="fa fa-warning"></i> Cuti</span>';
    } elseif ($value->id_stat_mhs=='N') {
       $ResultData[] = '<span class="btn btn-xs btn-danger btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Non-aktif"><i class="fa fa-warning"></i> Non-Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-xs btn-info btn-xs" data-toggle="tooltip" data-title="Mahasiswa ini Non-aktif"><i class="fa fa-info"></i> '.(isset($stat_mahasiswa[$value->id_stat_mhs])?$stat_mahasiswa[$value->id_stat_mhs]:'').'</span>';
    }
    $ResultData[] = $value->ip;
    $ResultData[] = $value->ipk;
    $ResultData[] = $value->sks_semester;
    $ResultData[] = $value->sks_total;
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    $ResultData[] = $value->akm_id;

    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>