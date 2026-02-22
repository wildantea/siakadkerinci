<?php
session_start();
include "../../inc/config.php";
function getJenisDaftar() {
  global $db2;
  $jenis_daftars = $db2->query("select * from jenis_daftar");
  foreach ($jenis_daftars as $jenis_daftar) {
    $data_jenis[$jenis_daftar->id_jenis_daftar] = $jenis_daftar->nm_jns_daftar;
  }
  return $data_jenis;
}
function getJenisKeluar() {
  global $db2;
  $jns_keluars = $db2->query("select * from jenis_keluar");
  foreach ($jns_keluars as $jns_keluar) {
    $data_jenis_keluar[$jns_keluar->id_jns_keluar] = $jns_keluar->ket_keluar;
  }
  return $data_jenis_keluar;
}

$semester_aktif = $db->fetch_single_row("semester_ref","aktif",1);

$periode_cuti = $semester_aktif->semester;



$columns = array(
    's.foto_user',
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mulai_smt',
    'is_submit_biodata',
    'date_updated',
    'jur_kode',
    'mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn(
     's.foto_user',
      'mulai_smt',
    'is_submit_biodata',
    'jur_kode',
    'mhs_id',

  );
  
  //set numbering is true
  $datatable2->setNumberingStatus(1);


  //set order by column
  //$datatable2->setOrderBy("keu_kwitansi.id_kwitansi desc");


  //set group by column
  $datatable2->setGroupBy("mahasiswa.nim");
 
$jur_kode = "";
//get default akses prodi 
$akses_prodi = get_akses_prodi();
//echo "$akses_prodi";
$akses_jur = $db->fetch_custom_single("select group_concat(kode_jur) as kode_jur from view_prodi_jenjang $akses_prodi");
if ($akses_jur) {
  $jur_kode = "and jur_kode in(".$akses_jur->kode_jur.")";
} else {
  //jika tidak group tidak punya akses prodi, set in 0
  $jur_kode = "and jur_kode in(0)";
}
 
  $prodi_jenjang = getProdiJenjang(); 
 // print_r($prodi_jenjang); 
  //print_r($prodi_jenjang);
  $jenis_daftar = getJenisDaftar();
  $jenis_keluar_array = getJenisKeluar();
  $mulai_smt = "";
  $is_lengkap_data = "";
  $jenis_keluar = "";
  $mulai_smt_end = "";
  $id_jenis_daftar = "";
  $fakultas = "";
  $is_cuti = 0;
  $where_is_cuti = "";
  $fakultas = "";
 $on_kelulusan = " tb_data_kelulusan.nim=mahasiswa.nim";
  if (isset($_POST['kode_prodi'])) {

      if ($_POST['kode_prodi']!='all') {
        $jur_kode = ' and jur_kode="'.$_POST['kode_prodi'].'"';
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
  
      if ($_POST['status_update']!='all') {
        $is_lengkap_data = ' and is_submit_biodata="'.$_POST['status_update'].'"';
      }

        if ($_POST['fakultas']!='all') {
          $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
        }
  
}

  $datatable2->setDebug(1);

$is_mahasiswa = '';
$is_mahasiswa = 'and mahasiswa.status="M"';


 // if ($is_cuti==0) {
$datatable2->setFromQuery("mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan  on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim where mhs_id is not null $jur_kode $fakultas $mulai_smt $is_lengkap_data $id_jenis_daftar $jenis_keluar $jenjang $is_mahasiswa");

$isi_query = "select mahasiswa.status,mahasiswa.tgl_awal_update,mahasiswa.tgl_akhir_update,mahasiswa.is_submit_biodata,mahasiswa.date_updated,
 s.is_photo_drived, 
 s.foto_user,mhs_id,mahasiswa.nim,mahasiswa.nama,mulai_smt,id_jns_daftar,jur_kode,tb_data_kelulusan.id_jenis_keluar as jns_keluar
 from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan  on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim where mhs_id is not null $jur_kode $fakultas $mulai_smt $is_lengkap_data $id_jenis_daftar $jenis_keluar $jenjang $is_mahasiswa";


      $query = $datatable2->execQuery($isi_query,$columns);
  //echo $isi_query;
  //buat inisialisasi array data
  $data = array(); 

  $i=1; 
  $login_as = "";

  $array_allowed_login_as = array(
    'admin'
    //,'keuangan','rektorat','admin_akademik','tipd'
  );

  foreach ($query as $value) {

    if (in_array($_SESSION['group_level'],$array_allowed_login_as)) {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=periode-update-biodata" class="btn btn-success btn-sm" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }

    $ResultData = array();
 $ResultData[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> <input type="checkbox" class="group-checkable check-selected"> <span></span></label>';
  
    if ($value->is_photo_drived=='Y') {
       $ResultData[] = "<img src='$value->foto_user=w50' style='width:50px' />";
    } else {
       $ResultData[] = "<img src='".base_url()."upload/back_profil_foto/$value->foto_user' style='width:50px' />";
    }
   
  
    $ResultData[] = $value->nim.' '.$login_as;;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->mulai_smt;
/*    $ResultData[] = tgl_indo($value->tgl_awal_update);
    $ResultData[] = tgl_indo($value->tgl_akhir_update);
        if (strtotime(date('Y-m-d H:i:s')) >= strtotime($value->tgl_awal_update) && strtotime(date('Y-m-d H:i:s')) <= strtotime($value->tgl_akhir_update)) {
      $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Periode Update Aktif"><i class="fa fa-check"></i> Aktif</span>';
    } else {
       $ResultData[] = '<span class="btn btn-danger btn-xs" data-toggle="tooltip" data-title="Periode Update Tidak Aktif"><i class="fa fa-close"></i> Tidak</span>';
    }*/
       if ($value->is_submit_biodata=='N') {
     $ResultData[] = '<span class="btn btn-warning btn-xs" data-toggle="tooltip" data-title="Belum Update Biodata"> Belum</span>';
    } else {
      $ResultData[] = '<span class="btn btn-success btn-xs" data-toggle="tooltip" data-title="Sudah Update Biodata"> Sudah</span>';
    }
    $ResultData[] = tgl_indo($value->date_updated);
    
    $ResultData[] = $prodi_jenjang[$value->jur_kode];

    $ResultData[] = $value->mhs_id; 
 
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>