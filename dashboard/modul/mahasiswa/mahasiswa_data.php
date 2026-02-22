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
    's.id',
    'mahasiswa.nim',
    'mahasiswa.nama',
    'mulai_smt',
    'mahasiswa.id_jns_daftar',
    'tb_data_kelulusan.id_jenis_keluar',
    '(select dosen
 from view_dosen where view_dosen.nip=mahasiswa.dosen_pemb)',
    'jur_kode',
    'is_submit_biodata',
    'mhs_id',
  );

  //if you want to exclude column for searching, put columns name in array
  $datatable2->setDisableSearchColumn('mulai_smt','mahasiswa.id_jns_daftar','tb_data_kelulusan.id_jenis_keluar','jur_kode');
  
  //set numbering is true
  $datatable2->setNumberingStatus(0);


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
  $jk = "";
  $jenis_keluar = "";
  $mulai_smt_end = "";
  $id_jenis_daftar = "";
  $jenjang = "";
  $is_cuti = 0;
  $where_is_cuti = "";
  $is_submit_biodata = "";
  $fakultas = "";
 $on_kelulusan = " tb_data_kelulusan.nim=mahasiswa.nim";
  if (isset($_POST['jur_kode'])) {
    if ($_POST['periode_cuti']!='all') {
  $periode_cuti = $_POST['periode_cuti'];
}
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

      if ($_POST['jenjang']!='all') {
        $jenjang = ' and id_jenjang="'.$_POST['jenjang'].'"';
      }

      if ($_POST['id_jenis_daftar']!='all') {
        $id_jenis_daftar = ' and id_jns_daftar="'.$_POST['id_jenis_daftar'].'"';
      }
  
      if ($_POST['jk']!='all') {
        $jk = ' and jk="'.$_POST['jk'].'"';
      }

      if ($_POST['is_submit_biodata']!='all') {
        $is_submit_biodata = ' and is_submit_biodata="'.$_POST['is_submit_biodata'].'"';
      }

    // if ($_POST['fakultas']!='all') {
    //   $fakultas = getProdiFakultas('mahasiswa.jur_kode',$_POST['fakultas']);
    // }
  
      if ($_POST['jenis_keluar']!='all') {

        if ($_POST['jenis_keluar']=='aktif') {
          $id_jenis_daftar = 'and id_jenis_keluar is null and mahasiswa.status="M"';
        } elseif ($_POST['jenis_keluar']=='calon') {
          $id_jenis_daftar = 'and mahasiswa.status="CM"';
        } elseif ($_POST['jenis_keluar']=='cuti') {
            $is_cuti = 1;
            $where_is_cuti = "and (select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) is not null";
        } else {
          $jenis_keluar = "and tb_data_kelulusan.id_jenis_keluar='".$_POST['jenis_keluar']."'";
          
        }
      }
  
}

  $datatable2->setDebug(1);

$is_mahasiswa = '';
if ($_SESSION['group_level']!='admin') {
  $is_mahasiswa = 'and mahasiswa.status="M"';
}




 // if ($is_cuti==0) {
$datatable2->setFromQuery("mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan  on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim where mhs_id is not null $jur_kode $fakultas $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $is_mahasiswa");

$isi_query = "select mahasiswa.status, (select dosen
 from view_dosen where view_dosen.nip=mahasiswa.dosen_pemb)  as nama_dosen,is_submit_biodata,
 s.is_photo_drived, 
 s.foto_user,mhs_id,mahasiswa.nim,mahasiswa.nama,mulai_smt,id_jns_daftar,jur_kode,tb_data_kelulusan.id_jenis_keluar as jns_keluar,(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti
 from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan  on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim where mhs_id is not null $jur_kode $fakultas $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $is_mahasiswa $is_submit_biodata";


      $query = $datatable2->execQuery($isi_query,$columns);

/*
  } else {

$datatable2->setFromQuery("mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan using(nim)
   where mhs_id is not null $jur_kode $fakultas $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $where_is_cuti $is_mahasiswa");
   $isi_query= "select  mahasiswa.status,  (select dosen
 from view_dosen where view_dosen.nip=mahasiswa.dosen_pemb)  as nama_dosen, s.foto_user,mhs_id,mahasiswa.nim,mahasiswa.nama,mulai_smt,id_jns_daftar,jur_kode,tb_data_kelulusan.id_jenis_keluar as jns_keluar,(select tb_data_cuti_mahasiswa.id_cuti from tb_data_cuti_mahasiswa inner join tb_data_cuti_mahasiswa_periode
using (id_cuti) where tb_data_cuti_mahasiswa_periode.periode='$periode_cuti' and tb_data_cuti_mahasiswa.nim=mahasiswa.nim) as is_cuti
 from mahasiswa inner join jurusan on mahasiswa.jur_kode=jurusan.kode_jur
 left join tb_data_kelulusan on ($on_kelulusan)
 left join sys_users s on s.username=mahasiswa.nim
   where mhs_id is not null $jur_kode $fakultas $mulai_smt $jk $id_jenis_daftar $jenis_keluar $jenjang $where_is_cuti $is_mahasiswa";
      $query = $datatable2->execQuery($isi_query,$columns);
  }*/
  //echo $isi_query;
  //buat inisialisasi array data
  $data = array(); 

  $i=1; 
  $login_as = "";

  $array_login_as = array('admin_validasi','admin','admin_akademik','TIPD');

  foreach ($query as $value) {
    $ResultData = array();

    if ($value->is_photo_drived=='Y') {
       $ResultData[] = $datatable2->number($i)."<img src='$value->foto_user=w50' style='width:50px' />";
    } else {
       $ResultData[] = $datatable2->number($i)."<img src='".base_url()."upload/back_profil_foto/$value->foto_user' style='width:50px' />";
    }
   
  
    $ResultData[] = $value->nim;
    $ResultData[] = $value->nama;
    $ResultData[] = $value->mulai_smt;
    $ResultData[] = (in_array($value->id_jns_daftar,array_keys($jenis_daftar)))?$jenis_daftar[$value->id_jns_daftar]:'Peserta Didik Baru';
    if (in_array($_SESSION['group_level'], $array_login_as)) {
      $login_as = '<a href="'.base_admin().'inc/login_as.php?id='.$value->nim.'&adm_id='.$_SESSION['id_user'].'&url=mahasiswa&back_uri=mahasiswa" class="btn btn-success btn-xs" data-toggle="tooltip" title="" data-original-title="Login As"><i class="fa fa-user"></i></a>';
    }
    if ($value->jns_keluar=='') {
       if ($value->is_cuti!='') {
         $ResultData[] = '<span class="btn btn-warning btn-xs"> Cuti</span> '.$login_as;
       } else {
        if ($value->status=='CM') {
          $ResultData[] = '<span class="btn btn-warning btn-xs"> Calon Mahasiswa</span> '.$login_as;
        }else{
          $ResultData[] = '<span class="btn btn-success btn-xs"> Aktif</span> '.$login_as;
        }
       
       }

    } elseif ($value->jns_keluar=='1' or $value->jns_keluar=='2') {
       $ResultData[] = '<span class="btn btn-primary btn-xs">'.$jenis_keluar_array[$value->jns_keluar].'</span> '.$login_as;
    } else {
      $ResultData[] = '<span class="btn btn-danger btn-xs">'.$jenis_keluar_array[$value->jns_keluar].'</span> '.$login_as;
    }
    $ResultData[] = $value->nama_dosen; 
    $ResultData[] = $prodi_jenjang[$value->jur_kode];

   if ($value->is_submit_biodata=='N') {
     $ResultData[] = '<span class="btn btn-warning btn-xs"> Belum</span>';
    } else {
      $ResultData[] = '<span class="btn btn-success btn-xs"> Sudah</span>';
    }

    $ResultData[] = $value->mhs_id; 
 
    $data[] = $ResultData;
    $i++;
  }

//set data
$datatable2->setData($data);
//create our json
$datatable2->createData();

?>