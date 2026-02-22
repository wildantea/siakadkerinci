<?php
session_start();
include "../../inc/config.php";

$columns = array(
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mulai_smt',
    '(select tb_data_kelulusan.id_jenis_keluar from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim limit 1)',
    '(select sum(sks) from krs_detail where krs_detail.nim=mahasiswa.nim)',
    'jur_kode',
    'mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn('mulai_smt','id_jns_keluar','(select tb_data_kelulusan.id_jenis_keluar from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim limit 1)','(select sum(sks) from krs_detail where krs_detail.nim=mahasiswa.nim)','jur_kode','mhs_id');
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);

  //set order by column
 // $datatable2->setOrderBy("mulai_smt desc");

  //set group by column
  //$new_table->group_by = "group by mahasiswa.mhs_id";

$jur_kode = aksesProdi('mahasiswa.jur_kode');


  $mulai_smt = "";
  $jk = "";
  $jenis_keluar = "";
  $mulai_smt_end = "";
  $id_jenis_daftar = "";
  $fakultas = '';
  
  if (isset($_POST['jur_kode'])) {

      if ($_POST['jur_kode']!='all') {
        $jur_kode = ' and jur_kode="'.$_POST['jur_kode'].'"';
      }
  
      if ($_POST['mulai_smt_end']!='all') {
        $mulai_smt_end = $_POST['mulai_smt_end'];
      }
      if ($_POST['mulai_smt']!='all') {
        if ($mulai_smt_end!="") {
            $mulai_smt = ' and left(mulai_smt,4) between '.$_POST['mulai_smt'].' and '.$mulai_smt_end;
        } else {
            $mulai_smt = ' and left(mulai_smt,4)="'.$_POST['mulai_smt'].'"';
        }

      }

      if ($_POST['jenis_keluar']!='all') {

        if ($_POST['jenis_keluar']=='aktif') {
          $jenis_keluar = 'and nim not in (select tb_data_kelulusan.nim from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim)';
        } else {
          $jenis_keluar = "and nim in (select tb_data_kelulusan.nim from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim and id_jenis_keluar='".$_POST['jenis_keluar']."')";
          
        }
      }
  
}
function getJenisKeluar() {
  global $db2;
  $jns_keluars = $db2->query("select * from jenis_keluar");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_jns_keluar] = $jns_keluar->ket_keluar;
  }
  return $data_jenis_keluar;
}
$jenis_keluar_array = getJenisKeluar();
$prodi_jenjang = getProdiJenjang();
$datatable2->setDebug(1);

$datatable2->setFromQuery("mahasiswa
 inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
      INNER JOIN sys_users on mahasiswa.nim=sys_users.username
       where mahasiswa.mhs_id is not null and mahasiswa.status='M'
$fakultas $jur_kode $mulai_smt $jk $id_jenis_daftar $jenis_keluar ");

  $query = $datatable2->execQuery("SELECT mhs_id,mahasiswa.nim,mahasiswa.nama,mulai_smt,id_jns_daftar,(select tb_data_kelulusan.id_jenis_keluar from tb_data_kelulusan where tb_data_kelulusan.nim=mahasiswa.nim limit 1) as jns_keluar,jur_kode,sys_users.foto_user,
  (select sum(sks) from krs_detail where krs_detail.nim=mahasiswa.nim) as sks_diambil_lulus
 from mahasiswa
 inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
      INNER JOIN sys_users on mahasiswa.nim=sys_users.username
       where mahasiswa.mhs_id is not null and mahasiswa.status='M'
    $fakultas $jur_kode $mulai_smt $jenis_keluar ",$columns);

  //buat inisialisasi array data
  $data = array();

  $i=1;
  $login_as = "";
  foreach ($query as $value) {
    $ResultData = array();
    $ResultData[] = $datatable2->number($i);
  
    $ResultData[] = $value->nim;
    $ResultData[] = trimmer($value->nama);
    $ResultData[] = $value->mulai_smt;
    if ($_SESSION['group_level']=='admin' or $_SESSION['group_level']=='administrator') {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=hasil-studi-mahasiswa" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }

    if ($value->jns_keluar=='') {
     $ResultData[] = '<span class="btn btn-success btn-xs"> Aktif</span> '.$login_as;
    } elseif ($value->jns_keluar=='1' or $value->jns_keluar=='2') {
       $ResultData[] = '<span class="btn btn-primary btn-xs">'.$jenis_keluar_array[$value->jns_keluar].'</span> '.$login_as;
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">'.$jenis_keluar_array[$value->jns_keluar].'</span> '.$login_as;
    }

    $ResultData[] = ($value->sks_diambil_lulus)>0?$value->sks_diambil_lulus:0;
    
    
    $ResultData[] = $prodi_jenjang[$value->jur_kode];
    $ResultData[] = '<a href="'.base_index_new().'hasil-studi-mahasiswa/show-nilai/'.en($value->nim).'" class="btn edit_data btn-success "><i class="fa fa-book"></i> Lihat Hasil Studi</a>';

    $data[] = $ResultData;
    $i++;
  }


//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>